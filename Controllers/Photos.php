<?php

class Photos extends Controllers
{
  public function __construct()
  {
    parent::__construct();

    session_start();
    if (empty($_SESSION['login'])) {
      header('Location: ' . base_url() . '/login');
    }
    getPermits(47);
  }

  public function photos()
  {
    $this->views->getView($this, "photos");
  }

  public function getPhotos()
  {
    if ($_SESSION['permitsModule']['r']) {
      $perPage = intval($_GET['perPage']);
      $filter = [
        'id' => !empty($_GET['id']) ? intval($_GET['id']) : '',
        'name' => !empty($_GET['name']) ? strClean($_GET['name']) : '',
        'description' => !empty($_GET['description']) ? strClean($_GET['description']) : '',
        'status' => !empty($_GET['status']) ? intval($_GET['status']) : '',
        'date' => !empty($_GET['date']) ? $_GET['date'] : '',
      ];
      $arrData = $this->model->selectPhotos($perPage, $filter);

      echo json_encode($arrData, JSON_UNESCAPED_UNICODE);
    }
    die();
  }

  public function getPhoto($id)
  { 
    $id = intval($id);
    if ($id > 0) {
      $arrData = $this->model->selectPhoto($id);
      if (empty($arrData)) {
        $arrRes = 0;
      } else {
        $arrRes = $arrData;
      }
      echo json_encode($arrRes, JSON_UNESCAPED_UNICODE);
    }
    die();
  }

  public function setPhotos()
  {
    $upload_photos = [];
    if (isset($_FILES["files"]) && $_FILES["files"]["name"][0]) {
      for ($i = 0; $i < count($_FILES["files"]["name"]); $i++) {
        $name = isset($_POST['name_' . $i]) ? strClean($_POST['name_' . $i]) : '';
        $description = isset($_POST['description_' . $i]) ?
          strClean($_POST['description_' . $i]) : '';

        if (
          $_FILES["files"]["type"][$i] == "image/jpeg" ||
          $_FILES["files"]["type"][$i] == "image/pjpeg" ||
          $_FILES["files"]["type"][$i] == "image/png"
        ) {

          if ($_FILES['files']['name'][$i] != '') {
            $type = $_FILES["files"]["type"][$i];
            $photo_type = explode("/", $type);
            $photo_name = 'photo_' . md5(date('d-m-y H:m:s') . $i) . '.' . $photo_type[1];

            $upload = uploadPhoto($_FILES["files"]["tmp_name"][$i], $photo_name);
            if ($upload) {
              //Subir Foto
              $request = $this->model->insertPhoto(
                $name,
                $description,
                $photo_name
              );
              if ($request > 0) {
                array_push($upload_photos, $i);
              }
            }
          }
        }
      }
    }
    if (count($upload_photos) > 0) {
      $success = count($upload_photos);
      $error = count($_FILES["files"]["name"]) - count($upload_photos);

      $arrRes = array(
        'status' => true,
        'msg' => $success . ' con éxito, ' . $error . ' con error'
      );
    } else {
      $arrRes = array('status' => false, 'msg' => 'Error al Subir Fotos.');
    }

    echo json_encode($arrRes, JSON_UNESCAPED_UNICODE);
    die();
  }

  public function delPhoto(int $id)
  {
    if ($_SESSION['permitsModule']['d']) {
      $id = intval($id);
      $requestDelete = $this->model->deletePhoto($id);
      if ($requestDelete == 1) {
        $arrRes = array('type' => true, 'msg' => 'Categoria Eliminada.');
      } else {
        $arrRes = array('type' => false, 'msg' => 'Error al eliminar Categoria.');
      }
      echo json_encode($arrRes, JSON_UNESCAPED_UNICODE);
    }
    die();
  }

  public function exportPhotos()
  {
    if ($_SESSION['permitsModule']['r']) {
      $perPage = intval($_GET['perPage']);
      $filter = [
        'id' => !empty($_GET['id']) ? intval($_GET['id']) : '',
        'name' => !empty($_GET['name']) ? strClean($_GET['name']) : '',
        'description' => !empty($_GET['description']) ? strClean($_GET['description']) : '',
        'status' => !empty($_GET['status']) ? intval($_GET['status']) : '',
        'date' => !empty($_GET['date']) ? $_GET['date'] : '',
      ];

      $data['data'] = $this->model->selectCategories($perPage, $filter);
      $data['type'] = $_GET['type'];
      $this->views->exportData("categories", $data);
    }
    die();
  }
}
