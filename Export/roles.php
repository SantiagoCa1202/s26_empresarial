<?php
if (!empty($_SESSION['permitsModule']['r'])) {
  if ($data['type'] == 'excel') {
    header('Content-type: application/vnd.ms-excel');
    header('Content-Disposition: attachment; filename=roles.xls');
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
    <caption>Informe de Roles</caption>
    <thead>
      <tr>
        <th>
          id
        </th>
        <th>
          nombre
        </th>
        <th>
          <?= $data['type'] == 'excel' ? utf8_decode("descripción") : 'descripción'; ?>
        </th>
        <th>
          fecha de registro
        </th>
        <th>
          estado
        </th>
      </tr>
    </thead>
    <tbody>
      <?php
      for ($i = 0; $i < count($data['data']['items']); $i++) {
      ?>
        <tr>
          <th><?= $data['data']['items'][$i]['id'] ?></th>
          <td><?= $data['data']['items'][$i]['name'] ?></td>
          <td><?= $data['data']['items'][$i]['description'] ?></td>
          <td><?= date("d/m/Y G:i:s", strtotime($data['data']['items'][$i]['created_at'])); ?></td>
          <td><?= $data['data']['items'][$i]['status'] ? 'activo' : 'inactivo' ?></td>
        </tr>
      <?php
      }
      ?>
    </tbody>
    <tfoot>
      <tr>
        <th colspan="2">Registros</th>
        <td colspan="2" style="text-align:right;"><?= $data['data']['info']['count']; ?></td>
      </tr>
    </tfoot>
    </tfoot>
  </table>
<?php
}
?>