<link rel="stylesheet" href="<?php echo base_url()?>harta/morsip/assets/bundles/datatables/datatables.min.css">
<link rel="stylesheet" href="<?php echo base_url()?>harta/morsip/assets/bundles/datatables/DataTables-1.10.16/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="<?php echo base_url()?>harta/morsip/assets/bundles/prism/prism.css">


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
            <h4>Item Kendaraan</h4>
            
          </div>
          <div class="card-body">
           <div class="form-group">
                      <div class="input-group mb-3">
                        <input type="text" class="form-control" placeholder="Cari Kendaraan" aria-label="">
                        <div class="input-group-append">
                          <button class="btn btn-primary" type="button">Cari</button>
                        </div>
                      </div>
                    </div>
          </div>
        </div>
      </div>
      <?php
              /*echo "<pre>";
              print_r($datamobil);
              echo "</pre>";*/
              $aw=0;
              foreach ($datamobil as $key){
      $hbg=str_replace(" ","",$key->nopol);
      $folder = "harta/pinjam/datamobil/".$hbg;
          $handle =  glob($folder."/*.*");?>



      <div class="col-12 col-md-4 col-lg-4">
          <div class="pricing pricing-highlight">
            <div class="pricing-title">
             KENDARAAN
            </div>
            <div class="pricing-padding">
              <div class="pricing-price">
                <div><?php echo $key->nopol?></div>
                <div><?php echo $key->nmerk?></div>
                <!-- <div>per month</div> -->
              </div>
              <div class="pricing-details">
                
                <label class="imagecheck mb-4">
                <input type="radio" name="itm" value="'.$key->id.'" class="imagecheck-input" required="required">
                  <span class="imagecheck-figure">
                    <div id="carouselExampleIndicators<?php echo $aw;?>" class="carousel slide" data-ride="carousel">            
                        <div class="carousel-inner">
                          <?php for ($ib=0; $ib < count($handle) ; $ib++) { 
                            //echo base_url().$handle[$ib]."<br>";
                            $drw ="";
                            if ($ib==0) {
                              $drw="active";
                            }?>
                            <div class="carousel-item <?php echo $drw;?>">
                              <img class="d-block w-100" src="<?php echo base_url().$handle[$ib];?>" alt="<?php echo $key->nopol;?>">
                                  <div class="carousel-caption d-none d-md-block">
                                    <span class="badge badge-primary"><?php echo $key->nopol;?></span>
                          </div>
                            </div>
                        <?php  }?>
                        </div>
                        <a class="carousel-control-prev" href="#carouselExampleIndicators<?php echo $aw;?>" role="button"
                          data-slide="prev">
                          <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                          <span class="sr-only">Previous</span>
                        </a>
                        <a class="carousel-control-next" href="#carouselExampleIndicators<?php echo $aw;?>" role="button"
                          data-slide="next">
                          <span class="carousel-control-next-icon" aria-hidden="true"></span>
                          <span class="sr-only">Next</span>
                        </a>
                    </div>
                  </span>
                </label>
                <table class="table table-sm table-striped">
                      <thead>
                        <tr>
                          <th class="text-center" ><b>RINCIAN</b></th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <td class="text-center">Merk :&nbsp<?php echo $key->nmerk;?></td>
                        </tr>
                        <tr>
                          <td class="text-center">Jenis :&nbsp<?php echo $key->jenis;?></td>
                        </tr>
                        <tr>
                          <td class="text-center">Kapasitas Mesin :&nbsp<?php echo $key->ukuran;?></td>
                        </tr>
                        <tr>
                          <td class="text-center"><?php echo $key->asal;?>&nbsp<?php echo $key->thbeli;?></td>
                        </tr>
                        <tr>
                          <td class="text-center"><?php 
                              $bulan = array('1' => 'Januari','2' => 'Februari','3' => 'Maret','4' => 'April','5' => 'Mei','6' => 'Juni','7' => 'Juli','8' => 'Agustus','9' => 'September','10' => 'Oktober','11' => 'November','12' => 'Desember' );
                              $kbv=explode("/", $key->pjk);
                              echo "Pajak ".$kbv[1]." ".$bulan[$kbv[0]];
                          ?>
                            
                          </td>
                        </tr>
                        <tr>
                          <td class="text-center"><?php 
                               $hkarr = array('0' => 'Kepala Dinas','1' => 'Sekretaris','2' => 'Kabid TIK','3' => 'Kabid PDKI','4' => 'Kabid IKP','5' => 'Kabid e-Gov','6' => 'kabid Statistik','7' => 'Pool Kantor','8' => 'Sekretariat','9' => 'TIK','10' => 'PDKI','11' => 'IKP','12' => 'e-Gov','13' => 'Statistik','14' => 'Komisi Informasi' );
                               $tst = array('0' => 'Bisa Dipinjam','1' => 'Perawatan','2' => 'Penggunaan Khusus');
                              echo "<b>".$tst[$key->status]."</b>-".$hkarr[$key->hak];

                          ?>
                            
                          </td>
                        </tr>
                       <!--  <tr class="text-center">
                          <td>
                            <b>PENGGUNAAN</b><br>
                            <?php /*$prlarr= array(1 =>"Dinas Luar Biasa" ,2=>"Fasilitasi Kunjungan Tamu",3=>"Rangkaian Keg. Pimpinan Daerah",4=>"Kegiatan Lainnya" );
                              $pb=explode("-", trim($key->r_perlu,"-"));*/
                              /*echo "<pre>";
                              print_r($pb);
                              echo "</pre>";
                              for ($i=0; $i <count($pb) ; $i++) { 
                                  switch ($pb[$i]) {
                                    case "1":
                                      $kyn="success";
                                      break;
                                    case "2":
                                      $kyn="danger";
                                      break;
                                    case "3":
                                      $kyn="danger";
                                      break;
                                    default:
                                      $kyn="dark";
                                  }
                                  echo '<span class="badge badge-'.$kyn.'">'.$prlarr[$pb[$i]].'</span><br>';
                              } */?> 
                          </td> -->
                          <tr class="text-center">
                          <td>
                            <b>JANGKAUAN</b><br>
                            <?php 
                             $dfarr= array(1 =>"Dalam Kota" ,2=>"Dalam Daerah",3=>"Luar Daerah" );
                              $fw=explode("-", trim($key->r_tujuan,"-"));
                              /*echo "<pre>";
                              print_r($pb);
                              echo "</pre>";*/
                              for ($i=0; $i <count($fw) ; $i++) { 
                                  switch ($fw[$i]) {
                                    case "1":
                                      $tgh="success";
                                      break;
                                    case "2":
                                      $tgh="warning";
                                      break;
                                    case "3":
                                      $tgh="danger";
                                      break;
                                    default:
                                      $tgh="dark";
                                  }
                                  echo '<span class="badge badge-'.$tgh.'">'.$dfarr[$fw[$i]].'</span><br>';
                              }?>
                          </td>
                        </tr>
                        <tr>

                          <td class="text-center"> <b>KETERANGAN</b><br><?php echo $key->ket;?></td>
                        </tr>
                      </tbody>
                </table>


              </div>
            </div>
            <div class="pricing-cta">
             
              <a href="#">Pinjam<i class="fas fa-arrow-right"></i></a>
            </div>
          </div>
      </div>
    


    <?php
      $aw++;
    }
    ?>

    </div>
  </div>
