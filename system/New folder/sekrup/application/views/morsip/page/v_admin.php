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
            <h4>Data Admin</h4>
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
                
                $tahun=$this->session->userdata('tahun'); ?>
                <!-- </ul> -->
          </div>
          <div class="card-body">
            
            <div class="table-responsive">

              <table class="table table-striped" id="table-1">
                <thead>
                  <tr>
                    <th class="text-center">NO</th>
                    <th class="text-center">Username</th>
                    <th class="text-center">Pemilik</th>
                    <th class="text-center">Last Login</th>
                    <th class="text-center">Hak</th>
                    <th class="text-center">Status</th>
                    <th class="text-center">Action</th>
                  </tr>
                </thead>
                <tbody>
                  <?php $i=1; foreach ($datasur as $key) { ?>
                      <tr>
                          <td class="text-center"><?php echo $i;?></td>
                          <td  class="text-center"><?php echo $key->username;?></td>
                          <td  class="text-center"><?php echo $key->bidang;?></td>
                          <td  class="text-center"><?php echo $key->lastlog."<br>".$key->pi;?></td>
                          <td  class="text-center">
                            <?php hk_admin($key->id);?>
                          </td>
                          <td class="text-center" >
                            <?php 
                                if ($key->st==1) {
                                  $hxz="info";
                                  $hxza="Akif";
                                }
                                else{
                                  $hxz="danger";
                                  $hxza="Tidak Akif"; 
                                }
                            $key->st;?>
                            <button type="button" class="btn btn-<?php echo $hxz;?>" data-toggle="tooltip" data-id="<?php echo $key->id?>" data-status="<?php echo $key->st?>" title="Status" id="status">
                                    <?php echo $hxza;?>
                              </button>
                          </td>
                          <td  class="text-center">
                              <?php 
                                $data=$this->encryption->encrypt(base64_encode($key->id)); 
                                $ff=str_replace(array('+','/','='),array('-','_','~'),$data);
                              ?>
                              <a href="<?php echo base_url();?>morsip/edit_admin/<?php echo $ff;?>">
                              <button type="button" class="btn btn-warning btn-sm" data-toggle="tooltip" title="Edit Admin">
                                <i class="fa fa-pen" aria-hidden="true"></i>
                              </button>
                              </a>
                              <a href="<?php echo base_url();?>morsip/hapus_admin/<?php echo $ff;?>">
                                <button type="button" class="btn btn-danger btn-sm" data-toggle="tooltip" title="Hapus Admin" id="hapus" >
                                <i  class="fa fa-trash" aria-hidden="true"></i>
                                </button>
                              </a>
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
<div class="modal fade" id="modal_hak" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
          aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Hak Admin</h5>
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
        <h5 class="modal-title" id="exampleModalLabel">Notifikasi</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <center>
                <h2>Status Admin Diubah</h2>  
        </center>
      </div>
      <div class="modal-footer bg-whitesmoke br">
             
      </div>
    </div>
  </div>
</div>
<script src="<?php echo base_url()?>harta/morsip/assets/js/jquery-3.6.3.min.js"></script>
<script type="text/javascript">
/*$(document).on('click','#lihathk',function(){
        //$('#modal_hak').modal('show');
        var id=$(this).data('hak');
        $.ajax({
                url : "<?php //echo base_url();?>morsip/ambil_hak_admin",
                method : "POST",
                data : {id: id},
                dataType: 'json',
                async : false,
                success: function(data){
                        $('#modal_hak').modal('show');
                        $("#mp-tex").text(data);                   
                }
            });       
});*/
$(document).on('click','#status',function(){
            
            var id = $(this).data('id');
            var st = $(this).data('status');
            
            $.ajax({
               type: 'POST',
               url: "<?php echo base_url();?>morsip/chgstatusadm",
               data: {
                id: id, status: st
               },
            success: function(dat) {
                
                $('#modal_status').modal('show');
                window.setTimeout(function(){window.location.reload()}, 1500);
                }
            })
        });

</script>