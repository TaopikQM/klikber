<link rel="stylesheet" href="<?php echo base_url()?>harta/morsip/assets/bundles/select2/dist/css/select2.min.css">
<link rel="stylesheet" href="<?php echo base_url()?>harta/morsip/assets/bundles/summernote/summernote-bs4.css">

<!-- Template CSS -->

<link rel="stylesheet" href="<?php echo base_url()?>harta/morsip/assets/css/components.css">
<!-- Custom style CSS -->

<script src="<?php echo base_url()?>harta/morsip/assets/js/jquery-3.6.3.min.js"></script>


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
            <h4>Input Daftar Role</h4>
            
          </div>
          <div class="card-body">
            <?php 
            date_default_timezone_set("Asia/Jakarta");
            $arrayName = array(
                  'class'=>"needs-validation",
                       'novalidate'=>'');
            echo form_open_multipart('role/store',$arrayName);?>
             
              <div class="form-group">
                  <label for="tgl">Nama Role</label>
                  <input type="text" class="form-control" name="nama_role" id="nama_role" required="required">
                   <?php  echo form_error('nama_role'); ?>
                  <div class="invalid-feedback">
                        Silahkan Masukan Nama role
                  </div>
              </div>
             
              <div class="form-group">
                <label>Keterangan</label>
                <textarea class="form-control "  name="keterangan" ><?php echo set_value('keterangan');?></textarea>
                <?php  echo form_error('keterangan'); ?>
                <div class="invalid-feedback">
                          Silahkan Input keterangan lainnya
                </div>
            </div>
            
            <div class="card-footer text-center">
              <button class="btn btn-primary mr-1" type="submit">Submit</button>
              <button class="btn btn-secondary" type="button" onclick="history.back()">Reset</button>
          </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

