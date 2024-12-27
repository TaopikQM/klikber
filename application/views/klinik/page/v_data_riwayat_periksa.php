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
            <!-- <a href="<?php echo base_url()?>pasien/daftarPoli" >
            <button type="button" class="btn btn-info btn-icon icon-left">
                  <i class="fas fa-plus"></i> Tambah
            </button></a> -->
            <h4>&nbsp|| Data Nomor  </h4>
            </br>
            <!-- <a href="<?php echo site_url('reports/mobil_ekspor_excel/exmobil') ?>" class="btn btn-success" target="_blank">
                <i class="fas fa-file-excel"></i> Export to Excel
            </a>  -->
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
                    <th class="text-center">TANGGAL PERIKSA</th>
                    <th class="text-center">RIWAYAT MEDIS</th>
                    <th class="text-center">DOKTER</th>
                    <th class="text-center">KELUHAN</th>
                    <th class="text-center">CATATAN</th>
                    <th class="text-center">OBAT</th>
                    <th class="text-center">ACTION</th>
                    
                  </tr>
                </thead>
                <tbody>
                  <?php
                    // echo "<pre>";
                    // print_r($periksa);
                    // echo "</pre>";
                  ?>
                  
                  <?php if (isset($periksa) && !empty($periksa)): ?>
                    <?php $nomori=1; foreach ($periksa as $key) { ?>
                        <tr bgcolor="#cccccc">
                            <td  class="text-center"><?php echo $nomori;?></td>
                            
                            <td class="text-center" >
                              <?php 
                                echo $key->tgl_periksa;
                              ?>    
                            </td> 
                            <td class="text-center"><?php echo $key->no_rm;?></td>
                            <td class="text-center"><?php echo $key->dokter_nama;?></td>
                            <td class="text-center"><?php echo $key->keluhan;?></td>
                            <td class="text-center"><?php echo $key->catatan;?></td>
                            <td class="text-center"><?php echo $key->obat;?></td>
                            
                            <td class="text-center" >
                            

                              <!-- <a href="<?php $dxc=$this->encryption->encrypt(base64_encode($key->id_periksa)); $ff=str_replace(array('+','/','='),array('-','_','~'),$dxc); echo base_url();?>dokter/edit_riwayat/<?php echo $key->id_periksa; ?>">
                              <button type="button" class="btn btn-warning btn-sm" data-toggle="tooltip" title="Edit">
                                <i class="fa fa-pen" aria-hidden="true"></i></button>
                              </button>
                              </a> -->
                              
                              <!-- <button type="button" class="btn btn-primary btn-sm" data-toggle="tooltip" title="Upload Ulang Dokumen">
                                <i  class="fa fa-upload" aria-hidden="true"></i>
                              </button> -->
                              <!-- <button type="button" class="btn btn-danger btn-sm" data-toggle="tooltip" title="Hapus " id="hapus"  data-id="<?php echo $key->id;?>" data-nama_role="<?php echo $key->nama_role; ?>">
                                <i  class="fa fa-trash" aria-hidden="true"></i>
                              </button> -->
                              <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#detailModal<?= $key->id_periksa; ?>">Detail</button>

                            </td>
                        </tr>
                    <?php $nomori++;
                      }
                    ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4" class="text-center">Tidak ada riwayat pasien.</td>
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
         <?php echo form_open_multipart('users/delete');?>
            <input type="hidden" name="id" value="" id="id_del">
            <button type="submit" class="btn btn-danger">Hapus</button>
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>  
        </form>
        
      </div>
    </div>
  </div>
</div>
<!-- Modal Detail -->
<?php foreach ($periksa as $key) : ?>
<div class="modal fade" id="detailModal<?= $key->id_periksa; ?>" tabindex="-1" role="dialog" aria-labelledby="modalDetailLabel<?= $key->id; ?>" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header d-flex flex-column align-items-center">
        <!-- Nomor Antrian dalam Kotak -->
        <div class="text-center mb-3" style="width: 100%;">
          <button class="btn btn-primary btn-lg" style="font-size: 28px; font-weight: bold; padding: 10px 20px; border-radius: 8px;">
            No. Antrian: <?php echo $key->tgl_periksa;?> 
          </button>
        </div>
        <h5 class="modal-title" id="modalDetailLabel<?= $key->id_periksa; ?>">Detail Riwayat Medis</h5>
        
         
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="position: absolute; top: 10px; right: 15px;">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-6">
            <p><strong>No. RM:</strong> <?= $key->no_rm; ?></p>
            <p><strong>Pasien:</strong> <?= $key->nama; ?></p>
            <p><strong>Jam:</strong> <?= $key->tgl_periksa; ?></p>
            <p><strong>Keluhan:</strong> <?= $key->keluhan; ?></p>
          </div>
          <div class="col-md-6">
            
          <p><strong>Obat:</strong> <?= $key->obat; ?></p>
          <p><strong>Catatan:</strong> <?= $key->catatan; ?></p>
          </div>
        </div>
        <div class="text-center mb-3" style="width: 100%;">
          <button class="btn btn-primary btn-lg" style="font-size: 28px; font-weight: bold; padding: 10px 20px; border-radius: 8px;">
            Biaya: <?php echo $key->biaya_periksa;?> 
          </button>
        </div>
            
        <!-- <p><strong>Status:</strong>
          <span class="badge <?= $key->status === 'Sudah Diperiksa' ? 'badge-success' : 'badge-warning'; ?>">
            <?= $key->status; ?>
          </span>
        </p> -->
        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<?php endforeach; ?>




<script src="<?php echo base_url()?>harta/morsip/assets/js/jquery-3.6.3.min.js"></script>
<script type="text/javascript">
$(document).on('click','#hapus',function(){
        $('#modal_hapus').modal('show');
        var id=$(this).data('id');
        var nama=$(this).data('username');
        var text="Hapus Users : "+nama;
        $("#mp-tex").text(text);
        $("#id_del").val(id);
});
$(document).ready(function(){

});
</script>

<!-- <script src="<?php //echo base_url()?>harta/morsip/assets/bundles/owlcarousel2/dist/owl.carousel.min.js"></script>
<script src="<?php //echo base_url()?>harta/morsip/assets/js/page/owl-carousel.js"></script>
 -->