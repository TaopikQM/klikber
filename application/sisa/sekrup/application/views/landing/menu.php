<?php 
if($this->session->userdata('idus') == null) {
    redirect('landing');
} ?>
<!DOCTYPE html>
<html lang="en">
   <head>
      <!-- Basic meta tags -->
      <meta charset="utf-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <title>e-Sekretariat</title>
      <!-- Bootstrap CSS -->
      <link rel="stylesheet" href="<?php echo base_url()?>harta/landing/css/bootstrap.min.css">
      <!-- Custom CSS -->
      <link rel="stylesheet" href="<?php echo base_url()?>harta/landing/css/style.css">
      <!-- Responsive CSS -->
      <link rel="stylesheet" href="<?php echo base_url()?>harta/landing/css/responsive.css">
      <!-- Favicon -->
      <link rel="icon" href="<?php echo base_url()?>harta/landing/images/jtg.png" type="image/gif" />
   </head>
   <body class="main-layout">
      
         <header>
            <div class="head_top">
               <div class="header">
                  <div class="container-fluid">
                     <div class="row">
                        <div class="col-xl-1 col-lg-1 col-md-1 col-sm-1 col logo_section">
                           <div class="full">
                              <div class="center-desk">
                                 <div class="logo">
                                    <a href="#"><img src="<?php echo base_url()?>harta/landing/images/klikber1.png" alt="#" /></a>
                                 </div>
                              </div>
                           </div>
                        </div>
                        <div class="col-xl-9 col-lg-9 col-md-9 col-sm-9">
                           <nav class="navigation navbar navbar-expand-md navbar-dark ">
                              <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExample04" aria-controls="navbarsExample04" aria-expanded="false" aria-label="Toggle navigation">
                              <span class="navbar-toggler-icon"></span>
                              </button>
                              <div class="collapse navbar-collapse" id="navbarsExample04">
                                 <ul class="navbar-nav mr-auto">
                                    <li class="nav-item">
                                       <a class="nav-link" href="#">Selamat Datang, <?php echo $name; ?>, <?php echo $username; ?> </a>
                                       <p class="text-center" ><?php echo $role; ?></p>
                                       <!-- <p class="text-center">ID: <?php echo $id_filtered; ?></p>
                                       <?php echo $name; ?> -->
                                    </li>
                                    <li class="nav-item">
                                       <a  href="<?php echo base_url()?>landing/out" class="btn btn-danger">LogOut</a>
                                    </li>
                                 </ul>
                              </div>
                           </nav>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </header>

        
         <div class="container">
            <div class="row">
               <div class="col-md-12">
                     <h3>Welcome, <?php echo $username; ?>!</h3>
                     <p>Your role is: <?php echo $role; ?></p>
                     <?php if (isset($user_data)): ?>
                        <h4>Details:</h4>
                        <ul>
                           <?php foreach ($user_data as $key => $value): ?>
                                 <li><strong><?php echo ucfirst($key); ?>:</strong> <?php echo $value; ?></li>
                           <?php endforeach; ?>
                        </ul>
                     <?php endif; ?>
                     <a href="<?php echo base_url('landing/out'); ?>" class="btn btn-danger">Logout</a>
               </div>
            </div>
         </div>

         <div class="business">
            <div class="container">
               <div class="row">
                  <?php 
                  $nbv = base64_encode('apli');
                  $hjk = $this->session->userdata(base64_encode('jajahan'));
                  
                  // foreach ($hjk as $key) {
                  //    if (base64_decode($key[$nbv]) == '1_morsip') { ?>
                        <!-- <div class="col-md-3">
                           <div class="titlepage">
                              <a href="<?php echo base_url('morsip');?>">
                              <img src="<?php echo base_url()?>harta/landing/images/mor-mb.png" alt="e-Morsip"/>
                              <p>Elekronik Nomor dan Arsip</p></a>
                           </div>
                        </div> -->
                     <!-- < ?php
                     } elseif (base64_decode($key[$nbv]) == '2_tamu') { ?> -->
                        <!-- <div class="col-md-3">
                           <div class="titlepage">
                              <span>APLIKASI TAMU</span>
                              <h2>Better Electric System</h2>
                              <p>Sistem Pemerintahan Berbasis Elektronik</p>
                           </div>
                        </div> -->
                     <!-- < ?php 
                     }
                  } ?> -->
                  <!-- <div class="col-md-3">
                           <div class="titlepage">
                              <a href="<?php echo base_url('morsip');?>">
                              <img src="<?php echo base_url()?>harta/landing/images/mor-mb.png" alt="e-Morsip"/>
                              <p>Elekronik Nomor dan Arsip</p></a>
                           </div>
                        </div>
                  <div class="col-md-3">
                     <div class="titlepage">
                        <a href="<?php echo base_url('pinjam');?>">
                        <img src="<?php echo base_url()?>harta/landing/images/pinjam.png" alt="e-Pinjam"/>
                        <p>Elekronik Peminjaman Aset</p></a>
                     </div>
                  </div> -->
                  <div class="row">
                     <?php if ($role == 'Dokter'): ?>
                        <div class="col-md-3">
                              <div class="titlepage">
                                 <a href="<?php echo base_url('dokter'); ?>">
                                    <img src="<?php echo base_url()?>harta/landing/images/klikber.png" alt="klikber"/>
                                    <p>Klinik Bersama</p>
                                 </a>
                              </div>
                        </div>
                     <?php elseif ($role == 'Pasien'): ?>
                        <div class="col-md-3">
                              <div class="titlepage">
                                 <a href="<?php echo base_url('pasien/profile'); ?>">
                                    <img src="<?php echo base_url()?>harta/landing/images/klikber.png" alt="klikber"/>
                                    <p>Klinik Bersama</p>
                                 </a>
                              </div>
                        </div>
                     <?php elseif ($role == 'Admin'): ?>
                        <div class="col-md-3">
                              <div class="titlepage">
                                 <a href="<?php echo base_url('admin'); ?>">
                                    <img src="<?php echo base_url()?>harta/landing/images/klikber.png" alt="klikber"/>
                                    <p>Klinik Bersama</p>
                                 </a>
                              </div>
                        </div>
                     <?php endif; ?>
                  </div>
                  <!-- <div class="col-md-3">
                     <div class="titlepage">
                        <a href="<?php echo base_url('dokter');?>">
                        <img src="<?php echo base_url()?>harta/landing/images/klikber.png" alt="klikber"/>
                        <p>Klinik Bersama</p></a>
                     </div>
                  </div>
                  <div class="col-md-3">
                     <div class="titlepage">
                        <a href="<?php echo base_url('pasien');?>">
                        <img src="<?php echo base_url()?>harta/landing/images/klikber.png" alt="klikber"/>
                        <p>Klinik Bersama</p></a>
                     </div>
                  </div>
                  <div class="col-md-3">
                     <div class="titlepage">
                        <a href="<?php echo base_url('admin');?>">
                        <img src="<?php echo base_url()?>harta/landing/images/klikber.png" alt="klikber"/>
                        <p>Klinik Bersama</p></a>
                     </div>
                  </div> -->
               </div>
            </div>
         </div>
         
         
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
            
         
      
      <script src="<?php echo base_url()?>harta/landing/js/jquery.min.js"></script>
      <script src="<?php echo base_url()?>harta/landing/js/bootstrap.bundle.min.js"></script>
   </body>
</html>
