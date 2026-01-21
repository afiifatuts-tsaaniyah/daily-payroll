<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Main CSS-->
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>/assets/css/main.css">
    <!-- Font-icon css-->
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>/fontawesome513/css/all.min.css">
    <!-- <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css"> -->
    <title>Login - Agincourt</title>
  </head>
  <body>
    <section class="material-half-bg">
      <div class="cover"></div>
    </section>
    <section class="login-content">
      <!-- <div class="logo"> -->
        <h1 style="color: white">Agincourt</h1>
      <!-- </div> -->
      <div class="login-box">
        <!-- <form class="login-form" method="POST" action="<?php #echo base_url() ?>/User/Activity/login"> -->
        <!-- FORM OPEN -->
        <?php
          $attributes = ['class' => 'login-form', 'id' => 'login-form'];
          echo form_open(base_url().'/Home/login', $attributes);
        ?>

          <h3 class="login-head"><i class="fas fa-lg fa-fw fa-user"></i>SIGN IN</h3>
           
          <div class="form-group">
            <label class="control-label">USERNAME</label>
            <input class="form-control" name="inputUser" type="text" placeholder="User Name" autofocus>
          </div>
          <div class="form-group">
            <label class="control-label">PASSWORD</label>
            <input class="form-control" name="inputPassword" type="password" placeholder="Password">
          </div>
          
          <div class="form-group btn-container">
            <button id="btnSignIn" class="btn btn-primary btn-block" type="submit"><i class="fas fa-sign-in-alt fa-fw"></i> SIGN IN</button>
          </div>
          <?php
            $session = \Config\Services::session();
            if(!empty($session->getFlashdata('errors'))){
              echo '<h5>
                          <code id="storeIdErr" class="errMsg"><span><div id="loginMsg">'.$session->getFlashdata('errors').'</span></code>
                        </h5>';

            } else if (!empty($session->getFlashdata('pesan'))) {
              echo '<h5>
                          <code id="storeIdErr" class="errMsg"><span><div id="loginMsg"></span></code>
                        </h5>';
          }            
          ?>
        <?php echo form_close() ?>  
        <!-- </form> -->
        <form class="forget-form" action="index.html">
          <!-- <h3 class="login-head"><i class="fas fa-lg fa-fw fa-lock"></i>Forgot Password ?</h3> -->
          <div class="form-group">
            <label class="control-label">EMAIL</label>
            <input class="form-control" type="text" placeholder="Email">
          </div>
          <div class="form-group btn-container">
            <button class="btn btn-primary btn-block"><i class="fas fa-unlock fa-lg fa-fw"></i>RESET</button>
          </div>
          <div class="form-group mt-3">
            <p class="semibold-text mb-0"><a href="#" data-toggle="flip"><i class="fa fa-angle-left fa-fw"></i> Back to Login</a></p>
          </div>
        </form>
      </div>
    </section>
    <!-- Essential javascripts for application to work-->
    <script src="<?php echo base_url(); ?>/assets/js/jquery-3.3.1.min.js"></script>
    <script src="<?php echo base_url(); ?>/assets/js/popper.min.js"></script>
    <script src="<?php echo base_url(); ?>/assets/js/bootstrap.min.js"></script>
    <script src="<?php echo base_url(); ?>/assets/js/main.js"></script>
    <!-- The javascript plugin to display page loading on top-->
    <script src="<?php echo base_url(); ?>/assets/js/plugins/pace.min.js"></script>
    <script type="text/javascript">
      // $(".errSaveMess").html("");
      //  if(bankCode.trim() == "")
      //  {
      //    $("#bankCode").focus();
      //    $(".errSaveMess").html("Bank Code cannot be empty");
      //  }
      //  else if(bankName.trim() == "")
      //  {
      //    $("#bankName").focus();
      //    $(".errSaveMess").html("Bank Name cannot be empty");
      //  }
      // Login Page Flipbox control
      $('.login-content [data-toggle="flip"]').click(function() {
      	$('.login-box').toggleClass('flipped');
      	return false;
      });
    </script>
  </body>
</html>