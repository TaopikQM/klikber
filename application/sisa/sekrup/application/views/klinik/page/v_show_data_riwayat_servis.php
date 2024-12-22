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
            <a class="btn btn-info" href="<?php echo base_url('riwayatservis'); ?>">
            <i class="fas fa-arrow-left"></i> Kembali
            </a>
          </div>
          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-striped" id="table-1">
                <thead class="text-center">
                  <tr>
                    <th>Tahun Rakitan</th>
                    <th>Akhir Pajak</th>
                    <th>Akhir STNK</th>
                  </tr>
                </thead>
                <tbody class="text-center ">
                      <tr>
                        <td><?php echo isset($mobil->th_rakit) ? $mobil->th_rakit : 'No Data'; ?></td>
                        <td><?php echo isset($mobil->pjk) ? $mobil->pjk : 'No Data'; ?></td>
                        <td>
                        <?php
                        if (isset($mobil->thbeli)) {
                            // Mengonversi tahun pembelian menjadi format tanggal
                            $tanggal_pembelian = strtotime($mobil->thbeli . '-01-01'); // Menganggap tanggal 1 Januari sebagai tanggal default

                            // Menghitung berapa kali 5 tahun telah berlalu sejak tanggal pembelian
                            $current_year = date('Y');
                            $tahun_akhir_stnk = $mobil->thbeli + (floor(($current_year - $mobil->thbeli) / 5) * 5) + 5;

                            // Menampilkan tahun akhir STNK
                            echo $tahun_akhir_stnk;
                        } else {
                            echo 'No Data';
                        }
                        ?>


                        </td>
              
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <div class="card">
          <div class="card-header d-flex justify-content-end align-items-right">
            <a class="btn btn-primary" href="<?php echo base_url('riwayatservis/tambah/' . $idmobil); ?>">
              <i class="fas fa-plus-circle"></i> Tambah Data
            </a>
          </div>
          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-striped" id="table-1">
                <thead class="text-center ">
                  <tr>
                    <th>No</th>
                    <th>Kilometer</th>
                    <th>Nama Pemegang</th>
                    <th>Tanggal Servis</th>
                    <th>Keterangan Servis</th>
                    <th>Aksi</th>
                  </tr>
                </thead>
                <tbody class="text-center">
                  <?php if (!empty($riwayat_servis) && (is_array($riwayat_servis) || is_object($riwayat_servis))): ?>
                    <?php $no = 1; foreach ($riwayat_servis as $key): ?>
                      <tr>
                        <td class="text-center"><?php echo $no++; ?></td>
                        <td><?php echo isset($key->kilometer) ? $key->kilometer : 'No Data'; ?></td>
                        <td><?php echo isset($key->nm_pemegang) ? $key->nm_pemegang : 'No Data'; ?></td>
                        <td><?php echo isset($key->blnket) ? $key->blnket : 'No Data'; ?></td>
                        <td><?php echo isset($key->ket) ? $key->ket : 'No Data'; ?></td>
                        <td>
                  
                          <a href="<?php echo base_url('riwayatservis/edit/' . $key->id); ?>" class="btn btn-icon icon-left btn-primary"><i class="fas fa-edit"></i> Edit</a>
                          <a href="<?php echo base_url('riwayatservis/delete/' . $key->id); ?>" class="btn btn-icon icon-left btn-danger" onclick="return confirm('Anda yakin ingin menghapus data ini?')"><i class="fas fa-trash"></i> Hapus</a>
                            <a href="<?php $data=$key->id; echo base_url('riwayatservis/cetak_surat_pengantar/'. $key->id);?>" target="_blank">
                            <button type="button" class="btn btn-info" data-toggle="tooltip" title="Print Surat Pengantar">
                              <i class="fa fa-print" aria-hidden="true" ></i>Print
                            </button></a>
                            <?php 
                            ?>
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
</section>

<script src="<?php echo base_url()?>harta/morsip/assets/bundles/datatables/datatables.min.js"></script>
<script src="<?php echo base_url()?>harta/morsip/assets/bundles/datatables/DataTables-1.10.16/js/dataTables.bootstrap4.min.js"></script>
<script src="<?php echo base_url()?>harta/morsip/assets/bundles/jquery-ui/jquery-ui.min.js"></script>
<script src="<?php echo base_url()?>harta/morsip/assets/js/page/datatables.js"></script>
