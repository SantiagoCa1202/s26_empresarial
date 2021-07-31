<?= head_(); ?>
<?= header_(); ?>

<div id="s26-checkBooks-view" class="container-fluid s26-container" tabindex="0">
  <?php
  if (empty($_SESSION['permitsModule']['r'])) {
  ?>
    <p>Acceso Restringido</p>
  <?php } else { ?>
    <div class="row align-items">
      <s26-sidebar title="Cheques" icon="money-check-alt" @update="allRows" @reset="onReset" v-model="activeSidebar" :url_export="url_export">
        <template v-slot:header>
          <?php
          if ($_SESSION['permitsModule']['w']) {
          ?>
            <button type="button" class="btn btn-info form-control mb-2" @click="setIdRow(0, 'update')">
              Nuevo
            </button>
          <?php
          }
          ?>
        </template>
        <template v-slot:search>
          <div class="container">
            <s26-form-input label="Id" size="sm" id="Id" type="text" v-model="filter.id" maxlength="11" @keyup="allRows" number autofocus></s26-form-input>
            <s26-select-bank-account size="sm" id="form-bank_account" v-model="filter.bank_account_id" all>
            </s26-select-bank-account>
            <s26-form-input label="N° de Cheque" size="sm" id="document" type="text" v-model="filter.n_check" maxlength="20" @keyup="allRows" number></s26-form-input>
            <s26-date-picker id="date_issue" enable="range" size="sm" v-model="filter.date_issue" @change="allRows" label="Fecha de Emisión" :dates="s26_data.date_issue"></s26-date-picker>
            <s26-date-picker id="date_payment" enable="range" size="sm" v-model="filter.date_payment" @change="allRows" label="Fecha de Pago" :dates="s26_data.date_payment"></s26-date-picker>
            <s26-form-input label="Beneficiario" size="sm" id="beneficiary" type="text" v-model="filter.beneficiary" maxlength="100" @keyup="allRows" text></s26-form-input>
            <s26-form-input label="Motivo" size="sm" id="reason" type="text" v-model="filter.reason" maxlength="100" @keyup="allRows" text></s26-form-input>
            <div class="mb-3 s26-form-group">
              <label class="form-label">
                Tipo de Emisión
              </label>
              <select id="form-type_check" class="form-select form-select-sm" v-model="filter.type" @change="allRows">
                <option value="">Todos</option>
                <option value="emitido">Emitido</option>
                <option value="recibido">Recibido</option>
              </select>
            </div>
            <div class="mb-3 s26-form-group">
              <label class="form-label">
                Estado de Cheque
              </label>
              <select id="form-payment_status" class="form-select form-select-sm" v-model="filter.payment_status">
                <option value="">Todos</option>
                <option value="por pagar">Por Pagar</option>
                <option value="pagado">Pagado</option>
                <option value="anulado">Anulado</option>
              </select>
            </div>
          </div>
        </template>
        <template v-slot:info>
          <div class="container">
            <div class="row">
              <div class="col-12">
                <s26-tarjet-info title="Registros" variant="primary" icon="list-ul">
                  <span class="fw-bold text-primary">
                    {{ filter.perPage }}
                  </span>
                  &nbsp
                  <span class="text-lowercase">
                    de
                  </span>
                  &nbsp
                  <span class="fw-bold text-primary">
                    {{ s26_data.info.count }}
                  </span>
                </s26-tarjet-info>
              </div>
            </div>
          </div>
        </template>
      </s26-sidebar>
      <s26-table :fields="fields" :rows="s26_data.info.count" @get="allRows" :sidebar="activeSidebar" v-model="filter.perPage" id action>
        <template v-slot:body>
          <tr v-for="item in s26_data.items" :key="item.id">
            <td class="length-int">{{ item.id }}</td>
            <td class="length-int" :title="item.bank_account.bank_entity.bank_entity">
              {{ (item.n_check).padStart(6, '0') }}
            </td>
            <td class="length-date">{{ $s26.formatDate(item.date_issue) }}</td>
            <td class="length-description">{{ item.beneficiary }}</td>
            <td class="length-action">
              <s26-icon icon="dollar-sign"></s26-icon>
              {{ $s26.currency(item.amount) }}
            </td>
            <td class="length-action">{{ item.type }}</td>
            <td :class="[
                'length-status',
                item.payment_status == 'pagado' ? 'text-success' : '',
                item.payment_status == 'por pagar' ? 'text-warning' : '',
                item.payment_status == 'anulado' ? 'text-danger' : '',
              ]">
              {{ item.payment_status }}
            </td>
            <td class="length-action">
              <s26-dropdown>
                <?php
                if ($_SESSION['permitsModule']['r']) {
                ?>
                  <li class="list-group-item border-0" @click="setIdRow(item.id, 'watch')">
                    Ver
                  </li>
                <?php
                }
                ?>
                <?php
                if ($_SESSION['permitsModule']['u']) {
                ?>
                  <li class="list-group-item border-0" @click="setIdRow(item.id, 'update')">
                    Editar
                  </li>
                <?php
                }
                ?>
              </s26-dropdown>
            </td>
          </tr>
        </template>
      </s26-table>
      <?php
      if ($_SESSION['permitsModule']['r']) {
      ?>
        <!-- Modal Ver-->
        <transition name="slide-fade">
          <s26-read-check v-model="action" :id="idRow" v-if="action == 'watch'">
          </s26-read-check>
        </transition>
      <?php
      }
      if ($_SESSION['permitsModule']['u'] || $_SESSION['permitsModule']['w']) {
      ?>
        <!-- Modal Nuevo-->
        <transition name="slide-fade">
          <s26-form-check v-model="action" :id="idRow" v-if="action == 'update'" @update="allRows"></s26-form-check>
        </transition>
      <?php

      }
      ?>
    </div>
  <?php
  }
  ?>
</div>
<?= footer_(); ?>