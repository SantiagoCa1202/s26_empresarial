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
    $this->views->getView($this, "media/photos");
  }

  public function getPhotos()
  {
    if ($_SESSION['permitsModule']['r']) {
      $perPage = intval($_GET['perPage']);
      $filter = [
        'id' => !empty($_GET['id']) ? intval($_GET['id']) : '',
        'name' => !empty($_GET['name']) ? strClean($_GET['name']) : '',
        'favorites' => !empty($_GET['favorites']) ? intval($_GET['favorites']) : '',
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
    if ($_SESSION['permitsModule']['r']) {
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
    }
    die();
  }

  public function uploadPhotos()
  {
    if ($_SESSION['permitsModule']['w']) {
      $upload_photos = [];
      if (isset($_FILES["upload_photos_data"]) && $_FILES["upload_photos_data"]["name"][0]) {
        for ($i = 0; $i < count($_FILES["upload_photos_data"]["name"]); $i++) {
          $name = isset($_POST['name_' . $i]) ? strClean($_POST['name_' . $i]) : '';
          $description = isset($_POST['description_' . $i]) ?
            strClean($_POST['description_' . $i]) : '';

          if (
            $_FILES["upload_photos_data"]["type"][$i] == "image/jpeg" ||
            $_FILES["upload_photos_data"]["type"][$i] == "image/pjpeg" ||
            $_FILES["upload_photos_data"]["type"][$i] == "image/png"
          ) {

            if ($_FILES['upload_photos_data']['name'][$i] != '') {
              $type = $_FILES["upload_photos_data"]["type"][$i];
              $photo_type = explode("/", $type);
              $photo_name = 'photo_' . md5(date('d-m-y H:m:s') . $i) . '.' . $photo_type[1];

              $upload = uploadPhoto($_FILES["upload_photos_data"]["tmp_name"][$i], $photo_name);
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
        $error = count($_FILES["upload_photos_data"]["name"]) - count($upload_photos);

        $arrRes = array(
          'status' => true,
          'msg' => $success . ' con éxito, ' . $error . ' con error'
        );
      } else {
        $arrRes = array('status' => false, 'msg' => 'Error al Subir Fotos.');
      }
      echo json_encode($arrRes, JSON_UNESCAPED_UNICODE);
    }
    die();
  }
  public function updatePhoto()
  {
    if ($_SESSION['permitsModule']['u']) {
      $id = intval($_POST['id']);

      $name = !empty($_POST['name']) ? strClean($_POST['name']) : '';
      $description = !empty($_POST['description']) ? strClean($_POST['description']) : '';
      $status = intval($_POST['status']);
      $request = "";
      if (
        valString($name, 0, 1000) &&
        valString($description, 0, 1000) &&
        ($status == 1 || $status == 2)
      ) {
        if ($_SESSION['permitsModule']['u']) {
          // Actualizar
          $request = $this->model->updatePhoto(
            $id,
            $name,
            $description,
            $status
          );
          $type = 2;
        } else {
          $request = -5;
        }
      } else {
        $type = 0;
        $request = -1;
      }
      $arrRes = s26_res("Foto", $request, $type);
      echo json_encode($arrRes, JSON_UNESCAPED_UNICODE);
    }
    die();
  }

  public function addToFavorites($id)
  {
    if ($_SESSION['permitsModule']['u']) {
      $id = intval($id);
      $request = $this->model->addToFavorites($id);
      if ($request == 1) {
        $arrRes = array('type' => true, 'msg' => 'Agregado a favoritos.');
      } else {
        $arrRes = array('type' => false, 'msg' => 'Error al agregar foto a favoritos.');
      }
      echo json_encode($arrRes, JSON_UNESCAPED_UNICODE);
    }
    die();
  }

  public function delPhoto($id)
  {
    if ($_SESSION['permitsModule']['d']) {
      $id = intval($id);
      $request = $this->model->deletePhoto($id);
    } else {
      $request = -5;
    }
    $arrRes = s26_res("Foto", $request, 3);
    echo json_encode($arrRes, JSON_UNESCAPED_UNICODE);
    die();
  }
}
