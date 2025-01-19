<!DOCTYPE html>
<html lang="en">


<!-- datatables.html  21 Nov 2019 03:55:21 GMT -->
<head>
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  <title>KLIKBER</title>
  <!-- General CSS Files -->
  <link rel="stylesheet" href="<?php echo base_url()?>harta/morsip/assets/css/app.min.css">
  <link rel="stylesheet" href="<?php echo base_url()?>harta/morsip/assets/bundles/owlcarousel2/dist/assets/owl.carousel.min.css">
  <link rel="stylesheet" href="<?php echo base_url()?>harta/morsip/assets/bundles/owlcarousel2/dist/assets/owl.theme.default.min.css">
  <!-- Template CSS -->
  <link rel="stylesheet" href="<?php echo base_url()?>harta/morsip/assets/css/style.css">
  <link rel="stylesheet" href="<?php echo base_url()?>harta/morsip/assets/css/components.css">
  <link rel="stylesheet" href="<?php echo base_url()?>harta/morsip/assets/bundles/prism/prism.css">
  <!-- Custom style CSS -->
  <link rel="stylesheet" href="<?php echo base_url()?>harta/morsip/assets/css/custom.css">
  <link rel="icon" href="<?php echo base_url()?>harta/landing/images/klikber-i.png" type="image/gif" />
     
  <!-- <link rel='shortcut icon' type='image/x-icon' href='<?php echo base_url()?>harta/jateng.ico' /> -->
   

</head>

