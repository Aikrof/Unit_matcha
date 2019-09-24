<?php

namespace App\Http\Controllers\Socket;

use Auth;
use App;
use Config;
use Crypt;
use SplObjectStorage;
use App\User;
use App\DeferredNotification as Notifi;
use App\Http\Controllers\Socket\MessageController;
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
use Illuminate\Session\SessionManager;

class WebSocketController implements MessageComponentInterface
{
    private $clients;

    private $online = [];
    
    public function __construct()
    {
        $this->clients = array();
    }

     /**
     * When a new connection is opened it will be passed to this method
     * @param  ConnectionInterface $conn The socket/connection that just connected to your application
     * @throws \Exception
     */
    public function onOpen(ConnectionInterface $conn)
    {
        //take user id
        if (!$this->getUserFromSession($conn))
            return;
        
        // $this->clients->attach($conn);

        $this->online[$conn->resourceId] = $conn->session->get(Auth::getName());

        //save connection and user id to clients array
        $this->clients[$conn->resourceId] = $conn;
    }
    
     /**
     * Get user id by user session
     * @param  ConnectionInterface $conn The socket/connection that just connected to your application
     * @return int id
     */
    protected function getUserFromSession(ConnectionInterface $conn)
    { 
        // Create a new session handler for this client
        $session = (new SessionManager(App::getInstance()))->driver();
        

        if (Config::get('session.driver') === 'file') {  
            clearstatcache();
        }

        // Get the cookies
        $cookies = $conn->httpRequest->getHeader('Cookie');
        
        if(!count($cookies))
            return (false);

        // Array of cookies
        $cookies = \GuzzleHttp\Psr7\parse_header($cookies)[0];

        if (empty($cookies[Config::get('session.cookie')]))
            return (false);

        // Get the laravel's one
        $laravelCookie = urldecode($cookies[Config::get('session.cookie')]);
        
        // get the user session id from it
        $idSession = Crypt::decrypt($laravelCookie, false);

        // Set the session id to the session handler
        $session->setId($idSession);

        // Bind the session handler to the client connection
        $conn->session = $session;
        $conn->session->start();

        //Take the user from a session
        // $userId = $conn->session->get(Auth::getName());
        
        return (true);
    }

     /**
     * This is called before or after a socket is closed (depends on how it's closed).  SendMessage to $conn will not result in an error if it has already been closed.
     * @param  ConnectionInterface $conn The socket/connection that is closing/closed
     * @throws \Exception
     */
    public function onClose(ConnectionInterface $conn)
    {
        $disconnectedId = $conn->resourceId;

        //Delete closed user socket and user id from online users
        unset($this->online[$disconnectedId]);

        //Delete closed user socket id from clients
        unset($this->clients[$disconnectedId]);
    }
    
    /**
     * If there is an error with one of the sockets, or somewhere in the application where an Exception is thrown,
     * the Exception is sent back down the stack, handled by the Server and bubbled back up the application through this method
     * @param  ConnectionInterface $conn
     * @param  \Exception $e
     * @throws \Exception
     */
    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        echo $e->getMessage() . '\n';
        // $userId = $conn->session->get(Auth::getName());
        // echo "An error has occurred with user $userId: {$e->getMessage()}\n";
        unset($this->clients[$conn->resourceId]);
        $conn->close();
    }
    
     /**
     * Triggered when a client sends data through the socket
     * @param  \Ratchet\ConnectionInterface $conn The socket/connection that sent the message to your application
     * @param  string $msg The message received
     * @throws \Exception
     */
    public function onMessage(ConnectionInterface $from, $msg)
    {
        $msg = json_decode($msg, false);

        switch ($msg->type) {
            case 'message': {
                $this->sendMessage($from, $msg);
                break;
            }
            case 'notification': {
                $this->sendNotification($from);
                break;
            }
            case 'newNotification': {
                $this->addNewNotification($from, $msg);
                break;
            }
        }
    }

    protected function sendMessage(ConnectionInterface $from, $msg)
    {
        $message = new MessageController(); 

        if (!$message->validateMessageRules($msg, $from->session->get(Auth::getName())))
            return;

        $recipient_data = $message->newMessageAdd();
        $recipient_key = array_search($recipient_data['to'], $this->online);

        if (empty($this->online[$recipient_key])){
            $message->deferNotifi();
            return;
        }

        $this->clients[$recipient_key]->send(
            json_encode([
                'type' => 'message',
                'action' => 'message',
                'room' => $recipient_data['room'],
                'from' => $recipient_data['from'],
                'msg' => $recipient_data['msg'],
                'time' => $recipient_data['time']
            ])
        );
    }

    protected function sendNotification(ConnectionInterface $from)
    {
        $id = $from->session->get(Auth::getName());

        $notification = Notifi::where('to_id', $id)
                            ->join('users', 'users.id', '=', 'from_id')
                            ->select('from_id', 'type', 'users.login')
                            ->get();

        if (empty($notification))
            return;

        $data = [];
        foreach ($notification as $user){
            array_push($data, [
                'login' => ucfirst(strtolower($user->login)),
                'type' => $user->type,
            ]);
        }

        // Notifi::where('to_id', $id)->delete();

        $from->send(
            json_encode([
                'type' => 'notification',
                'notifi_data' => $data,
            ])
        );
    }

    protected function addNewNotification(ConnectionInterface $from, $msg)
    {
        $from_id = $from->session->get(Auth::getName());

        if (!$recipient_key = array_search($msg->to_id, $this->online)){

            Notifi::create([
                'to_id' => $msg->to_id,
                'from_id' => $from_id,
                'type' => $msg->action,
            ]);
        }
        else{
            $this->clients[$recipient_key]->send(
                json_encode([
                    'type' => 'alert',
                    'from' => ucfirst(strtolower(User::find($from_id)->login)),
                    'action' => $msg->action,
                ])
            );
        }
    }
}
