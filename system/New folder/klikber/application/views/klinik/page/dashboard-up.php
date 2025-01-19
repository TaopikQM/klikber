<link rel="stylesheet" href="<?php echo base_url()?>harta/morsip/assets/bundles/datatables/datatables.min.css">
<link rel="stylesheet" href="<?php echo base_url()?>harta/morsip/assets/bundles/datatables/DataTables-1.10.16/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="<?php echo base_url()?>harta/morsip/assets/bundles/prism/prism.css">

<!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="harta/plugins/fontawesome-free/css/all.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Tempusdominus Bootstrap 4 -->
  <link rel="stylesheet" href="harta/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
  <!-- iCheck -->
  <link rel="stylesheet" href="harta/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- JQVMap -->
  <link rel="stylesheet" href="harta/plugins/jqvmap/jqvmap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="harta/dist/css/adminlte.min.css">
  <!-- overlayScrollbars -->
  <link rel="stylesheet" href="harta/plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
  <!-- Daterange picker -->
  <link rel="stylesheet" href="harta/plugins/daterangepicker/daterangepicker.css">
  <!-- summernote -->
  <link rel="stylesheet" href="harta/plugins/summernote/summernote-bs4.min.css">

<link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.9.0/main.min.css' rel='stylesheet' />
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.9.0/main.min.js'></script>



<section class="section">

  <div class="section-body">
    <div class="row">
      <div class="col-12">
        <div class="card"> 
          <div class="card-header">
          </div>
          <div class="card-body">
            <div class="row">
                <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box bg-info"  style="margin-bottom: 20px;">
                    <div class="inner">
                        <h3><?php echo $total_dokter; ?></h3>
                        
                        <p>Total Dokter</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-person"></i>
                    </div>
                    <a href="dokter/tab" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <!-- ./col -->
                <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box bg-success"  style="margin-bottom: 20px;">
                    <div class="inner">
                        <h3><?php echo $total_pasien; ?></h3>
 
                        <p>Total Pasien</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-ios-body"></i>
                    </div>
                    <a href="pasien/tab" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <!-- ./col -->
                <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box bg-secondary"  style="margin-bottom: 20px;">
                    <div class="inner">
                        <h3><?php echo $total_admin; ?></h3>

                        <p>Total Admin</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-person-add"></i> 
                    </div>
                    <a href="admin/tab" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <!-- ./col -->
                <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box bg-light"  style="margin-bottom: 20px;">
                    <div class="inner">
                        <h3><?php echo $total_users; ?></h3>

                        <p>Total Users</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-person-add"></i>
                    </div>
                    <a href="users/tab" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <!-- ./col -->
                <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box bg-warning"  style="margin-bottom: 20px;">
                    <div class="inner">
                        <h3><?php echo $total_obat; ?></h3>

                        <p>Total Obat</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-ios-medkit"></i>
                    </div>
                    <a href="obat" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <!-- ./col -->
                <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box bg-danger"  style="margin-bottom: 20px;">
                    <div class="inner">
                        <h3><?php echo $total_poli; ?></h3>

                        <p>Total Poli</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-ios-home"></i>
                    </div>
                    <a href="poli/tab" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                
                
                <!-- ./col -->
                <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box bg-purple"  style="margin-bottom: 20px;">
                    <div class="inner">
                        <h3><?php echo $total_role; ?></h3>

                        <p>Total Role</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-ios-settings"></i>
                    </div>
                    <a href="role" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <!-- ./col -->
                <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box bg-light"  style="margin-bottom: 20px;">
                    <div class="inner">
                        <h3><?php echo $total_logs; ?></h3>

                        <p>Log Users</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-ios-settings"></i>
                    </div>
                    <a href="users/log" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
            </div>
            
                    
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<script src="<?php echo base_url()?>harta/morsip/assets/js/jquery-3.6.3.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>


</head>
<body>
