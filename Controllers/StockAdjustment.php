<?php

class StockAdjustment extends Controllers
{
  public function __construct()
  {
    parent::__construct();

    session_start();
    if (empty($_SESSION['login'])) {
      header('Location: ' . base_url() . '/login');
    }
    getPermits(19);
  }

  public function stockAdjustment()
  {
    $this->views->getView($this, "stockAdjustment");
  }

  public function getAdjustments()
  {
    if ($_SESSION['permitsModule']['r']) {
      $perPage = intval($_GET['perPage']);
      $filter = [
        'establishment_id' => $_SESSION['userData']['establishment_id'],
        'ean_code' => !empty($_GET['ean_code']) ? strClean($_GET['ean_code']) : '',
        'sku' => !empty($_GET['sku']) ? strClean($_GET['sku']) : '',
        'product' => !empty($_GET['product']) ? strClean($_GET['product']) : '',
        'model' => !empty($_GET['model']) ? strClean($_GET['model']) : '',
        'trademark' => !empty($_GET['trademark']) ? strClean($_GET['trademark']) : '',
      ];
      $arrData = $this->model->selectAdjustments($perPage, $filter);

      echo json_encode($arrData, JSON_UNESCAPED_UNICODE);
    }
    die();
  }
}
