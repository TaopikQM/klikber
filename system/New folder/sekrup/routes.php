<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller'] = 'landing';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

$route['morsip']='morsip/Morsip';
$route['morsip/(:any)'] = 'morsip/Morsip/$1';
$route['user'] = 'morsip/Morsip';
$route['morsip/(:any)/(:any)'] = 'morsip/Morsip/$1/$2';

$route['tte']='morsip/Tte';
$route['tte/(:any)'] = 'morsip/Tte/$1';
$route['tte/(:any)/(:any)'] = 'morsip/tte/$1/$2';

$route['pinjam']='pinjam/Pinjam';
$route['pinjam/(:any)'] = 'pinjam/Pinjam/$1';
$route['pinjam/(:any)/(:any)'] = 'pinjam/Pinjam/$1/$2';
$route['pinjam/(:any)/(:any)/(:any)'] = 'pinjam/Pinjam/$1/$2/$3';

$route['pinjamuser']='pinjam/Pinjamuser';
$route['pinjamuser/(:any)'] = 'pinjam/Pinjamuser/$1';
$route['pinjamuser/(:any)/(:any)'] = 'pinjam/Pinjamuser/$1/$2';
$route['pinjamuser/(:any)/(:any)/(:any)'] = 'pinjam/Pinjamuser/$1/$2/$3';

$route['ruangan']='pinjam/Ruangan';
$route['ruangan/(:any)'] = 'pinjam/Ruangan/$1';
$route['ruangan/(:any)/(:any)'] = 'pinjam/Ruangan/$1/$2';
$route['ruangan/(:any)/(:any)/(:any)'] = 'pinjam/Ruangan/$1/$2/$3';

$route['alat']='pinjam/Alat';
$route['alat/(:any)'] = 'pinjam/Alat/$1';
$route['alat/(:any)/(:any)'] = 'pinjam/Alat/$1/$2';
$route['alat/(:any)/(:any)/(:any)'] = 'pinjam/Alat/$1/$2/$3';

$route['mobil']='pinjam/Mobil';
$route['mobil/(:any)'] = 'pinjam/Mobil/$1';
$route['mobil/(:any)/(:any)'] = 'pinjam/Mobil/$1/$2';
$route['mobil/(:any)/(:any)/(:any)'] = 'pinjam/Mobil/$1/$2/$3';

$route['riwayatservis']='pinjam/RiwayatServis';
// $route['riwayatservis/show/'] = 'pinjam/RiwayatServis/show/$1';
$route['riwayatservis/(:any)'] = 'pinjam/RiwayatServis/$1';
$route['riwayatservis/(:any)/(:any)'] = 'pinjam/RiwayatServis/$1/$2';
$route['riwayatservis/(:any)/(:any)/(:any)'] = 'pinjam/RiwayatServis/$1/$2/$3';

$route['riwayatservis/tambah/(:num)'] = 'riwayatservis/tambah/$1';
$route['riwayatservis/tambah'] = 'riwayatservis/tambah';

$route['riwayatservis/edit/(:num)'] = 'pinjam/RiwayatServis/edit/$1';

$route['riwayatservisalat']='pinjam/RiwayatServisAlat';
$route['riwayatservisalat/(:any)'] = 'pinjam/RiwayatServisAlat/$1';
$route['riwayatservisalat/(:any)/(:any)'] = 'pinjam/RiwayatServisAlat/$1/$2';
$route['riwayatservisalat/(:any)/(:any)/(:any)'] = 'pinjam/RiwayatServisAlat/$1/$2/$3';

$route['riwayatservisruang']='pinjam/RiwayatServisRuang';
$route['riwayatservisruang/(:any)']='pinjam/RiwayatServisRuang/$1';
$route['riwayatservisruang/(:any)/(:any)']='pinjam/RiwayatServisRuang/$1/$2';
$route['riwayatservisruang/(:any)/(:any)/(:any)']='pinjam/RiwayatServisRuang/$1/$2/$3';

$route['riwayatservisruang/tambah/(:num)'] = 'riwayatservisruang/tambah/$1'; 
$route['riwayatservisruang/tambah'] = 'riwayatservisruang/tambah';

$route['riwayatservisruang/edit/(:num)'] = 'pinjam/RiwayatServisRuang/edit/$1';
    
$route['reports/mobil_ekspor_excel/exmobil'] = 'reports/Mobil_ekspor_excel/exmobil';


$route['absen']='absen/AttendanceController';
$route['absen/adduser'] = 'absen/AddUser';
$route['absen/user'] = 'absen/UserController'; 

$route['absen/user/add'] = 'absen/regis';
$route['absen/user/atten'] = 'absen/AddUser';
$route['absen/user/getall'] = 'absen/UserController/getAll'; 
$route['absen/user/update'] = 'absen/UserController/update';
$route['absen/user/updateS'] = 'absen/UserController/updateS';
$route['absen/user/delete'] = 'absen/UserController/delete_user';

$route['absen/faces/checkdes']='absen/AttendanceController/checkDescriptor';


$route['login/penilai']='login/login_penilai';
$route['home/out']='login/out';

$route['sekrea']='login/login_sekre';
$route['landinga']='landing/Landing';


