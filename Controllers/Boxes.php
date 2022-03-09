<?php
class Boxes extends Controllers
{
  public function __construct()
  {
    parent::__construct();
    session_start();
    if (empty($_SESSION['login'])) {
      header('Location: ' . base_url() . '/login');
    }
    getPermits(12);
  }
  public function boxes()
  {
    $this->views->getView($this, 'boxes');
  }

  public function getBoxes()
  {
    if ($_SESSION['permitsModule']['r']) {
      $perPage = intval($_GET['perPage']);

      if ($_SESSION['permits'][41]['r'] && $_SESSION['userData']['access_boxes'] == 1) {
        // ACCESO A CAJAS TODOS LOS ESTALBECIMIENTOS
        $establishment_id = !empty($_GET['establishment_id']) ? intval($_GET['establishment_id']) : "";
        $box_id = !empty($_GET['id']) ? intval($_GET['id']) : '';
      } else if ($_SESSION['userData']['access_boxes'] == 1) {
        // ACCESO A CAJA DEL ESTABLECIMIENTO LOGEADO
        $establishment_id = $_SESSION['userData']['establishment_id'];
        $box_id = !empty($_GET['id']) ? intval($_GET['id']) : '';
      } else {
        // ACCESO A LA CAJA REGISTRADA CON EL DISPOSITIVO
        $establishment_id = $_SESSION['userData']['establishment_id'];
        $box_id = $_SESSION['userData']['device']['box_id'];
      }

      $filter = [
        'id' => $box_id,
        'name' => !empty($_GET['name']) ? strClean($_GET['name']) : '',
        'status' => !empty($_GET['status']) ? intval($_GET['status']) : '',
        'cash' => !empty($_GET['cash']) ? intval($_GET['cash']) : '',
        'establishment_id' => $establishment_id,
      ];
      $arrData = $this->model->selectBoxes($perPage, $filter);

      echo json_encode($arrData, JSON_UNESCAPED_UNICODE);
    }
    die();
  }

  public function getBox($id = '')
  {
    if ($_SESSION['permitsModule']['r']) {
      $id = $id != '' ? $id : intval($_GET['id']);
      $cash = !empty($_GET['cash']) ? intval($_GET['cash']) : ''; 
      if ($id > 0) {
        $arrData = $this->model->selectBox($id,$cash);
        $arrRes = (empty($arrData)) ? 0 : $arrData;

        echo json_encode($arrRes, JSON_UNESCAPED_UNICODE);
      }
    }
    die();
  }

  public function setBox()
  {
    
    $id = intval($_POST['id']);
    $name = strClean($_POST['name']);
    $cash = floatval($_POST['cash']);
    $status = !empty($_POST['status']) ? intval($_POST['status']) : 1;

    $request = "";
    if (
      $status == 1 || $status == 2
    ) {
      if ($id == 0) {

        if ($_SESSION['permitsModule']['w']) {
          //Crear
          $request = $this->model->insertBox(
            $name,
            $cash,
            $status
          );
          $type = 1;
        } else {
          $request = -5;
        }
      } else {

        if ($_SESSION['permitsModule']['u']) {
          // Actualizar
          $request = $this->model->updateBox(
            $id,
            $name,
            $cash,
            $status
          );
          $type = 2;
        } else {
          $request = -5;
        }
      }
    } else {
      $type = 0;
      $request = -1;
    }
    $arrRes = s26_res("Caja", $request, $type);
    echo json_encode($arrRes, JSON_UNESCAPED_UNICODE);
    die();
  }

  public function delBox(int $id)
  {
    if ($_SESSION['permitsModule']['d']) {
      $id = intval($id);
      $request = $this->model->deleteBox($id);
    } else {
      $request = -5;
    }
    $arrRes = s26_res("Caja", $request, 3);
    echo json_encode($arrRes, JSON_UNESCAPED_UNICODE);
    die();
  }
}
