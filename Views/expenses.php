<?= head_(); ?>
<?= header_(); ?>

<div id="s26-expenses-view" class="container-fluid s26-container" tabindex="0">
  <?php
  if (empty($_SESSION['permitsModule']['r'])) {
  ?>
    <p>Acceso Restringido</p>
  <?php } else { ?>
    <div class="row align-items">
      <s26-sidebar title="Egresos" icon="chart-bar" @update="allRows" @reset="onReset" v-model="activeSidebar" :url_export="url_export">
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
            <s26-form-input label="Id" id="Id" type="tel" v-model="filter.id" maxlength="11" @keyup="allRows" number autofocus></s26-form-input>
            <s26-form-input label="N° de Documento" id="n_document" type="tel" v-model="filter.n_document" maxlength="11" @keyup="allRows" number autofocus></s26-form-input>
            <s26-form-input label="Razón Social" id="tradename" type="text" v-model="filter.tradename" maxlength="50" @keyup="allRows" text></s26-form-input>
            <s26-form-input label="Descripción" id="description" type="text" v-model="filter.description" maxlength="100" @keyup="allRows" text></s26-form-input>
            <s26-form-input label="Importe" id="amount" type="tel" v-model="filter.amount" @keyup="allRows" number money></s26-form-input>
            <s26-select-status all label="Cuenta" v-model="filter.account" @change="allRows" :options="['Costo', 'Ganancia']"></s26-select-status>
            <s26-select-bank-account id="form-bank_account" v-model="filter.bank_account_id" all @change="allRows">
            </s26-select-bank-account>
            <s26-select-payment-method id="filter-payment_method_id" v-model="filter.payment_method_id" all @change="allRows"></s26-select-payment-method>
            <?php if ($_SESSION['permits'][41]['r']) {  ?>
              <s26-select-establishment id="filter-establishment" all v-model="filter.establishment_id" @change="allRows"></s26-select-establishment>
            <?php } ?>
            <s26-date-picker id="date" enable="range" v-model="filter.date" @change="allRows" label="fecha" :dates="s26_data.dates"></s26-date-picker>
            <s26-select-status all label="Estado" v-model="filter.status" @change="allRows"></s26-select-status>
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
                <s26-tarjet-info title="Costo" variant="warning" icon="money-bill-wave">
                  <s26-icon icon="dollar-sign"></s26-icon>
                  {{ s26_data.info.total_cost }}
                </s26-tarjet-info>
                <s26-tarjet-info title="Ganancia" variant="purple" icon="money-bill-wave">
                  <s26-icon icon="dollar-sign"></s26-icon>
                  {{ s26_data.info.total_gain }}
                </s26-tarjet-info>
              </div>
            </div>
          </div>
        </template>
      </s26-sidebar>

      <section :class="['main px-0', { 'mainWidth-100': !activeSidebar }]">
        <div class="s26-container-table">
          <div class="row mx-3" v-for="item in s26_data.items" :key="item.id">
            <div class="col-12 fs-5 fw-bold s26-text-blue mb-3 py-2 thead-sticky">
              {{ $s26.formatDate(item.date + ' 00:00:00', 'md') }}
              <span class="float-end fs-6">
                {{ item.items.length }}
              </span>
            </div>
            <div class="col-sm-12 mb-3" v-for="expense in item.items" :key="item.id">

              <div class="tarjet-expenses">
                <div class="expense-header row mx-0">
                  <div class="col-10">
                    <h1 class="m-0 h5 fw-600">
                      {{ expense.tradename }}
                      <span class="fw-normal h6">{{ expense.n_document }} </span>
                    </h1>
                  </div>
                  <div :class="['col-2 text-end', expense.status == 1 ? 'text-success' : 'text-danger']">
                    {{ expense.status == 1 ? 'Activo' : 'Inactivo' }}
                  </div>
                </div>
                <div class="expense-body row mx-0">
                  <div class="col-8 fs-6 overflow-auto h-100" v-html="expense.description">
                  </div>
                  <div class="col-4 text-end">
                    <s26-icon icon="dollar-sign" class="fs-4"></s26-icon>
                    <span class="fs-4"> {{ $s26.currency(expense.amount) }} </span>
                  </div>
                </div>
                <div class="expense-footer row mx-0">
                  <div class="col-10">
                    <span :class="['mx-2 fw-600', expense.account == 1 ? 'text-success' : 'text-warning']">
                      {{ expense.account == 1 ? 'Costo' : 'Ganancia' }}
                    </span>
                    <span class="mx-2 text-primary fw-600">
                      {{ expense.payment_method }}
                    </span>
                    <span class="mx-2 text-primary fw-600">
                      {{ expense.bank_entity }}
                    </span>
                  </div>
                  <div class="col-2 row mx-0 px-0">
                    <?php
                    if ($_SESSION['permitsModule']['r']) {
                    ?>
                      <div class="col-4 s26-align-center">
                        <button type="button" class="btn btn-link" style="color: #fcbf12" @click="setIdRow(expense.id, 'watch')">
                          <s26-icon icon='eye'></s26-icon>
                        </button>
                      </div>
                    <?php
                    }
                    if ($_SESSION['permitsModule']['u']) {
                    ?>
                      <div class="col-4 s26-align-center">
                        <button type="button" class="btn btn-link" @click="setIdRow(expense.id, 'update')">
                          <s26-icon icon='edit'></s26-icon>
                        </button>
                      </div>
                    <?php
                    }
                    if ($_SESSION['permitsModule']['d']) {
                    ?>
                      <div class="col-4 s26-align-center">
                        <button type="button" class="btn btn-link text-danger" @click="setIdRow(expense.id, 'delete')">
                          <s26-icon icon='trash-alt'></s26-icon>
                        </button>
                      </div>
                    <?php
                    }
                    ?>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>

      <?php
      if ($_SESSION['permitsModule']['r']) {
      ?>
        <!-- Modal Ver-->
        <transition name="slide-fade">
          <s26-read-expense v-model="action" :id="idRow" v-if="action == 'watch'">
          </s26-read-expense>
        </transition>
      <?php
      }
      if ($_SESSION['permitsModule']['u'] || $_SESSION['permitsModule']['w']) {
      ?>
        <!-- Modal Nuevo-->
        <transition name="slide-fade">
          <s26-form-expense v-model="action" :id="idRow" v-if="action == 'update'" @update="allRows"></s26-form-expense>
        </transition>
      <?php

      }
      ?>
      <?php
      if ($_SESSION['permitsModule']['d']) {
      ?>
        <!-- Modal Eliminar -->
        <transition name="slide-fade">
          <s26-delete v-model="action" @update="allRows" v-if="action == 'delete'" :post_delete="'expenses/delExpense/' + idRow"></s26-delete>
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
<style>
  .tarjet-expenses {
    background: #fff;
    height: 125px;
    padding: .5rem;
    box-shadow: 0 2px 8px 2px rgb(93 130 170 / 21%);
    border-radius: .4rem;
    color: var(--s26-blue);
  }

  .expense-header,
  .expense-footer {
    height: 25%;
    align-items: center;
    padding-left: .5rem;
    padding-right: .5rem;
  }

  .expense-body {
    height: 50%;
    padding-left: .5rem;
    padding-right: .5rem;
    align-items: center;
  }
</style>