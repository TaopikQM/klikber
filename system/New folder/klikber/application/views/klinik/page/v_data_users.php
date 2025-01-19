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
            <a href="<?php echo base_url()?>dokter/tambah_jadwal" >
            <button type="button" class="btn btn-info btn-icon icon-left">
                  <i class="fas fa-plus"></i> Tambah
            </button></a>
            <h4>&nbsp|| Data Users  </h4>
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
                    <th class="text-center">USERNAME</th>
                    <th class="text-center">PASSWORD</th>
                    <th class="text-center">ROLE</th>
                    <th class="text-center">STATUS</th>
                    <th class="text-center">ACTION</th>
                    
                  </tr>
                </thead>
                <tbody>
                  <?php
                    // echo "<pre>";
                    // print_r($jadwal);
                    // echo "</pre>";
                  ?>
                  <?php if (isset($users) && !empty($users)): ?>
                    <?php $nomori=1; foreach ($users as $key) { ?>
                        <tr bgcolor="#cccccc">
                            <td  class="text-center"><?php echo $nomori;?></td>
                            
                            <td class="text-center" >
                              <?php 
                                echo $key->username;
                              ?>    
                            </td>
                            <td class="text-center" >
                              <?php 
                                echo $key->password;
                              ?>    
                            </td>
                            <td class="text-center" >
                            <?php echo $key->nama_role; ?>    
                            </td>
                            <!-- <td class="text-center" >
                            <?php echo $key->id_role; ?>    
                            </td> -->
                            <td class="text-center">
                              <button 
                                type="button" 
                                class="btn status-btn btn-sm" 
                                data-id="<?php echo $key->id; ?>" 
                                data-status="<?php echo $key->id_role; ?>" 
                                style="background-color: <?php echo $key->id_role == '1' ? 'green' : 'red'; ?>; color: white;">
                                <?php echo ucfirst($key->id_role); ?>
                              </button>
                            </td>

                            
                            
                            <td class="text-center" >

                              <!-- <a href="<?php $dxc=$this->encryption->encrypt(base64_encode($key->id)); $ff=str_replace(array('+','/','='),array('-','_','~'),$dxc); echo base_url();?>admin/edit_jadwal/<?php echo $ff; ?>">
                              <button type="button" class="btn btn-warning btn-sm" data-toggle="tooltip" title="Edit">
                                <i class="fa fa-pen" aria-hidden="true"></i></button>
                              </button>
                              </a> -->
                              
                              <!-- <button type="button" class="btn btn-primary btn-sm" data-toggle="tooltip" title="Upload Ulang Dokumen">
                                <i  class="fa fa-upload" aria-hidden="true"></i>
                              </button> -->
                              <!-- <button type="button" class="btn btn-danger btn-sm" data-toggle="tooltip" title="Hapus" id="hapus" data-id="<?php echo $key->id;?>" data-hari="<?php echo $key->hari;?>">
                                <i  class="fa fa-trash" aria-hidden="true"></i>
                              </button>
                              <button type="button" class="btn btn-danger btn-sm" data-toggle="tooltip" title="Status" id="status" data-id="<?php echo $key->id;?>" data-status="<?php echo $key->status;?>">
                                <i  class="fa fa-trash" aria-hidden="true"></i>
                              </button> -->
                              <button type="button" class="btn btn-warning btn-sm" data-toggle="tooltip" title="Ubah Status" id="status" data-id="<?php echo $key->id;?>" data-status="<?php echo $key->id_role;?>">
                                <i class="fa fa-edit" aria-hidden="true"></i>
                              </button>
                              
                              
                            </td>
                        </tr>
                    <?php $nomori++;
                      }
                    ?>
                  <?php else: ?>
                        <tr>
                            <td colspan="4" class="text-center">Tidak ada data Jadwal.</td>
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
         <?php echo form_open_multipart('users/delete_log');?>
            <input type="hidden" name="id" value="" id="id_del">
            <button type="submit" class="btn btn-danger">Hapus</button>
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>  
        </form>
        
      </div>
    </div>
  </div>
</div>
<!-- Modal untuk Ubah Status -->
<div class="modal fade" id="modal_status" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Yakin Ubah Status?</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p id="mp-tex"></p>
      </div>
      <div class="modal-footer bg-whitesmoke br">
         <?php echo form_open_multipart('dokter/update_status');?>
            <input type="hidden" name="id" value="" id="id_del">
            <input type="hidden" name="status" value="" id="status_input">
            <button type="submit" class="btn btn-danger">Ubah</button>
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
        var nama=$(this).data('hari');
        var text="Hapus Jadwal : "+nama;
        $("#mp-tex").text(text);
        $("#id_del").val(id);
});
$(document).on('click', '#status', function(){
    $('#modal_status').modal('show');
    var id = $(this).data('id');
    var status = $(this).data('status');
    var text = "Ubah Status Jadwal : " + (status === 'active' ? 'Inactive' : 'Active');
    
    $("#mp-tex").text(text);
    $("#id_del").val(id);
    $("#status_input").val(status === 'active' ? 'inactive' : 'active');
});

$('#modal_status form').submit(function(e){
    e.preventDefault();
    
    var id = $('#id_del').val();
    var newStatus = $('#status_input').val();
    var currentTime = new Date().toISOString();
    
    $.ajax({
        url: '<?php echo base_url();?>dokter/update_status',
        type: 'POST',
        data: {
            id: id,
            status: newStatus,
            updated_at: currentTime
        },
        success: function(response) {
            // Tidak memerlukan respon sukses
            $('#modal_status').modal('hide');
            
            // Update status di tabel langsung tanpa reload halaman
            var newStatusText = newStatus === 'active' ? 'Active' : 'Inactive';
            var statusButton = $('#status[data-id="' + id + '"]');
            statusButton.attr('data-status', newStatus);
            statusButton.html('<i class="fa fa-edit" aria-hidden="true"></i> ');
            
            // Anda bisa menambahkan kode untuk mengupdate kolom waktu di tabel jika diperlukan
            var waktuCell = statusButton.closest('tr').find('.waktu-ubah-status');
            waktuCell.text(currentTime); // Update waktu yang diubah di tabel
            // Refresh halaman tanpa reload
            location.reload();
        },
        error: function(xhr, status, error) {
            // Tidak menggunakan alert lagi
            var msg = 'Gagal mengupdate status';
            $('#modal_status').modal('hide');
            // Refresh halaman tanpa reload
            location.reload();
            // Jika menggunakan `session`, atur flashdata seperti ini:
            // $this->session->set_flashdata('notif', array('tipe' => 3, 'isi' => msg));
            // Kemudian pada halaman menampilkan notifikasi menggunakan `session->flashdata()`
        }
    });
});
$(document).ready(function(){

});

</script>

<script src="<?php //echo base_url()?>harta/morsip/assets/bundles/owlcarousel2/dist/owl.carousel.min.js"></script>
<script src="<?php //echo base_url()?>harta/morsip/assets/js/page/owl-carousel.js"></script>
