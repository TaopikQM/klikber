
<div class="main-sidebar sidebar-style-2">
    <aside id="sidebar-wrapper">
        <div class="sidebar-brand">
            <a href="#"> <img alt="image" src="<?php echo base_url()?>harta/klikber/assets/img/klikber.png" class="header-logo" /> <span class="logo-name">KLIKBER</span></a>
        </div>
        <ul class="sidebar-menu">
            <li class="menu-header">Menu Aplikasi</li>
            
            <!-- Jika Role adalah 'admin' -->
            <?php if ($this->session->userdata('role') == 'Admin'): ?>
            <li class="dropdown">
                <a href="<?php echo base_url('admin')?>" class="nav-link"><i data-feather="monitor"></i><span>Dashboard</span></a>
            </li>
            <!-- <li class="dropdown">
                <a href="#" class="menu-toggle nav-link has-dropdown"><i data-feather="mail"></i><span>Peminjaman</span></a>
                <ul class="dropdown-menu">
                    <li><a class="nav-link" href="<?php echo base_url('#')?>">Input Peminjaman</a></li>
                    <li><a class="nav-link" href="<?php echo base_url('pinjam/mobil')?>">Data Peminjaman Mobil</a></li>
                    <li><a class="nav-link" href="<?php echo base_url('pinjam/ruangan')?>">Data Peminjaman Ruangan</a></li>
                    <li><a class="nav-link" href="<?php echo base_url('pinjam/alat')?>">Data Peminjaman Alat</a></li>
                </ul>
            </li> -->
            <li class="dropdown">
                <a href="#" class="menu-toggle nav-link has-dropdown"><i data-feather="feather"></i><span>Data</span></a>
                <ul class="dropdown-menu">
                    <li><a href="<?php echo base_url('dokter/tab')?>">Dokter</a></li>
                    <li><a href="<?php echo base_url('pasien/tab')?>">Pasien</a></li>
                    <li><a href="<?php echo base_url('admin/tab')?>">Admin</a></li>
                    <li><a href="<?php echo base_url('poli')?>">Poli</a></li>
                    <li><a href="<?php echo base_url('obat')?>">Obat</a></li>
                    <li><a href="<?php echo base_url('role')?>">Role</a></li>
                    <li><a href="<?php echo base_url('users/log')?>">log Users</a></li>
                    <li><a href="<?php echo base_url('users/tab')?>">Users</a></li>
                </ul>
            </li>

            <?php elseif ($this->session->userdata('role') == 'Dokter'): ?>
            <!-- Jika Role adalah 'dokter' -->
            <li class="dropdown">
                <a href="<?php echo base_url('landing/menu')?>" class="nav-link"><i data-feather="monitor"></i><span>Dashboard</span></a>
            </li>
            <!-- <li class="dropdown">
                <a href="#" class="menu-toggle nav-link has-dropdown"><i data-feather="mail"></i><span>Peminjaman</span></a>
                <ul class="dropdown-menu">
                    <li><a class="nav-link" href="<?php echo base_url('#')?>">Input Peminjaman</a></li>
                    <li><a class="nav-link" href="<?php echo base_url('pinjam/mobil')?>">Data Peminjaman Mobil</a></li>
                    <li><a class="nav-link" href="<?php echo base_url('pinjam/ruangan')?>">Data Peminjaman Ruangan</a></li>
                </ul>
            </li> -->
            <li class="dropdown">
                <a href="#" class="menu-toggle nav-link has-dropdown"><i data-feather="feather"></i><span>Data</span></a>
                <ul class="dropdown-menu">
                    <li><a href="<?php echo base_url('dokter/jadwal')?>">Jadwal</a></li>
                    <li><a href="<?php echo base_url('dokter/daftar_pasien')?>">Daftar Pasien</a></li>
                    <li><a href="<?php echo base_url('dokter/riwayat_pasien')?>">Riwayat Pasien</a></li>
                    <li><a href="<?php echo base_url('dokter/konsultasi')?>">Konsultasi</a></li>
                   
                    <!-- <li><a href="<?php echo base_url('dokter')?>">Dokter</a></li>
                    <li><a href="<?php echo base_url('admin')?>">Admin</a></li>
                    <li><a href="<?php echo base_url('poli')?>">Poli</a></li>
                    <li><a href="<?php echo base_url('obat')?>">Obat</a></li> -->
                </ul>
            </li>
            <?php elseif ($this->session->userdata('role') == 'Pasien'): ?>
            <!-- Jika Role adalah 'pasien' -->
            <li class="dropdown">
                <a href="<?php echo base_url('landing/menu')?>" class="nav-link"><i data-feather="monitor"></i><span>Dashboard</span></a>
            </li>
            <!-- <li class="dropdown">
                <a href="#" class="menu-toggle nav-link has-dropdown"><i data-feather="mail"></i><span>Peminjaman</span></a>
                <ul class="dropdown-menu">
                    <li><a class="nav-link" href="<?php echo base_url('pinjam/ruangan')?>">Data Peminjaman Ruangan</a></li>
                </ul>
            </li> -->
            <li class="dropdown">
                <a href="#" class="menu-toggle nav-link has-dropdown"><i data-feather="feather"></i><span>Data</span></a>
                <ul class="dropdown-menu">
                    <li><a href="<?php echo base_url('pasien/riwayat')?>">Data Daftar Poli</a></li>
                    
                    <li><a href="<?php echo base_url('pasien/riwayat_pasien')?>">Riwayat Periksa</a></li>
                    <li><a href="<?php echo base_url('pasien/konsultasi')?>">Konsultasi</a></li>
                    <!-- <li><a href="<?php echo base_url('pasien')?>">Pasien</a></li> -->
                    <!-- <li><a href="<?php echo base_url('poli')?>">Poli</a></li>
                    <li><a href="<?php echo base_url('obat')?>">Obat</a></li> -->
                </ul>
            </li>
            <?php endif; ?>
        </ul>
    </aside>
</div>
