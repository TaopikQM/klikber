<link rel="stylesheet" href="<?php echo base_url()?>harta/morsip/assets/bundles/datatables/datatables.min.css">
<link rel="stylesheet" href="<?php echo base_url()?>harta/morsip/assets/bundles/datatables/DataTables-1.10.16/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="<?php echo base_url()?>harta/morsip/assets/bundles/prism/prism.css">
<link rel="stylesheet" href="<?php echo base_url()?>harta/morsip/assets/bundles/select2/dist/css/select2.min.css">





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
            <!-- <a href="<?php echo base_url()?>pasien/addKonsutasi" >
            <button type="button" class="btn btn-info btn-icon icon-left">
                  <i class="fas fa-plus"></i> Tambah
            </button></a> -->
            <h4>&nbsp|| Data Konsultasi Pasien  </h4>
            </br>
            
          </div>
          <div class="card-body">

            <div class="table-responsive">

              <table class="table table-striped table-hover" id="table-1" style="width:100%;" >
                <thead>
                  <tr bgcolor="#cccccc" >
                    <th class="text-center">NO</th>
                    <th class="text-center">TANGGAL KONSULTASI</th>
                    <th class="text-center">NAMA PASIEN</th>
                    <th class="text-center">SUBJECT</th>
                    <th class="text-center">PERTANYAAN</th>
                    <th class="text-center">TANGGAPAN</th>
                    <th class="text-center">ACTION</th>
                    
                  </tr>
                </thead>
                <tbody>
                  <?php
                    // echo "<pre>";
                    // print_r($riwayat);
                    // echo "</pre>";
                  ?>
                  <?php if (isset($riwayat) && !empty($riwayat)): ?> 
                    <?php $nomori=1; foreach ($riwayat as $key) { ?>
                        <tr bgcolor="#cccccc">
                            <td  class="text-center"><?php echo $nomori;?></td>
                            
                            <td class="text-center" >
                            
                              <?php 
                                echo $key['tgl_konsultasi'];
                              ?> 
                            </td> 
                             
                            <td class="text-center" >
                              
                              <?php 
                                echo $key['nama'];
                              ?>
                            </td> 
                            
                            <td class="text-center" >
                            <?php 
                                echo $key['subject'];
                              ?>
                            </td> 
                            <td class="text-center" >
                              <?php 
                                echo $key['pertanyaan'];
                              ?>    
                            </td> 
                            <td class="text-center" >
                              <?php 
                                echo $key['jawaban'];
                              ?>    
                            </td> 
                            <td class="text-center" >
                            
                            <?php if(empty ($key['jawaban'])){?>
                               <a href="<?php $dxc=$this->encryption->encrypt(base64_encode($key['id_konsul'])); $ff=str_replace(array('+','/','='),array('-','_','~'),$dxc); echo base_url();?>dokter/ekonsul/<?php echo $ff; ?>">
                               <button type="button" class="btn btn-primary btn-sm" data-toggle="tooltip" title="Tanggapi">
                                 <!-- <i class="fa fa-pen" aria-hidden="true"></i> -->TANGGAPI
                                </button>
                               </button>
                               </a><?php
                            }else{?>
                                <a href="<?php $dxc=$this->encryption->encrypt(base64_encode($key['id_konsul'])); $ff=str_replace(array('+','/','='),array('-','_','~'),$dxc); echo base_url();?>dokter/ekonsul/<?php echo $ff; ?>">
                              <button type="button" class="btn btn-warning btn-sm" data-toggle="tooltip" title="Edit">
                                <i class="fa fa-pen" aria-hidden="true"></i></button>
                              </button>
                              </a><?php
                            }?>

                            
                              
                            
                              <!-- <button type="button" class="btn btn-primary btn-sm" data-toggle="tooltip" title="Upload Ulang Dokumen">
                                <i  class="fa fa-upload" aria-hidden="true"></i>
                              </button> -->
                              <!-- <button type="button" class="btn btn-danger btn-sm" data-toggle="tooltip" title="Hapus " id="hapus"  data-id="<?php echo $key['id_konsul'];?>" data-pertanyaan="<?php echo $key['pertanyaan']; ?>">
                                <i  class="fa fa-trash" aria-hidden="true"></i>
                              </button> -->
                              <!-- <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#detailModal<?= $key['id']; ?>">Detail</button> -->
                              <!-- <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#detailModal<?= $key['id']; ?>">Detail</button> -->

                            </td>
                        </tr>
                    <?php $nomori++;
                      }
                    ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4" class="text-center">Tidak ada data Konsultasi.</td>
                        </tr>
                    <?php endif; ?>
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
         <?php echo form_open_multipart('pasien/dkonsul');?>
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
        var nama=$(this).data('pertanyaan');
        var text="Hapus Konsultasi : "+nama;
        $("#mp-tex").text(text);
        $("#id_del").val(id);
});
$(document).ready(function(){

});
</script>

<!-- <script src="<?php //echo base_url()?>harta/morsip/assets/bundles/owlcarousel2/dist/owl.carousel.min.js"></script>
<script src="<?php //echo base_url()?>harta/morsip/assets/js/page/owl-carousel.js"></script>
 -->