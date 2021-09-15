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
        'name' => !empty($_GET['name']) ? strClean($_GET['name']) : '',
        'serie' => !empty($_GET['serie']) ? strClean($_GET['serie']) : '',
        'trademark' => !empty($_GET['trademark']) ? strClean($_GET['trademark']) : '',
        'provider' => !empty($_GET['provider']) ? intval($_GET['provider']) : '',
        'category' => !empty($_GET['category']) ? intval($_GET['category']) : '',
        'cost' => !empty($_GET['cost']) ? strClean($_GET['cost']) : '',
        'pvp' => !empty($_GET['pvp']) ? strClean($_GET['pvp']) : '',
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
        $arrRes = (empty($arrData)) ? 0 : $arrData;

        echo json_encode($arrRes, JSON_UNESCAPED_UNICODE);
      }
    }
    die();
  }

  public function searchProduct($code)
  {
    if ($_SESSION['permitsModule']['r']) {
      $code = strClean(strClean($code));

      $arrData = $this->model->searchProduct($code);

      $arrRes = (empty($arrData)) ? 0 : $arrData;

      echo json_encode($arrRes, JSON_UNESCAPED_UNICODE);
    }
    die();
  }

  public function setProduct()
  {
    //EN PRODUCTOS
    $id = intval($_POST['id']);
    $auxiliary_code = strClean($_POST['auxiliary_code']);
    $ean_code = strClean($_POST['ean_code']);
    $name = strClean($_POST['name']);
    $description = strClean($_POST['description']);
    $trademark = strClean($_POST['trademark']);
    $model = !empty($_POST['model']) ? strClean($_POST['model']) : '';
    $type_product = strClean($_POST['type_product']); //producto - servicio
    $type = strClean($_POST['type']); // original / replica     
    $remanufactured = boolval($_POST['remanufactured']);
    $category_id = intval($_POST['category_id']);
    $discontinued = boolval($_POST['discontinued']);
    $serial = boolval($_POST['serial']);
    $discount = boolval($_POST['discount']);
    $offer = boolval($_POST['offer']);
    $pvp_manual = boolval($_POST['pvp_manual']);
    $iva = floatval($_POST['iva']);
    $cost = floatval($_POST['cost']);
    $pvp_1 = !empty($_POST['pvp_1']) ? floatval($_POST['pvp_1']) : 0;
    $pvp_2 = !empty($_POST['pvp_2']) ? floatval($_POST['pvp_2']) : 0;
    $pvp_3 = !empty($_POST['pvp_3']) ? floatval($_POST['pvp_3']) : 0;
    $pvp_distributor = !empty($_POST['pvp_distributor']) ?  floatval($_POST['pvp_distributor']) : 0;
    $pvp_offer = !empty($_POST['pvp_offer']) ?  floatval($_POST['pvp_offer']) : 0;
    $product_length = !empty($_POST['product_length']) ?  floatval($_POST['product_length']) : 0;
    $product_width = !empty($_POST['product_width']) ?  floatval($_POST['product_width']) : 0;
    $product_height = !empty($_POST['product_height']) ?  floatval($_POST['product_height']) : 0;
    $product_weight = !empty($_POST['product_weight']) ?  floatval($_POST['product_weight']) : 0;
    $box_length = !empty($_POST['box_length']) ?  floatval($_POST['box_length']) : 0;
    $box_width = !empty($_POST['box_width']) ?  floatval($_POST['box_width']) : 0;
    $box_height = !empty($_POST['box_height']) ?  floatval($_POST['box_height']) : 0;
    $box_weight = !empty($_POST['box_weight']) ?  intval($_POST['box_weight']) : 0;
    $box_stacking = !empty($_POST['box_stacking']) ?  intval($_POST['box_stacking']) : 0;
    $status = boolval($_POST['status']) == 1 ? 1 : 2;

    //EN PRODUCTOS FOTOS
    $photos = !empty($_POST['photos']) ? arrClean($_POST['photos'], "int") : [];

    //EN PRODUCTOS SERIES
    $series = !empty($_POST['series']) ? arrClean($_POST['series']) : [];

    //EN PRODUCTOS PROVEEDORES
    $providers = is_array($_POST['providers']) ? arrClean($_POST['providers'], "int") : [];
    //EN PRODUCTOS ENTRADAS 
    $document_id = intval($_POST['document_id']);
    $amount = intval($_POST['amount']);
    $min_stock = intval($_POST['min_stock']);
    $max_stock = intval($_POST['max_stock']);
    //ESTABLECIMIENTO MATRIZ
    $matrix = $_SESSION['userData']['establishment']['company']['matrix_establishment_id'];

    $request = "";
    $response = [];
    if (
      val_ean13($ean_code) &&
      valString($name, 3, 100) &&
      valString($description, 3, 100000) &&
      valString($trademark, 3, 100) &&
      $category_id > 0 &&
      ($type_product == 'producto' || $type_product == 'servicio') &&
      ($type == 'original' || $type == 'réplica') &&
      ($remanufactured == 1 || $remanufactured == 0) &&
      ($serial == 1 || $serial == 0) &&
      ($discount == 1 || $discount == 0) &&
      ($offer == 1 || $offer == 0) &&
      ($pvp_manual == 1 || $pvp_manual == 0) &&
      ($status == 1 || $status == 2) &&
      ($discontinued == 1 || $discontinued == 0) &&
      $amount >= 0 &&
      ($iva == 0 || $iva == 12) &&
      $cost >= 0 &&
      $pvp_1 >= 0 &&
      $pvp_2 >= 0 &&
      $pvp_3 >= 0 &&
      $pvp_distributor >= 0 &&
      $pvp_offer >= 0 &&
      $product_length >= 0 &&
      $product_height >= 0 &&
      $product_width >= 0 &&
      $product_weight >= 0 &&
      $box_length >= 0 &&
      $box_height >= 0 &&
      $box_width >= 0 &&
      $box_stacking >= 0 &&
      $box_weight >= 0
    ) {
      if ($id == 0) {

        if ($_SESSION['permitsModule']['w']) {
          //Crear Producto
          $request = $this->model->insertProduct(
            $auxiliary_code,
            $ean_code,
            $name,
            $description,
            $trademark,
            $model,
            $type_product,
            $type,
            $remanufactured,
            $category_id,
            $discontinued,
            $serial,
            $discount,
            $offer,
            $pvp_manual,
            $iva,
            $amount,
            $cost,
            $pvp_1,
            $pvp_2,
            $pvp_3,
            $pvp_distributor,
            $pvp_offer,
            $product_length,
            $product_width,
            $product_height,
            $product_weight,
            $box_length,
            $box_width,
            $box_height,
            $box_weight,
            $box_stacking,
            $status,
          );
        } else {
          $request = -5;
        }
        $type = 1;
      } else {

        if ($_SESSION['permitsModule']['u']) {
          // Actualizar
          $request = $this->model->updateProduct(
            $auxiliary_code,
            $ean_code,
            $name,
            $description,
            $trademark,
            $model,
            $type_product,
            $type,
            $remanufactured,
            $category_id,
            $discontinued,
            $serial,
            $discount,
            $offer,
            $pvp_manual,
            $iva,
            $cost,
            $pvp_1,
            $pvp_2,
            $pvp_3,
            $pvp_distributor,
            $pvp_offer,
            $product_length,
            $product_width,
            $product_height,
            $product_weight,
            $box_length,
            $box_width,
            $box_height,
            $box_weight,
            $box_stacking,
            $status,
          );
        }
        $type = 2;
      }
      $arrRes = s26_res("Producto", $request, $type);
      array_push($response, $arrRes);
      if ($request > 0) {

        if ($type == 1) {
          //Insertar Entrada
          $request_entry = $this->model->insertEntry(
            $request,
            $amount,
            $cost,
            $document_id
          );

          $arrRes = s26_res("Entrada", $request_entry);
          array_push($response, $arrRes);
          //Insertar Entrada Establecimientos
          $request_entry_establishment = $this->model->insertEntryEstablishment(
            $request,
            $amount,
            $matrix,
            $matrix,
          );
          $arrRes = s26_res("Entrada en Establecimiento", $request_entry_establishment);
          array_push($response, $arrRes);
          //INSERTAR PRODUCTO EN ESTABLECIMIENTOS 
          $request_establishment = $this->model->insertEstablishment(
            $request,
            $amount,
            $min_stock,
            $max_stock,
            $matrix,
          );
          $arrRes = s26_res("Producto en Establecimiento", $request_establishment, 4);
          array_push($response, $arrRes);
          //INSERTAR FOTOS
          $arrPhotos = [];
          if (count($photos) > 0) {
            for ($i = 0; $i < count($photos); $i++) {
              $request_photo = $this->model->insertPhotos($request, $photos[$i]);

              if ($request_photo > 0) {
                array_push($arrPhotos, $i);
              }
            }
          }
          if (count($arrPhotos) > 0) {
            $arrRes = array(
              'type' => 1,
              'msg' => count($arrPhotos) . ' Fotos guardadas correctamente.'
            );
            array_push($response, $arrRes);
          } else {
            $arrRes = array('type' => 0, 'msg' => 'No se ingresaron Fotos, puedes ingresarlas más tarde.');
            array_push($response, $arrRes);
          }
          //INSERTAR SERIES
          $arrSeries = [];
          if (count($series) > 0 && $serial == 1) {
            for ($i = 0; $i < count($series); $i++) {
              $request_series = $this->model->insertSerie($request, $request_entry, $series[$i]);

              if ($request_series > 0) {
                array_push($arrSeries, $i);
              }
            }
          }
          if (count($arrSeries) > 0) {
            $arrRes = array(
              'type' => 1,
              'msg' => count($arrSeries) . ' Series guardadas correctamente.'
            );
            array_push($response, $arrRes);
          } else {
            $arrRes = array('type' => 0, 'msg' => 'No se ingresaron Series, puedes ingresarlas más tarde.');
            array_push($response, $arrRes);
          }
          //INSERTAR PROVEEDORES
          $arrProviders = [];
          if (count($providers) > 0) {
            for ($i = 0; $i < count($providers); $i++) {
              $request_providers = $this->model->insertProvider($request, $providers[$i]);

              if ($request_providers > 0) {
                array_push($arrProviders, $i);
              }
            }
          }
          if (count($arrProviders) > 0) {
            $arrRes = array(
              'type' => 1,
              'msg' => count($arrProviders) . ' Proveedores guardados correctamente.'
            );
            array_push($response, $arrRes);
          } else {
            $arrRes = array('type' => 0, 'msg' => 'No se ingresaron Proveedores, puedes ingresarlos más tarde.');
            array_push($response, $arrRes);
          }
        } else {
        }
      }
    } else {
      $arrRes = array('type' => 0, 'msg' => 'Error al Ingresar datos. Compruebe que los datos ingresados sean correctos');
      array_push($response, $arrRes);
    }
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
    die();
  }

  public function addAmount()
  {

    $product_id = intval($_POST['id']);
    $amount = intval($_POST['amount']);
    $update_pvp = boolval($_POST['update_pvp']);
    $cost = floatval($_POST['cost']);
    $pvp_1 = floatval($_POST['pvp_1']);
    $pvp_2 = floatval($_POST['pvp_2']);
    $pvp_3 = floatval($_POST['pvp_3']);
    $pvp_distributor = floatval($_POST['pvp_distributor']);
    $document_id = intval($_POST['document_id']);
    $matrix = $_SESSION['userData']['establishment']['company']['matrix_establishment_id'];

    $response = [];
    if (
      $product_id > 0 &&
      $amount > 0 &&
      $cost >= 0 &&
      $pvp_1 >= 0 &&
      $pvp_2 >= 0 &&
      $pvp_3 >= 0 &&
      $pvp_distributor >= 0 &&
      $document_id >= -1
    ) {
      if ($_SESSION['permitsModule']['w']) {
        //Insertar Entrada
        $request = $this->model->insertEntry(
          $product_id,
          $amount,
          $cost,
          $document_id
        );
      } else {
        $request = -5;
      }
      $arrRes = s26_res("Entrada", $request);
      array_push($response, $arrRes);

      if ($request > 0) {
        //actualizar costo y stock
        $request = $this->model->updateCost(
          $product_id,
          $amount,
          $cost
        );
        $arrRes = s26_res("Costo", $request, 2);
        array_push($response, $arrRes);
        //actualizar precios 
        if ($update_pvp) {
          $request = $this->model->updatePrices(
            $product_id,
            $pvp_1,
            $pvp_2,
            $pvp_3,
            $pvp_distributor,
          );
          $arrRes = s26_res("Precios", $request, 2);
          array_push($response, $arrRes);
        }

        //Insertar Entrada Establecimientos
        $request_entry_establishment = $this->model->insertEntryEstablishment(
          $product_id,
          $amount,
          $matrix,
          $matrix,
        );
        $arrRes = s26_res("Entrada en Establecimiento", $request_entry_establishment, 2);
        array_push($response, $arrRes);
        //Actualizar Stock en Establecimiento 
        $request_establishment = $this->model->updateEstablishment(
          $product_id,
          $amount,
          $matrix,
        );
        $arrRes = s26_res("Producto en Establecimiento", $request_establishment, 2);
        array_push($response, $arrRes);
      }
    } else {
      $arrRes = array('type' => 0, 'msg' => 'Error al Ingresar datos. Compruebe que los datos ingresados sean correctos');
      array_push($response, $arrRes);
    }
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
    die();
  }
}
