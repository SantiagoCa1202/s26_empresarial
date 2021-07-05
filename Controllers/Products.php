<?php

class Products extends Controllers
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

  public function products()
  {
    $this->views->getView($this, "products");
  }

  public function getProducts()
  {
    if ($_SESSION['permitsModule']['r']) {
      $perPage = intval($_GET['perPage']);
      $filter = [
        'id' => !empty($_GET['id']) ? intval($_GET['id']) : '',
        'auxiliary_code' => !empty($_GET['auxiliary_code']) ? intval($_GET['auxiliary_code']) : '',
        'ean_code' => !empty($_GET['ean_code']) ? intval($_GET['ean_code']) : '',
        'name' => !empty($_GET['name']) ? intval($_GET['name']) : '',
        'serie' => !empty($_GET['serie']) ? intval($_GET['serie']) : '',
        'trademark' => !empty($_GET['trademark']) ? intval($_GET['trademark']) : '',
        'provider' => !empty($_GET['provider']) ? intval($_GET['provider']) : '',
        'category' => !empty($_GET['category']) ? intval($_GET['category']) : '',
        'cost' => !empty($_GET['cost']) ? intval($_GET['cost']) : '',
        'pvp' => !empty($_GET['pvp']) ? intval($_GET['pvp']) : '',
        'status' => !empty($_GET['status']) ? intval($_GET['status']) : '',

      ];
      $arrData = $this->model->selectProducts($perPage, $filter);

      echo json_encode($arrData, JSON_UNESCAPED_UNICODE);
    }
    die();
  }

  public function getProduct($id)
  {
    if ($_SESSION['permitsModule']['r']) {
      $id = intval(strClean($id));
      if ($id > 0) {
        $arrData = $this->model->selectProduct($id);
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
