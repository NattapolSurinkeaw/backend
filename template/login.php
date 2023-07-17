<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Wynnsoft Management</title>
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>css/bootstrap/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>css/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>plugins/jquery-confirm/css/jquery-confirm.css">
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>css/login-style.css">
    <!-- Google Font -->
    <link href='https://fonts.googleapis.com/css?family=Roboto:100,200,300,400,500,600,700,800,900' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Oswald:100,200,300,400,500,600,700,800,900' rel='stylesheet' type='text/css'>
    
    <script src="https://www.google.com/recaptcha/api.js?render=6LeKgfYUAAAAAIKlaV8XhOydFlZMPBrKC9Ya3PEz&hl=th"></script>
  </head>
  <body class="hold-transition login-page">
    <div class="body_admin_login">
      <div class="login_wynn_logo">
        <img src="<?php echo SITE_URL; ?>images/Logo-Wynnsoft-Management.png">
      </div>
      <h2 style="text-align:center;margin-top:35px;color:#006699">Welcome To Backend </h2>
      <div style="text-align:center;margin:0px;color:#006699;position:absolute;top:12px;left:157px;font-size:14px;">
      <span style="margin:0;color:steelblue">W</span>
      <span style="margin:0;color:red">y</span>
      <span style="margin:0;color:yellow">n</span>
      <span style="margin:0;color:yellow">n</span>
      <span style="margin:0;color:steelblue">s</span>
      <span style="margin:0;color:red">o</span>
      <span style="margin:0;color:mediumseagreen">f</span>
      <span style="margin:0;color:mediumseagreen">t</span>
    </div>
      <div class="blog_form_login">
        <form id="formlogin">
          <label for="" style="color: #7e7e7e;"><i class="fa fa-envelope" aria-hidden="true"></i> Email: </label>
          <input type="text" name='email' placeholder="E-Mail" id="user" style="margin:0 0 15px 0;">
          <div class="text-error user-error"></div>
          <label for="" style="color: #7e7e7e;"><i class="fa fa-unlock-alt" aria-hidden="true"></i> Password: </label>
          <input type="password" name="password" id="password" placeholder="Password" style="margin:0 0 15px 0;">
          <div class="text-error password-error"></div>
          <button type="submit" class="bt_login">
            <i class="fa fa-sign-in" aria-hidden="true"></i>
            Login
          </button>
        </form>
        <ul class="nav nav-tabs editor_pw">
          <li class="forgot_or_regis"><a data-toggle="tab" href="#forgot_pw" id="forgot-pw" class="button-forgot"><i class="fa fa-unlock-alt"></i>Forgot Password</a></li>
          <li class="forgot_or_regis"><a data-toggle="tab" href="#editor_regis" id="editor-regis" class="button-regis"><i class="fa fa-user-secret"></i>Admin Register</a></li>
        </ul>
        <div class="tab-content">
          <div class="tab-pane fade" id="editor_regis">
            <div class="blog_form_editor_regis" style="margin-top: 20px;">
              <form id="formregis">
                
                <label for="" style="color: #7e7e7e;"><i class="fa fa-envelope" aria-hidden="true"></i> Email: </label>
                <input type="text" name="email" id="regis-email" placeholder="E-Mail" style="margin:0 0 10px 0;">
                <div class="text-error regis-email-error"></div>
                <label for="" style="color: #7e7e7e;"><i class="fa fa-unlock-alt" aria-hidden="true"></i> Password: </label>
                <input type="password" name='password' id="regis-pass" placeholder="Password" style="margin:0 0 10px 0;">
                <div class="text-error regis-pass-error"></div>
                <label for="" style="color: #7e7e7e;"><i class="fa fa-user" aria-hidden="true"></i> Display Name: </label>
                <input type="text" name='display-name' id="regis-display" placeholder="Display Name" style="margin:0 0 10px 0;">
                <div class="text-error regis-display-error"></div>
                <label for="" style="color: #7e7e7e;"><i class="fa fa-phone" aria-hidden="true"></i> Phone number: </label>
                <input type="text" name='phone' id="regis-phone" placeholder="Phone number" style="margin:0 0 10px 0;">
                <button class="bt-editor-regis button-regis" id="bt-editor-regis" style="width: 100%;"><i class="fa fa-user-plus" aria-hidden="true"></i> Register</button>
              </form>
            </div>
          </div>
          <div class="tab-pane fade" id="forgot_pw">
            <div class="blog_form_editor_regis" style="margin-top: 50px;">
              <form id="formforgot">
              <label for="" style="color: #7e7e7e;"><i class="fa fa-envelope" aria-hidden="true"></i> Email: </label>
                <input name ="email" id="email-editor-forgot" type="text" placeholder="E-Mail" style="margin:0 0 15px 0;">
                <div class="text-error email-forgot-error"></div>
                <button class="bt-editor-regis button-reset" id="bt-editor-forgot"><i class="fa fa-key" aria-hidden="true"></i> reset password</button>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </body>
  <script>
      var tokenderr = "";
      grecaptcha.ready(function() {
          grecaptcha.execute('6LeKgfYUAAAAAIKlaV8XhOydFlZMPBrKC9Ya3PEz', {action: 'login'}).then(function(token) {
            tokenderr = token
          });
      });  
  </script>
  <script src="<?php echo SITE_URL; ?>js/jquery/jquery.min.js"></script>
  <script src="<?php echo SITE_URL; ?>js/jquery-ui.min.js"></script>
  <script src="<?php echo SITE_URL; ?>js/bootstrap.min.js"></script>
  <script src="<?php echo SITE_URL; ?>plugins/jquery-confirm/js/jquery-confirm.min.js"></script>
  <script src="<?php echo SITE_URL; ?>js/pages/login.js?v=<?=date('mdhis')?>"></script>

  <style>
    .grecaptcha-badge{
      bottom: 2% !important;
      right: 1% !important; 
    }
  </style>
</html>
