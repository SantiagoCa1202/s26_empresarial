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
        'variants' => !empty($_GET['variants']) ? intval($_GET['variants']) : '',
        'sku' => !empty($_GET['sku']) ? strClean($_GET['sku']) : '',
        'product' => !empty($_GET['product']) ? strClean($_GET['product']) : '',
        'model' => !empty($_GET['model']) ? strClean($_GET['model']) : '',
        'trademark' => !empty($_GET['trademark']) ? strClean($_GET['trademark']) : '',
        'provider' => !empty($_GET['provider']) ? intval($_GET['provider']) : '',
        'category' => !empty($_GET['category']) ? strClean($_GET['category']) : '',
        'pvp' => !empty($_GET['pvp']) ? floatval($_GET['pvp']) : '',
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

        echo json_encode($arrRes, JSON_NUMERIC_CHECK);
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

  // OBTENER VARIANTES 
  public function getVariants($id)
  {
    if ($_SESSION['permitsModule']['r']) {
      $arrData = $this->model->selectVariants($id);
      echo json_encode($arrData, JSON_UNESCAPED_UNICODE);
    }
    die();
  }

  public function getVariant($id)
  {
    if ($_SESSION['permitsModule']['r']) {
      $id = intval(strClean($id));
      if ($id > 0) {
        $arrData = $this->model->selectVariant($id);
        $arrRes = (empty($arrData)) ? 0 : $arrData;

        echo json_encode($arrRes, JSON_NUMERIC_CHECK);
      }
    }
    die();
  }

  // OBTENER PROVEEDORES 
  public function getProviders($id)
  {
    if ($_SESSION['permitsModule']['r']) {
      $arrData = $this->model->selectProviders($id);
      echo json_encode($arrData, JSON_UNESCAPED_UNICODE);
    }
    die();
  }

  // OBTENER SERIES 
  public function getProductSeries()
  {
    if ($_SESSION['permitsModule']['r']) {
      $filter = [
        'product_id' => !empty($_GET['product_id']) ? intval($_GET['product_id']) : '',
        'search_serie' => !empty($_GET['search_serie']) ? strClean($_GET['search_serie']) : '',
      ];

      $arrData = $this->model->selectProductSeries($filter);

      echo json_encode($arrData, JSON_UNESCAPED_UNICODE);
    }
    die();
  }

  ////////////////////////////////////////////////////////////////////////////

  /////////////// Reporte de Producto
  public function  reportProduct()
  {
    if ($_SESSION['permitsModule']['r']) {

      $id = intval($_GET['id']);
      $type = strClean($_GET['type']);
      $arrData = $this->model->selectReportProduct($id, $type);
      echo json_encode($arrData, JSON_UNESCAPED_UNICODE);
    }
    die();
  }

  public function setProduct()
  {
    //EN PRODUCTOS
    $id = intval($_POST['id']);
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
    $pvp_manual = boolval($_POST['pvp_manual']);
    $iva = floatval($_POST['iva']);

    //EN PRODUCTOS SERIES
    $series = !empty($_POST['series']) ? arrClean($_POST['series']) : [];

    //EN PRODUCTOS VARIANTES
    $variants = !empty($_POST['variants']) ? $_POST['variants'] : [];

    //EN PRODUCTOS PROVEEDORES
    $providers = is_array($_POST['providers']) ? arrClean($_POST['providers'], "int") : [];
    //EN PRODUCTOS ENTRADAS 
    $document_id = !empty($_POST['document_id']) ? intval($_POST['document_id']) : '';

    //ESTABLECIMIENTO MATRIZ
    $matrix = $_SESSION['userData']['establishment']['company']['matrix_establishment_id'];

    $request = "";
    $response = [];
    if (
      valString($name, 3, 100) &&
      valString($description, 3, 100000) &&
      $category_id > 0 &&
      ($type_product == 'producto' || $type_product == 'servicio') &&
      ($type == 'original' || $type == 'réplica') &&
      ($remanufactured == 1 || $remanufactured == 0) &&
      ($serial == 1 || $serial == 0) &&
      ($discount == 1 || $discount == 0) &&
      ($pvp_manual == 1 || $pvp_manual == 0) &&
      ($discontinued == 1 || $discontinued == 0) &&
      ($iva == 0 || $iva == 12) &&
      (count($variants) > 0 && $id == 0 || count($variants) == 0 && $id > 0)
    ) {
      if ($id == 0) {

        if ($_SESSION['permitsModule']['w']) {
          //Crear Producto
          $request = $this->model->insertProduct(
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
            $pvp_manual,
            $iva,
          );
        } else {
          $request = -5;
        }
        $type = 1;
      } else {

        //ACTUALIZAR PRODUCTO
        if ($_SESSION['permitsModule']['u']) {

          $request = $this->model->updateProduct(
            $id,
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
            $pvp_manual,
            $iva,
          );
        } else {
          $request = -5;
        }
        $type = 2;
      }

      $arrRes = s26_res("Producto", $request, $type);
      array_push($response, $arrRes);
      if ($request > 0) {

        if ($type == 1) { //NUEVO PRODUCTO

          //STOCK A INGRESAR
          $amount = 0;
          for ($i = 0; $i < count($variants); $i++) {
            $amount += $variants[$i]['amount'];
          }

          // INSERTAR VARIANTES
          $res_variant = [];
          $res_variants = [];
          $arrPhotos = [];

          for ($i = 0; $i < count($variants); $i++) {
            // INSERTAR VARIANTES
            $request_variant = $this->model->insertVariant(
              $request,
              strClean($variants[$i]['code']),
              strClean($variants[$i]['sku']),
              intval($variants[$i]['amount']),
              intval($variants[$i]['min_stock']),
              intval($variants[$i]['max_stock']),
              floatval($variants[$i]['cost']),
              floatval($variants[$i]['pvp_1']),
              floatval($variants[$i]['pvp_2']),
              floatval($variants[$i]['pvp_3']),
              floatval($variants[$i]['pvp_distributor']),
              intval($variants[$i]['variants']['color_id']),
              strClean($variants[$i]['variants']['size']),
              strClean($variants[$i]['variants']['fragance']),
              strClean($variants[$i]['variants']['net_content']),
              strClean($variants[$i]['variants']['shape']),
              strClean($variants[$i]['variants']['package']),
              strClean($variants[$i]['additional_info']),
            );

            if ($request_variant > 0) {
              array_push($res_variant, $i);

              //DIMESIONES
              $request_variants_dimensions = $this->model->insertVariantDimensions(
                $request_variant,
                floatval($variants[$i]['variants']['dimensions']['product_length']),
                floatval($variants[$i]['variants']['dimensions']['product_height']),
                floatval($variants[$i]['variants']['dimensions']['product_width']),
                floatval($variants[$i]['variants']['dimensions']['product_weight']),
                floatval($variants[$i]['variants']['dimensions']['box_length']),
                floatval($variants[$i]['variants']['dimensions']['box_height']),
                floatval($variants[$i]['variants']['dimensions']['box_width']),
                floatval($variants[$i]['variants']['dimensions']['box_weight']),
                floatval($variants[$i]['variants']['dimensions']['box_stacking']),
              );
              if ($request_variant > 0) {
                array_push($res_variants, $request_variants_dimensions);
              }

              // INSERTAR ENTRADA VARIANTE
              $request_entry_variant = $this->model->insertEntryVariant(
                $request_variant,
                intval($variants[$i]['amount']),
                floatval($variants[$i]['cost']),
                intval($document_id)
              );
              if ($request_variant > 0) {
                array_push($res_variants, $request_entry_variant);
              }
              // INSERTAR VARIANTE EN ESTABLECIMIENTO
              $request_variant_establishment = $this->model->insertVariantEstablishment($request_variant, $matrix, intval($variants[$i]['amount']));

              if ($request_variant_establishment > 0) {
                array_push($res_variants, $request_variant_establishment);

                //Insertar Entrada Establecimientos
                $request_entry_establishment = $this->model->insertEntryEstablishment(
                  $request_variant,
                  intval($variants[$i]['amount']),
                  $matrix,
                  $matrix,
                );
                array_push($res_variants, $request_entry_establishment);
              }

              // INSERTAR FOTOS DE VARIANTES
              $variants_photos = !empty($variants[$i]['photos']) ? $variants[$i]['photos'] : [];
              if (count($variants_photos) > 0) {
                for ($p = 0; $p < count($variants[$i]['photos']); $p++) {
                  $request_photo = $this->model->insertPhotos($request_variant, intval($variants[$i]['photos'][$p]));

                  if ($request_photo > 0) {
                    array_push($arrPhotos, $p);
                  }
                }
              }
            }
          }
          // RESPUESTA VARINTE
          if (count($res_variant) > 0) {
            $arrRes = array(
              'type' => 1,
              'msg' => count($res_variant) . ' variantes guardadas correctamente.'
            );
            array_push($response, $arrRes);
          } else {
            $arrRes = array('type' => 0, 'msg' => 'Error de Sistema, notificalo al personal a cargo.');
            array_push($response, $arrRes);
          }
          // RESPUESTA VARIANTES 
          if (count($res_variants) == count($variants) * 4) {
            $arrRes = array(
              'type' => 1,
              'msg' => 'Variantes de Producto guardadas correctamente.'
            );
            array_push($response, $arrRes);
          } else {
            $arrRes = array('type' => 0, 'msg' => 'Error de Sistema, notificalo al personal a cargo.');
            array_push($response, $arrRes);
          }
          //RESPUESTA FOTOS 
          if (count($arrPhotos) > 0) {
            $arrRes = array(
              'type' => 1,
              'msg' => count($arrPhotos) . ' Fotos de ' . count($variants) .  ' variantes, guardadas correctamente.'
            );
            array_push($response, $arrRes);
          } else {
            $arrRes = array('type' => 2, 'msg' => 'No se ingresaron Fotos, puedes ingresarlas más tarde.');
            array_push($response, $arrRes);
          }

          //INSERTAR SERIES
          $arrSeries = [];
          if (count($series) > 0 && $serial == 1) {
            for ($i = 0; $i < count($series); $i++) {
              $request_series = $this->model->insertSerie(
                $request,
                intval($document_id),
                strClean($series[$i])
              );

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
            $arrRes = array('type' => 2, 'msg' => 'No se ingresaron Series, puedes ingresarlas más tarde.');
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
            $arrRes = array('type' => 2, 'msg' => 'No se ingresaron Proveedores, puedes ingresarlos más tarde.');
            array_push($response, $arrRes);
          }
        } else if ($type == 2) {
          //ELIMINAR PROVEEDORES 
          $this->model->deleteProviders($id);
          //INSERTAR PROVEEDORES
          $arrProviders = [];
          if (count($providers) > 0) {
            for ($i = 0; $i < count($providers); $i++) {
              $request_providers = $this->model->insertProvider($id, $providers[$i]);

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
            $arrRes = array('type' => 2, 'msg' => 'No se ingresaron Proveedores, puedes ingresarlos más tarde.');
            array_push($response, $arrRes);
          }
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

    $variant_id = intval($_POST['id']);
    $product_id = intval($_POST['product_id']);
    $amount = intval($_POST['amount']);
    $update_pvp = boolval($_POST['update_pvp']);
    $cost = floatval($_POST['cost']);
    $pvp_1 = floatval($_POST['pvp_1']);
    $pvp_2 = floatval($_POST['pvp_2']);
    $pvp_3 = floatval($_POST['pvp_3']);
    $pvp_distributor = floatval($_POST['pvp_distributor']);
    $document_id = intval($_POST['document_id']);
    $serial = boolval($_POST['serial']);
    
    //EN PRODUCTOS SERIES
    $series = !empty($_POST['series']) ? arrClean($_POST['series']) : [];

    $matrix = $_SESSION['userData']['establishment']['company']['matrix_establishment_id'];

    $response = [];
    if (
      $variant_id > 0 &&
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
        // Insertar Entrada Variante
        $request_variant = $this->model->insertEntryVariant(
          intval($variant_id),
          intval($amount),
          floatval($cost),
          intval($document_id)
        );


        if ($request_variant > 0) {
          //actualizar costo y stock
          $request = $this->model->updateCost(
            $variant_id,
            $amount,
            $cost
          );
          $arrRes = s26_res("Costo", $request, 2);
          array_push($response, $arrRes);
          //actualizar precios 
          if ($update_pvp) {
            $request = $this->model->updatePrices(
              $variant_id,
              $pvp_1,
              $pvp_2,
              $pvp_3,
              $pvp_distributor,
            );
            $arrRes = s26_res("Precios", $request, 2);
            array_push($response, $arrRes);
          }

          //INSERTAR SERIES
          $arrSeries = [];
          if (count($series) > 0 && $serial == 1) {
            for ($i = 0; $i < count($series); $i++) {
              $request_series = $this->model->insertSerie(
                $request,
                intval($document_id),
                strClean($series[$i])
              );

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
            $arrRes = array('type' => 2, 'msg' => 'No se ingresaron Series, puedes ingresarlas más tarde.');
            array_push($response, $arrRes);
          }

          //Insertar Entrada Establecimientos
          $request_entry_establishment = $this->model->insertEntryEstablishment(
            $variant_id,
            $amount,
            $matrix,
            $matrix,
          );
          $arrRes = s26_res("Entrada en Establecimiento", $request_entry_establishment, 2);
          array_push($response, $arrRes);
        }
      } else {
        $request = -5;
        $request_variant = -5;
      }
      $arrRes = s26_res("Entrada de Producto", $request);
      array_push($response, $arrRes);

      $arrRes = s26_res("Entrada de Variante", $request_variant);
      array_push($response, $arrRes);
    } else {
      $arrRes = array('type' => 0, 'msg' => 'Error al Ingresar datos. Compruebe que los datos ingresados sean correctos');
      array_push($response, $arrRes);
    }
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
    die();
  }

  public function toggleProduct()
  {
    if ($_SESSION['permitsModule']['u']) {
      $variants = $_POST['variants']['items'];
      $response = [];
      $arrProd = [];
      foreach ($variants as $variant) {
        $establishment_variant = $variant['establishment_stock']['items'];
        foreach ($establishment_variant as $estab) {
          if ($estab['stock'] == 0) {
            $id = intval($estab['id']);
            array_push($arrProd, $id);
            $status = $estab['status'] == 'true' ? 1 : 0;
            $request = $this->model->updateStatus(
              $id,
              $status
            );
            if ($request > 0) {
              array_push($response, $request);
            };
          }
        }
      }
      if (count($response) == count($arrProd)) {
        $arrRes = s26_res("Estado de Producto", 1, 1);
        echo json_encode($arrRes, JSON_UNESCAPED_UNICODE);
      } else {
        $arrRes = array('type' => 0, 'msg' => 'Error al Actualizar los Datos.');;
        echo json_encode($arrRes, JSON_UNESCAPED_UNICODE);
      }
    }
    die();
  }
}
