<!DOCTYPE html>
<html lang="en">
   <head>
      <!-- basic -->
      <meta charset="utf-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <!-- mobile metas -->
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <meta name="viewport" content="initial-scale=1, maximum-scale=1">
      <!-- site metas -->
      <title>e-Sekretariat</title>
      <meta name="keywords" content="">
      <meta name="description" content="">
      <meta name="author" content="">
      <!-- bootstrap css -->
      <link rel="stylesheet" href="<?php echo base_url()?>harta/landing/css/bootstrap.min.css">
      <!-- style css -->
      <link rel="stylesheet" href="<?php echo base_url()?>harta/landing/css/style.css">
      <!-- Responsive-->
      <link rel="stylesheet" href="<?php echo base_url()?>harta/landing/css/responsive.css">
      <!-- fevicon -->
      <link rel="icon" href="<?php echo base_url()?>harta/landing/images/jtg.png" type="image/gif" />
     
      <script src="<?php echo base_url()?>harta/morsip/assets/js/jquery-3.6.3.min.js"></script>
   </head>
   <!-- body -->
   <body class="main-layout">
       <script type="text/javascript">
        $(document).ready(function(){
            $('.but-ref').on('click', function(){
                    $.ajax({
                     url : "<?php echo base_url();?>landing/refresh_captcha_tte",
                     method : "POST",
                     async : false,
                     success: function(data){
                        $('#captcha').html(data);
                               
                     }
                 });
            });
        });
    </script>
      <!-- header -->
      <header>
         <!-- header inner -->
         <div  class="head_top">
            <div class="header">
               <div class="container-fluid">
                  <div class="row">
                     <div class="col-xl-3 col-lg-3 col-md-3 col-sm-3 col logo_section">
                        <div class="full">
                           <div class="center-desk">
                              <div class="logo">
                                 <a href="<?php echo base_url();?>landing"><img src="<?php echo base_url()?>harta/landing/images/logo.png" alt="#" /></a>
                              </div>
                           </div>
                        </div>
                     </div>
                     


                  </div>
               </div>
            </div>
            <!-- end header inner -->
            <!-- end header -->
            <!-- banner -->
            
         </div>
      </header>
      <!-- end banner -->
      <!-- business -->
      
      <!-- end business -->
      <!-- Projects -->
      
      <!-- end projects -->
      <!-- Testimonial -->
      
     
      <!-- end Testimonial -->
      <!-- contact -->
      <div id="contact" class="contact">
         <div class="container">
           
            <div class="row">
               <div class="col-md-12">
                  <div class="titlepage">
                     <h2>Login Proses TTE e-Morsip</h2>
                     <span>Silahkan LogIn Menggunakan User dan Password yang anda miliki</span>
                  </div>
               </div>
            </div>
         </div>
         <div class="container">
            <div class="row" id="form-section">
               <div class="col-md-12 ">
                     <?php
                     if($this->session->flashdata('notif_login') == TRUE){
                     ?>
                        <div class="alert alert-danger alert-mg-b-0" role="alert"><?php echo $this->session->flashdata('notif_login');?></div>
                     <?php
                     }
                     ?>
                  <form class="main_form" name="login" action="<?php echo base_url('landing/auth_tte');?>" method="post" enctype="multipart/form-data">
                     <div class="row">
                        <div class="col-md-12 ">
                           <input class="form_contril" placeholder="Username" type="text" name="user">
                        </div>
                        <div class="col-md-12">
                           <input class="form_contril" placeholder="Password" type="password" name="pass">
                        </div>
                        <div class="col-md-12">
                           <center><span id="captcha"><?php echo $captcha;?>&nbsp&nbsp&nbsp</span><button type="button" class="btn  btn-primary but-ref" ><i class="fa fa-circle-o-notch" aria-hidden="true"></i></button></center>
                        </div>
                        <div class="col-md-12">
                           <input class="form_contril" placeholder="Captcha" type="text" name="capt" autocomplete="off">
                        </div>
                        <div class="col-sm-12">
                           <button type="submit" class="send_btn">Log In</button>
                        </div>
                     </div>
                  </form>
               </div>
            </div>
         </div>
      </div>
      <!-- end contact -->
      <!--  footer -->
      <footer>
         <div class="footer">
            
            <div class="copyright">
               <div class="container">
                  <div class="row">
                     <div class="col-md-12">
                        <p>Copyright 2023 All Right Reserved By Sekretariat Dinas Komunikasi dan Inforatika Provinsi Jawa Tengah <br>Desain By <a href="https://html.design/"> Free  html Templates</a></p>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </footer>
      
   </body>
  
</html>

