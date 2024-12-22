


<link rel="stylesheet" href="<?php echo base_url()?>harta/morsip/assets/bundles/select2/dist/css/select2.min.css">
<link rel="stylesheet" href="<?php echo base_url()?>harta/morsip/assets/bundles/summernote/summernote-bs4.css">

<!-- Template CSS -->

<link rel="stylesheet" href="<?php echo base_url()?>harta/morsip/assets/css/components.css">
<!-- Custom style CSS -->

<script src="<?php echo base_url()?>harta/morsip/assets/js/jquery-3.6.3.min.js"></script>


<style type="text/css">

#upload-button {
    width: 150px;
    display: block;
    margin: 20px auto;
}

#file-to-upload {
    display: none;
}

#pdf-main-container {
    width: 400px;
    margin: 20px auto;
}

#pdf-loader {
    display: none;
    text-align: center;
    color: #999999;
    font-size: 13px;
    line-height: 100px;
    height: 100px;
}

#pdf-contents {
    display: none;
}

#pdf-meta {
    overflow: hidden;
    margin: 0 0 20px 0;
}

#pdf-buttons {
    float: center;
}

#page-count-container {
    float: right;
}

#pdf-current-page {
    display: inline;
}

#pdf-total-pages {
    display: inline;
}

#pdf-canvas {
    border: 1px solid rgba(0,0,0,0.2);
    box-sizing: border-box;
}

#page-loader {
    height: 100px;
    line-height: 100px;
    text-align: center;
    display: none;
    color: #999999;
    font-size: 13px;
}

</style>
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
            <h4>Input Dokter</h4>
            
          </div>
          <div class="card-body">
            <form action="<?php echo site_url('klinik/add_dokter'); ?>" method="post"> <!-- Ganti dengan URL yang sesuai -->
                    
              <div class="form-row">
                <div class="form-group col-md-3">
                    <label for="tgl">Nama Dokter</label>
                    <input type="text" class="form-control" name="nama" id="nama"  required="required">
                    <?php  echo form_error('nama'); ?>
                    <div class="invalid-feedback">
                          Silahkan Masukan Nama Dokter
                    </div>
                </div>

                <div class="form-group col-md-3">
                    <label for="tgl">Nomor HPP</label>
                    <input type="text" class="form-control" name="no_hp" id="no_hp" maxlength="17"  required="required">
                    <?php  echo form_error('no_hp'); ?>
                    <div class="invalid-feedback">
                          Silahkan Masukan Nomor HP
                    </div>
                </div>

                <div class="form-group col-md-3">
                  <label>Poli</label>
                  <select name="id_poli" class="form-control select2" id="id_poli" required="required">                    
                      <option value="NULL" id="dfpinjam">Pilih Poli</option>
                      <?php foreach ($poli as $p): ?>
                          <option value="<?= $p->id ?>"><?= $p->nama_poli ?></option>
                      <?php endforeach; ?>                     
                                          
                  </select>
                  <?php  echo form_error('id_poli'); ?>
                  <div class="invalid-feedback">
                          Silahkan Pilih Jenis Poli
                    </div>
                </div>
              </div>

              <div class="form-row" >
            
              
              
              </div>
            
              <div class="form-group">
                <label>Alamat</label>
              <textarea class="form-control "  name="alamat" ><?php echo set_value('alamat');?></textarea>
                <?php  echo form_error('alamat'); ?>
                <div class="invalid-feedback">
                          Silahkan Input Alamat
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




