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
    $idUser = intval($_SESSION['idUser']);
    $arrData = $this->model->selectToken($token, $idUser);

    if (empty($arrData)) {
      $arrRes = array('status' => false, 'msg' => 'Codigo de Seguridad Incorrecto');
    } else {
      $request_token = $this->model->updateToken($idUser );
      $arrRes = array('status' => true);
    }
    echo json_encode($arrRes, JSON_UNESCAPED_UNICODE);
    die();
  }
  public function newToken()
  {
    $idUser = intval($_SESSION['idUser']);
    $request_token = $this->model->updateToken($idUser);
    $request_token = $this->model->insertToken($idUser);
    die();
  }

  public function disabledToken()
  {
    $idUser = intval($_SESSION['idUser']);
    $request_token = $this->model->updateToken($idUser);
    die();
  }
}
