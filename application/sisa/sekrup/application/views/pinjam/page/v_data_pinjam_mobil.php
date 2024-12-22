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
            <h4>Data Nomor || </h4>
            <!-- <ul class="pagination"> -->
              <?php
                
                //print_r($datasur);
                /*$tahuna=$this->session->userdata(base64_encode('jajahan'));
                //print_r($tahuna);
                  $hm=false;
                for ($i=0; $i <count($tahuna) ; $i++) {
                  //echo base64_decode( $tahuna[$i][base64_encode('hk')])."<br>";
                 // echo $this->encryption->decrypt( ( $tahuna[$i][base64_encode('hk')]))."<br>";
                    if (base64_decode( $tahuna[$i][base64_encode('apli')])=='1_morsip') {
                          $hm=true;
                          $bm=explode("_", $this->encryption->decrypt( ( $tahuna[$i][base64_encode('hk')])) );
                          //$hak=$bm[1];
                          $hk=$bm;
                    }
                    
                }
                if (!$hm) {
                  redirect('landing/menu');
                }*/
                //echo "ini hak->".$hak;
                //print_r($hk);
/*                $hak=$hk[1];
                $tahun=$this->session->userdata('tahun');
                $fg=$jumdat/1000;$i=1;
                while ($i <=ceil($fg)) {*/ ?>
                  <!-- <div class="page-item"><a class="page-link" href="<?php //echo base_url();?>morsip/datanomor/<?php //echo$i;?>"><?php //echo"$i";?></a></div> -->
                <?php //$i++; }
              ?><!-- </ul> -->
          </div>
          <div class="card-body">

            <div class="table-responsive">

              <table class="table table-striped" id="table-1">
                <thead>
                  <tr>
                    <th class="text-center">NO</th>
                    <th class="text-center">Admin<br>Subbag/Bidang</th>
                    <th class="text-center">NOMOR PEMINJAMAN</th>
                    <th class="text-center">NOPOL KENDARAN/MERK</th>
                    <th class="text-center">TANGAL PEMINJAMAN</th>
                    <th class="text-center">DURASI PINJAM</th>
                    <th class="text-center">DRIVER/<br>PENUMPANG</th>
                    <th class="text-center">TUJUAN</th>
                    <th class="text-center">KEPERLUAN</th>
                    <th class="text-center">STATUS PEMINJAMAN</th>
                    <th class="text-center">ACTION</th>
                    
                  </tr>
                </thead>
                <tbody>
                  <?php
                    /*echo "<pre>";
                    print_r($datamobil);
                    echo "</pre>";*/
                  ?>
                  <?php $i=1; foreach ($datamobil as $key) { ?>
                      <tr>
                          <td class="text-center"><?php echo $i;?></td>
                          <td class="text-center" >
                            <?php 
                              $dhp=getAdmin($key->idadmin);
                              echo $dhp->username."<br>".$dhp->nm_bid;
                            ?>    
                          </td>
                          <td  class="text-center">
                            <?php echo $key->nopinjam;
                            ?>
                          </td>
                          <td  class="text-center">
                            <?php  
                              if (($key->nopol && $key->nmerk) != NULL) {
                                  echo $key->nopol."<br>";
                                  echo $key->nmerk;
                              }else{
                                echo '<div class="alert alert-danger"><center>DATA ITEM TELAH DIHAPUS</center></div>';
                              }
                            ?>
                          </td>
                          <td class="text-center" ><?php echo $key->tglaju;?></td>
                          <td class="text-center">
                            <?php 
                              $tga=date_create(date('Y-m-d',$key->tglin));
                              $tgb=date_create(date('Y-m-d',$key->tglot));
                              $jmhar=date_diff($tga,$tgb);
                            ?>
                              Durasi peminjaman selama <?php echo $jmhar->d+1;?> Hari<br>Mulai tanggal<br>
                            <?php echo date('d-m-Y',$key->tglin);?><br>s/d<br><?php echo date('d-m-Y',$key->tglot);?><br>
                          </td>
                          <td class="text-center">
                            <?php
                               $nmdrvr=namaSPT($key->drv,date('Y'));
                               echo "Nama Driver<br>".$nmdrvr."<br><br>Nama Penumpang<br>";
                               $apen=explode("-", trim($key->penumpang,"-"));

                               //print_r($apen);
                               for ($dh=0; $dh < count($apen) ; $dh++) { 
                                 echo "- ".namaSPT($apen[$dh],date('Y'))."<br>";
                               }

                            ?>
                          </td>
                          <td class="text-center"  ><?php 
                                $asf = array('1' =>'Dalam Kota' ,'2' =>'Dalam Daerah' ,'3' =>'Luar Daerah');
                                echo $asf[$key->mbl_tujuan];?>
                            
                          </td>
                          <td class="text-center" ><?php 
                                $hku = array(
                                  '1' => 'Dinas Luar Biasa',
                                  '2' => 'Fasilitasi Kunjungan Tamu',
                                  '3' => 'Rangkaian Kegiatan Pimpinan Daerah',
                                  '4' => 'Kegiatan Lainnya'
                                 );
                                echo $hku[$key->mbl_perlu];?><br>
                                <?php echo $key->mbl_ket_perlu;?>        
                          </td>
                          <td class="text-center" >
                              <?php
                                 $k0=0; $k1=0; $k2=0;
                                switch($key->sts_pjm){
                                    case 0 : $k0=1;break;
                                    case 1 : $k1=1;break;
                                    case 2 : $k2=3;break;
                                    default  : $k0=1;break;
                                }
                                $voba = array(
                                  '1' => 'success' , 
                                  '3' => 'danger' , 
                                  '0' => 'default');
                              ?>
                              <div class="btn-group-vertical" role="group" aria-label="Basic example">
                                <button type="button" class="btn btn-<?php echo $voba[$k2]; ?> btn-md">Ditolak</button>
                                <button type="button" class="btn btn-<?php echo $voba[$k0]; ?> btn-md">Ajuan Awal</button>
                                <button type="button" class="btn btn-<?php echo $voba[$k1]; ?> btn-md">Disetujui</button>
                              </div>
                          </td>
                          <td class="text-center" >
                            <?php
                              if ($key->sts_pjm==1) { ?>
                            <a href="<?php $data=$this->encryption->encrypt(base64_encode($key->id_pjm)); $ff=str_replace(array('+','/','='),array('-','_','~'),$data); echo base_url();?>pinjam/cetak_pinjam/<?php echo $ff; ?>" target="_blank">
                            <button type="button" class="btn btn-info btn-sm" data-toggle="tooltip" title="Print Doc">
                              <i class="fa fa-print" aria-hidden="true" ></i>
                            </button></a>
                            <?php }
                            ?>
                            <!-- <button type="button" class="btn btn-success btn-sm" data-toggle="tooltip" title="Upload Dokumen Untuk TTE">
                              <i  class="fa fa-plus" aria-hidden="true"></i>
                            </button> -->
                             <a href="<?php   
                                $data=$this->encryption->encrypt(base64_encode($key->id_pjm)); $ff=str_replace(array('+','/','='),array('-','_','~'),$data); echo base_url();?>pinjam/edit_pinjaman_mobil/<?php echo $ff; ?>">
                            <button type="button" class="btn btn-warning btn-sm" data-toggle="tooltip" title="Edit Peminjaman">
                              <i class="fa fa-pen" aria-hidden="true"></i></button></a>
                            </button>
                            <button type="button" class="btn btn-primary btn-sm" data-toggle="tooltip" id="ubahst" data-id="<?php echo $key->id_pinjam;?>" data-status="<?php echo $key->sts_pjm;?>"title="Ubah Status Peminjaman">
                              <i  class="fa fa-upload" aria-hidden="true"></i>
                            </button>
                            <button type="button" class="btn btn-danger btn-sm" data-toggle="tooltip" title="Hapus Nomor" id="hapus" data-nopin="<?php echo $key->nopinjam;?>" data-id="<?php echo $key->id_pinjam;?>" data-nopol="<?php echo $key->nopol;?>">
                              <i  class="fa fa-trash" aria-hidden="true"></i>
                            </button>
                            
                            
                          </td>
                      </tr>
                  <?php $i++;
                    }
                  ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
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
        <p id="mp-tex1"></p>
      </div>
      <div class="modal-footer bg-whitesmoke br">
         <?php echo form_open_multipart('pinjam/hapus_pinjam_mobil');?>
            <input type="hidden" name="id" value="" id="id_del">
            <button type="submit" class="btn btn-danger">Hapus</button>
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>  
        </form>
        
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="modal_status" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
          aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Yakin Merubah Status?</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <?php echo form_open_multipart('pinjam/ubah_status_pinjam');?>
      <div class="modal-body">
        <select class="form-control" id="pilihstatus" name="pilihstatus" >
            <option value="2">Tolak</option>
            <option value="0">Ajuan Awal</option>
            <option value="1">Setujui</option>
            
            
        </select>
        <p id="mp-tex3"></p>
      </div>
      <div class="modal-footer bg-whitesmoke br">
            <input type="hidden" name="id" value="" id="id_st">
            <button type="submit" class="btn btn-primary">Ubah</button>
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
        var no=$(this).data('nopin');
        var hal=$(this).data('nopol');
        var text="Hapus Nomor Peminjaman : "+no;
        var tgh= "Nomor Kendaraan : "+hal;
        $("#mp-tex").text(text);
        $("#mp-tex1").text(tgh);
        $("#id_del").val(id);
});

$(document).ready(function(){
  $(document).on('click','#ubahst',function(){
          $('#modal_status').modal('show');
          var id=$(this).data('id');
          var st=$(this).data('status');
          //document.getElementById("#pilihstatus").value =st;
          $("#pilihstatus").val(st).change();
          $("#id_st").val(id);
  });
});
</script>