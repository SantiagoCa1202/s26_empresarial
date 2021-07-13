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

//VALIDAR NUMERO DE FACTURA 
function val_doc($n_doc){
  $array_doc = explode('-',$n_doc);
  if(count($array_doc) === 3 && 
    strlen($array_doc['0']) === 3 && 
    strlen($array_doc['1']) === 3 && 
    strlen($array_doc['2']) === 9 && 
    is_numeric($array_doc['0']) && 
    is_numeric($array_doc['1']) && 
    is_numeric($array_doc['2']) && 
    strlen($n_doc) === 17 &&
    false !== strpos($n_doc, "-")
    ){
    return true;
  }
  return false;
}
