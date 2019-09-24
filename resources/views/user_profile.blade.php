@extends('layouts.default')

<!-- ACTIVE NAV-LINK -->
@section('user')
{{'active'}}
@endsection
<!-- /ACTIVE NAV-LINK -->

<!-- CONTENT -->
@section('content')

<div class="container-fluid">

<div class="row mr-20">
    <div class="col-md-6 resizable-choice">
        <ul class="tab-group">
            <li class="tab active"><a href="#Gallery">Gallery</a></li>
            <li class="tab"><a href="#Edit">Edit Profile</a></li>
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
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Add image to your gallery</label>
                        <div class="as">
                            <label>
                                <input type="file" name="img" class="none" id="inp_img">
                                <div class="form-group user_location_cont">
                                    <p class="btn form-control">Add</p>
                                </div>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Delete image from your gallery</label>
                        <div class="as">
                            <label>
                                <div class="form-group user_location_cont">
                                    <p class="btn form-control remove_img">Delete</p>
                                </div>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
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

<!-- Profile Edit Area -->
<div class="col-md-8 profile_edit profile_choice resizable-area" style="display: none;" id="edit">
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">Edit Profile</h4>
        </div>
        <div class="card-body">
        <form>
            <div class="row">
            	<div class="col-md-6 pr-1">
                	<div class="form-group">
                		<label>Username</label>
                		<input type="text" class="form-control" placeholder="Username" disabled="" value="{{ucfirst(strtolower($data['user']['login']))}}">
                	</div>
            	</div>
            	<div class="col-md-6 pr-1">
                	<div class="form-group">
                    	<label for="exampleInputEmail1">Email address</label>
                    	<input type="email" class="form-control" placeholder="Email" disabled="" value="{{$data['user']['email']}}">
                	</div>
            	</div>
        	</div>
            <div class="row">
                <div class="col-md-6 pr-1">
                    <div class="form-group">
                        <label>First Name</label>
                        <input type="text" class="form-control edit edit_inp" name="first_name" autocomplete="off" placeholder="First Name" value="{{$data['info']['first_name']}}">
                    </div>
                </div>
                <div class="col-md-6 pr-1">
                    <div class="form-group">
                        <label>Last Name</label>
                        <input type="text" class="form-control edit_inp" name="last_name" autocomplete="off" placeholder="Last Name" value="{{$data['info']['last_name']}}">
                	</div>
                </div>
            </div>
            <div class="row user_a_b">
                <div class="col-md-4 pr-1">
                    <div class="form-group">
                        <label>Gender</label>
                        <input type="text" class="form-control" placeholder="gender" disabled="" value="{{ $data['info']['gender']}}">
                    </div>
                </div>
                <div class="col-md-4 pr-1">
                    <div class="form-group">
                        <label>Orientation</label>
                        <p class="form-control dropdown-toggle nav-link flexible" data-toggle="dropdown">
                            <span>{{$data['info']['orientation']}}</span>
                        </p>
                        <input type="hidden" class="form-control">
                        <ul class="dropdown-menu orient_choose">
                            <a class="dropdown-item edit_select" name="orientation" change="flexible">Heterosexual</a>
                            <a class="dropdown-item edit_select" name="orientation" change="flexible">Bisexual</a>
                            <a class="dropdown-item edit_select" name="orientation" change="flexible">Homosexual</a>
                        </ul>
                    </div>
                </div>
                <div class="col-md-4 pr-1">
                    <div class="form-group">
                        <label>Age</label>
                        <input type="text" class="form-control edit_inp" name="age" autocomplete="off" placeholder="Age" value="{{($data['info']['age'] === 0) ? '' : $data['info']['age']}}">
                    </div>
                </div>
            </div>
            <div class="row user_a_b">
                <div class="col-md-4 pr-1">
                    <div class="form-group">
                        <label>Birth Day</label>
                        <input type="text" maxlength="2" class="form-control edit_inp" name="day" autocomplete="off" placeholder="Day" value="{{($data['birthday']['day'] === 0) ? '' : $data['birthday']['day']}}">
                    </div>
                </div>
                <div class="col-md-4 pr-1">
                    <div class="form-group">
                        <label>Birth Month</label>
                        <p class="form-control dropdown-toggle nav-link birthday_buttn" data-toggle="dropdown">
                            <span>{{$data['birthday']['month'] === null ? 'Month' : $data['birthday']['month']}}</span>
                        </p>
                        <input type="hidden" class="form-control">
                        <ul class="dropdown-menu orient_choose">
                            <a class="dropdown-item edit_select" name="month" change="birthday_buttn">January</a>
                            <a class="dropdown-item edit_select" name="month" change="birthday_buttn">February</a>
                            <a class="dropdown-item edit_select" name="month" change="birthday_buttn">March</a>
                            <a class="dropdown-item edit_select" name="month" change="birthday_buttn">April</a>
                            <a class="dropdown-item edit_select" name="month" change="birthday_buttn">May</a>
                            <a class="dropdown-item edit_select" name="month" change="birthday_buttn">June</a>
                            <a class="dropdown-item edit_select" name="month" change="birthday_buttn">July</a>
                            <a class="dropdown-item edit_select" name="month" change="birthday_buttn">August</a>
                            <a class="dropdown-item edit_select" name="month" change="birthday_buttn">September</a>
                            <a class="dropdown-item edit_select" name="month" change="birthday_buttn">October</a>
                            <a class="dropdown-item edit_select" name="month" change="birthday_buttn">November</a>
                            <a class="dropdown-item edit_select" name="month" change="birthday_buttn">December</a>
                        </ul>
                    </div>
                </div>
                <div class="col-md-4 pr-1">
                    <div class="form-group">
                        <label>Birth Year</label>
                        <input type="text" maxlength="4" class="form-control edit_inp" name="year" autocomplete="off" placeholder="year" value="{{($data['birthday']['year'] === 0) ? '' : $data['birthday']['year']}}">
                    </div>
                </div>
            </div>
             <div class="row">
                <div class="col-md-12 pr-1">
                    <div class="form-group user_location_cont">
                        <p class="btn form-control remove_birthday">Remove Birthday</p>
                    </div>
                </div>
            </div>
            <div class="row">
            	<div class="col-md-6 pr-1">
                	<div class="form-group">
                    	<label>City</label>
                    	<input type="text" class="form-control city_local"disabled="" value="{{$data['location']['user_access'] === 1 ? $data['location']['city'] : ''}}">
                	</div>
            	</div>
                <div class="col-md-6 pr-1">
                	<div class="form-group">
                    	<label>Country</label>
                    	<input type="text" class="form-control country_local" disabled="" value="{{$data['location']['user_access'] === 1 ? $data['location']['country'] : ''}}">
                	</div>
            	</div>
            </div>

            <div class="row">
                <div class="col-md-12 pr-1">
                        <label>Location</label>
                    <div class="form-group user_location_cont">
                        <p class="btn form-control user_location_add">Add Location</p>
                        <p class="btn form-control user_location_remove">Remove Location</p>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <label>Add Interests</label>
                    <p class="help_small_user"><small>*Add your interests with tag #</small></p>
                    <p class="help_small_user"><small>*To save / write new tag just press # or press add</small></p>
                    <div style="display: flex;">
                            <input class="form-control" id="interestsHelp" autocomplete="off" placeholder="Add your interests with tag #" oninput="tagHelper(this.value)" value="#">
                            <p class="btn btn_inter" style="margin-left: 10px;cursor: pointer;">Add</p>
                    </div>
                    <div class="row pr-1 helperRel">
                        <div class="helperProfInt helperAbs" style="display: none;"></div>
                    </div>
                    <label>Remove Interests</label>
                    <p class="help_small_user"><small>To delete tag just click to tag</small></p>
                    <div class="col-md-12 form-group interest_cont">
                        @if ($data['interests'] && !empty($data['interests'][0]))
                            @foreach ($data['interests'] as $tag)
                                <p class="tag_se">#{{$tag}}</p>
                             @endforeach
                        @endif
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group about">
                        <label>About Me</label>
                        <p class="help_small_user"><small>*To save just click on an empty area or press button 'Add Changes'</small></p>
                        <textarea rows="4" cols="80" class="form-control edit_inp" name="about" placeholder="Here can be your description">{{$data['info']['about']}}</textarea>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 form-group fle_xeble_col">
                    <p class="btn btn_inter cursor flex_end">Add Changes</p>
                </div>
            </div>
        </form>
    	</div>
    </div>
</div>
<!-- /Profile Edit Area -->

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
                        <input type="file" id="profile_avatar" name="icon">
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

@endsection
<!-- /CONTENT -->

<!-- CONTENT SCRIPT -->
@section('script')
    <script type="text/javascript" src="js/user.js"></script>
@endsection
 <!-- /CONTENT SCRIPT -->