<div class="main-sidebar sidebar-style-2">
        <aside id="sidebar-wrapper">
          <div class="sidebar-brand">
            <a href="<?php echo base_url('morsip')?>"> <img alt="image" src="<?php echo base_url()?>harta/morsip/assets/img/logotte.png" class="header-logo" /> <span
                class="logo-name"></span>
            </a>
          </div>
          <ul class="sidebar-menu">
            <li class="menu-header">Menu Aplikasi</li>
            <li class="dropdown">
              <a href="<?php echo base_url('morsip')?>" class="nav-link"><i data-feather="monitor"></i><span>Dashboard</span></a>
            </li>
            <li class="dropdown">
              <a href="#" class="menu-toggle nav-link has-dropdown"><i
                  data-feather="mail"></i><span>Nomor Surat</span></a>
              <ul class="dropdown-menu">
                <li><a class="nav-link" href="<?php echo base_url('morsip')?>">Data Nomor</a></li>
                <li><a class="nav-link" href="<?php echo base_url('morsip/input_nomor')?>">Input Nomor</a></li>
              </ul>
            </li>
            <li class="dropdown">
              <a href="#" class="menu-toggle nav-link has-dropdown"><i
                  data-feather="feather"></i><span>e-SPT</span></a>
              <ul class="dropdown-menu">
                <li><a href="<?php echo base_url('morsip/input_spt')?>">Input SPT</a></li>
                <li><a href="<?php echo base_url('morsip/dataspt')?>">Daftar SPT</a></li>
              </ul>
            </li>

            
           
           <li class="dropdown">
              <a href="#" class="menu-toggle nav-link has-dropdown"><i
                  data-feather="user-check"></i><span>Pegawai</span></a>
              <ul class="dropdown-menu">
                <li><a href="<?php echo base_url('morsip/input_pegawai')?>">Input Pegawai</a></li>
                <li><a href="<?php echo base_url('morsip/datapegawai')?>">Daftar Pegawai</a></li>
                <li><a href="<?php echo base_url('morsip/datajabatan')?>">Daftar Jabatan</a></li>
              </ul>
            </li>
            <li class="dropdown">
              <a href="#" class="menu-toggle nav-link has-dropdown"><i data-feather="command"></i><span>Master SPT</span></a>
              <ul class="dropdown-menu">
                <li><a class="nav-link" href="<?php echo base_url('morsip/dasarsurat')?>">Dasar Surat</a></li>
                <li><a class="nav-link" href="<?php echo base_url('morsip/keperluan')?>">Keperluan</a></li>
               
              </ul>
            </li>
            <li class="dropdown">
              <a href="#" class="menu-toggle nav-link has-dropdown"><i data-feather="mail"></i><span>Email</span></a>
              <ul class="dropdown-menu">
                <li><a class="nav-link" href="email-inbox.html">Inbox</a></li>
                <li><a class="nav-link" href="email-compose.html">Compose</a></li>
                <li><a class="nav-link" href="email-read.html">read</a></li>
              </ul>
            </li>
            
          </ul>
        </aside>
      </div>