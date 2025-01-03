<link rel="stylesheet" href="<?php echo base_url()?>harta/morsip/assets/bundles/datatables/datatables.min.css">
<link rel="stylesheet" href="<?php echo base_url()?>harta/morsip/assets/bundles/datatables/DataTables-1.10.16/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="<?php echo base_url()?>harta/morsip/assets/bundles/prism/prism.css">

<section class="section">
  <?php if($this->session->flashdata('notif') != NULL): ?>
    <div class="alert <?php echo $this->session->flashdata('notif')['tipe'] == 1 ? 'alert-primary' : ($this->session->flashdata('notif')['tipe'] == 2 ? 'alert-warning' : 'alert-danger'); ?> alert-dismissible show fade">
      <div class="alert-body">
        <button class="close" data-dismiss="alert">
          <span>&times;</span>
        </button>
        <?php echo $this->session->flashdata('notif')['isi'];?>
      </div>
    </div>
  <?php endif; ?>

  <div class="section-body">
    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-striped table-hover" id="table-1" style="width:100%;">
                <thead>
                  <tr bgcolor="#cccccc">
                    <th class="text-center">NO</th>
                    <th class="text-center">GAMBAR</th>
                    <th class="text-center">NAMA ALAT</th>
                    <th class="text-center">KETERANGAN</th>
                    <th class="text-center">ACTION</th>
                  </tr>
                </thead>
                <tbody>
                <?php $nomori=1; foreach ($dataalat as $key) : ?>
                      <tr>
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
                          </td> </a>
                          <td class="text-center"><?php echo $key->ket;?></td>
                          <td class="text-center">
                        <a href="<?php echo base_url();?>riwayatservisalat/show/<?php echo $key->id; ?>">
                          <button type="button" class="btn btn-info btn-sm" data-toggle="tooltip" title="Lihat Detail">
                            <i class="fa fa-eye" aria-hidden="true"></i>
                          </button>
                        </a>
                      </td>
                      </tr>
                  <?php $nomori++; endforeach; ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<script src="<?php echo base_url()?>harta/morsip/assets/bundles/datatables/datatables.min.js"></script>
<script src="<?php echo base_url()?>harta/morsip/assets/bundles/datatables/DataTables-1.10.16/js/dataTables.bootstrap4.min.js"></script>
<script src="<?php echo base_url()?>harta/morsip/assets/bundles/jquery-ui/jquery-ui.min.js"></script>
<script src="<?php echo base_url()?>harta/morsip/assets/js/page/datatables.js"></script>
