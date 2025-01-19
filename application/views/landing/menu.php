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
      <title>KLIKBEE</title>
      <!-- Bootstrap CSS --> 
      <link rel="stylesheet" href="<?php echo base_url()?>harta/landing/css/bootstrap.min.css">
      <!-- Custom CSS -->
      <link rel="stylesheet" href="<?php echo base_url()?>harta/landing/css/style.css">
      <!-- Responsive CSS -->
      <link rel="stylesheet" href="<?php echo base_url()?>harta/landing/css/responsive.css">
      <!-- Favicon -->
      <link rel="icon" href="<?php echo base_url()?>harta/landing/images/klikber-i.png" type="image/gif" />
     
   </head>
   <style>
.rounded-transparent {
    position: relative;
    display: inline-block;
    border-radius: 50%; /* Membuat bentuk lingkaran */
    padding: 10px; /* Memberikan ruang untuk efek transparan */
    background: linear-gradient(
        to bottom right,
        rgba(255, 255, 255, 0.5),
        rgba(0, 0, 0, 0.1)
    ); /* Gradasi transparan */
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); /* Bayangan halus */
}

.rounded-transparent img {
    border-radius: 50%; /* Membulatkan gambar */
    display: block;
    width: 100%; /* Gambar responsif */
    height: auto;
    object-fit: cover; /* Menjaga proporsi gambar */
}

   </style>
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
                                       <a class="nav-link" href="#">Selamat Datang, <?php echo $name; ?>-<?php echo $username; ?> </a>
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

        
         <!-- <div class="container">
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
         </div> -->

         <div class="business">
            <div class="container">
               <div class="row">
                  
                  
                     <?php if ($role == 'Dokter'): ?>
                        <div class="row">
                           <div class="col-md-3">
                                 <div class="titlepage">
                                    <a href="<?php echo base_url('dokter/profile'); ?>">
                                       <img  class="img-fluid" src="<?php echo base_url()?>harta/landing/images/klikber.png" alt="klikber-profile"/>
                                       <p>Profile Dokter</p>
                                    </a>
                                 </div>
                           </div>
                           <div class="col-md-3">
                                 <div class="titlepage">
                                    <a href="<?php echo base_url('dokter/jadwal'); ?>">
                                       <div class="rounded-transparent">
                                          <img  class="img-fluid" src="<?php echo base_url()?>harta/landing/images/jp.png" alt="Kelola Jadwal Periksa"/>
                                       </div>
                                       <p>Kelola Jadwal Periksa</p>
                                    </a>
                                 </div>
                           </div>
                           <div class="col-md-3">
                                 <div class="titlepage">
                                    <a href="<?php echo base_url('dokter/daftar_pasien'); ?>">
                                       <div class="rounded-transparent">
                                          <img  class="img-fluid" src="<?php echo base_url()?>harta/landing/images/mdp.png" alt="Melihat Daftar Pasien"/>
                                       </div>
                                       <p>Melihat Daftar Pasien</p>
                                    </a>
                                 </div>
                           </div>
                           <div class="col-md-3">
                                 <div class="titlepage">
                                    <a href="<?php echo base_url('dokter/riwayat_pasien'); ?>">
                                    <div class="rounded-transparent">
                                       <img class="img-fluid" src="<?php echo base_url()?>harta/landing/images/mrp.png" alt="Melihat Riwayat Pasien"/>
                                    </div>
                                       <p>Melihat Riwayat Pasien</p>
                                    </a>
                                 </div>
                           </div>
                           <div class="col-md-3">
                                 <div class="titlepage">
                                    <a href="<?php echo base_url('dokter/konsultasi'); ?>">
                                    <div class="rounded-transparent">
                                       <img class="img-fluid" src="<?php echo base_url()?>harta/landing/images/konsul.png" alt="Konsultasi"/>
                                    </div>
                                       <p>Pasien Konsultasi</p>
                                    </a>
                                 </div>
                           </div>
                        </div>

                       
                     <?php elseif ($role == 'Pasien'): ?>
                        <div class="row">
                           <div class="col-md-3">
                                 <div class="titlepage">
                                    <a href="<?php echo base_url('pasien/profile'); ?>">
                                       <img  class="img-fluid" src="<?php echo base_url()?>harta/landing/images/klikber.png" alt="klikber-profile"/>
                                       
                                       <p>Klinik Bersama</p>
                                    </a>
                                 </div>
                           </div>
                           <div class="col-md-3">
                                 <div class="titlepage">
                                    <a href="<?php echo base_url('pasien/riwayat'); ?>">
                                    <div class="rounded-transparent">
                                       <img class="img-fluid" src="<?php echo base_url()?>harta/landing/images/mrp.png" alt="Daftar Poli"/>
                                    </div>
                                       <p>Daftar Poli</p>
                                    </a>
                                 </div>
                           </div>
                           <div class="col-md-3">
                                 <div class="titlepage">
                                    <a href="<?php echo base_url('pasien/riwayat_pasien'); ?>">
                                    <div class="rounded-transparent">
                                       <img class="img-fluid" src="<?php echo base_url()?>harta/landing/images/mrp.png" alt="Daftar Poli"/>
                                    </div>
                                       <p>Riwayat Periksa</p>
                                    </a>
                                 </div>
                           </div>
                           <div class="col-md-3">
                                 <div class="titlepage">
                                    <a href="<?php echo base_url('pasien/konsultasi'); ?>">
                                    <div class="rounded-transparent">
                                       <img class="img-fluid" src="<?php echo base_url()?>harta/landing/images/konsul.png" alt="Konsultasi"/>
                                    </div>
                                       <p>Konsultasi</p>
                                    </a>
                                 </div>
                           </div>
                           
                        </div>
                     <?php elseif ($role == 'Admin'): ?>
                        <div class="row">
                           <div class="col-md-3">
                                 <div class="titlepage">
                                    <a href="<?php echo base_url('admin'); ?>">
                                       <img  class="img-fluid" src="<?php echo base_url()?>harta/landing/images/klikber.png" alt="klikber-profile"/>
                                       
                                    <p>Klinik Bersama</p>
                                    </a>
                                 </div>
                           </div>
                        </div>
                     <?php endif; ?>
                  
                 
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
