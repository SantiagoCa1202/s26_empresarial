<?php
if (!empty($_SESSION['permitsModule']['r'])) {
  if ($data['type'] == 'excel') {
    header('Content-type: application/vnd.ms-excel');
    header('Content-Disposition: attachment; filename=cheques.xls');
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
    <caption>Informe de Cheques</caption>
    <thead>
      <tr>
        <th>
          id
        </th>
        <th>
          Cuenta Bancaria
        </th>
        <th>
          N° de cheque
        </th>
        <th>
          Fecha de Emisión
        </th>
        <th>
          Fecha de Pago
        </th>
        <th>
          Beneficiario
        </th>
        <th>
          Motivo
        </th>
        <th>
          Monto
        </th>
        <th>
          Saldo
        </th>
        <th>
          Tipo
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
          <td><?= $data['data']['items'][$i]['bank_account']['bank_entity']['bank_entity'] ?></td>
          <td>
            <?=
            str_pad($data['data']['items'][$i]['n_check'], 6, "0", STR_PAD_LEFT)
            ?>
          </td>
          <td>
            <?=
            date("d/m/Y", strtotime($data['data']['items'][$i]['date_issue']));
            ?>
          </td>
          <td>
            <?=
            date("d/m/Y", strtotime($data['data']['items'][$i]['date_payment']));
            ?>
          </td>
          <th><?= $data['data']['items'][$i]['beneficiary'] ?></th>
          <th><?= $data['data']['items'][$i]['reason'] ?></th>
          <th><?= $data['data']['items'][$i]['amount'] ?></th>
          <th><?= $data['data']['items'][$i]['balance'] ?></th>
          <th><?= $data['data']['items'][$i]['type'] ?></th>
          <td><?= $data['data']['items'][$i]['payment_status'] ?></td>
        </tr>
      <?php
      }
      ?>
    </tbody>
    <tfoot>
      <tr>
        <th colspan="2">Registros</th>
        <td colspan="9" style="text-align:right;"><?= $data['data']['info']['count']; ?></td>
      </tr>
    </tfoot>
    </tfoot>
  </table>
<?php
}
?>