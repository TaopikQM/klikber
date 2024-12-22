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
          <div class="card-header d-flex justify-content-end align-items-right">
            <a class="btn btn-info mr-2" href="<?php echo base_url('riwayatservisruang'); ?>">
            <i class="fas fa-arrow-left"></i> Kembali
            </a>

            <a class="btn btn-primary" href="<?php echo base_url('riwayatservisruang/tambah/' . $id_ruang); ?>">
              <i class="fas fa-plus-circle"></i> Tambah Data
            </a>
          </div>
          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-striped" id="table-1">
                <thead class="text-center ">
                  <tr>
                    <th>No</th>
                    <th>Tanggal Servis</th>
                    <th>Keterangan Servis</th>
                    <th>Aksi</th>
                  </tr>
                </thead>
                <tbody class="text-center">
                  <?php if (!empty($rs_ruang) && (is_array($rs_ruang) || is_object($rs_ruang))): ?>
                    <?php $no = 1; foreach ($rs_ruang as $key): ?>
                      <tr>
                        <td class="text-center"><?php echo $no++; ?></td>
                        <td><?php echo isset($key->tgl_servis) ? $key->tgl_servis : 'No Data'; ?></td>
                        <td><?php echo isset($key->ket) ? $key->ket : 'No Data'; ?></td>
                        <td>
                          <a href="<?php echo base_url('riwayatservisruang/edit/' . $key->id); ?>" class="btn btn-icon icon-left btn-primary"><i class="fas fa-edit"></i> Edit</a>
                          <a href="<?php echo base_url('riwayatservisruang/delete/' . $key->id); ?>" class="btn btn-icon icon-left btn-danger" onclick="return confirm('Anda yakin ingin menghapus data ini?')"><i class="fas fa-trash"></i> Hapus</a>
                        </td>
                      </tr>
                    <?php endforeach;?>
                  <?php else: ?>
                    <tr>
                      <td colspan="8" class="text-center">Data tidak ditemukan.</td>
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
  </div>
</section>

<script src="<?php echo base_url()?>harta/morsip/assets/bundles/datatables/datatables.min.js"></script>
<script src="<?php echo base_url()?>harta/morsip/assets/bundles/datatables/DataTables-1.10.16/js/dataTables.bootstrap4.min.js"></script>
<script src="<?php echo base_url()?>harta/morsip/assets/bundles/jquery-ui/jquery-ui.min.js"></script>
<script src="<?php echo base_url()?>harta/morsip/assets/js/page/datatables.js"></script>
