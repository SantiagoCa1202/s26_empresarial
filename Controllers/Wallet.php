<?php

class Wallet extends Controllers
{

  public function __construct()
  {
    parent::__construct();

    session_start();
    if (empty($_SESSION['login'])) {
      header('Location: ' . base_url() . '/login');
    }

    getPermits(27);
  }

  public function wallet()
  {
    $this->views->getView($this, "wallet");
  }

  public function getWallet()
  {

    if ($_SESSION['permitsModule']['r']) {
      $arrData = $this->model->selectWallet();

      echo json_encode($arrData, JSON_NUMERIC_CHECK);
    }
    die();
  }

  public function getReportSales()
  {
    if ($_SESSION['permitsModule']['r']) {

      if ($_SESSION['permits'][41]['r'] && $_SESSION['userData']['access_boxes'] == 1) {
        // ACCESO A TODAS LAS CAJAS DE TODOS LOS ESTALBECIMIENTOS
        $establishment_id = !empty($_GET['establishment_id']) ? intval($_GET['establishment_id']) : "";
        $box_id = !empty($_GET['box_id']) ? intval($_GET['box_id']) : '';
      } else if ($_SESSION['userData']['access_boxes'] == 1) {
        // ACCESO A TODAS LAS CAJAS DEL ESTABLECIMIENTO LOGEADO
        $establishment_id = $_SESSION['userData']['establishment_id'];
        $box_id = !empty($_GET['box_id']) ? intval($_GET['box_id']) : '';
      } else {
        // ACCESO A LA CAJA REGISTRADA CON EL DISPOSITIVO
        $establishment_id = $_SESSION['userData']['establishment_id'];
        $box_id = $_SESSION['userData']['box_id'];
      }

      $date = !empty($_GET['date']) ? $_GET['date'] : '';

      $arrData = $this->model->selectReportSales($date, $establishment_id, $box_id);
      $arrRes = (empty($arrData)) ? 0 : $arrData;

      echo json_encode($arrRes, JSON_UNESCAPED_UNICODE);
    }
    die();
  }

  public function getReportReturns()
  {
    if ($_SESSION['permitsModule']['r']) {

      if ($_SESSION['permits'][41]['r'] && $_SESSION['userData']['access_boxes'] == 1) {
        // ACCESO A TODAS LAS CAJAS DE TODOS LOS ESTALBECIMIENTOS
        $establishment_id = !empty($_GET['establishment_id']) ? intval($_GET['establishment_id']) : "";
        $box_id = !empty($_GET['box_id']) ? intval($_GET['box_id']) : '';
      } else if ($_SESSION['userData']['access_boxes'] == 1) {
        // ACCESO A TODAS LAS CAJAS DEL ESTABLECIMIENTO LOGEADO
        $establishment_id = $_SESSION['userData']['establishment_id'];
        $box_id = !empty($_GET['box_id']) ? intval($_GET['box_id']) : '';
      } else {
        // ACCESO A LA CAJA REGISTRADA CON EL DISPOSITIVO
        $establishment_id = $_SESSION['userData']['establishment_id'];
        $box_id = $_SESSION['userData']['box_id'];
      }

      $date = !empty($_GET['date']) ? $_GET['date'] : '';

      $arrData = $this->model->selectReportReturns($date, $establishment_id, $box_id);
      $arrRes = (empty($arrData)) ? 0 : $arrData;

      echo json_encode($arrRes, JSON_UNESCAPED_UNICODE);
    }
    die();
  }

  public function getReportSalesPerCategories()
  {
    if ($_SESSION['permitsModule']['r']) {

      if ($_SESSION['permits'][41]['r'] && $_SESSION['userData']['access_boxes'] == 1) {
        // ACCESO A TODAS LAS CAJAS DE TODOS LOS ESTALBECIMIENTOS
        $establishment_id = !empty($_GET['establishment_id']) ? intval($_GET['establishment_id']) : "";
        $box_id = !empty($_GET['box_id']) ? intval($_GET['box_id']) : '';
      } else if ($_SESSION['userData']['access_boxes'] == 1) {
        // ACCESO A TODAS LAS CAJAS DEL ESTABLECIMIENTO LOGEADO
        $establishment_id = $_SESSION['userData']['establishment_id'];
        $box_id = !empty($_GET['box_id']) ? intval($_GET['box_id']) : '';
      } else {
        // ACCESO A LA CAJA REGISTRADA CON EL DISPOSITIVO
        $establishment_id = $_SESSION['userData']['establishment_id'];
        $box_id = $_SESSION['userData']['box_id'];
      }

      $date = !empty($_GET['date']) ? $_GET['date'] : '';

      $arrData = $this->model->selectReportSalesPerCategories($date, $establishment_id, $box_id);

      echo json_encode($arrData, JSON_UNESCAPED_UNICODE);
    }
    die();
  }

