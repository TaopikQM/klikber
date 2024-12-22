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
            <h4>Data SPT || </h4>
            <!-- <ul class="pagination"> -->
              <?php
                
                //print_r($datasur);
                $tahuna=$this->session->userdata(base64_encode('jajahan'));
                //print_r($tahuna);
                  $hm=false;
                for ($i=0; $i <count($tahuna) ; $i++) {
                  //echo base64_decode( $tahuna[$i][base64_encode('hk')])."<br>";
                 // echo $this->encryption->decrypt( ( $tahuna[$i][base64_encode('hk')]))."<br>";
                    if (base64_decode( $tahuna[$i][base64_encode('apli')])=='1_morsip') {
                          $hm=true;
                          $bm=explode("_", $this->encryption->decrypt( ( $tahuna[$i][base64_encode('hk')])) );
                          $hak=$bm[1];
                    }
                    
                }
                if (!$hm) {
                  redirect('landing/menu');
                }
                //echo "ini hak->".$hak;
                //print_r($hak);
                
                $tahun=$this->session->userdata('tahun');
                $fg=$jumdat/1000;$i=1;
                while ($i <=ceil($fg)) {?>
                  <div class="page-item"><a class="page-link" href="<?php echo base_url();?>morsip/dataspt/<?php echo$i;?>"><?php echo"$i";?></a></div>
                <?php $i++; }
              ?><!-- </ul> -->
          </div>
          <div class="card-body">

            <div class="table-responsive">

              <table class="table table-striped" id="table-1" width="100%">
                <thead>
                  <tr>
                    <th class="text-center">NO</th>
                    <th class="text-center">BIDANG<br>(SUB BAGIAN)</th>
                    <th class="text-center">NOMOR SURAT</th>
                    <th class="text-center">TANGAL SURAT</th>
                    <th class="text-center">NAMA</th>
                    <th class="text-center">KEPERLUAN</th>
                    <th class="text-center">DURASI</th>
                    <th class="text-center">PROGRES</th>
                    <th class="text-center">AKSI</th>
                    
                  </tr>
                </thead>
                <tbody>
                  <?php $ia=1; foreach ($datasur as $key) { ?>
                      <tr>
                          <td ><?php echo $ia;?></td>
                          <td ><?php echo $key->bidang;?></td>
                          <td class="text-center">
                              <?php echo "094/".$key->nosur;
                                $data=$this->encryption->encrypt(base64_encode($key->id)); 
                                $ff=str_replace(array('+','/','='),array('-','_','~'),$data);
                              ?>
                              <a href="<?php echo base_url();?>morsip/preview_spt/<?php echo $ff; ?>" target="_blank"><button type="button" class="btn btn-warning btn-md" data-toggle="tooltip" title="Preview Dokumen"><i class="fa fa-print" aria-hidden="true" ></i></button></a>
                          </td>
                          <td  ><?php echo date("d-m-Y",$key->tgldl);?></td>
                          <td >
                            <?php
                              $sx=explode("-",$key->nmdl);
                              for ($i=0; $i <count($sx) ; $i++) { 
                                echo "* ".$sx[$i]."<br><br>";
                              }
                            ?>
                              
                            </td>
                          <td  width=""><?php echo base64_decode($key->keperluan);?></td>
                          <td  align="center">
                            <?php echo date("d-m-Y",$key->tglin)."<br>s/d<br>".date("d-m-Y",$key->tglot);
                              $gh=abs($key->tglot-$key->tglin);
                              $hit=ceil($gh/86400)+1;
                              echo "<br>Selama ".$hit." Hari";
                            ?>
                            
                          </td>
                          <td  >
                            <?php
                             $k1=0; $k2=0; $k3=0; $k4=0;$k5=0;
                            switch($key->kdbid){
                                case strlen($key->kdbid)>1 : $k1=1;break;
                                case 1 : $k4=1;break;
                                case 5 : $k5=1;break;
                                case 2 : $k3=1;break;
                                default  : $k2=1;break;
                            }
                            $voba = array(
                        '1' => 'success' , 
                       '0' => 'info');
                            ?>
                            <div class="btn-group-vertical" role="group" aria-label="Basic example">
                              <?php
                                if ($key->a==3) { ?>
                                    <button type="button" class="btn btn-danger btn-md ditolak">Ditolak</button>
                              <?php    
                                }
                              ?>
                              <button type="button" class="btn btn-<?php echo $voba[$k1]; ?> btn-md">Kasi</button>
                              <button type="button" class="btn btn-<?php echo $voba[$k2]; ?> btn-md">Ka Bidang</button>
                              <button type="button" class="btn btn-<?php echo $voba[$k3]; ?> btn-md">Sekretaris</button>
                              <button type="button" class="btn btn-<?php echo $voba[$k4]; ?> btn-md">Ka Dinas</button>
                              <button type="button" class="btn btn-<?php echo $voba[$k5]; ?> btn-md">ACC</button>
                            </div>
                          </td>
                          <td  ><?php
                                if (file_exists(FCPATH."harta/morsip/doc/dokumen$tahun/spt/tte/$key->nosur.pdf") && $key->a==1 && $key->kdbid==5){ ?>
                              <button type="button" class="btn btn-info btn-md" data-toggle="tooltip" title="Print Doc"><i class="fa fa-print" aria-hidden="true" ></i></button>
                            <?php }?>
                              <a href="<?php   
                                $data=$this->encryption->encrypt(base64_encode($key->id)); $ff=str_replace(array('+','/','='),array('-','_','~'),$data); echo base_url();?>morsip/edit_spt/<?php echo $ff; ?>">
                              <button type="button" class="btn btn-warning btn-md" data-toggle="tooltip" title="Edit SPT"><i class="fa fa-pen" aria-hidden="true"></i></button></a>

                              <button type="button" class="btn btn-danger btn-md" data-toggle="tooltip" title="Hapus SPT" id="hapus" data-nosur="<?php echo $key->nosur;?>" data-id="<?php echo $key->id;?>" data-hal="<?php echo base64_decode($key->keperluan);?>" >
                              <i  class="fa fa-trash" aria-hidden="true">
                          
                          </td>
                          
                      </tr>
                  <?php $ia++;
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
<div class="modal fade" id="modal_ditolak" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
          aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Alasan Penolakan</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p id="mp-tex"></p>
      </div>
      <div class="modal-footer bg-whitesmoke br">
         <?php echo form_open_multipart('morsip/hapus_spt');?>
            <input type="hidden" name="id" value="" id="id_del">
            <input type="hidden" name="nosur" value="" id="id_nosur">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>  
        
        
      </div>
    </div>
  </div>
</div>

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
         <?php echo form_open_multipart('morsip/hapus_spt');?>
            <input type="hidden" name="id" value="" id="id_del">
            <input type="hidden" name="nosur" value="" id="id_nosur">
            <button type="submit" class="btn btn-danger">Hapus</button>
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>  
        </form>
        
      </div>
    </div>
  </div>
</div>
<script src="<?php echo base_url()?>harta/morsip/assets/js/jquery-3.6.3.min.js"></script>
<script type="text/javascript">
$(document).on('click','.ditolak',function(){
        $('#modal_ditolak').modal('show');
        /*var id=$(this).data('id');
        var no=$(this).data('nosur');
        var hal=$(this).data('hal');
        var text="Hapus Nomor Surat : 094/"+no+" - "+hal;
        $("#mp-tex").text(text);
        $("#id_del").val(id);
        $("#id_nosur").val(no);*/
});  
$(document).on('click','#hapus',function(){
        $('#modal_hapus').modal('show');
        var id=$(this).data('id');
        var no=$(this).data('nosur');
        var hal=$(this).data('hal');
        var text="Hapus Nomor Surat : 094/"+no+" - "+hal;
        $("#mp-tex").text(text);
        $("#id_del").val(id);
        $("#id_nosur").val(no);
});
$(document).ready(function(){

});
</script>