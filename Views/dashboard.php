<?= head_(); ?>
<?= header_(); ?>

<div id="s26-dashboard"></div>
<br>
<br>
<br>
<br>
<script>
  let f = '2021-7-5';

  let fecha = new Date(f);
  console.log(fecha.toLocaleDateString())
</script>
<?php

// // Get user IP address
// if ( isset($_SERVER['HTTP_CLIENT_IP']) && ! empty($_SERVER['HTTP_CLIENT_IP'])) {
//   $ip = $_SERVER['HTTP_CLIENT_IP'];
// } elseif ( isset($_SERVER['HTTP_X_FORWARDED_FOR']) && ! empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
//   $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
// } else {
//   $ip = (isset($_SERVER['REMOTE_ADDR'])) ? $_SERVER['REMOTE_ADDR'] : '0.0.0.0';
// }

// $ip = filter_var($ip, FILTER_VALIDATE_IP);
// $ip = ($ip === false) ? '0.0.0.0' : $ip;

// echo $ip;

?>
<?= dep($_SESSION) ?>
<?= footer_(); ?>