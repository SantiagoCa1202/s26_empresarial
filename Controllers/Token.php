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

    $token = intval($_POST['code']);
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
