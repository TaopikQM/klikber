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
              <h4>&nbsp || &nbsp</h4>
              <a href="<?php echo base_url();?>morsip/input_jabatan">
              <button type="button" class="btn btn-warning btn-md" data-toggle="tooltip" title="Tambah Jabatan"><i class="fa fa-pen" aria-hidden="true"> Tambah</i></button></a>
          </div>
          <div class="card-body">

            <div class="table-responsive">

              <table class="table table-striped" id="table-1" width="100%">
                <thead>
                  <tr>
                    <th class="text-center">NO</th>
                    <th class="text-center">NAMA JABATAN</th>
                    <th class="text-center">EDIT</th>
                    <th class="text-center">HAPUS</th>
                    
                  </tr>
                </thead>
                <tbody>
                  <?php $ia=1; foreach ($datasur as $key) { ?>
                      <tr>
                          <td ><?php echo $ia;?></td>
                          <td ><?php echo $key->nama_jabatan;?></td>
                         
                          <td class="text-center" >
                              <a href="<?php   
                                $data=$this->encryption->encrypt(base64_encode($key->id)); $ff=str_replace(array('+','/','='),array('-','_','~'),$data); echo base_url();?>morsip/edit_jabatan/<?php echo $ff; ?>">
                              <button type="button" class="btn btn-warning btn-md" data-toggle="tooltip" title="Edit SPT"><i class="fa fa-pen" aria-hidden="true"></i></button></a>
                          </td>
                          <td class="text-center" >
                              <button type="button" class="btn btn-danger btn-md" data-toggle="tooltip" title="Hapus SPT" id="hapus" data-id="<?php echo $key->id;?>" data-jabatan="<?php echo $key->nama_jabatan;?>" >
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
         <?php echo form_open_multipart('morsip/hapus_jabatan');?>
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
        var jab=$(this).data('jabatan');
        var text="Hapus Jabatan"+jab;
        $("#mp-tex").text(text);
        $("#id_del").val(id);
});
</script>