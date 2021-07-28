<?php
if (!empty($_SESSION['permitsModule']['r'])) {
  if ($data['type'] == 'excel') {
    header('Content-type: application/vnd.ms-excel');
    header('Content-Disposition: attachment; filename=retenciones.xls');
    header('Pragma: no-cache');
    header('Expires: 0');
  };
?>

  <style>
    table {
      border-spacing: 0;
    }

    table caption {
      font-weight: 600;
      font-size: 1.5rem;
    }

    table tr th,
    table tr td {
      padding: .2rem .5rem;
    }
  </style>

  <table border=1>
    <caption>Informe de Retenciones</caption>
    <thead>
      <tr>
        <th>
          Id
        </th>
        <th>
          <?= $data['type'] == 'excel' ? utf8_decode("Fecha de Emisión") : 'Fecha de Emisión'; ?>
        </th>
        <th>
          Ruc
        </th>
        <th>
          <?= $data['type'] == 'excel' ? utf8_decode("Razón social") : 'Razón social'; ?>
        </th>
        <th>
          <?= $data['type'] == 'excel' ? utf8_decode("Descripción") : 'Descripción'; ?>
        </th>
        <th>
          N° de doc.
        </th>
        <th>
          <?= $data['type'] == 'excel' ? utf8_decode("N° de Autorización") : 'N° de Autorización'; ?>
        </th>
        <th>
          Ret. Iva
        </th>
        <th>
          Ret. Imp. Rent.
        </th>
        <th>
          Total
        </th>
      </tr>
    </thead>
    <tbody>
      <?php
      for ($i = 0; $i < count($data['data']['items']); $i++) {
      ?>
        <tr>
          <th><?= $data['data']['items'][$i]['id'] ?></th>
          <td>
            <?=
            date("d/m/Y G:i:s", strtotime($data['data']['items'][$i]['date_issue']));
            ?>
          </td>
          <td><?= $data['data']['items'][$i]['document'] ?></td>
          <td><?= $data['data']['items'][$i]['business_name'] ?></td>
          <td><?= $data['data']['items'][$i]['description'] ?></td>
          <td><?= $data['data']['items'][$i]['n_document'] ?></td>
          <td><?= $data['data']['items'][$i]['n_authorization'] ?></td>
          <td><?= $data['data']['items'][$i]['ret_iva'] ?></td>
          <td><?= $data['data']['items'][$i]['ret_imp_rent'] ?></td>
          <td><?= $data['data']['items'][$i]['total'] ?></td>
        </tr>
      <?php
      }
      ?>
    </tbody>
    <tfoot>
      <tr>
        <th colspan="2">Registros</th>
        <td colspan="8" style="text-align:right;"><?= $data['data']['info']['count']; ?></td>
      </tr>
    </tfoot>
    </tfoot>
  </table>
<?php
}
?>