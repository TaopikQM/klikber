<link rel="stylesheet" href="<?php echo base_url()?>harta/morsip/assets/bundles/select2/dist/css/select2.min.css">
<link rel="stylesheet" href="<?php echo base_url()?>harta/morsip/assets/bundles/summernote/summernote-bs4.css">

<!-- Template CSS -->

<link rel="stylesheet" href="<?php echo base_url()?>harta/morsip/assets/css/components.css">
<script src="<?php echo base_url()?>harta/morsip/assets/js/jquery-3.6.3.min.js"></script>
<!-- <script src="https://demo.dewankomputer.com/php/dewan-mask/js/jquery.min.js"></script> -->

<!-- Custom style CSS -->
<section class="section">
<?php
foreach ($dasrut as $keya) {
  $isi=$keya->isi;
  $id=$keya->id;
}
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
            <h4>Edit Dasar Surat</h4>
            
          </div>
          <div class="card-body">

            <?php $arrayName = array(
                  'class'=>"needs-validation",
                       'novalidate'=>'');
            echo form_open_multipart('morsip/save_e_dasrut',$arrayName);?>
            <div class="form-group">
              <div class="form-group col-md-12">
                  <?php
                      date_default_timezone_set("Asia/Jakarta");
                      $tgloi= date("Y-m-d");
                  ?>
                  <label for="user">Dasar Surat</label>
                  <textarea name="nama" id="nama" required="required" class="form-control" placeholder="Input Dasar Surat"><?php echo $isi; ?></textarea>
                  <input type="hidden" name="id" value="<?php echo $id;?>">
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


