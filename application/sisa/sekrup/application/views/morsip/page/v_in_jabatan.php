
<link rel="stylesheet" href="<?php echo base_url()?>harta/morsip/assets/bundles/select2/dist/css/select2.min.css">
<link rel="stylesheet" href="<?php echo base_url()?>harta/morsip/assets/bundles/summernote/summernote-bs4.css">

<!-- Template CSS -->

<link rel="stylesheet" href="<?php echo base_url()?>harta/morsip/assets/css/components.css">


<script src="<?php echo base_url()?>harta/morsip/assets/js/jquery-3.6.3.min.js"></script>
<!-- <script src="https://demo.dewankomputer.com/php/dewan-mask/js/jquery.min.js"></script> -->


<!-- Custom style CSS -->

<section class="section">
<?php
if($this->session->flashdata('notif') != NULL){
    $tep=$this->session->flashdata('notif')['tipe'];
    $is=$this->session->flashdata('notif')['isi'];
    $cs = array('1' =>'alert-primary' ,'2' =>'alert-warning','3' =>'alert-danger');
    
?>
<div class="alert <?php echo $cs[$tep];?> alert-dismissible show fade">
  <div class="alert-body">
    <button class="close" data-dismiss="alert">
      <span>&times;</span>
    </button>
    <?php echo $is;?>
  </div>
</div>
<?php }
?>
  <div class="section-body">
    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-header">
            <h4>Input Jabatan</h4>
            
          </div>
          <div class="card-body">

            <?php $arrayName = array(
                  'class'=>"needs-validation",
                       'novalidate'=>'');
            echo form_open_multipart('morsip/save_jabatan',$arrayName);?>
            <div class="form-group">
              <div class="form-group col-md-12">
                  <?php
                      date_default_timezone_set("Asia/Jakarta");
                      $tgloi= date("Y-m-d");
                  ?>
                  <label for="user">NAMA JABATAN</label>
                  <input type="text" class="form-control" name="nama" id="nama" required="required"  value="<?php echo set_value('nama'); ?>" placeholder="Input Nama Jabatan">
                   <?php  echo form_error('nama'); ?>
                  <div class="invalid-feedback">
                        Silahkan Input Nama Jabatan
                  </div>
              </div>
            </div>
            
            <div class="card-footer text-center">
              <button class="btn btn-primary mr-1" type="submit">Submit</button>
              <button class="btn btn-secondary" type="reset">Reset</button>
            </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>


