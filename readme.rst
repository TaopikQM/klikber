# README untuk Aplikasi Klinik KLIKBER

## Deskripsi Proyek
**KLIKBER** adalah aplikasi yang dirancang untuk mengelola jadwal secara efisien dan memfasilitasi interaksi antara penyedia layanan dan pengguna. Aplikasi ini bertujuan untuk mengurangi waktu tunggu, mengoptimalkan alokasi waktu layanan, dan meningkatkan kepuasan pengguna, khususnya dalam layanan kesehatan. Dengan fitur-fitur yang lengkap, KLIKBER membantu klinik dalam manajemen data pasien, dokter, poli, dan obat.

## Fitur Utama
- **Manajemen Dokter**: Admin dapat menambah, mengedit, atau menghapus data dokter.
- **Manajemen Pasien**: Admin dapat mengelola data pasien secara efisien.
- **Manajemen Poli**: Admin dapat mengelola berbagai jenis poli yang tersedia.
- **Manajemen Obat**: Admin dapat mengelola stok obat dan informasi terkait.
- **Pendaftaran Pasien**: Pasien dapat mendaftar untuk mendapatkan layanan.
- **Jadwal Periksa**: Dokter dapat mengatur jadwal pemeriksaan pasien.

## User Roles
Aplikasi ini memiliki tiga jenis pengguna dengan peran yang berbeda:

1. **Admin**
   - Login sebagai Admin
   - Mengelola Dokter
   - Mengelola Pasien
   - Mengelola Poli
   - Mengelola Obat

2. **Pasien**
   - Login sebagai Pasien
   - Mendaftar ke Poli
   - Melakukan Pendaftaran Pasien

3. **Dokter**
   - Login sebagai Dokter
   - Memperbaharui Data Dokter
   - Input Jadwal Periksa
   - Memeriksa Pasien
   - Menghitung Biaya Periksa
   - Memberikan Catatan Obat
   - Menampilkan Riwayat Pasien

## Teknologi yang Digunakan
- PHP (CodeIgniter 3)
- MySQL
- HTML/CSS/JavaScript
- Bootstrap

## Instalasi dan Pengaturan Awal

### Prasyarat
- Pastikan Anda memiliki XAMPP atau server lokal lain yang terpasang.
- PHP 7.x atau lebih tinggi.
- Database MySQL.

### Langkah-langkah Instalasi

1. **Download Aplikasi**
   - Clone repositori ini ke komputer Anda:
     ```bash
     git clone https://github.com/TaopikQM/klikber.git
     ```

2. **Pindahkan ke Folder XAMPP**
   - Pindahkan folder aplikasi ke dalam direktori `htdocs` di instalasi XAMPP Anda.

3. **Buat Database**
   - Buka phpMyAdmin melalui browser Anda (`http://localhost/phpmyadmin`).
   - Buat database baru dengan nama `klikber`.
   - Import file SQL yang tersedia di folder aplikasi untuk membuat tabel yang diperlukan.

4. **Konfigurasi File**
   - Edit file konfigurasi aplikasi (biasanya `config.php` atau `.env`) untuk menyesuaikan pengaturan database:
     ```php
     $dbHost = 'localhost';
     $dbUser = 'root';
     $dbPass = '';
     $dbName = 'klikber';
     ```

5. **Akses Aplikasi**
   - Jalankan server Apache dan MySQL melalui XAMPP.
   - Akses aplikasi melalui browser dengan alamat `http://localhost/nama_folder_aplikasi`.

### Pengaturan Akun Awal
- Untuk login sebagai admin, 
		usename= A2024-010
		pass= A2024-010

- Untuk login sebagai dokter, 
		usename= D2024-022
		pass= D2024-022

- Untuk login sebagai pasien, 
		usename= P2024-021
		pass= P2024-021

	gunakan username dan password yang telah ditentukan dalam dokumen pengaturan awal

- Tambahkan pasien dan dokter melalui antarmuka admin setelah login.

## Penggunaan Aplikasi
Setelah pengaturan selesai, pengguna dapat melakukan login sesuai dengan peran masing-masing (Admin, Pasien, Dokter) dan menggunakan fitur-fitur yang tersedia sesuai dengan kebutuhan mereka.

## Penutup
KLIKBER adalah solusi inovatif untuk manajemen klinik modern. Dengan aplikasi ini, diharapkan proses administrasi klinik menjadi lebih efisien dan pelayanan kepada pasien meningkat. Untuk pertanyaan lebih lanjut atau bantuan teknis, silakan hubungi pengembang melalui halaman kontak di repositori ini.


link lengkap versi app klikber : https://drive.google.com/drive/folders/10WPWpz3m-kIPuiTMPEgIl4slhYiEgBmP?usp=sharing

konfigurasi db ada di file database


###################
What is CodeIgniter
###################

CodeIgniter is an Application Development Framework - a toolkit - for people
who build web sites using PHP. Its goal is to enable you to develop projects
much faster than you could if you were writing code from scratch, by providing
a rich set of libraries for commonly needed tasks, as well as a simple
interface and logical structure to access these libraries. CodeIgniter lets
you creatively focus on your project by minimizing the amount of code needed
for a given task.

*******************
Release Information
*******************

This repo contains in-development code for future releases. To download the
latest stable release please visit the `CodeIgniter Downloads
<https://codeigniter.com/download>`_ page.

**************************
Changelog and New Features
**************************

You can find a list of all changes for each release in the `user
guide change log <https://github.com/bcit-ci/CodeIgniter/blob/develop/user_guide_src/source/changelog.rst>`_.

*******************
Server Requirements
*******************

PHP version 5.6 or newer is recommended.

It should work on 5.3.7 as well, but we strongly advise you NOT to run
such old versions of PHP, because of potential security and performance
issues, as well as missing features.

************
Installation
************

Please see the `installation section <https://codeigniter.com/user_guide/installation/index.html>`_
of the CodeIgniter User Guide.

*******
License
*******

Please see the `license
agreement <https://github.com/bcit-ci/CodeIgniter/blob/develop/user_guide_src/source/license.rst>`_.

*********
Resources
*********

-  `User Guide <https://codeigniter.com/docs>`_
-  `Language File Translations <https://github.com/bcit-ci/codeigniter3-translations>`_
-  `Community Forums <http://forum.codeigniter.com/>`_
-  `Community Wiki <https://github.com/bcit-ci/CodeIgniter/wiki>`_
-  `Community Slack Channel <https://codeigniterchat.slack.com>`_

Report security issues to our `Security Panel <mailto:security@codeigniter.com>`_
or via our `page on HackerOne <https://hackerone.com/codeigniter>`_, thank you.

***************
Acknowledgement
***************

The CodeIgniter team would like to thank EllisLab, all the
contributors to the CodeIgniter project and you, the CodeIgniter user.
