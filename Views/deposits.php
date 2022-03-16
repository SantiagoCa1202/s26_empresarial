<?= head_(); ?>
<?= header_(); ?>

<div id="s26-deposits-view" class="container-fluid s26-container" tabindex="0">
  <?php
  if (empty($_SESSION['permitsModule']['r'])) {
  ?>
    <p>Acceso Restringido</p>
  <?php } else { ?>
    <div class="row align-items">
      <s26-sidebar title="Depositos" icon="piggy-bank" @update="allRows" @reset="onReset" v-model="activeSidebar">
        <template v-slot:header>
          <?php
          if ($_SESSION['permitsModule']['w'] && $_SESSION['userData']['box']['status'] == 1) {
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
            <s26-select-bank-account label="Cuenta Bancaria" id="form-account" v-model="filter.bank_account_id" all @change="allRows">
            </s26-select-bank-account>
            <s26-form-input label="Descripción" id="description" type="text" v-model="filter.description" maxlength="100" @keyup="allRows" text></s26-form-input>
            <s26-form-input label="Importe" id="amount" type="tel" v-model="filter.amount" @keyup="allRows" number money></s26-form-input>
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
                <s26-tarjet-info title="Total" variant="warning" icon="money-bill-wave">
                  <s26-icon icon="dollar-sign"></s26-icon>
                  {{ s26_data.info.total_deposit }}
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
            <div class="col-sm-12 mb-3" v-for="deposit in item.items" :key="item.id">

              <div class="tarjet-deposits">
                <div class="deposit-body row mx-0">

                  <div class="col-9 fs-6">
                    <a href="#" class=" btn btn-link p-0 text-decoration-none" @click.prevent="$s26.getInfoRow(deposit.bank_account_id, 'bankAccounts')">
                      {{ deposit.bank_account }}
                    </a>
                  </div>
                  <div class="col-3 text-end">
                    <s26-icon icon="dollar-sign" class="fs-5"></s26-icon>
                    <span class="fs-5"> {{ $s26.currency(deposit.amount) }} </span>
                  </div>
                </div>
                <div class="deposit-footer row mx-0">
                  <div class="col-10">
                    <span :class="['mx-2 fw-600', deposit.status == 1 ? 'text-success' : 'text-danger']">
                      {{ deposit.status == 1 ? 'Activo' : 'Inactivo' }}
                    </span>
                  </div>
                  <div class="col-2 row mx-0 px-0">
                    <?php
                    if ($_SESSION['permitsModule']['r']) {
                    ?>
                      <div class="col-4 s26-align-center">
                        <button type="button" class="btn btn-link" style="color: #fcbf12" @click="setIdRow(deposit.id, 'watch')">
                          <s26-icon icon='eye'></s26-icon>
                        </button>
                      </div>
                    <?php
                    }
                    if ($_SESSION['permitsModule']['u'] && $_SESSION['userData']['box']['status'] == 1) {
                    ?>
                      <div class="col-4 s26-align-center">
                        <button type="button" class="btn btn-link" @click="setIdRow(deposit.id, 'update')">
                          <s26-icon icon='edit'></s26-icon>
                        </button>
                      </div>
                    <?php
                    }
                    if ($_SESSION['permitsModule']['d'] && $_SESSION['userData']['box']['status'] == 1) {
                    ?>
                      <div class="col-4 s26-align-center">
                        <button type="button" class="btn btn-link text-danger" @click="setIdRow(deposit.id, 'delete')">
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
          <transition name="fade">
            <button class="btn btn-outline-info float-end" v-if="this.filter.perPage < s26_data.info.count" @click="loadMore">
              Cargar Más...
            </button>
          </transition>
          <div class="col-12 rounded shadow-sm bg-white py-2 text-center s26-text-blue fw-bold" v-if="s26_data.info.count == 0">
            Sin Registros
          </div>
        </div>
      </section>

      <?php
      if ($_SESSION['permitsModule']['r']) {
      ?>
        <!-- Modal Ver-->
        <transition name="slide-fade">
          <s26-read-deposit v-model="action" :id="idRow" v-if="action == 'watch'">
          </s26-read-deposit>
        </transition>
      <?php
      }
      if ($_SESSION['permitsModule']['u'] || $_SESSION['permitsModule']['w']) {
      ?>
        <!-- Modal Nuevo-->
        <transition name="slide-fade">
          <s26-form-deposit v-model="action" :id="idRow" v-if="action == 'update'" @update="allRows"></s26-form-deposit>
        </transition>
      <?php

      }
      ?>
      <?php
      if ($_SESSION['permitsModule']['d']) {
      ?>
        <!-- Modal Eliminar -->
        <transition name="slide-fade">
          <s26-delete v-model="action" @update="allRows" v-if="action == 'delete'" :post_delete="'deposits/delDeposit/' + idRow"></s26-delete>
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
  .tarjet-deposits {
    background: #fff;
    height: 80px;
    padding: .5rem;
    box-shadow: 0 2px 8px 2px rgb(93 130 170 / 21%);
    border-radius: .4rem;
    color: var(--s26-blue);
  }

  .deposit-header,
  .deposit-footer {
    height: 25%;
    align-items: center;
    padding-left: .5rem;
    padding-right: .5rem;
  }

  .deposit-body {
    height: 50%;
    padding-left: .5rem;
    padding-right: .5rem;
    align-items: center;
  }
</style>