  public function getReportSalesPerPaymentMethod()
  {
    if ($_SESSION['permitsModule']['r']) {

      if ($_SESSION['permits'][41]['r'] && $_SESSION['userData']['access_boxes'] == 1) {
        // ACCESO A TODAS LAS CAJAS DE TODOS LOS ESTALBECIMIENTOS
        $establishment_id = !empty($_GET['establishment_id']) ? intval($_GET['establishment_id']) : "";
        $box_id = !empty($_GET['box_id']) ? intval($_GET['box_id']) : '';
      } else if ($_SESSION['userData']['access_boxes'] == 1) {
        // ACCESO A TODAS LAS CAJAS DEL ESTABLECIMIENTO LOGEADO
        $establishment_id = $_SESSION['userData']['establishment_id'];
        $box_id = !empty($_GET['box_id']) ? intval($_GET['box_id']) : '';
      } else {
        // ACCESO A LA CAJA REGISTRADA CON EL DISPOSITIVO
        $establishment_id = $_SESSION['userData']['establishment_id'];
        $box_id = $_SESSION['userData']['box_id'];
      }

      $date = !empty($_GET['date']) ? $_GET['date'] : '';

      $arrData = $this->model->selectReportSalesPerPaymentMethod($date, $establishment_id, $box_id);

      echo json_encode($arrData, JSON_UNESCAPED_UNICODE);
    }
    die();
  }

  public function getReportExternalIncomes()
  {
    if ($_SESSION['permitsModule']['r']) {

      if ($_SESSION['permits'][41]['r'] && $_SESSION['userData']['access_boxes'] == 1) {
        // ACCESO A TODAS LAS CAJAS DE TODOS LOS ESTALBECIMIENTOS
        $establishment_id = !empty($_GET['establishment_id']) ? intval($_GET['establishment_id']) : "";
        $box_id = !empty($_GET['box_id']) ? intval($_GET['box_id']) : '';
      } else if ($_SESSION['userData']['access_boxes'] == 1) {
        // ACCESO A TODAS LAS CAJAS DEL ESTABLECIMIENTO LOGEADO
        $establishment_id = $_SESSION['userData']['establishment_id'];
        $box_id = !empty($_GET['box_id']) ? intval($_GET['box_id']) : '';
      } else {
        // ACCESO A LA CAJA REGISTRADA CON EL DISPOSITIVO
        $establishment_id = $_SESSION['userData']['establishment_id'];
        $box_id = $_SESSION['userData']['box_id'];
      }

      $date = !empty($_GET['date']) ? $_GET['date'] : '';

      $arrData = $this->model->selectReportExternalIncomes($date, $establishment_id, $box_id);
      $arrRes = (empty($arrData)) ? 0 : $arrData;

      echo json_encode($arrRes, JSON_UNESCAPED_UNICODE);
    }
    die();
  }

  public function getReportExpenses()
  {
    if ($_SESSION['permitsModule']['r']) {

      if ($_SESSION['permits'][41]['r'] && $_SESSION['userData']['access_boxes'] == 1) {
        // ACCESO A TODAS LAS CAJAS DE TODOS LOS ESTALBECIMIENTOS
        $establishment_id = !empty($_GET['establishment_id']) ? intval($_GET['establishment_id']) : "";
        $box_id = !empty($_GET['box_id']) ? intval($_GET['box_id']) : '';
      } else if ($_SESSION['userData']['access_boxes'] == 1) {
        // ACCESO A TODAS LAS CAJAS DEL ESTABLECIMIENTO LOGEADO
        $establishment_id = $_SESSION['userData']['establishment_id'];
        $box_id = !empty($_GET['box_id']) ? intval($_GET['box_id']) : '';
      } else {
        // ACCESO A LA CAJA REGISTRADA CON EL DISPOSITIVO
        $establishment_id = $_SESSION['userData']['establishment_id'];
        $box_id = $_SESSION['userData']['box_id'];
      }

      $date = !empty($_GET['date']) ? $_GET['date'] : '';

      $arrData = $this->model->selectReportExpenses($date, $establishment_id, $box_id);
      $arrRes = (empty($arrData)) ? 0 : $arrData;

      echo json_encode($arrRes, JSON_UNESCAPED_UNICODE);
    }
    die();
  }

  public function getReportDeposits()
  {
    if ($_SESSION['permitsModule']['r']) {

      if ($_SESSION['permits'][41]['r'] && $_SESSION['userData']['access_boxes'] == 1) {
        // ACCESO A TODAS LAS CAJAS DE TODOS LOS ESTALBECIMIENTOS
        $establishment_id = !empty($_GET['establishment_id']) ? intval($_GET['establishment_id']) : "";
        $box_id = !empty($_GET['box_id']) ? intval($_GET['box_id']) : '';
      } else if ($_SESSION['userData']['access_boxes'] == 1) {
        // ACCESO A TODAS LAS CAJAS DEL ESTABLECIMIENTO LOGEADO
        $establishment_id = $_SESSION['userData']['establishment_id'];
        $box_id = !empty($_GET['box_id']) ? intval($_GET['box_id']) : '';
      } else {
        // ACCESO A LA CAJA REGISTRADA CON EL DISPOSITIVO
        $establishment_id = $_SESSION['userData']['establishment_id'];
        $box_id = $_SESSION['userData']['box_id'];
      }

      $date = !empty($_GET['date']) ? $_GET['date'] : '';

      $arrData = $this->model->selectReportDeposits($date, $establishment_id, $box_id);
      $arrRes = (empty($arrData)) ? 0 : $arrData;

      echo json_encode($arrRes, JSON_UNESCAPED_UNICODE);
    }
    die();
  }

