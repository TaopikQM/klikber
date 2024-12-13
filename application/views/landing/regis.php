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
                     <h2>Register</h2>
                     <span>Silahkan Lengkapi data</span>
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
                     <form class="main_form" action="<?php echo site_url('landing/store'); ?>" method="post"> 
            
                     <div class="row">
                     <div class="col-md-12">
                        <label for="nama">Nama Pasien</label>
                        <input type="text" class="form-control" name="nama" id="nama" value="<?php echo set_value('nama'); ?>" required>
                        <?php echo form_error('nama', '<div class="text-danger">', '</div>'); ?>
                     </div>

                     <!-- Nomor ktp -->
                     <div class="col-md-12">
                        <label for="no_ktp">Nomor KTP</label>
                        <input type="number" class="form-control" name="no_ktp" id="no_ktp" value="<?php echo set_value('no_ktp'); ?>" maxlength="20" required>
                        <?php echo form_error('no_ktp', '<div class="text-danger">', '</div>'); ?>
                     </div>
                     
                     <!-- Nomor HP -->
                     <div class="col-md-12">
                        <label for="no_hp">Nomor HP</label>
                        <input type="number" class="form-control" name="no_hp" id="no_hp" value="<?php echo set_value('no_hp'); ?>" maxlength="17" required>
                        <?php echo form_error('no_hp', '<div class="text-danger">', '</div>'); ?>
                     </div>

                     <div class="col-md-12">
                        <label for="no_rm">Nomor RM</label>
                        <input type="text" class="form-control" id="no_rm" name="no_rm" value="<?= $no_rm; ?>" readonly>
                        <?php echo form_error('no_rm', '<div class="text-danger">', '</div>'); ?>
                     </div>

                     <!-- Alamat -->
                     <div class="col-md-12">
                        <label for="alamat">Alamat</label>
                        <textarea class="form-control" name="alamat" id="alamat" required><?php echo set_value('alamat'); ?></textarea>
                        <?php echo form_error('alamat', '<div class="text-danger">', '</div>'); ?>
                     </div>

                     
                        <div class="col-md-12 ">
                           <label for="alamat">Username dan Pass</label>
                           <div class="input-group" style="display: flex; align-items: center;">
                              <input 
                                    class="form-control" 
                                    id="usernameInput" 
                                    placeholder="Username" 
                                    type="text" 
                                    name="user" 
                                    value="<?= $no_rm; ?>" 
                                    readonly
                                    style="flex: 1; margin-right: 10px;"
                              >
                              <button 
                                    type="button" 
                                    onclick="copyToClipboard()" 
                                    style="cursor: pointer; background: none; border: none;"
                              >
                                    <i class="fa fa-copy" style="font-size: 20px; color: #007bff;"></i>
                              </button>
                           </div>
                        </div>
                        <div class="col-sm-12">
                           <button class="send_btn" type="submit">Register</button>
                        </div>
                        
                        
                     </div>
                  </form>
                  <form class="main_form" method="post" action="<?php echo base_url('landing'); ?>">
                     <div class="row">
                        <div class="col-sm-12">
                              <button class="send_btn" type="submit">Login</button>
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
<script>
    function copyToClipboard() {
        // Ambil elemen input
        const input = document.getElementById('usernameInput');
        // Pilih teks di dalam input
        input.select();
        input.setSelectionRange(0, 99999); // Untuk perangkat mobile
        // Salin teks ke clipboard
        navigator.clipboard.writeText(input.value)
            .then(() => {
                alert('Username dan Password ' + input.value + ' telah disalin!');
            })
            .catch(err => {
                alert('Gagal menyalin: ' + err);
            });
    }
</script>


