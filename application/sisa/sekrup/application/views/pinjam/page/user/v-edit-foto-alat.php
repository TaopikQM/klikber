<link href="<?php echo base_url()?>harta/morsip/assets/bundles/lightgallery/dist/css/lightgallery.css" rel="stylesheet">


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


foreach ($data as $key) {
    $nopola=$key->nmbarang;
    $idb=$key->id;  
}

?>

  <div class="section-body">
    <div class="row clearfix">
      <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 col-12">
        <div class="card">
          <div class="card-header">
            <h4>Edit Foto Alat/Barang</h4>
          </div>
          <div class="card-body">
             <?php
              /*echo "<pre>"; 
              print_r($data);
              echo "</pre>"; */

              $arrayName = array('class'=>"needs-validation",'novalidate'=>'');
              echo form_open_multipart('alat/up_foto',$arrayName);
            ?>
             <div class="form-row" >
               <div class="form-group col-md-12">
                <label>Tambah Foto Alat/Barang</label>
                <input type="file" class="form-control" multiple="" name="foto[]" accept="image/*">
               </div>
            </div>
            <div class="card-footer text-center">
              <?php $dxc=$this->encryption->encrypt(base64_encode($idb)); $ff=str_replace(array('+','/','='),array('-','_','~'),$dxc);?>
              <input type="hidden" name="nmalat" value="<?php echo $nopola;?>">
              <input type="hidden" name="id" value="<?php echo $ff;?>">
              <button class="btn btn-primary mr-1" type="submit">Upload</button>
              <button class="btn btn-secondary" type="reset">Reset</button>
            </div>
            </form>
            <?php
              /*echo "<pre>"; 
              print_r($data);
              echo "</pre>"; */

              $arrayName = array('class'=>"needs-validation",'novalidate'=>'');
              echo form_open_multipart('alat/del_foto',$arrayName);
            ?>

            <div id="aniimated-thumbnials" class="list-unstyled row clearfix">
              <?php 
                $hbg=str_replace(" ","",strtoupper($nopola));
                $folder = "harta/pinjam/dataalat/".$hbg;
                $handle =  glob($folder."/*.*");
                if ($handle) {
                  for ($i=0; $i <count($handle) ; $i++) { 
                      $hp=explode("/", $handle[$i]);
                  ?>
                    <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
                      <center>
                      <input class="form-check-input" type="checkbox" id="" name="nmfoto[]" value="<?php echo $hp[4]; ?>"></center>
                      <a href="<?php echo base_url().$handle[$i];?>" data-sub-html="<?php echo $hp[4]; ?>">
                        <img class="img-responsive thumbnail" src="<?php echo base_url().$handle[$i];?>" alt="<?php echo $nopola;?>">
                      </a>
                    </div>                
              <?php  }
                }
                else{
                  echo '<div class="col-lg-12 col-md-4 col-sm-6 col-xs-12"><div class="alert alert-danger"><center>DATA TIDAK DITEMUKAN</center></div></div>';     
                }
                ?>
                
            </div>

            <div class="card-footer text-center">
              <?php $dxc=$this->encryption->encrypt(base64_encode($idb)); $ff=str_replace(array('+','/','='),array('-','_','~'),$dxc);?>
              <input type="hidden" name="nmalat" value="<?php echo $nopola;?>">
              <input type="hidden" name="id" value="<?php echo $ff;?>">
              <button class="btn btn-danger mr-1" type="submit">Hapus</button>
              <button class="btn btn-secondary" type="reset">Reset</button>
            </div>

            </form>
          
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

