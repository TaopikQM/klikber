<link rel="stylesheet" href="<?php echo base_url()?>harta/morsip/assets/bundles/select2/dist/css/select2.min.css">
<link rel="stylesheet" href="<?php echo base_url()?>harta/morsip/assets/bundles/summernote/summernote-bs4.css">

<!-- Template CSS -->
<link rel="stylesheet" href="<?php echo base_url()?>harta/morsip/assets/css/components.css">
<!-- Custom style CSS -->

<script src="<?php echo base_url()?>harta/morsip/assets/js/jquery-3.6.3.min.js"></script>
<section class="section">
<?php
if ($this->session->flashdata('notif') != NULL) {
    $tep = $this->session->flashdata('notif')['tipe'];
    $is = $this->session->flashdata('notif')['isi'];
    $cs = array('1' => 'alert-primary', '2' => 'alert-warning', '3' => 'alert-danger');
?>
<div class="alert <?php echo $cs[$tep]; ?> alert-dismissible show fade">
  <div class="alert-body">
    <button class="close" data-dismiss="alert">
      <span>&times;</span>
    </button>
    <?php echo $is; ?>
  </div>
</div>
<?php } ?>
  <div class="section-body">
    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-header">
            <h4>Input Riwayat Servis Alat</h4>
          </div>
          <div class="card-body">
            <?php 
            $arrayName = array(
              'class' => "needs-validation",
              'novalidate' => ''
            );
            echo form_open_multipart('riwayatservisalat/save_rs_alat', $arrayName);
            ?>

            <div class="form-row">
            <div class="form-group col-md-3">
    <?php
    date_default_timezone_set("Asia/Jakarta");
    $id_alat = NULL; // Inisialisasi $id_alat dengan NULL sebagai default

    if ($dataalat != NULL) {
        foreach ($dataalat as $ky) {
            $id_alat = $ky->id;
        }
    }
    ?>
    <label for="akhir_pajak">Alat</label>
    <?php
    $nh = ($id_alat != NULL) ? "disabled=disabled" : "";
    ?>
    <select name="id_alat" class="form-control select2" id="id_alat" required="required" <?php echo $nh; ?>>
        <option value="">Pilih Jenis Pinjaman</option>
        <?php
        foreach ($allalat as $key) { ?>
            <option value="<?php echo $key->id; ?>" <?php if ($key->id == $id_alat) {
            echo "selected=selected";
            } ?>> <?php echo $key->nmbarang; ?> </option>
        <?php  }
        ?>
    </select>
    <?php if ($id_alat != NULL) { ?>
        <input type="hidden" name="id_alat" value="<?php echo $id_alat; ?>">
    <?php } ?>
</div>

            </div>

            <div class="form-row">
            <div class="form-group col-md-2">
              <label for="tgl_servis">Tanggal Servis</label>
              <input type="date" class="form-control" name="tgl_servis" id="tgl_servis">
            </div>
            </div>

            <div class="form-row">
            <div class="form-group col-md-12">
              <label for="ket">Keterangan Servis</label>
              <textarea class="form-control" name="ket" rows="6" id="ket"></textarea>
            </div>
            </div>
            
            <div class="card-footer text-center">
              <button class="btn btn-primary mr-1" type="submit" name="simpan">Submit</button>
              <button class="btn btn-secondary" type="reset">Reset</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<script src="<?php echo base_url()?>harta/morsip/assets/bundles/select2/dist/js/select2.min.js"></script>
<script>
$(document).ready(function() {
    $('.select2').select2();
});
</script>
