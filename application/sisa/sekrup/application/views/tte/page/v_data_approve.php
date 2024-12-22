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
            <h4>Data Ajuan SPT || </h4>
            <!-- <ul class="pagination"> -->
              <?php
                $id=base64_decode( $this->session->userdata(base64_encode('kod')) );
                if ($id != $kode) {
                  redirect('landing/tte');
                }
                
                $tahun=$this->session->userdata('tahun');
                $fg=$jumdat/1000;$i=1;
                while ($i <=ceil($fg)) {?>
                  <div class="page-item"><a class="page-link" href="<?php echo base_url();?>morsip/dataspt/<?php echo$i;?>"><?php echo"$i";?></a></div>
                <?php $i++; }
              ?><!-- </ul> -->

              <button data-toggle="modal" data-target="#konfrim" type="button" id="checSem" class="btn mb-1 btn-rounded btn-outline-warning" data-hr="<?php echo base64_decode( $this->session->userdata(base64_encode('kod')) );?>">Approve All</button>
          </div>
          <div class="card-body">
            <?php 
             /* echo "<pre>";
              print_r($datasur);
              echo "</pre>";*/
              echo "<pre>";
              print_r($this->session->userdata(base64_encode('kod')));
              echo "</pre>";
            ?>
            <div class="table-responsive">

              <table class="table table-striped" id="table-1" width="100%">
                <thead>
                  <tr>
                    <th class="text-center"><input type="checkbox" id="checSemua" class="cka"></th>
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

                  <?php 
                  $ia=1; foreach ($datasur as $key) { ?>
                      <tr>
                          <td >
                            <input type="checkbox" class="ck" id="checSemua" value="<?php echo $key->id;?>" data-kdbid="<?php echo $key->kdbid;?>" data-nos="<?php echo $key->nosur;?>" >
                          </td>
                          <td ><?php echo $ia;?></td>
                          <td ><?php echo $key->bidang;?></td>
                          <td class="text-center">
                              <?php echo "094/".$key->nosur;
                                $data=$this->encryption->encrypt(base64_encode($key->id)); 
                                $ff=str_replace(array('+','/','='),array('-','_','~'),$data);
                              ?>
                              <br>
                              <a href="<?php echo base_url();?>tte/preview_spt/<?php echo $ff; ?>" target="_blank"><button type="button" class="btn btn-warning btn-md" data-toggle="tooltip" title="Preview Dokumen"><i class="fa fa-print" aria-hidden="true" ></i></button></a>
                          </td>
                          <td  ><?php echo date("d-m-Y",$key->tgldl);?></td>
                          <td >
                            <?php
                              $sx=explode("-",trim($key->nmdl,"-"));
                              for ($i=0; $i <count($sx) ; $i++) { 
                                echo "* ".  namaSPT(base64_decode($sx[$i]),$tahun)."<br>";
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
                              <button type="button" class="btn btn-<?php echo $voba[$k1]; ?> btn-md">Kasi</button>
                              <button type="button" class="btn btn-<?php echo $voba[$k2]; ?> btn-md">Ka Bidang</button>
                              <button type="button" class="btn btn-<?php echo $voba[$k3]; ?> btn-md">Sekretaris</button>
                              <button type="button" class="btn btn-<?php echo $voba[$k4]; ?> btn-md">Ka Dinas</button>
                              <button type="button" class="btn btn-<?php echo $voba[$k5]; ?> btn-md">ACC</button>
                            </div>
                          </td>
                          <td class="text-center" >
                              <button data-toggle="modal" data-target="#konfrim" type="button" class="btn btn-primary btn-md singsetuju" data-toggle="tooltip" title="ACC SPT" data-kdbid="<?php echo $key->kdbid;?>" data-nos="<?php echo $key->nosur;?>" data-id="<?php echo $key->id;?>">Setujui</button>
                              <br><br>
                              <button type="button" class="btn btn-danger btn-md" data-toggle="tooltip" title="Tolak SPT" id="hapus" data-nosur="<?php echo $key->nosur;?>" data-id="<?php echo $key->id;?>" data-kdbid="<?php echo $key->kdbid;?>" data-hal="<?php echo base64_decode($key->keperluan);?>" >Tolak
                              </button>
                          
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
<div class="modal fade" id="modal_hapus" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Yakin Tolak?</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <?php echo form_open_multipart('tte/tolak_spt');?>
      <div class="modal-body">
        <p id="mp-tex"></p>
         <textarea name="altolak" placeholder="Masukan Alasan Penolakan"  rows="4" cols="50"></textarea>
      </div>
      <div class="modal-footer bg-whitesmoke br">
            <input type="hidden" name="id" value="" id="id_del">
            <input type="hidden" name="nosur" value="" id="id_nosur">
            <input type="hidden" name="kdbid" value="" id="kdbid">
           
            <button type="submit" class="btn btn-danger">Tolak</button>
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>          
      </div>
      </form>
    </div>
  </div>
</div>

<div class="modal fade" id="konfrim" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Konfirmasi Persetujuan</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <?php $arrayName = array('class' => "form-inline komfrim", 'name'=> "confirm", 'method'=> "POST" );
            echo form_open_multipart('tte/aprovespt', $arrayName);
      ?>
      <div class="modal-body">
        <p id="mp-tex"></p>
         <input type="password" name="passs" class="form-control" placeholder="Password" required="required">
      </div>
      <div class="modal-footer bg-whitesmoke br">
            
            <input type="hidden" name="nosur" value="" id="nosur">
            <input type="hidden" name="kdbid" value="" id="kdbid">
            <input type="hidden" name="ckall" value="" id="ckall">

            <button type="submit" class="btn btn-primary">Setujui</button>
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>          
      </div>
      </form>
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
        var kdbid=$(this).data('kdbid');
        var text="Hapus Nomor Surat : 094/"+no+" - "+hal;
        $("#mp-tex").text(text);
        $("#id_del").val(id);
        $("#id_nosur").val(no);
        $("#kdbid").val(kdbid);
});
$(document).ready(function(){
     $(".cka").click(function(){
        $('input:checkbox').not(this).prop('checked', this.checked);
    });

     $('#checSem').hide();
    $(document).on('click','#checSemua', function(){
        var array = [];

        $("input:checked.ck").each(function() {  
            array.push($(this).attr("value")); 
        }); 
        if (array.length>0) {
            $('#checSem').show();
        }else{
            $('#checSem').hide();
        }
    });

    $(document).on('click','#checSem', function(){
        var tok="1";
        var ckli = []; 
        var kdb = []; 
        var nosr = []; 
            $("input:checked.ck").each(function() { 
                //array.push($(this).attr("value")); 
                ckli.push($(this).attr("value"));
               
                kdb.push($(this).data("kdbid"));
                nosr.push($(this).data("nos"));

            }); 
        if (ckli.length>0) {
            var ida = $(this).data('hr');
                if (ida == 1) {
                    $(".modal-title #jud").text("Masukan Passphrese Penerbitan TTE");
                }
            $(".modal-footer #ckall").val(ckli);
            $(".modal-footer #kdbid").val(kdb);
            $(".modal-footer #nosur").val(nosr);
            
        }else{
            $(".modal-footer #ckall").val(NULL);
            $(".modal-footer #kdbid").val(NULL);
            $(".modal-footer #nosur").val(NULL);
            //alert("Tidak Ada Data Terpilih."); 
        }
    });
    $(document).on('click','.singsetuju', function(){
        var ckli = $(this).data("id"); 
        var kdb = $(this).data("kdbid"); 
        var nosr = $(this).data("nos"); 
        $(".modal-footer #ckall").val(ckli);
        $(".modal-footer #kdbid").val(kdb);
        $(".modal-footer #nosur").val(nosr);
            
        
    });


    /*$(document).on('click','.btn_submit', function(){
        var data = $('.komfrim').serialize();
        
        $.ajax({
            type: 'POST',
            url: "<?php //echo base_url()?>tte/aprovespt",
            data: data,
            success: function(msg) {
                //$("#tamp").html(msg);
                //location.reload();
                //$(".modal").fadeOut();
                //$(".dtabel").load("page/tabel_data.php");
            }
        });
    });*/



});
</script>