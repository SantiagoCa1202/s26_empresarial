<?= head_(); ?>
<?= header_(); ?>

<div id="s26-dashboard"></div>
<br>
<br>
<br>
<br>

<?php

$amount = [
  [
    "cant" => 10
  ],
  [
    "cant" => 10
  ],
  [
    "cant" => 10
  ],
  [
    "cant" => 10
  ],
];
$total = 0;
for ($i = 0; $i < count($amount); $i++) {
  $total += $amount[$i]['cant'];

}

echo $total;

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



DELETE FROM `products`;
DELETE FROM `products_entries`;
DELETE FROM `products_entries_establishments`;
DELETE FROM `products_entries_variants`;
DELETE FROM `products_establishments`;
DELETE FROM `products_photos`;
DELETE FROM `products_providers`;
DELETE FROM `products_series`;
DELETE FROM `products_variant`;
DELETE FROM `products_variants`;
DELETE FROM `products_variant_dimensions`;
alter table products AUTO_INCREMENT=1;
alter table products_entries AUTO_INCREMENT=1;
alter table products_entries_establishments AUTO_INCREMENT=1;
alter table products_entries_variants AUTO_INCREMENT=1;
alter table products_establishments AUTO_INCREMENT=1;
alter table products_photos AUTO_INCREMENT=1;
alter table products_providers AUTO_INCREMENT=1;
alter table products_series AUTO_INCREMENT=1;
alter table products_variant AUTO_INCREMENT=1;
alter table products_variants AUTO_INCREMENT=1;
alter table products_variant_dimensions AUTO_INCREMENT=1;