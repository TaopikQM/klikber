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
      <title>KLIKBER</title>
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
      <link rel="icon" href="<?php echo base_url()?>harta/landing/images/klikber-i.png" type="image/gif" />
     
     
   </head>
   <!-- body -->
   <body class="main-layout">
      
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
                                 <a href="<?php echo base_url();?>landing"><img src="<?php echo base_url()?>harta/landing/images/klikber1.png" alt="#" /></a>
                                 <!-- <a href="<?php echo base_url('absen');?>">ab</a> -->
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
            <section class="banner_main">
               <div class="container-fluid">
                  <div class="row d_flex">
                     <div class="col-md-6">
                        <div class="text-bg">
                           <h1>KLIKBER</h1>
                           <p>Aplikasi yang dirancang untuk mengelola jadwal secara efisien, memfasilitasi interaksi antara penyedia layanan dan pengguna. Aplikasi ini membantu mengurangi waktu tunggu, mengoptimalkan alokasi waktu layanan, dan meningkatkan kepuasan pengguna, khususnya dalam layanan kesehatan.</p>
                        </div>
                     </div>
                     <div class="col-md-6">
                        <div class="text-img">
                           <figure><img src="<?php echo base_url()?>harta/landing/images/box_img.png" alt="#"/></figure>
                        </div>
                     </div>
                  </div>
               </div>
            </section>
         </div>
      </header>
      <!-- end banner -->
      <!-- business -->
      <div class="business">
         <div class="container">
            <div class="row">
               <div class="col-md-12">
                  <div class="titlepage">
                     <img src="<?php echo base_url()?>harta/landing/images/klikber1.png" alt="#"/>
                     <!-- <span>Reduce Administrative and More Faster</span>
                     <h2>Better Electric System</h2>
                     <p>Sistem Pemerintahan Berbasis Elektronik</p> -->
                  </div>
               </div>
            </div>
            
         </div>
      </div>
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
                     <h2>Login</h2>
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
                     
                  <form class="main_form" name="login" action="<?php echo base_url('landing/auth');?>" method="post" enctype="multipart/form-data">
                     <div class="row">
                        <div class="col-md-12 ">
                           <input class="form_contril" placeholder="Username" type="text" name="user">
                        </div>
                        <div class="col-md-12">
                           <input class="form_contril" placeholder="Password" type="password" name="pass">
                        </div>
                        <div class="col-md-12">
                           <center><?php echo $captcha;?>&nbsp<button class="btn  btn-primary"><i class="fa fa-circle-o-notch" aria-hidden="true"></i>
</button></center>
                        </div>
                        <div class="col-md-12">
                           <input class="form_contril" placeholder="Captcha" type="text" name="capt" autocomplete="off">
                        </div>
                        <div class="col-sm-12">
                           <button class="send_btn">Log In</button>
                        </div>
                        
                     </div>
                     
                  </form>
                  <form class="main_form" method="post" action="<?php echo base_url('landing/regis'); ?>">
                  <div class="row">
                     <div class="col-sm-12">
                           <button class="send_btn" type="submit">Regis</button>
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
                        <p>Copyright © <?php echo date('Y'); ?> All Rights Reserved By Klinik Bersama</p>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </footer>
      
   </body>
</html>

