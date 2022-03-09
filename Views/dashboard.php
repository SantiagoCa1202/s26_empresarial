<?= head_(); ?>
<?= header_(); ?>

<div id="s26-dashboard"></div>
<br>
<br>
<br>
<br>

<?php

//   //Get user IP address
//  if ( isset($_SERVER['HTTP_CLIENT_IP']) && ! empty($_SERVER['HTTP_CLIENT_IP'])) {
//    $ip = $_SERVER['HTTP_CLIENT_IP'];
//  } elseif ( isset($_SERVER['HTTP_X_FORWARDED_FOR']) && ! empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
//    $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
//  } else {
//    $ip = (isset($_SERVER['REMOTE_ADDR'])) ? $_SERVER['REMOTE_ADDR'] : '0.0.0.0';
//  }

// $ip = filter_var($ip, FILTER_VALIDATE_IP);
// $ip = ($ip === false) ? '0.0.0.0' : $ip;

// echo $ip;

?>
<?= dep($_SESSION) ?>
<?= footer_(); ?>



DELETE FROM `products`;
DELETE FROM `products_entries_establishments`;
DELETE FROM `products_entries_variants`;
DELETE FROM `products_establishments`;
DELETE FROM `products_photos`;
DELETE FROM `products_providers`;
DELETE FROM `products_series`;
DELETE FROM `products_variant`;
DELETE FROM `products_variant_dimensions`;
alter table products AUTO_INCREMENT=1;
alter table products_entries_establishments AUTO_INCREMENT=1;
alter table products_entries_variants AUTO_INCREMENT=1;
alter table products_establishments AUTO_INCREMENT=1;
alter table products_photos AUTO_INCREMENT=1;
alter table products_providers AUTO_INCREMENT=1;
alter table products_series AUTO_INCREMENT=1;
alter table products_variant AUTO_INCREMENT=1;
alter table products_variant_dimensions AUTO_INCREMENT=1;


//PROCEDIMIENTO ALMACENADO

DELIMITER $$

CREATE PROCEDURE update_cost_variant(n_amount int, n_cost decimal(10,2), variant_id int)
BEGIN
DECLARE new_stock int;
DECLARE new_total decimal(10,2);
DECLARE new_cost decimal(10,2);

DECLARE current_stock int;
DECLARE current_cost decimal(10,2);

SELECT SUM(stock) INTO current_stock FROM products_establishments WHERE product_variant_id = variant_id;
SELECT cost INTO current_cost FROM products_variant WHERE id = variant_id;

SET new_stock = current_stock + n_amount;
SET new_total = (current_stock * current_cost) + (n_amount * n_cost);
SET new_cost = new_total / new_stock;

UPDATE products_variant SET cost = new_cost WHERE id = variant_id;

SELECT new_stock,new_cost;

END;$$
DELIMITER ;