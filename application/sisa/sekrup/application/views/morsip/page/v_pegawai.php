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
            <h4>Data Pegawai || </h4>
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
                    <th class="text-center">NIP</th>
                    <th class="text-center">NAMA</th>
                    <th class="text-center">JABATAN</th>
                    <th class="text-center">PANGKAT/GOL</th>
                    <th class="text-center">STATUS</th>
                    <th class="text-center">ACTION</th>
                    
                  </tr>
                </thead>
                <tbody>
                  <?php $i=1; foreach ($datasur as $key) { ?>
                      <tr>
                        <td ><?php echo $i;?></td>
                        <td width="200px"><?php echo $this->encryption->decrypt( $key->nip);?></td>
                        <td  ><?php echo base64_decode($key->nama);?></td>
                        <td  ><?php echo $key->nama_jabatan;?></td>
                        <td  ><?php echo $key->golru;?></td>
	                    <td class="text-center">
	                      	<?php
	                      		if ($key->status == 1) {
									$hy="btn-info";
									$tg="AKTIF";                          			
	                      		}else{
	                      			$hy="btn-danger";
									$tg="NON AKTIF";
	                      		}
	                      		$fg=$key->status;?>
	                      	<button type="button" class="btn <?php echo $hy;?> btn-sm" data-toggle="tooltip" title="Satatus Kepegawaian"><?php echo $tg;?></button>
	                    </td>
                          <!-- <td  class="text-center"> -->
                        <td>
                        	<?php
	                      		if ($key->status == 1) { ?>
									<button type="button" class="btn btn-danger btn-md" data-toggle="tooltip" title="Non Aktifkan Pegawai" id="hapus" data-nama="<?php echo base64_decode($key->nama);?>" data-id="<?php echo $key->id;?>" data-status="<?php echo $key->status;?>">
                              			<i  class="fa fa-trash" aria-hidden="true"></i>
                              		</button>                          			
	                      	<?php
	                      		}else{ ?>
	                      			<button type="button" class="btn btn-info btn-md" data-toggle="tooltip" title="Aktifkan Pegawai" id="hapus" data-nama="<?php echo base64_decode($key->nama);?>" data-id="<?php echo $key->id;?>" data-status="<?php echo $key->status;?>">
                              			<i  class="fa fa-heart" aria-hidden="true"></i>
                              		</button>
	                      	<?php
	                      		} ?>
	                      		<a href="<?php   
                                $data=$this->encryption->encrypt(base64_encode($key->id)); $ff=str_replace(array('+','/','='),array('-','_','~'),$data); echo base_url();?>morsip/edit_pegawai/<?php echo $ff; ?>">
                              <button type="button" class="btn btn-warning btn-md" data-toggle="tooltip" title="Edit Pegawai"><i class="fa fa-pen" aria-hidden="true"></i></button></a>


                          
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
         <?php echo form_open_multipart('morsip/hapus_pegawai');?>
            <input type="hidden" name="id" value="" id="id_del">
            <input type="hidden" name="status" value="" id="id_status">
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
        var status=$(this).data('status');
        var nama=$(this).data('nama');
        var text="Rubah Status Pegawai : "+nama;
        $("#mp-tex").text(text);
        $("#id_del").val(id);
        $("#id_status").val(status);
});

</script>