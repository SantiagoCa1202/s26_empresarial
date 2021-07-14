<?php
if (!empty($_SESSION['permitsModule']['r'])) {
  if ($data['type'] == 'excel') {
    header('Content-type: application/vnd.ms-excel');
    header('Content-Disposition: attachment; filename=cuentas bancarias.xls');
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
    <caption>Informe de Cuentas Bancarias</caption>
    <thead>
      <tr>
        <th>
          id
        </th>
        <th>
          Entidad Bancaria
        </th>
        <th>
          <?= $data['type'] == 'excel' ? utf8_decode("Número de Cuenta") : 'Número de Cuenta'; ?>
        </th>
        <th>
          Tipo de Cuenta
        </th>
        <th>
          Chequera
        </th>
        <th>
          Fecha de Registro
        </th>
        <th>
          Estado
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
            <?= $data['data']['items'][$i]['bank_entity']['bank_entity'] ?>
          </td>
          <td><?= $data['data']['items'][$i]['n_account'] ?></td>
          <td><?= $data['data']['items'][$i]['account_type'] ?></td>
          <td>
            <?=
              $data['data']['items'][$i]['checkbook'] ? 'activo' : 'inactivo'
            ?>
          </td>
          <td><?= date("d/m/Y G:i:s", strtotime($data['data']['items'][$i]['created_at'])); ?></td>
          <td>
            <?= $data['data']['items'][$i]['status'] ? 'activo' : 'inactivo' ?>
          </td>
        </tr>
      <?php
      }
      ?>
    </tbody>
    <tfoot>
      <tr>
        <th colspan="2">Registros</th>
        <td colspan="5" style="text-align:right;"><?= $data['data']['info']['count']; ?></td>
      </tr>
    </tfoot>
    </tfoot>
  </table>
<?php
}
?>