  public function getReportBuys()
  {
    if ($_SESSION['permitsModule']['r']) {

      if ($_SESSION['permits'][41]['r'] && $_SESSION['userData']['access_boxes'] == 1) {
        // ACCESO A TODAS LAS CAJAS DE TODOS LOS ESTALBECIMIENTOS
        $establishment_id = !empty($_GET['establishment_id']) ? intval($_GET['establishment_id']) : "";
        $box_id = !empty($_GET['box_id']) ? intval($_GET['box_id']) : '';
      } else if ($_SESSION['userData']['access_boxes'] == 1) {
        // ACCESO A TODAS LAS CAJAS DEL ESTABLECIMIENTO LOGEADO
        $establishment_id = $_SESSION['userData']['establishment_id'];
        $box_id = !empty($_GET['box_id']) ? intval($_GET['box_id']) : '';
      } else {
        // ACCESO A LA CAJA REGISTRADA CON EL DISPOSITIVO
        $establishment_id = $_SESSION['userData']['establishment_id'];
        $box_id = $_SESSION['userData']['box_id'];
      }

      $date = !empty($_GET['date']) ? $_GET['date'] : '';

      $arrData = $this->model->selectReportBuys($date, $establishment_id, $box_id);
      $arrRes = (empty($arrData)) ? 0 : $arrData;

      echo json_encode($arrRes, JSON_UNESCAPED_UNICODE);
    }
    die();
  }

  public function getReportCustomers()
  {
    if ($_SESSION['permitsModule']['r']) {

      if ($_SESSION['permits'][41]['r'] && $_SESSION['userData']['access_boxes'] == 1) {
        // ACCESO A TODAS LAS CAJAS DE TODOS LOS ESTALBECIMIENTOS
        $establishment_id = !empty($_GET['establishment_id']) ? intval($_GET['establishment_id']) : "";
        $box_id = !empty($_GET['box_id']) ? intval($_GET['box_id']) : '';
      } else if ($_SESSION['userData']['access_boxes'] == 1) {
        // ACCESO A TODAS LAS CAJAS DEL ESTABLECIMIENTO LOGEADO
        $establishment_id = $_SESSION['userData']['establishment_id'];
        $box_id = !empty($_GET['box_id']) ? intval($_GET['box_id']) : '';
      } else {
        // ACCESO A LA CAJA REGISTRADA CON EL DISPOSITIVO
        $establishment_id = $_SESSION['userData']['establishment_id'];
        $box_id = $_SESSION['userData']['box_id'];
      }

      $date = !empty($_GET['date']) ? $_GET['date'] : '';

      $arrData = $this->model->selectReportCustomers($date, $establishment_id, $box_id);
      $arrRes = (empty($arrData)) ? 0 : $arrData;

      echo json_encode($arrRes, JSON_UNESCAPED_UNICODE);
    }
    die();
  }

  public function getReportProducts()
  {
    if ($_SESSION['permitsModule']['r']) {

      if ($_SESSION['permits'][41]['r'] && $_SESSION['userData']['access_boxes'] == 1) {
        // ACCESO A TODAS LAS CAJAS DE TODOS LOS ESTALBECIMIENTOS
        $establishment_id = !empty($_GET['establishment_id']) ? intval($_GET['establishment_id']) : "";
        $box_id = !empty($_GET['box_id']) ? intval($_GET['box_id']) : '';
      } else if ($_SESSION['userData']['access_boxes'] == 1) {
        // ACCESO A TODAS LAS CAJAS DEL ESTABLECIMIENTO LOGEADO
        $establishment_id = $_SESSION['userData']['establishment_id'];
        $box_id = !empty($_GET['box_id']) ? intval($_GET['box_id']) : '';
      } else {
        // ACCESO A LA CAJA REGISTRADA CON EL DISPOSITIVO
        $establishment_id = $_SESSION['userData']['establishment_id'];
        $box_id = $_SESSION['userData']['box_id'];
      }

      $date = !empty($_GET['date']) ? $_GET['date'] : '';

      $arrData = $this->model->selectReportProducts($date, $establishment_id, $box_id);
      $arrRes = (empty($arrData)) ? 0 : $arrData;

      echo json_encode($arrRes, JSON_UNESCAPED_UNICODE);
    }
    die();
  }
}
