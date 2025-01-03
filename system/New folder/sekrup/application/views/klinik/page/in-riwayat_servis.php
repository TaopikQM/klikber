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
            <h4>Input Riwayat Servis</h4>
          </div>
          <div class="card-body">
            <?php
           /* echo "<pre>";
            print_r($datamobil);
            print_r($allmobil);
            echo "</pre>";*/
            ?>
            <?php 
            $arrayName = array(
              'class' => "needs-validation",
              'novalidate' => ''
            );
            echo form_open_multipart('riwayatservis/save_riwayat', $arrayName);
            ?>

            <div class="form-row">
            <div class="form-group col-md-3">
    <?php
    date_default_timezone_set("Asia/Jakarta");
    if ($datamobil != NULL) {
        foreach ($datamobil as $ky) {
            $idmobil = $ky->id;
        }
    } else {
        $idmobil = NULL;
    }
    ?>
    <label for="akhir_pajak">Mobil</label>
    <?php
    if ($idmobil != NULL) {
        $nh = "disabled=disabled";
    } else {
        $nh = "";
    }
    ?>
    <select name="idmobil" class="form-control select2" id="idmobil" required="required" <?php echo $nh; ?>>
        <option value="">Pilih Jenis Pinjaman</option>
        <?php
        foreach ($allmobil as $key) { ?>
            <option value="<?php echo $key->id; ?>" <?php if ($key->id == $idmobil) {
                                                            echo "selected=selected";
                                                        } ?>> <?php echo $key->nmerk . " - " . $key->nopol; ?> </option>
        <?php  }
        ?>
    </select>
    <?php if ($idmobil != NULL) { ?>
        <input type="hidden" name="idmobil" value="<?php echo $idmobil; ?>">
    <?php } ?>
</div>

            </div>

            <div class="form-row">
            <div class="form-group  col-md-12">
              <label for="kilometer">Kilometer</label>
              <input type="number" class="form-control" name="kilometer" id="kilometer">
            </div>
            </div>

            <div class="form-row">
            <div class="form-group  col-md-12">
                <label for="nm_pemegang">Nama Pemegang</label>
                <select name="nm_pemegang" id="nm_pemegang" class="form-control">
                <option value="NULL" disabled selected >Pilih Nama Pemegang</option>
                    <?php foreach ($driver as $driv): ?>
                        <option value="<?php echo $driv->nmdriver; ?>">
                            <?php echo $driv->nmdriver; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            </div>

            <div class="form-row">
            <div class="form-group col-md-6">
              <label for="blnket">Tanggal Servis</label>
              <input type="date" class="form-control" name="blnket" id="blnket">
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
            </div>
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
