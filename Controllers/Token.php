<?php

class Token extends Controllers
{
  public function __construct()
  {
    session_start();
    if (empty($_SESSION['login'])) {
      header('Location: ' . base_url() . '/login');
    }
    parent::__construct();
  }

  public function valToken()
  {

    $code_1 = intval($_POST['code_1']);
    $code_2 = intval($_POST['code_2']);
    $code_3 = intval($_POST['code_3']);
    $code_4 = intval($_POST['code_4']);
    $code_5 = intval($_POST['code_5']);
    $code_6 = intval($_POST['code_6']);

    $token = $code_1 . $code_2 . $code_3 . $code_4 . $code_5 . $code_6;
    $arrData = $this->model->selectToken($token);

    if (empty($arrData)) {
      $arrRes = array('status' => false, 'msg' => 'Codigo de Seguridad Incorrecto');
    } else {
      $request_token = $this->model->updateToken();
      $arrRes = array('status' => true);
    }
    echo json_encode($arrRes, JSON_UNESCAPED_UNICODE);
    die();
  }
  public function newToken()
  {
    $request_token = $this->model->updateToken();
    $request_token = $this->model->insertToken();
    die();
  }

  public function disabledToken()
  {
    $request_token = $this->model->updateToken();
    die();
  }
}
