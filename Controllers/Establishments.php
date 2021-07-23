<?php

class Establishments extends Controllers
{
  public function __construct()
  {
    parent::__construct();

    session_start();
    if (empty($_SESSION['login'])) {
      header('Location: ' . base_url() . '/login');
    }
    getPermits(41);
  }

  public function establishments()
  {
    $this->views->getView($this, "establishments");
  }

  public function getEstablishments()
  {
    if ($_SESSION['permitsModule']['r']) {
      $perPage = isset($_GET['perPage']) ? intval($_GET['perPage']) : '10000';
      $filter = [
        'n_establishment' => !empty($_GET['n_establishment']) ? intval($_GET['n_establishment']) : '',
        'tradename' => isset($_GET['tradename']) ? strClean($_GET['tradename']) : '',
        'city' => isset($_GET['city']) ? strClean($_GET['city']) : '',
        'status' => !empty($_GET['status']) ? intval($_GET['status']) : '',
      ];
      $arrData = $this->model->selectEstablishments($perPage, $filter);
      echo json_encode($arrData, JSON_UNESCAPED_UNICODE);
    }
    die();
  }

  public function getEstablishment(int $id)
  {
    if ($_SESSION['permitsModule']['r']) {
      $id = intval(strClean($id));
      if ($id > 0) {
        $arrData = $this->model->selectEstablishment($id);
        $arrRes = (empty($arrData)) ? 0 : $arrData;

        echo json_encode($arrRes, JSON_UNESCAPED_UNICODE);
      }
    }
    die();
  }
}
