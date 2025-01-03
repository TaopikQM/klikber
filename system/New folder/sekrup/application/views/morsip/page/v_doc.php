<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  <title>e-Morsip</title>
  <!-- General CSS Files -->
  <link rel='shortcut icon' type='image/x-icon' href='<?php echo base_url()?>harta/jateng.ico' />
</head>
<?php
$tahun=$this->session->userdata('tahun');
$lk=base_url()."harta/morsip/doc/dokumen".$tahun."/".$link.".pdf?".time();
 ?>
<embed src="<?php echo $lk;?>" width=100% height="800">

          
