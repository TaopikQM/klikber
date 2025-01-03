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
            <h4>Input Daftar Obat</h4>
            
          </div>
          <div class="card-body">
            <?php 
            date_default_timezone_set("Asia/Jakarta");
            $arrayName = array(
                  'class'=>"needs-validation",
                       'novalidate'=>'');
            echo form_open_multipart('obat/store',$arrayName);?>
             <div class="form-row">
              <div class="form-group col-md-6">
                  <label for="tgl">Nama Obat</label>
                  <input type="text" class="form-control" name="nama_obat" id="nama_obat" required="required">
                   <?php  echo form_error('nama_obat'); ?>
                  <div class="invalid-feedback">
                        Silahkan Masukan Nama Obat
                  </div>
              </div>
              <div class="form-group col-md-12">
                <label>Kemasan</label>
                <select name="kemasan" class="form-control select2" id="kemasan" required="required">                    
                    <option value="NULL" id="dfkemasan">Pilih Kemasan</option>                     
                    <option value="0">Tablet</option>                     
                    <option value="1">Botol</option>                      
                </select>
                 <?php  echo form_error('stsken'); ?>
                <div class="invalid-feedback">
                        Silahkan Pilih Jenis Kemasan
                  </div>
              </div>
              <!-- <div class="form-group col-md-12">
                  <label for="harga">Harga</label>
                  <input type="text" class="form-control" value="150000" name="harga" id="harga" disabled="disabled">
                  <input type="hidden" name="harga" value="150000"> 
                  <?php  echo form_error('harga'); ?>
                  <div class="invalid-feedback">
                        Silahkan Masukan Harga
                  </div>
              </div> -->
              <div class="form-group col-md-12">
                  <label for="harga">Harga</label>
                  <input type="text" class="form-control" name="harga" id="harga" value="<?php echo set_value('harga', '150000'); ?>">
                  <?php echo form_error('harga'); ?>
                  <div class="invalid-feedback">
                      Silahkan Masukan Harga
                  </div>
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

