<?php
if (!empty($_SESSION['permitsModule']['r'])) {
  if ($data['type'] == 'excel') {
    header('Content-type: application/vnd.ms-excel');
    header('Content-Disposition: attachment; filename=usuarios.xls');
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
    <caption>Informe de Usuarios</caption>
    <thead>
      <tr>
        <th>
          id
        </th>
        <th>
          <?= $data['type'] == 'excel' ? utf8_decode("cédula") : 'cédula / ruc'; ?>
        </th>
        <th>
          nombres
        </th>
        <th>
          apellidos
        </th>
        <th>
          email
        </th>
        <th>
          <?= $data['type'] == 'excel' ? utf8_decode("teléfono") : 'teléfono'; ?>
        </th>
        <th>
          sexo
        </th>
        <th>
          fecha de nacimiento
        </th>
        <th>
          rol
        </th>
        <th>
          establecimiento
        </th>
        <th>
          seguro
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
          <td><?= $data['data']['items'][$i]['id'] ?></td>
          <td><?= $data['data']['items'][$i]['document'] ?></td>
          <td><?= $data['data']['items'][$i]['name'] ?></td>
          <td><?= $data['data']['items'][$i]['last_name'] ?></td>
          <td><?= $data['data']['items'][$i]['email'] ?></td>
          <td><?= $data['data']['items'][$i]['phone'] ?></td>
          <td><?= $data['data']['items'][$i]['gender']['name'] ?></td>
          <td><?= date("d/m/Y G:i:s", strtotime($data['data']['items'][$i]['date_of_birth'])); ?></td>
          <td><?= $data['data']['items'][$i]['role']['name'] ?></td>
          <td><?= $data['data']['items'][$i]['establishment']['tradename'] ?></td>
          <td><?= $data['data']['items'][$i]['insurance'] ? 'si' : 'no' ?></td>
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
        <td colspan="11" style="text-align:right;"><?= $data['data']['info']['count']; ?></td>
      </tr>
    </tfoot>
    </tfoot>
  </table>
<?php
}
?>