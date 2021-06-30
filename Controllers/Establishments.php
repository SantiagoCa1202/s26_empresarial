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
    getPermits(42);
  }

  public function establishment()
  {
    $this->views->getView($this, "establishment");
  }

  public function getEstablishments()
  {
    if ($_SESSION['permitsModule']['r']) {
      $perPage = isset($_GET['perPage']) ? intval($_GET['perPage']) : '10000';
      $filter = [
        'id' => !empty($_GET['id']) ? intval($_GET['id']) : '',
        'tradename' => isset($_GET['tradename']) ? strClean($_GET['tradename']) : '',
        'city' => isset($_GET['city']) ? strClean($_GET['city']) : '',
        'status' => !empty($_GET['status']) ? intval($_GET['status']) : '',
        'date' => !empty($_GET['date']) ? $_GET['date'] : '',
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
        if (empty($arrData)) {
          $arrRes = 0;
        } else {
          $arrRes = $arrData;
        }
        echo json_encode($arrRes, JSON_UNESCAPED_UNICODE);
      }
    }
    die();
  }
}
