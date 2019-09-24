@extends('layouts.default')

@section('settings')
{{'active'}}
@endsection

<!-- CONTENT -->
@section('content')
<div class="container-fluid">


<div class="row">
<div class="col-md-12">
<div class="card">
<div class="card-header settings_header">

	<div class="row mr-rigtht-20">
		<div class="col-md-12">
			<p class="set_change_name">Change your login:</p>
			<p class="btn" id="set_change_login"><label>Change Login</label></p>
		</div>
	</div>
	<div class="row mr-rigtht-20">
		<div class="col-md-12">
			<p class="set_change_name">Change your password:</p>
			<p class="btn" id="set_change_passwd"><label>Change Password</label></p>
		</div>
	</div>
	<div class="row mr-rigtht-20">
		<div class="col-md-12">
			<p class="set_change_name">Change your email:</p>
			<p class="btn" id="set_change_email"><label>Change Email</label></p>
		</div>
	</div>

</div>
</div>
</div>
</div>


<div class="row">
<div class="col-md-12">
<div class="card">
<div class="card-body">

	<div class="row">
		<div class="col-md-6">
			<label>Add to Block list:</label>
			<div class="col-md-12 add_remove_block_cont">
				<input class="form-control" id="block_user_login" type="text" name="block" autocomplete="off">
				<p class="btn block_user_btn">Add</p>
			</div>
		</div>
		<div class="col-md-6">
			<label>Remove from Block list:</label>
			<div class="col-md-12 add_remove_block_cont">
				<input class="form-control" id="remove_block_user_login" type="text" name="block" autocomplete="off">
				<p class="btn remove_block_user_btn">Remove</p>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
			<label>List of blocked users:</label>
			<div class="col-md-12 blocked_user_container">
				@if ($data->count() > 0)
				@foreach ($data as $user)
				<div class="blocked_user_div">
					<img class="blocked_user_img" src="{{$user['icon']}}">
					<p class="blocked_user_login">{{$user['login']}}</p>
					<img class="block_user_div_btn" src="/img/icons/close-button.png" title="Remove from Blocked users?">
				</div>
				@endforeach
				@endif
			</div>
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
<script type="text/javascript" src="js/settings.js"></script>
@endsection
 <!-- /CONTENT SCRIPT -->