<?php

class Documents extends Controllers
{
  public function __construct()
  {
    parent::__construct();

    session_start();
    if (empty($_SESSION['login'])) {
      header('Location: ' . base_url() . '/login');
    }
    getPermits(40);
  }

  public function documents()
  {
    $this->views->getView($this, "documents");
  }

  public function getDocuments()
  {
    if ($_SESSION['permitsModule']['r']) {
      $perPage = intval($_GET['perPage']);
      $filter = [
        'type_doc_id' => !empty($_GET['type_doc_id']) ? intval($_GET['type_doc_id']) : '',
        'n_point' => !empty($_GET['n_point']) ? strClean($_GET['n_point']) : '',
        'status' => !empty($_GET['status']) ? intval($_GET['status']) : '',
      ];
      $arrData = $this->model->selectDocuments($perPage, $filter);

      echo json_encode($arrData, JSON_UNESCAPED_UNICODE);
    }
    die();
  }

  public function getDocument($id)
  {
    if ($_SESSION['permitsModule']['r']) {
      $id = intval(strClean($id));
      if ($id > 0) {
        $arrData = $this->model->selectDocument($id);
        $arrRes = (empty($arrData)) ? 0 : $arrData;

        echo json_encode($arrRes, JSON_UNESCAPED_UNICODE);
      }
    }
    die();
  }

  public function setDocument()
  {

    $id = intval($_POST['id']);
    $document_id = intval($_POST['document_id']);
    $n_point = intval($_POST['n_point']);
    $sequential_numbering = intval($_POST['sequential_numbering']);
    $print = intval($_POST['print']);
    $legend = strClean($_POST['legend']);
    $location_legend = strClean($_POST['location_legend']);
    $size = intval($_POST['size']);
    $status = intval($_POST['status']);
    if ($id == 0) {

      $n_authorization = bigintval($_POST['n_authorization']);
      $from = intval($_POST['from']);
      $to = intval($_POST['to']);
      $authorization_date = strClean($_POST['authorization_date']);
      $expiration_date = strClean($_POST['expiration_date']);
    } else {
      $n_authorization = "";
      $from = "";
      $to = "";
      $authorization_date = "";
      $expiration_date = "";
    }

    $establishment_id = $_SESSION['userData']['establishment_id'];

    $val_auth = false;

    if ((strlen($n_authorization) == 10 || strlen($n_authorization) == 49) &&
      $from > 0 && $to > 0 && $to > $from &&
      $sequential_numbering >= $from && $sequential_numbering < $to &&
      val_date($authorization_date) &&
      val_date($expiration_date)
    ) {
      $val_auth = true;
    }
    $request = "";
    if (
      $document_id > 0 &&
      $n_point > 0 &&
      $sequential_numbering > 0 &&
      ($print == 1 || $print == 2) &&
      ($size == 1 || $size == 2 || $size == 3) &&
      ($id == 0 && $val_auth || $id > 0 && $val_auth == false) &&
      ($status == 1 || $status == 2)
    ) {
      if ($id == 0) {

        if ($_SESSION['permitsModule']['w']) {
          //Crear Punto de Emision
          $request = $this->model->insertDocument(
            $document_id,
            $establishment_id,
            $n_point,
            $sequential_numbering,
            $print,
            $size,
            $legend,
            $location_legend,
            $status,
          );
        } else {
          $request = -5;
        }
        $type = 1;
      } else {

        if ($_SESSION['permitsModule']['u']) {
          // Actualizar
          $request = $this->model->updateDocument(
            $id,
            $document_id,
            $establishment_id,
            $n_point,
            $sequential_numbering,
            $print,
            $size,
            $legend,
            $location_legend,
            $status,
          );
        } else {
          $request = -5;
        }
        $type = 2;
      }
      if ($request > 0) {
        if ($type == 1) {
          //INSERTAR AUTORIZACION
          $request_auth = $this->model->insertAuthorization(
            $request,
            $n_authorization,
            $from,
            $to,
            $authorization_date,
            $expiration_date,
          );
        }
      }
    } else {
      $type = 0;
      $request = -1;
    }
    $arrRes = s26_res("Documento", $request, $type);
    echo json_encode($arrRes, JSON_UNESCAPED_UNICODE);
    die();
  }

  public function delDocument(int $id)
  {
    if ($_SESSION['permitsModule']['d']) {
      $id = intval($id);
      $request = $this->model->deleteDocument($id);
    } else {
      $request = -5;
    }
    $arrRes = s26_res("Documento", $request, 3);
    echo json_encode($arrRes, JSON_UNESCAPED_UNICODE);
    die();
  }

  //AUTORIZACIONES
  public function getAuthorizations()
  {
    if ($_SESSION['permitsModule']['r']) {
      $perPage = !empty($_GET['perPage']) ? intval($_GET['perPage']) : '';
      $filter = [
        'emission_point_id' => isset($_GET['emission_point_id']) ? $_GET['emission_point_id'] : '',
        'n_authorization' => isset($_GET['n_authorization']) ? $_GET['n_authorization'] : '',
      ];
      $arrData = $this->model->selectAuthorizations($perPage, $filter);
      echo json_encode($arrData, JSON_UNESCAPED_UNICODE);
    }
    die();
  }

  public function getAuthorization($id)
  {
    if ($_SESSION['permitsModule']['r']) {
      $id = intval(strClean($id));
      if ($id > 0) {
        $arrData = $this->model->selectAuthorization($id);
        $arrRes = (empty($arrData)) ? 0 : $arrData;

        echo json_encode($arrRes, JSON_UNESCAPED_UNICODE);
      }
    }
    die();
  }

  public function setAuthorization()
  {
    $id = intval($_POST['id']);
    $emission_point_id = intval($_POST['emission_point_id']);
    $authorization = bigintval($_POST['authorization']);
    $from = intval($_POST['from_']);
    $to = intval($_POST['to_']);
    $authorization_date = strClean($_POST['authorization_date']);
    $expiration_date = strClean($_POST['expiration_date']);

    $request = "";
    if (
      (strlen($authorization) == 10 || strlen($authorization) == 49) &&
      $from > 0 && $to > 0 && $to > $from &&
      val_date($authorization_date) &&
      val_date($expiration_date)
    ) {
      if ($id == 0) {

        if ($_SESSION['permitsModule']['w']) {
          //Crear Nueva Autorizacion
          $request = $this->model->insertAuthorization(
            $emission_point_id,
            $authorization,
            $from,
            $to,
            $authorization_date,
            $expiration_date,
          );
        } else {
          $request = -5;
        }
        $type = 1;
      } else {

        if ($_SESSION['permitsModule']['u']) {
          // Actualizar
          $request = $this->model->updateAuthorization(
            $id,
            $authorization,
            $from,
            $to,
            $authorization_date,
            $expiration_date,
          );
        } else {
          $request = -5;
        }
        $type = 2;
      }
    } else {
      $type = 0;
      $request = -1;
    }
    $arrRes = s26_res("Autorización", $request, $type);
    echo json_encode($arrRes, JSON_UNESCAPED_UNICODE);
    die();
  }
}
