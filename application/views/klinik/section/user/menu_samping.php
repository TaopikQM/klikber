<div class="main-sidebar sidebar-style-2">
        <aside id="sidebar-wrapper">
          <div class="sidebar-brand">
            <a href="<?php echo base_url('pinjamuser')?>"> <img alt="image" src="<?php echo base_url()?>harta/pinjam/assets/img/ePinjam.png" class="header-logo" /> <span
                class="logo-name">Pinjam</span>
            </a>
          </div>
          <ul class="sidebar-menu">
            <li class="menu-header">Menu Aplikasi</li>
            <li class="dropdown">
              <a href="<?php echo base_url('pinjamuser')?>" class="nav-link"><i data-feather="monitor"></i><span>Dashboard</span></a>
            </li>
            <li class="dropdown">
              <a href="#" class="menu-toggle nav-link has-dropdown"><i
                  data-feather="mail"></i><span>Peminjaman</span></a>
              <ul class="dropdown-menu">
                <li><a class="nav-link" href="<?php echo base_url('user/input')?>">Input Peminjaman</a></li>
                <li><a class="nav-link" href="<?php echo base_url('user/mobil')?>">Data Peminjaman Mobil</a></li>
                
              </ul>
            </li>
            <li class="dropdown">
              <a href="#" class="menu-toggle nav-link has-dropdown"><i
                  data-feather="feather"></i><span>Daftar Aset</span></a>
              <ul class="dropdown-menu">
                <li><a href="<?php echo base_url('user/datamobil')?>">Mobil</a></li>
                <li><a href="<?php echo base_url('user/dataruangan')?>">Ruangan</a></li>
                <li><a href="<?php echo base_url('user/dataalat')?>">Alat</a></li>
              </ul>
            </li>

           
            
          </ul>
        </aside>
      </div>