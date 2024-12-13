<link rel="stylesheet" href="<?php echo base_url()?>harta/morsip/assets/bundles/select2/dist/css/select2.min.css">
<link rel="stylesheet" href="<?php echo base_url()?>harta/morsip/assets/bundles/summernote/summernote-bs4.css">

<link href="<?php echo base_url()?>harta/morsip/assets/bundles/lightgallery/dist/css/lightgallery.css" rel="stylesheet">
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
            <h4>Edit Obat</h4>
            
          </div>
          <div class="card-body">
            <?php 
            foreach ($obat as $ky ) {
              $id=$ky->id; 
              $nama_obat=$ky->nama_obat;
              $kemasan=$ky->kemasan;
              $harga=$ky->harga;
            }
            /*echo "<pre>";
            print_r($data);
            echo "</pre>";*/


            date_default_timezone_set("Asia/Jakarta");
            $arrayName = array(
                  'class'=>"needs-validation",
                       'novalidate'=>'');
            echo form_open_multipart('obat/update',$arrayName);?>
             <div class="form-row">
              <div class="form-group col-md-6">
                  <label for="tgl">Nama Obat</label>
                  <input type="hidden" name="naobatold" value="<?php echo $nama_obat;?>">
                  <input type="text" class="form-control" name="nama_obat" id="nama_obat" value="<?php echo $nama_obat;?>" required="required">
                   <?php  echo form_error('nama_obat'); ?>
                  <div class="invalid-feedback">
                        Silahkan Masukan Nama Obat
                  </div>
              </div>
            </div>
            <div class="form-group"> 
              <label for="kemasan">Kemasan Obat</label>
              <select name="kemasan" class="form-control" id="kemasan" required>
                  <option value="NULL">Pilih Kemasan</option>
                  <option value="0" <?php echo ($kemasan == '0') ? 'selected' : ''; ?>>Tablet</option>
                  <option value="1" <?php echo ($kemasan == '1') ? 'selected' : ''; ?>>Botol</option>
              </select>
              <?php echo form_error('kemasan'); ?>
              <div class="invalid-feedback">
                    Silahkan Pilih Kemasan
              </div>
            </div>
            <div class="form-row">
                
                
                <label for="harga">Harga</label>
                <input type="text" class="form-control" id="harga" name="harga" value="<?php echo $harga; ?>" readonly>
                <?php echo form_error('harga');?>
                <div class="invalid-feedback">
                        Silahkan Masukan Harga
                </div>
            
            </div> 
            
            <div class="card-footer text-center">
            <input type="hidden" name="id" value="<?php echo $id;?>">
              <button class="btn btn-primary mr-1" type="submit">Submit</button>
              <a href="<?= base_url('obat'); ?>" class="btn btn-secondary">Kembali</a>

            </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<script type="text/javascript">
 
<!-- JS Libraies -->
<script src="<?php echo base_url()?>harta/morsip/assets/bundles/lightgallery/dist/js/lightgallery-all.js"></script>
<!-- Page Specific JS File -->
<script src="<?php echo base_url()?>harta/morsip/assets/js/page/light-gallery.js"></script>

