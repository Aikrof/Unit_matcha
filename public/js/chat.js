document.addEventListener('DOMContentLoaded', function(){
    $("#search").on("keyup", function() {
        var value = $(this).val().toLowerCase();
        $("#friend-list li .username").filter(function() {
             $(this).parent().toggle($(this).text().toLowerCase().indexOf(value) > -1);
        });
    });
});


$('.list-group-item').click(function(){
    let $elem = $(this);

    let $active = $('#friend-list').find('.active');
    $active.removeClass('active');
    
    $('.chat_header > b').attr('data', "");

    $elem.children('.miss_message').text("");
    $elem.children('.miss_message').hide();

    if (!$('.chat_cont').attr('style'))
    {
        $('.chat_cont').hide();
        if ($active[0] === $elem[0])
            return;
    }

    let $login = $elem.find('.username').text();
    let $room = $elem.attr('data');

    let $data = {
        'login' : $login,
        'room' : $room
    };

    sender.form('/getMessages', {'data' : $data}, function(request){
        $('.message-scroll').children().remove();
        
        if (request.data){
            for (let data of request.data){
                $('.message-scroll').append(
                    printMessage(data)
                );
            }
        }
        
        $elem.addClass('active');

        $('.chat_header > b').text($login);
        $('.chat_header > b').attr('data', $room);
        $('.chat_cont').show();

        $('.message-scroll')[0].scrollTop = $('.message-scroll')[0].scrollHeight;
    });
});

(function(){
    $('.chat_text').on('keypress', function(key){
        if (key.which == 13 && key.shiftKey)
            return;
        else if (key.which == 13){
            let $msg = $(this).val().replace(/<\/?[^>]+(>|$)/g, "");

            $(this).val('');

            if (!checkText($msg))
                return;

            addNewMessage($msg);
            prepearMessage($msg);

            return (false);
        }
    });

    $('.send_text_msg').click(function(){
        let $msg = $('.chat_text').val().replace(/<\/?[^>]+(>|$)/g, "");

        $('.chat_text').val('');

        if (!checkText($msg))
            return;

        addNewMessage($msg);
        prepearMessage($msg);
    });

    function checkText($msg){
        for (let char of $msg){
            if (char !== " " && char != '\n' &&
                char != '\t')
                return (true);
        }

        return (false);
    }

    function addNewMessage($msg){

        let date = new Date();

        $('.message-scroll').append(
            printMessage({
                'user' : 'auth',
                'msg' : $msg,
                'time' : date.getHours() + ":" + date.getMinutes(),
            })
        );
        $('.message-scroll')[0].scrollTop += 42;
    }

    function prepearMessage($msg){
        
        let data = {
            'type' : 'message',
            'room' : $('.chat_header > b').attr('data'),
            'to' : $('.chat_header > b').text(),
            'msg' : $msg
        }

        sendMsg(data);
    }
}());

function getNewMessage(data){
    if (roomActive()){
        let obj = {
            'user' : "target",
            'msg' : data.msg,
            'time' : data.time,
        }
        $('.message-scroll').append(
            printMessage(obj)
        );
        $('.message-scroll')[0].scrollTop += 42;
    }
    else{
        let $miss = $('.username:contains("'+ data.from +'")').parent().children('.miss_message');
        
        $miss.show();
        
        if ($miss.text() !== "")
            $miss.text(parseInt($miss.text()) + 1);
        else
            $miss.text(1);
    }

    function roomActive(){
        let $user_room = $('.chat_header > b').attr('data');

        return ($user_room === data.room ? true : false);
    }
}


function printMessage(data){
    return ('\
    <div class="row '+getClassCont(data.user)+'">\
        <div class="card message-card m-1" style="word-break: break-word;">\
            <div class="card-body p-2 msg_'+ data.user +'">\
                <span class="body_message">'+ data.msg +'</span>\
                <span class="float-right mx-1">\
                    <small>'+ data.time +'</small>\
                </span>\
            </div>\
        </div>\
    </div>\
   ');

    function getClassCont(user){
        return (user === "auth" ? "justify-content-end" : "");
    }
}