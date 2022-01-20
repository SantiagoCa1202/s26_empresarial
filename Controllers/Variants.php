<?php

class Variants extends Controllers
{
  public function __construct()
  {
    parent::__construct();

    session_start();
    if (empty($_SESSION['login'])) {
      header('Location: ' . base_url() . '/login');
    }
    getPermits(4);
  }

  public function variants()
  {
    $this->views->getView($this, "variants");
  }

  public function getVariants()
  {
    if ($_SESSION['permitsModule']['r']) {
      $perPage = intval($_GET['perPage']);
      $filter = [
        'ean_code' => !empty($_GET['ean_code']) ? strClean($_GET['ean_code']) : '',
        'sku' => !empty($_GET['sku']) ? strClean($_GET['sku']) : '',
        'product' => !empty($_GET['product']) ? strClean($_GET['product']) : '',
        'model' => !empty($_GET['model']) ? strClean($_GET['model']) : '',
        'trademark' => !empty($_GET['trademark']) ? strClean($_GET['trademark']) : '',
        'category' => !empty($_GET['category']) ? strClean($_GET['category']) : '',
        'pvp' => !empty($_GET['pvp']) ? strClean($_GET['pvp']) : '',
      ];
      $arrData = $this->model->selectVariants($perPage, $filter);

      echo json_encode($arrData, JSON_UNESCAPED_UNICODE);
    }
    die();
  }
}