</section>
<div class="modal fade" id="modal_hapus" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
          aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Yakin Hapus?</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p id="mp-tex"></p>
      </div>
      <div class="modal-footer bg-whitesmoke br">
         <?php echo form_open_multipart('mobil/hapus_mobil');?>
            <input type="hidden" name="id" value="" id="id_del">
            <input type="hidden" name="nopol" value="" id="nopol">
            <input type="hidden" name="nmfile" value="" id="nmfile">
            <button type="submit" class="btn btn-danger">Hapus</button>
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>  
        </form>
        
      </div>
    </div>
  </div>
</div>
<script src="<?php echo base_url()?>harta/morsip/assets/js/jquery-3.6.3.min.js"></script>
<script type="text/javascript">
$(document).on('click','#hapus',function(){
        $('#modal_hapus').modal('show');
        var id=$(this).data('id');
        var no=$(this).data('nopol');
        var mrk=$(this).data('merk');
        var mk=$(this).data('nmfile');
        var text="Hapus Mobil Nomor : "+no+" - "+mrk;
        $("#mp-tex").text(text);
        $("#id_del").val(id);
        $("#nopol").val(no);
        $("#nmfile").val(mk);
});
$(document).ready(function(){

});
</script>

<!-- <script src="<?php //echo base_url()?>harta/morsip/assets/bundles/owlcarousel2/dist/owl.carousel.min.js"></script>
<script src="<?php //echo base_url()?>harta/morsip/assets/js/page/owl-carousel.js"></script>
 -->