<?php
//Retorna la Url del Proyecto
function base_url()
{
  return BASE_URL;
}

//retorna la url de Assets
function asset($file)
{
  return BASE_URL . "/Assets/" . $file;
}

//node_modules
function node($package)
{
  return BASE_URL . "/node_modules/" . $package;
}

function head_($data = "")
{
  $view_header = "Views/Template/head.php";
  require_once($view_header);
}
function footer_($data = "")
{
  $view_footer = "Views/Template/footer.php";
  require_once($view_footer);
}
function header_($data = "")
{
  $view_header = "Views/Template/Menu/header.php";
  require_once($view_header);
}
function scripts_()
{
  $scripts = "Views/Template/scripts.php";
  require_once($scripts);
}

function data_style($name)
{
  $src = "";
  if (file_exists('Assets/css/style_view/' . $name . '.css')) {
    $src .= '<link type="text/css" rel="stylesheet" href="' . BASE_URL . '/Assets/css/style_view/' . $name . '.css">';
  }
  $src .= '<script src="' . BASE_URL . '/Assets/js/data/' . $name . '.js"></script>';

  return $src;
}

//Muestra Array Formateado
function dep($data)
{
  $format = print_r('<pre>');
  $format .= print_r($data);
  $format .= print_r('</pre>');
  return $format;
}

//ENVIO DE EMAIL 
function sendEmail($data, $template)
{
  $affair = $data['affair'];
  $destination_email = $data['email'];
  $company = NAME_SENDER;
  $sender = EMAIL_SENDER;

  //ENVIO DE EMAIL
  $de = "MIME-Version: 1.0\r\n";
  $de .= "Content-type: text7html; charset=UTF-8\r\n";
  $de .= "From: {$company} <{$sender}>\r\n";
  ob_start();
  require_once("Views/Template/Email/" . $template . ".php");
  $message = ob_get_clean();
  $send = mail($destination_email, $affair, $message, $de);

  return $send;
}

function getPermits(int $idModule)
{
  require_once("Models/PermitsModel.php");
  $objPermits = new PermitsModel;
  $role_id = $_SESSION['userData']['role_id'];
  $arrPermits = $objPermits->permitsModule($role_id);
  $permits = '';
  $permitsModule = '';

  if (count($arrPermits) > 0) {
    $permits = $arrPermits;
    $permitsModule = isset($arrPermits[$idModule]) ? $arrPermits[$idModule] : "";
  }
  $_SESSION['permits'] = $permits;
  $_SESSION['permitsModule'] = $permitsModule;
}

//SUBIDA DE IMAGENES 
function uploadPhoto(string $tmp_name, string $name)
{
  $destination = 'Assets/media/uploads/photos/' . $name;
  $move = move_uploaded_file($tmp_name, $destination);

  return $move;
}

//SUBIDA DE Archivos 
function uploadFile(string $tmp_name, string $name)
{
  $destination = 'Assets/media/uploads/files/' . $name;
  $move = move_uploaded_file($tmp_name, $destination);

  return $move;
}

