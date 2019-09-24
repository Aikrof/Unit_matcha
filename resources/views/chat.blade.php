@extends('layouts.default')

@section('chat')
{{'active'}}
@endsection

<!-- CONTENT -->
@section('content')

<div class="container-fluid ">

<div class="row" style="display: flex;align-items: center;justify-content: center;">
<div class="col-md-10">

    <div class="row h-100">
        <div class="col-3 border-chat-lightgray px-0" id="sidebar" style="border: 1px solid #dfdfdf">
            <div id="sidebar-content" class="w-100 h-100">
                <div class="input-group p-0 d-xs-none" id="search-group">
                    <input type="text" class="form-control border-0" placeholder="Search..." id="search">
                    <span class="input-group-addon no-hover">
                        <i class="fa fa-search fa-fw" style="font-size: 20px;"></i>
                    </span>
                </div>
                <div class="sidebar-scroll" id="list-group">
                    <ul class="list-group w-100" id="friend-list">
                        @foreach($data as $user)

                        <li class="list-group-item p-1  hover-bg-lightgray" data="{{$user['room']}}">
                            <img class="chat_user_icon" src="{{$user['icon']}}">
                            <span class="d-xs-none username">{{$user['login']}}</span>
                            
                            @if ($user['new'])
                            <span class="badge badge-primary">new</span>
                            @endif
                            <span class="badge badge-success miss_message" style="display: none;"></span>
                        </li>
                        
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
        <div class="col d-flex p-0 see_when_click">
            <div class="card chat_cont" style="display: none;">
                <div class="card-header bg-darkblue py-1 px-2" style="flex: 1 1">
                    <div class="d-flex flex-row justify-content-start">
                        <div class="col">
                            <div class="my-0 chat_header">
                                <b class="c-e74" style="font-size: 21px;"></b>
                                <label><small>last seen Feb 18 2017</small></label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body bg-lightgrey d-flex flex-column p-0 chat_body">
                    <div class="container-fluid message-scroll" style="flex: 1 1;">
                    </div>
                </div>
                <div class="input-group send_new_messg_conn">
                    <textarea class="form-control border-0 chat_text" placeholder="Input message..."></textarea>
                    <span class="input-group-addon addon_pdg_btn no-hover send_text_msg">
                        <img src="/img/icons/telegram.png" class="telega_img">
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
</div>

@endsection
<!-- /CONTENT -->

<!-- CONTENT SCRIPT -->
@section('script')
<script type="text/javascript" src="js/chat.js"></script>
@endsection
 <!-- /CONTENT SCRIPT -->