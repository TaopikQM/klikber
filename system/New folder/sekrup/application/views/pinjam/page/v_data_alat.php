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
            <a href="<?php echo base_url()?>alat/tambah">
            <button type="button" class="btn btn-info btn-icon icon-left">
                  <i class="fas fa-plus"></i> Tambah
            </button></a>
            <h4>&nbsp|| Data Nomor  </h4>
            </br>
            <a href="<?php echo site_url('reports/alat_ekspor_excel/exalat') ?>" class="btn btn-success" target="_blank">
                <i class="fas fa-file-excel"></i> Export to Excel
            </a> 
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

              <table class="table table-striped table-hover" id="table-1" style="width:100%;" >
                <thead>
                  <tr bgcolor="#cccccc" >
                    <th class="text-center">NO</th>
                    <th class="text-center">GAMBAR</th>
                    <th class="text-center">NAMA ALAT</th>
                    <th class="text-center">STATUS ALAT</th>
                    <th class="text-center">KETERANGAN</th>
                    <th class="text-center">ACTION</th>
                    
                  </tr>
                </thead>
                <tbody>
                  <?php
                    /*echo "<pre>";
                    print_r($dataruang);
                    echo "</pre>";*/
                  ?>
                  <?php $nomori=1; foreach ($dataalat as $key) { ?>
                      <tr bgcolor="#cccccc">
                          <td  class="text-center"><?php echo $nomori;?></td>
                          <td  class="text-center">
                            <?php 
                              $hbg=str_replace(" ","",$key->nmbarang);
                              $folder = "harta/pinjam/dataalat/".strtoupper($hbg);
                              $handle =  glob($folder."/*.*");
                              if (isset($handle[0]) && $handle !=NULL) {
                                echo '<a href="'.base_url().$handle[0].'" target="_blank">';
                                echo '<img class="d-block w-100" width="100" height="120" src="'.base_url().$handle[0].'" alt="'.$key->nmbarang.'"></a>';
                              }
                            ?>
                          </td>
                          <td class="text-center" >
                            <?php 
                              echo $key->nmbarang."<br>";
                            ?>    
                          </td> 
                          
                          
                          <td class="text-center" ><?php
                              $tst = array('0' => 'Bisa Dipinjam','1' => 'Perawatan','2' => 'Penggunaan Khusus');
                              $hta="success";
                              if ($key->status==2) {
                                 $hta="danger";
                               }
                           ?> 
                             <span class="badge badge-<?php echo $hta;?>"><?php echo $tst[$key->status];?></span>
                          </td>
                          
                          
                          <td class="text-center"><?php echo $key->ket;?></td>
                          <td class="text-center" >
                            <?php
                              if (file_exists(FCPATH."harta/pinjam/dataalat/".$key->nmfile.".pdf")) {
                                  $wpy="";?>
                            <a href="<?php echo base_url()?>harta/pinjam/dataalat/<?php echo $key->nmfile;?>.pdf">
                            <button type="button" class="btn btn-info btn-sm" data-toggle="tooltip" title="Print Doc Profil">
                              <i class="fa fa-print" aria-hidden="true" ></i>
                            </button></a>    
                            <?php  }else{ ?>
                            <button type="button" class="btn btn-danger btn-sm" data-toggle="tooltip" title="Dokumen tidak Tersedia">
                              <i class="fa fa-print" aria-hidden="true" ></i>
                            </button>
                            <?php
                              }
                            ?>

                            <a href="<?php $dxc=$this->encryption->encrypt(base64_encode($key->id)); $ff=str_replace(array('+','/','='),array('-','_','~'),$dxc); echo base_url();?>alat/foto/<?php echo $ff; ?>">
                            <button type="button" class="btn btn-dark btn-sm" data-toggle="tooltip" title="Edit Foto">
                              <i  class="fa fa-camera" aria-hidden="true"></i>
                            </button></a>

                            <a href="<?php $dxc=$this->encryption->encrypt(base64_encode($key->id)); $ff=str_replace(array('+','/','='),array('-','_','~'),$dxc); echo base_url();?>alat/edit/<?php echo $ff; ?>">
                            <button type="button" class="btn btn-warning btn-sm" data-toggle="tooltip" title="Edit Ruangan">
                              <i class="fa fa-pen" aria-hidden="true"></i></button>
                            </button>
                            </a>
                            
                            <!-- <button type="button" class="btn btn-primary btn-sm" data-toggle="tooltip" title="Upload Ulang Dokumen">
                              <i  class="fa fa-upload" aria-hidden="true"></i>
                            </button> -->
                            <button type="button" class="btn btn-danger btn-sm" data-toggle="tooltip" title="Hapus Nomor" id="hapus" data-nmfile="<?php echo $key->nmfile;?>" data-nmbarang="<?php echo $key->nmbarang;?>" data-id="<?php echo $key->id;?>" >
                              <i  class="fa fa-trash" aria-hidden="true"></i>
                            </button>
                          </td>
                      </tr>
                  <?php $nomori++;
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
         <?php echo form_open_multipart('alat/hapus');?>
            <input type="hidden" name="id" value="" id="id_del">
            <input type="hidden" name="nmbarangmod" value="" id="nmbarangmod">
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
    var no=$(this).data('nmbarang');
    var noa=$(this).data('nmfile');
    var text="Hapus Ruangan "+no;
    $("#mp-tex").text(text);
    $("#id_del").val(id);
    $("#nmbarangmod").val(no);
    $("#nmfile").val(noa);
});

</script>

<!-- <script src="<?php //echo base_url()?>harta/morsip/assets/bundles/owlcarousel2/dist/owl.carousel.min.js"></script>
<script src="<?php //echo base_url()?>harta/morsip/assets/js/page/owl-carousel.js"></script>
 -->