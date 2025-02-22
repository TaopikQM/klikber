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
            <h4>Konsultasi</h4>
            
          </div>
          <div class="card-body">
            <?php 
            foreach ($pasien as $ky ) {
              $idk=$ky['id_konsul']; 
              $nama=$ky['nama']; 
              $subject=$ky['subject']; 
              $pertanyaan=$ky['pertanyaan'];
              $jawaban=$ky['jawaban'];
            }
            // echo "<pre>";
            // print_r($pasien);
            // echo "</pre>";


            date_default_timezone_set("Asia/Jakarta");
            $arrayName = array(
                  'class'=>"needs-validation",
                       'novalidate'=>'');
            echo form_open_multipart('dokter/updateKon',$arrayName);?>
            
             
              <div class="form-group ">
                  <label for="dokter_nama">Nama Pasien</label>
                  <input type="hidden" name="id_dokter_old" value="<?php echo $nama;?>">
                  <input type="text" class="form-control" name="nama" id="nama" value="<?php echo $nama;?>" readonly>
                  <?php echo form_error('dokter_nama'); ?>
                  <div class="invalid-feedback">
                        Silahkan Masukan Nama Pasien
                  </div>
              </div>
              <div class="form-group ">
                  <label for="dokter_nama">Subject</label>
                  <input type="hidden" name="id_dokter_old" value="<?php echo $subject;?>">
                  <input type="text" class="form-control" name="subject" id="subject" value="<?php echo $subject;?>" readonly>
                  <?php echo form_error('dokter_nama'); ?>
                  <div class="invalid-feedback">
                        Silahkan Masukan Nama Pasien
                  </div>
              </div>
              <div class="form-group">
                <label for="pertanyaan">Pertanyaan</label>
                <textarea readonly class="form-control" name="pertanyaan"><?php echo $pertanyaan; ?></textarea>
                <?php echo form_error('pertanyaan'); ?>
                <div class="invalid-feedback">
                          Silahkan Input pertanyaan
                </div>
              </div>
           
              <div class="form-group">
                <label for="jawaban">Jawaban</label>
                <textarea class="form-control" name="jawaban"><?php echo $jawaban; ?></textarea>
                <?php echo form_error('jawaban'); ?>
                <div class="invalid-feedback">
                          Silahkan Input jawaban
                </div>
              </div>
            <div class="card-footer text-center">
            <input type="hidden" name="idk" value="<?php echo $idk;?>">
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

<script type="text/javascript">
 
<!-- JS Libraies -->
<script src="<?php echo base_url()?>harta/morsip/assets/bundles/lightgallery/dist/js/lightgallery-all.js"></script>
<!-- Page Specific JS File -->
<script src="<?php echo base_url()?>harta/morsip/assets/js/page/light-gallery.js"></script>

