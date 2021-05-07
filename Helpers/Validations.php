<?php
function valString(string $str, int $minLength = 1, int $maxLength = 1000)
{
  if ($str == '' || strlen($str) < $minLength || strlen($str) > $maxLength) {
    return false;
  } else {
    return true;
  }
}
