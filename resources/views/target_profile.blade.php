@extends('layouts.default')

<!-- ACTIVE NAV-LINK -->
@section('home')
{{'active'}}
@endsection
<!-- /ACTIVE NAV-LINK -->

<!-- CONTENT -->
@section('content')

<div class="container-fluid">
@if (!empty($data))
<div class="row mr-20">
    <div class="col-md-6 resizable-choice">
        <ul class="tab-group">
            <li class="tab active"><a href="#Gallery">Gallery</a></li>
        </ul>
    </div>
</div>

<div class="row a4" id="profile-content">

<!-- Profile IMG Area -->
<div class="col-md-8 profile_img profile_choice resizable-area" id="gallery">
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">Gallery</h4>
        </div>
        <div class="card-body">
            <div class="row user_img_area">
                @if (!empty($data['content']))
                @foreach ($data['content'] as $content)
                <div class="row fle_xeble taget_img pos_rel">
                    <div class="col-md-11 pos_rel img_cont">
                        <img class="form-group pr_img_21" src="{{$content['img']}}" data="{{$content['id']}}">
                        <div class="user_func posr_abs hov_func" style="display: none">
                            <div class="hov_comments_fa hov_fa comments col-white"></div>
                            <div class="hov_img_fa_red hov_fa hov_img_fa see_img_likes col-white like pos_rel">
                                <small class="like like_count posr_abs">
                                <!-- возв ajax с колл. лайков -->
                                </small>
                            </div>
                        </div>
                        <div class="box_commnets_hidden posr_abs none">
                            <div class="commment_exit_box">
                                <div class="comment_close"></div>
                            </div>
                            <div class="row comment_box">
                                <div class="col-md-12 users_coments">
                                    <!-- возврат ajax с комментами -->
                                </div>
                                <div class="col-md-12 add_new_comment">
                                    <label>Add your comment</label>
                                    <div class=" textarea_comment_cont">
                                        <textarea class="form-control new_comment" name="new_comment" placeholder="Add your comment"></textarea>
                                        <p class="btn snd_new_comment">Add</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="box_likes_hidden posr_abs none">
                            <div class="commment_exit_box">
                                <div class="comment_close"></div>
                            </div>
                            <div class="row like_box">
                                <div class="col-md-12 users_like">
                                    <!-- возврат ajax с лайками -->
                                </div>
                                <div class="col-md-12 add_new_like">
                                    <label>Add your like</label>
                                   
                                        <p class="btn snd_new_like form-control">Add / Remove like</p>
                                </div>
                            </div>
                        </div>
                    </div>
                            
                </div>
                @endforeach
                @endif
            </div>
        </div>
    </div>
</div>
<!-- Profile IMG Area -->

<!-- User Info Area -->
<div class="col-md-4 resizable-info-area">
    <div class="card card-user">
        <div class="card-image">
            <img src="{{'img/sidebar/' . $data['user']['backgroundImg']}}" alt="...">
       	</div>
        <div class="card-body">
            <div class="author">
                <a href="#">
                    <label>
                	   <img class="avatar border-gray cursor" src="{{$data['info']['icon']}}" alt="...">
                	</label>
                    <h5 class="title">{{$data['info']['first_name'] . " " . $data['info']['last_name']}}</h5>
                </a>
                <p class="description">{{$data['user']['login']}}</p>
            </div>
            <p class="description text-center">
                <span>Age: </span>{{$data['info']['age']}}
            </p>
            <p class="description text-center">
                <span>Birthday: </span>{{
                $data['birthday']['day'] . "." . $data['birthday']['month'] . "." . $data['birthday']['year']
            }}
            </p>
            <p class="description text-center">
                <span>Location: </span>
                @if ($data['location']['user_access'])
                    {{$data['location']['country'] . "," . $data['location']['city']}}
                @endif
            </p>
            <p class="description text-center">
                <span>Interests: </span>
                  @if ($data['interests'] && !empty($data['interests'][0]))
                    @foreach ($data['interests'] as $tag)
                        {{'#' . $tag . " "}}
                    @endforeach
                    @endif
            </p>
            <p class="description text-center">
                <span>About: </span>
                {{$data['info']['about']}}
            </p>
       	</div>
    </div>
</div>
<!-- /User Info Area -->
</div>
</div>
@else
<div class="col-md-12">
    <div class="card">
        <div class="card-body">
            <h1 class="no_info c-e74">
                Sorry, you was blocked by this user.
            </h1>
        </div>
    </div>
</div>
@endif

@endsection
<!-- /CONTENT -->

<!-- CONTENT SCRIPT -->
@section('script')
    <script type="text/javascript" src="js/user.js"></script>
@endsection
 <!-- /CONTENT SCRIPT -->