<body>
  <div class="loader"></div>
  <div id="app">
    <div class="main-wrapper main-wrapper-1">
      <div class="navbar-bg"></div>
      <?php $this->load->view('klinik/section/menu_atas')?>
      <?php $this->load->view('klinik/section/menu_samping')?>
      <!-- Main Content -->
      <div class="main-content">
        <?php echo $konten;?>
        <div class="settingSidebar">
          <a href="javascript:void(0)" class="settingPanelToggle"> <i class="fa fa-spin fa-cog"></i>
          </a>
          <div class="settingSidebar-body ps-container ps-theme-default">
            <div class=" fade show active">
              <div class="setting-panel-header">Setting Panel
              </div>
              <div class="p-15 border-bottom">
                <h6 class="font-medium m-b-10">Select Layout</h6>
                <div class="selectgroup layout-color w-50">
                  <label class="selectgroup-item">
                    <input type="radio" name="value" value="1" class="selectgroup-input-radio select-layout" checked>
                    <span class="selectgroup-button">Light</span>
                  </label>
                  <label class="selectgroup-item">
                    <input type="radio" name="value" value="2" class="selectgroup-input-radio select-layout">
                    <span class="selectgroup-button">Dark</span>
                  </label>
                </div>
              </div>
              <div class="p-15 border-bottom">
                <h6 class="font-medium m-b-10">Sidebar Color</h6>
                <div class="selectgroup selectgroup-pills sidebar-color">
                  <label class="selectgroup-item">
                    <input type="radio" name="icon-input" value="1" class="selectgroup-input select-sidebar">
                    <span class="selectgroup-button selectgroup-button-icon" data-toggle="tooltip"
                      data-original-title="Light Sidebar"><i class="fas fa-sun"></i></span>
                  </label>
                  <label class="selectgroup-item">
                    <input type="radio" name="icon-input" value="2" class="selectgroup-input select-sidebar" checked>
                    <span class="selectgroup-button selectgroup-button-icon" data-toggle="tooltip"
                      data-original-title="Dark Sidebar"><i class="fas fa-moon"></i></span>
                  </label>
                </div>
              </div>
              <div class="p-15 border-bottom">
                <h6 class="font-medium m-b-10">Color Theme</h6>
                <div class="theme-setting-options">
                  <ul class="choose-theme list-unstyled mb-0">
                    <li title="white" class="active">
                      <div class="white"></div>
                    </li>
                    <li title="cyan">
                      <div class="cyan"></div>
                    </li>
                    <li title="black">
                      <div class="black"></div>
                    </li>
                    <li title="purple">
                      <div class="purple"></div>
                    </li> 
                    <li title="orange">
                      <div class="orange"></div>
                    </li>
                    <li title="green">
                      <div class="green"></div>
                    </li>
                    <li title="red">
                      <div class="red"></div>
                    </li>
                  </ul>
                </div>
              </div>
              <div class="p-15 border-bottom">
                <div class="theme-setting-options">
                  <label class="m-b-0">
                    <input type="checkbox" name="custom-switch-checkbox" class="custom-switch-input"
                      id="mini_sidebar_setting">
                    <span class="custom-switch-indicator"></span>
                    <span class="control-label p-l-10">Mini Sidebar</span>
                  </label>
                </div>
              </div>
              <div class="p-15 border-bottom">
                <div class="theme-setting-options">
                  <label class="m-b-0">
                    <input type="checkbox" name="custom-switch-checkbox" class="custom-switch-input"
                      id="sticky_header_setting">
                    <span class="custom-switch-indicator"></span>
                    <span class="control-label p-l-10">Sticky Header</span>
                  </label>
                </div>
              </div>
              <div class="mt-4 mb-4 p-3 align-center rt-sidebar-last-ele">
                <a href="#" class="btn btn-icon icon-left btn-primary btn-restore-theme">
                  <i class="fas fa-undo"></i> Restore Default
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>
      <footer class="main-footer">
        <div class="footer-left">
          <a href="#">KLIKBER</a></a>
        </div>
        <div class="footer-right">
        </div>
      </footer>
    </div>
  </div>
  <!-- General JS Scripts -->
  <script src="<?php echo base_url()?>harta/morsip/assets/js/app.min.js"></script>
  
  <!-- JS Libraies --><!-- Page Specific JS File -->
  <script src="<?php echo base_url()?>harta/morsip/assets/bundles/owlcarousel2/dist/owl.carousel.min.js"></script>
  <!-- Page Specific JS File -->
  <script src="<?php echo base_url()?>harta/morsip/assets/js/page/owl-carousel.js"></script>
  <!-- JS Data Tabel -->
  <script src="<?php echo base_url()?>harta/morsip/assets/bundles/datatables/datatables.min.js"></script>
  <script src="<?php echo base_url()?>harta/morsip/assets/bundles/datatables/DataTables-1.10.16/js/dataTables.bootstrap4.min.js"></script>
  <script src="<?php echo base_url()?>harta/morsip/assets/bundles/jquery-ui/jquery-ui.min.js"></script>
  <script src="<?php echo base_url()?>harta/morsip/assets/js/page/datatables.js"></script>

  <!-- end JS Select 2 -->
  <script src="<?php echo base_url()?>harta/pinjam/assets/js/select2.full.min.js"></script>

  <!-- end JS Text Editor -->
  <script src="<?php echo base_url()?>harta/morsip/assets/bundles/summernote/summernote-bs4.js"></script>
 
  <!-- Template JS Modal -->
  <script src="<?php echo base_url()?>harta/morsip/assets/bundles/prism/prism.js"></script>

  <!-- Template JS File -->
  <script src="<?php echo base_url()?>harta/morsip/assets/js/scripts.js"></script>
  <!-- Custom JS File -->
  <script src="<?php echo base_url()?>harta/pinjam/assets/js/custom.js"></script>

  <script src="<?php echo base_url()?>harta/morsip/assets/pdf/pdf.js"></script>

  <script src="<?php echo base_url()?>harta/morsip/assets/pdf/pdf.worker.js"></script>
  
  <script src="<?php echo base_url()?>harta/morsip/assets/bundles/lightgallery/dist/js/lightgallery-all.js"></script>
  <!-- Page Specific JS File -->
  <script src="<?php echo base_url()?>harta/morsip/assets/js/page/light-gallery.js"></script>

</body>


<!-- datatables.html  21 Nov 2019 03:55:25 GMT -->
</html>