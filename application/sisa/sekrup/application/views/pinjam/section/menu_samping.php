<div class="main-sidebar sidebar-style-2">
        <aside id="sidebar-wrapper">
          <div class="sidebar-brand">
            <a href="<?php echo base_url('pinjam')?>"> <img alt="image" src="<?php echo base_url()?>harta/pinjam/assets/img/ePinjam.png" class="header-logo" /> <span
                class="logo-name">poliklinik</span>
            </a>
          </div>
          <ul class="sidebar-menu">
            <li class="menu-header">Menu Aplikasi</li>
            <li class="dropdown">
              <a href="<?php echo base_url('pinjam')?>" class="nav-link"><i data-feather="monitor"></i><span>Dashboard</span></a>
            </li>
            <li class="dropdown">
              <a href="#" class="menu-toggle nav-link has-dropdown"><i
                  data-feather="mail"></i><span>Peminjaman</span></a>
              <ul class="dropdown-menu">
                <li><a class="nav-link" href="<?php echo base_url('pinjam/input')?>">Input Peminjaman</a></li>
                <li><a class="nav-link" href="<?php echo base_url('pinjam/mobil')?>">Data Peminjaman Mobil</a></li>
                <li><a class="nav-link" href="<?php echo base_url('pinjam/ruangan')?>">Data Peminjaman Ruangan</a></li>
                <li><a class="nav-link" href="<?php echo base_url('pinjam/alat')?>">Data Peminjaman Alat</a></li>
                
              </ul>
            </li>
            <li class="dropdown">
              <a href="#" class="menu-toggle nav-link has-dropdown"><i
                  data-feather="feather"></i><span>Data </span></a>
              <ul class="dropdown-menu">
              <li><a href="<?php echo base_url('dokter')?>">Dokter</a></li>
                <li><a href="<?php echo base_url('pasien')?>">Pasien</a></li>
                <li><a href="<?php echo base_url('alat')?>">bb</a></li>
              </ul>
            </li>

            <li class="dropdown">
              <a href="#" class="menu-toggle nav-link has-dropdown"><i
                  data-feather="settings"></i><span>Riwayat Servis</span></a>
              <ul class="dropdown-menu">
                <li><a class="nav-link" href="<?php echo base_url('riwayatservis')?>">Mobil</a></li>
                <li><a class="nav-link" href="<?php echo base_url('riwayatservisruang')?>">Ruangan</a></li>
                <li><a class="nav-link" href="<?php echo base_url('riwayatservisalat')?>">Alat</a></li>
              </ul>
            </li>

          </ul>
        </aside>
      </div>