//RESPUESTAS DE SERVIDOR 
function s26_res($name, $res, $type = 1, $del = 'Eliminar')
{

  if ($res > 0) {
    if ($type == 1) {
      $arrRes = array('type' => 1, 'msg' => $name . ' guardado correctamente.');
    } else if ($type == 2) {
      $arrRes = array('type' => 2, 'msg' => $name . ' actualizado correctamente.');
    } else if ($type == 3) {
      $arrRes = array('type' => 2, 'msg' => $del == 'Eliminar' ? $name . ' eliminado' : $name . ' anulado' .  ' correctamente.');
    } else if ($type == 4) {
      $arrRes = array('type' => 1, 'msg' => $name . ' asignado correctamente.');
    }
  } else if ($res == 0) {
    if ($type == 1 || $type == 2 || $type == 3) {
      $arrRes = array('type' => 0, 'msg' => 'Error al ' . $del . ' ' . $name);
    }
  } else if ($res == -1) {
    $arrRes = array('type' => 0, 'msg' => 'Error al Ingresar ' . $name . '. Compruebe que los datos ingresados sean correctos');
  } else if ($res == -2) {
    $arrRes = array('type' => 0, 'msg' => 'El Registro ya existe.');
  } else if ($res == -3) {
    $arrRes = array('type' => 0, 'msg' => 'No se puede realizar esta acci??n, registro vinculado a otras tablas.');
  } else if ($res == -4) {
    $arrRes = array('type' => 0, 'msg' => 'No se puede realizar esta acci??n, registro especial, se enviara un informe a servicio t??cnico.');
  } else if ($res == -5) {
    $arrRes = array('type' => 0, 'msg' => 'No tiene permiso para realizar esta acci??n.');
  } else if ($res == -6) {
    $arrRes = array('type' => 0, 'msg' => 'El Registro ya existe, restauralo desde la papelera.');
  } else if ($res == -7) {
    $arrRes = array('type' => 3, 'msg' => 'Existe Un Registro Similar.');
  } else if ($res == -8) {
    $arrRes = array('type' => 0, 'msg' => 'El Registro ya se Encuentra Anulado.');
  } else {
    $arrRes = array('type' => 0, 'msg' => 'Error del Sistema, comunique a servicio al t??cnico.');
  }

  return $arrRes;
}
//Eliminar exceso de espacios y caracteres especiales 
function strClean($strString)
{
  $string = preg_replace(['/\s+/', '/^\s|\s$/'], [' ', ''], $strString);
  $string = trim($string);
  $string = stripslashes($string);
  $string = str_ireplace("<script>", "", $string);
  $string = str_ireplace("</script>", "", $string);
  $string = str_ireplace("<script src>", "", $string);
  $string = str_ireplace("<script type=>", "", $string);
  $string = str_ireplace("SELECT * FROM", "", $string);
  $string = str_ireplace("DELETE FROM", "", $string);
  $string = str_ireplace("INSERT INTO", "", $string);
  $string = str_ireplace("SELECT COUNT(*) FROM", "", $string);
  $string = str_ireplace("DROP TABLE", "", $string);
  $string = str_ireplace("OR '1'='1'", "", $string);
  $string = str_ireplace('OR "1"="1"', "", $string);
  $string = str_ireplace('OR `1`=`1`', "", $string);
  $string = str_ireplace("is NULL; --", "", $string);
  $string = str_ireplace("is NULL; --", "", $string);
  $string = str_ireplace("LIKE '", "", $string);
  $string = str_ireplace('LIKE "', "", $string);
  $string = str_ireplace("LIKE `", "", $string);
  $string = str_ireplace("OR 'a'='a'", "", $string);
  $string = str_ireplace('OR "a"="a"', "", $string);
  $string = str_ireplace("OR `a`=`a`", "", $string);
  $string = str_ireplace("OR `a`=`a", "", $string);
  $string = str_ireplace("--", "", $string);
  $string = str_ireplace("^", "", $string);
  $string = str_ireplace("[", "", $string);
  $string = str_ireplace("]", "", $string);
  $string = str_ireplace("==", "", $string);
  $string = strtolower($string);
  return $string;
}

// limpiador de array
function arrClean($arr, $type = "")
{
  $new_arr = [];

  if (count($arr) > 0) {
    for ($i = 0; $i < count($arr); $i++) {
      array_push($new_arr, $type == "int" ? intval($arr[$i]) : strClean($arr[$i]));
    }
  }
  return $new_arr;
}

//genera una contrase??a de 10 caracteres
function passGenerator($length = 10)
{
  $pass = "";
  $longitudPass = $length;
  $string = "@ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz123456789";
  $logitudString = strlen($string);

  for ($i = 1; $i <= $longitudPass - 1; $i++) {
    $pos = rand(0, $logitudString - 1);
    $pass .= substr($string, $pos, 1);
  }
  return $pass;
}

//generador de token 
function token()
{
  $r1 = bin2hex(random_bytes(10));
  $r2 = bin2hex(random_bytes(10));
  $r3 = bin2hex(random_bytes(10));
  $r4 = bin2hex(random_bytes(10));
  $token = $r1 . '-' . $r2 . '-' . $r3 . '-' . $r4;
  return $token;
}

//formato para valores monetarios
function formatMoney($ammount)
{
  $ammount = number_format((float)$ammount, 2, SPD, SPM);
  return $ammount;
}

// formato de fecha
function dateFormat($date, $type = 1)
{
  $strDate = strtotime($date);
  if ($type == 1) {
    $date = date("d/m/Y", $strDate);
  } else if ($type == 2) {
    $date = date("Y-m-d", $strDate);
  } else if ($type == 3) {
    $date = utf8_encode(strftime('%A %d de %B de %Y', $strDate));
  }
  return $date;
}

function group_array_by_date(string $column, array $array)
{
  //EXTRAER FECHAS E INSERTARLAS EN UN NUEVO ARRAY 
  $dates = [];

  foreach ($array as $key => $value) {
    $date = date("Y-m-d", strtotime($value[$column]));
    array_push($dates, $date);
  }

  // SUPRIMIR DUPLICADOS
  $dates = array_unique($dates);
  $dates = array_values($dates);

  $items = [];

  for ($i = 0; $i < count($dates); $i++) {

    $arr = [];

    for ($e = 0; $e < count($array); $e++) {
      $date = date("Y-m-d", strtotime($array[$e][$column]));

      if ($dates[$i] == $date) {
        array_push($arr, $array[$e]);
      }
    }

    $item = [
      'date' => $dates[$i],
      'items' => $arr,
    ];
    array_push($items, $item);
  }

  return $items;
}

// VALIDAR SI EXISTE UNA URL 
function url_exists($url) {
  $hdrs = @get_headers($url);
  return is_array($hdrs) ? preg_match('/^HTTP\\/\\d+\\.\\d+\\s+2\\d\\d\\s+.*$/',$hdrs[0]) : false;
}

// ACTIVE POST FROM AXIOS POST CONTENTS
// $_POST = json_decode(file_get_contents('php://input'), true);
