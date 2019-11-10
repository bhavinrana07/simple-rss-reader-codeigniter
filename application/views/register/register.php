<?php $this->load->view('common/header'); ?>

<body>
  <div class="form">

    <ul class="tab-group">
      <li class="tab active"><a href="#signup">Sign Up</a></li>
      <li class="tab"><a href="#login">Log In</a></li>
    </ul>

    <div class="tab-content">
      <div id="signup">
        <h1>Sign Up for Free</h1>
        <div id="errors_register">

        </div>
        <form action="<?php echo base_url(); ?>index.php/register/registration/" method="post" id="form_register">

          <div class="top-row">
            <div class="field-wrap">
              <label>
                First Name<span class="req">*</span>
              </label>
              <input type="text" autocomplete="off" name="first_name" />
            </div>

            <div class="field-wrap">
              <label>
                Last Name<span class="req">*</span>
              </label>
              <input type="text" autocomplete="off" name="last_name" />
            </div>
          </div>

          <div class="field-wrap">
            <label>
              Email Address<span class="req">*</span>
            </label>
            <input type="text" autocomplete="off" name="email" />
            <span class="email_message"></span>
          </div>

          <div class="field-wrap">
            <label>
              Set A Password<span class="req">*</span>
            </label>
            <input type="password" autocomplete="off" name="password" />
          </div>

          <button type="submit" class="button button-block" />Get Started</button>

        </form>
      </div>

      <div id="login">
        <h1>Welcome Back!</h1>
        <div id="errors_login">
        </div>

        <form action="<?php echo base_url(); ?>index.php/register/login/" method="post" id="form_login">

          <div class="field-wrap">
            <label>
              Email Address<span class="req">*</span>
            </label>
            <input type="text" autocomplete="off" name="email" />
          </div>

          <div class="field-wrap">
            <label>
              Password<span class="req">*</span>
            </label>
            <input type="password" autocomplete="off" name="password" />
          </div>


          <button type="submit" class="button button-block" />Log In</button>

        </form>

      </div>

    </div>

  </div>
  <?php $this->load->view('common/footer'); ?>

</body>

</html>