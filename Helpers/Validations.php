<?php
function valString(string $str, int $minLength = 1, int $maxLength = 1000)
{
  if ($str == '' || strlen($str) < $minLength || strlen($str) > $maxLength) {
    return false;
  } else {
    return true;
  }
}
function val_date($date, $format = 'Y-m-d')
{
  $d = DateTime::createFromFormat($format, $date);
  return $d && $d->format($format) == $date;
}
