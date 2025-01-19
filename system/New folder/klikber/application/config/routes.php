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


$route['landing'] = 'Landing';
$route['landing/(:any)'] = 'Landing/$1';
$route['landing/(:any)/(:any)'] = 'Landing/$1/$2';
$route['landing/(:any)/(:any)/(:any)'] = 'Landing/$1/$2/$3';

$route['poli'] = 'klinik/Poli';
$route['poli/(:any)'] = 'klinik/Poli/$1';
$route['poli/(:any)/(:any)'] = 'klinik/Poli/$1/$2';
$route['poli/(:any)/(:any)/(:any)'] = 'klinik/Poli/$1/$2/$3';

$route['dokter'] = 'klinik/Dokter';
$route['dokter/(:any)'] = 'klinik/Dokter/$1';
$route['dokter/(:any)/(:any)'] = 'klinik/Dokter/$1/$2';
$route['dokter/(:any)/(:any)/(:any)'] = 'klinik/Dokter/$1/$2/$3';

$route['pasien'] = 'klinik/Pasien';
$route['pasien/(:any)'] = 'klinik/Pasien/$1';
$route['pasien/(:any)/(:any)'] = 'klinik/Pasien/$1/$2';
$route['pasien/(:any)/(:any)/(:any)'] = 'klinik/Pasien/$1/$2/$3';

$route['admin'] = 'klinik/Admin';
$route['admin/(:any)'] = 'klinik/Admin/$1';
$route['admin/(:any)/(:any)'] = 'klinik/Admin/$1/$2';
$route['admin/(:any)/(:any)/(:any)'] = 'klinik/Admin/$1/$2/$3';


$route['obat'] = 'klinik/Obat';
$route['obat/(:any)'] = 'klinik/Obat/$1';
$route['obat/(:any)/(:any)'] = 'klinik/Obat/$1/$2';
$route['obat/(:any)/(:any)/(:any)'] = 'klinik/Obat/$1/$2/$3';

$route['role'] = 'klinik/Role';
$route['role/(:any)'] = 'klinik/Role/$1';
$route['role/(:any)/(:any)'] = 'klinik/Role/$1/$2';
$route['role/(:any)/(:any)/(:any)'] = 'klinik/Role/$1/$2/$3';

$route['users'] = 'klinik/Users';
$route['users/(:any)'] = 'klinik/Users/$1';
$route['users/(:any)/(:any)'] = 'klinik/Users/$1/$2';
$route['users/(:any)/(:any)/(:any)'] = 'klinik/Users/$1/$2/$3';

$route['login/penilai']='login/login_penilai';
$route['home/out']='login/out';

$route['sekrea']='login/login_sekre';
$route['landinga']='landing/Landing';


