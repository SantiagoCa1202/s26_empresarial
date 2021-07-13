<?php
class Files extends Controllers
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
  public function files()
  {
    $this->views->getView($this, 'media/files');
  }
  public function getfiles()
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
      $arrData = $this->model->selectFiles($perPage, $filter);

      echo json_encode($arrData, JSON_UNESCAPED_UNICODE);
    }
    die();
  }

  public function getfile($id)
  {
    if ($_SESSION['permitsModule']['r']) {
      $id = intval(strClean($id));
      if ($id > 0) {
        $arrData = $this->model->selectFile($id);
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

  public function uploadFiles()
  {
    if ($_SESSION['permitsModule']['w']) {
      $upload_files = [];

      if (count($_FILES["upload_files_data"]["name"]) <= 10) {

        if (isset($_FILES["upload_files_data"]) && $_FILES["upload_files_data"]["name"][0]) {
          for ($i = 0; $i < count($_FILES["upload_files_data"]["name"]); $i++) {
            $name = isset($_POST['name_' . $i]) ? strClean($_POST['name_' . $i]) : '';
            $description = isset($_POST['description_' . $i]) ?
              strClean($_POST['description_' . $i]) : '';

            if (
              $_FILES["upload_files_data"]["type"][$i] == "application/pdf"
            ) {

              if ($_FILES['upload_files_data']['name'][$i] != '') {
                $type = $_FILES["upload_files_data"]["type"][$i];
                $file_type = explode("/", $type);
                $file_name = 'file_' . md5(date('d-m-y H:m:s') . $i) . '.' . $file_type[1];

                $type = $file_type[1] == 'pdf' ? 'pdf' : '';
                $upload = uploadFile($_FILES["upload_files_data"]["tmp_name"][$i], $file_name);
                if ($upload) {
                  //Subir Foto
                  $request = $this->model->insertFile(
                    $name,
                    $description,
                    $file_name,
                    $type
                  );
                  if ($request > 0) {
                    array_push($upload_files, $i);
                  }
                }
              }
            }
          }
          if (count($upload_files) > 0) {
            $success = count($upload_files);
            $error = count($_FILES["upload_files_data"]["name"]) - count($upload_files);

            $arrRes = array(
              'status' => true,
              'msg' => $success . ' con éxito, ' . $error . ' con error'
            );
          } else {
            $arrRes = array('status' => false, 'msg' => 'Error al Subir Archivos.');
          }
        }
      } else {
        $arrRes = array('status' => false, 'msg' => 'Maximo 10 Archivos.');
      }

      echo json_encode($arrRes, JSON_UNESCAPED_UNICODE);
    }
    die();
  }
  public function updateFile()
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
          $request = $this->model->updateFile(
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
      $arrRes = s26_res("Archivo", $request, $type);
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

  public function delFile($id)
  {
    if ($_SESSION['permitsModule']['d']) {
      $id = intval($id);
      $request = $this->model->deleteFile($id);
    } else {
      $request = -5;
    }
    $arrRes = s26_res("Archivo", $request, 3);
    echo json_encode($arrRes, JSON_UNESCAPED_UNICODE);
    die();
  }
}
