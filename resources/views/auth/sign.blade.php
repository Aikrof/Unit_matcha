  <div class="form"> 
      <ul class="tab-group">
        <li class="tab active"><a href="#signup">Sign Up</a></li>
        <li class="tab"><a href="#signin">Sign In</a></li>
      </ul>
      
      <div class="tab-content">
        <div id="signup">   
          <h1 class="hh">Sign Up for Free</h1>
          
          <form id="register">
          <div class="top-row">
            <div class="field-wrap">
              <label>
                First Name<span class="req">*</span>
              </label>
              <input type="text" name="first_name" autocomplete="off" />
            </div>

            <div class="field-wrap">
              <label>
                Last Name<span class="req">*</span>
              </label>
              <input type="text" name="last_name" autocomplete="off"/>
            </div>
          </div>

           <div class="field-wrap">
            <label>
              Login<span class="req">*</span>
            </label>
            <input type="text" name="login" autocomplete="off"/>
          </div>

          <div class="field-wrap">
            <label>
              Email Address<span class="req">*</span>
            </label>
            <input type="email" name="email" autocomplete="off"/>
          </div>
          
          <div class="field-wrap">
            <label>
             Password<span class="req">*</span>
            </label>
            <input type="password" name="password" autocomplete="off"/>
          </div>

           <div class="field-wrap">
            <label>
              Confirm<span class="req">*</span>
            </label>
            <input type="password" name="confirm" autocomplete="off"/>
          </div>

          <div class="field-wrap">
            <div id="gen_cont">
              <span class="gender mitmash_v1" id="f_gender">Male</span><span class="gere">*</span> <span class="mitmash_v1">/</span> <span class="gender mitmash_v1" id="t_gender">Female</span><span class="gere">*</span>
              <input type="hidden" name="gender" id="gender">
            </div>
          </div>
          <button type="submit" class="sign_button sign_button-block"/>Sign Up</button>
          
          </form>

        </div>
        
        <div id="signin">   
          <h1 class="hh sig">Welcome Back!</h1>
          
          <form id="login">
          
            <div class="field-wrap sig">
            <label>
              Login<span class="req">*</span>
            </label>
            <input type="text" name="login" autocomplete="off"/>
          </div>
          
          <div class="field-wrap sig">
            <label>
              Password<span class="req">*</span>
            </label>
            <input type="password" name="password" autocomplete="off"/>
          </div>
          <div class="checkbox_cont sig">
              <input type="checkbox" id="checkbox_remember" data="1" name="remember" value="true" checked>
            <label for="checkbox_remember" id="leb_check"><div><p>Remember me</p></div></label>
          </div>


          <div class="forg_cont">
            <p class="forgot"><a>Forgot Password?</a></p>
          </div>
          <button type="submit" class="sign_button sign_button-block sig"/>Sign In</button>
          </form>

          <div class="tab-content">
            <form id="email">
             <div class="field-wrap forg_wrap">
            <label>
              Email Address<span class="req">*</span>
            </label>
            <input type="email" name="email" autocomplete="off"/>
           </div>
          <div class="field-wrap forg_wrap">
            <button type="submit" class="sign_button sign_button-block"/>Send New Password</button>
          </div>
            </form>
          <!-- </div> -->
        </div>
        
      </div><!-- tab-content -->
      
</div> <!-- /form -->
