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
                $tahuna=$this->session->userdata(base64_encode('jajahan'));
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
                }
                //echo "ini hak->".$hak;
                //print_r($hk);
                $hak=$hk[1];
                $tahun=$this->session->userdata('tahun');
                $fg=$jumdat/1000;$i=1;
                while ($i <=ceil($fg)) {?>
                  <div class="page-item"><a class="page-link" href="<?php echo base_url();?>morsip/datanomor/<?php echo$i;?>"><?php echo"$i";?></a></div>
                <?php $i++; }
              ?><!-- </ul> -->
          </div>
          <div class="card-body">

            <div class="table-responsive">

              <table class="table table-striped" id="table-1">
                <thead>
                  <tr>
                    <th class="text-center">NO</th>
                    <th class="text-center">SUB BAGIAN</th>
                    <th class="text-center">NOMOR SURAT</th>
                    <th class="text-center">TANGAL SURAT</th>
                    <th class="text-center">KEPADA</th>
                    <th class="text-center">PERIHAL</th>
                    <th class="text-center">STATUS DOKUMEN</th>
                    <th class="text-center">ACTION</th>
                    
                  </tr>
                </thead>
                <tbody>
                  <?php $i=1; foreach ($datasur as $key) { ?>
                      <tr>
                          <td ><?php echo $i;?></td>
                          <td  ><?php echo $key->bidang;?></td>
                          <td  class="text-center">
                            <?php echo $key->kodesur."/".$key->nosur;

                            $daa=$this->encryption->encrypt(base64_encode($key->nosur)); 
                                  $knj=str_replace(array('+','/','='),array('-','_','~'),$daa);

                            ?>
                            <a href="<?php echo base_url();?>morsip/downloadqr/<?php echo $knj; ?>"> <button type="button" class="btn btn-warning btn-sm" data-toggle="tooltip" title="Unduh QR-Code"><i class="fa fa-qrcode" aria-hidden="true" ></i></button></a>
                          </td>
                          <td  ><?php echo $key->tgl;?></td>
                          <td  ><?php echo $key->kpd;?></td>
                          <td  ><?php echo $key->perihal;?></td>
                          <td  >
                            <?php 
                              if ($key->k==212 && !file_exists(FCPATH."harta/morsip/doc/dokumen$tahun/$key->nmf.pdf")) {
                                echo "Dokumen e-SPT";
                              }elseif(file_exists(FCPATH."harta/morsip/doc/dokumen$tahun/$key->nmf.pdf")){
                                $arr = array('0' =>"Belum Upload Doc TTE" , '1' =>"Proses TTE" , '2' =>"Selesai TTE" ,'3' =>"Pengajuan Sekretaris",'4' =>"Revisi TTE", '5' =>"Pengajuan Revisi TTE"); 
                               echo $arr[$key->a];
                              }

                              elseif (!file_exists(FCPATH."harta/morsip/doc/dokumen$tahun/$key->nmf.pdf")) {
                                echo "Dokumen Tidak Ditemukan";
                              }
                              else{
                                echo "Error";
                              }

                            ?>
                          
                          </td>
                          <td>
                            <?php
                              if (file_exists(FCPATH."harta/morsip/doc/dokumen$tahun/$key->nmf.pdf")) { 
                                  $data=$this->encryption->encrypt(base64_encode($key->nmf)); 
                                  $ff=str_replace(array('+','/','='),array('-','_','~'),$data);  ?>
                                  <a href="<?php echo base_url();?>morsip/view_doc/<?php echo $ff;?>" target="_blank">
                                  <button type="button" class="btn btn-info btn-sm" data-toggle="tooltip" title="Print Doc">
                                    <i class="fa fa-print" aria-hidden="true" ></i>
                                  </button>
                                  </a>
                                 <?php   
                                  if($key->a==0){
                                    ?>
                                    <a href="<?php   
                                $data=$this->encryption->encrypt(base64_encode($key->id)); $ff=str_replace(array('+','/','='),array('-','_','~'),$data); echo base_url();?>morsip/up_tte/<?php echo $ff; ?>">
                                    <button type="button" class="btn btn-success btn-sm" data-toggle="tooltip" title="Upload Dokumen Untuk TTE">
                                      <i  class="fa fa-plus" aria-hidden="true"></i>
                                    </button></a>
                                  <?php
                                  }
                                ?>
                            <?php }
                              if (($key->isu=0 && $key->k!=212) || $hak==0) { ?>
                                <a href="<?php   
                                $data=$this->encryption->encrypt(base64_encode($key->id)); $ff=str_replace(array('+','/','='),array('-','_','~'),$data); echo base_url();?>morsip/edit_nomor/<?php echo $ff; ?>">
                                <button type="button" class="btn btn-warning btn-sm" data-toggle="tooltip" title="Edit Nomor"><i class="fa fa-pen" aria-hidden="true"></i></button></a>
                              <?php 
                                }
                                if (!file_exists(FCPATH."harta/morsip/doc/dokumen$tahun/$key->nmf.pdf") && $key->k!=212){ ?>
                                    <a href="<?php $data=$this->encryption->encrypt(base64_encode($key->id)); $ff=str_replace(array('+','/','='),array('-','_','~'),$data); echo base_url();?>morsip/up_doc/<?php echo $ff; ?>">
                                    <button type="button" class="btn btn-primary btn-sm" data-toggle="tooltip" title="Upload Ulang Dokumen">
                                      <i  class="fa fa-upload" aria-hidden="true"></i>
                                    </button>
                                    </a>
                                <?php
                                }
                             ?>
                            <button type="button" class="btn btn-danger btn-sm" data-toggle="tooltip" title="Hapus Nomor" id="hapus" data-nosur="<?php echo $key->nosur;?>" data-id="<?php echo $key->id;?>" data-hal="<?php echo strip_tags($key->perihal);?>">
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
      </div>
      <div class="modal-footer bg-whitesmoke br">
         <?php echo form_open_multipart('morsip/hapus_nomor');?>
            <input type="hidden" name="id" value="" id="id_del">
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
        var no=$(this).data('nosur');
        var hal=$(this).data('hal');
        var text="Hapus Nomor Surat : "+no+" - "+hal;
        $("#mp-tex").text(text);
        $("#id_del").val(id);
});
$(document).ready(function(){

});
</script>