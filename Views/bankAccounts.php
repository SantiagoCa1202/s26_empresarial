<?= head_(); ?>
<?= header_(); ?>

<div id="s26-bankAccounts-view" class="container-fluid s26-container" tabindex="0">
  <?php
  if (empty($_SESSION['permitsModule']['r'])) {
  ?>
    <p>Acceso Restringido</p>
  <?php } else { ?>
    <div class="row align-items">
      <s26-sidebar title="Cuentas Bancarias" icon="wallet" @update="allRows" @reset="onReset" v-model="activeSidebar" :url_export="url_export">
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
            <s26-select-status all label="Estado" v-model="status" @change="allRows"></s26-select-status>
          </div>
        </template>
        <template v-slot:info>
          <div class="container">
            <div class="row">
              <div class="col-12">
                <s26-tarjet-info title="Registros" variant="primary" icon="list-ul">
                  <span class="fw-bold text-primary">
                    {{ perPage }}
                  </span>
                  &nbsp
                  <span class="text-lowercase">
                    de
                  </span>
                  &nbsp
                  <span class="fw-bold text-primary">
                    {{ rows }}
                  </span>
                </s26-tarjet-info>
              </div>
            </div>
          </div>
        </template>
      </s26-sidebar>

      <section :class="['main px-0', { 'mainWidth-100': !activeSidebar }]">
        <div class="s26-container-table">
          <div class="row mx-3">
            <div class="col-sm-6 mb-3" v-for="item in items" :key="item.id">
              <div class="tarjet-bank-account">
                <div class="bank-account-header row mx-0">
                  <div class="col-8">
                    <h1 class="fs-5 fw-bold m-0">
                      <s26-icon icon="star" class="text-warning" v-if="item.predetermined == 1"></s26-icon>
                      {{ item.bank_entity.bank_entity }}
                      <a :href="item.bank_entity.url" target="_blank">
                        <s26-icon icon="link"></s26-icon>
                      </a>
                    </h1>
                  </div>
                  <div class="col-4 text-end">
                    {{ item.n_account }}
                  </div>
                </div>
                <div class="bank-account-body row mx-0">
                  <div class="col-4 text-center fs-6">
                    Efectivo
                    <br>
                    Contable
                  </div>
                  <div class="col-8 text-end">
                    <s26-icon icon="dollar-sign" class="fs-2"></s26-icon>
                    <span class="fs-2"> {{ $s26.currency(item.amount) }} </span>
                  </div>
                  <div class="col-12 text-end text-secondary fs-6 opacity-50">
                    Por Efectivizar:
                    <span class="fw-bold fs-6">
                      <s26-icon icon="dollar-sign"></s26-icon>
                      {{ $s26.currency(item.amount_pending) }}
                    </span>
                  </div>
                </div>
                <div class="bank-account-footer row mx-0">
                  <div class="col-9">
                    <span :class="[item.checkbook == 1 ? 'text-primary fw-bold' : 'text-secondary opacity-50']">
                      chequera
                    </span>
                    <span :class="['px-3', item.status == 1 ? 'text-primary fw-bold' : 'text-secondary opacity-50']">
                      Activo
                    </span>
                    <span class="text-primary fw-bold">
                      {{ item.account_type }}
                    </span>

                  </div>
                  <div class="col-3 row mx-0 px-0">
                    <?php
                    if ($_SESSION['permitsModule']['r']) {
                    ?>
                      <div class="col-4 s26-align-center">
                        <button type="button" class="btn btn-link" style="color: #fcbf12" @click="setIdRow(item.id, 'watch')">
                          <s26-icon icon='eye'></s26-icon>
                        </button>
                      </div>
                    <?php
                    }
                    if ($_SESSION['permitsModule']['u']) {
                    ?>
                      <div class="col-4 s26-align-center">
                        <button type="button" class="btn btn-link" @click="setIdRow(item.id, 'update')">
                          <s26-icon icon='edit'></s26-icon>
                        </button>
                      </div>
                    <?php
                    }
                    if ($_SESSION['permitsModule']['d']) {
                    ?>
                      <div class="col-4 s26-align-center">
                        <button type="button" class="btn btn-link text-danger" @click="setIdRow(item.id, 'delete')">
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
          <s26-read-bank-account v-model="action" :id="idRow" v-if="action == 'watch'">
          </s26-read-bank-account>
        </transition>
      <?php
      }
      if ($_SESSION['permitsModule']['u'] || $_SESSION['permitsModule']['w']) {
      ?>
        <!-- Modal Nuevo-->
        <transition name="slide-fade">
          <s26-form-bank-account v-model="action" :id="idRow" v-if="action == 'update'" @update="allRows"></s26-form-bank-account>
        </transition>
      <?php

      }
      ?>
      <?php
      if ($_SESSION['permitsModule']['d']) {
      ?>
        <!-- Modal Eliminar -->
        <transition name="slide-fade">
          <s26-delete v-model="action" @update="allRows" v-if="action == 'delete'" :post_delete="'bankAccounts/delBankAccount/' + idRow"></s26-delete>
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
  .tarjet-bank-account {
    background: #fff;
    height: 150px;
    padding: .5rem;
    box-shadow: 0 2px 8px 2px rgb(93 130 170 / 21%);
    border-radius: .4rem;
    color: var(--s26-blue);
  }

  .bank-account-header,
  .bank-account-footer {
    height: 25%;
    align-items: center;
    padding-left: .5rem;
    padding-right: .5rem;
  }

  .bank-account-body {
    height: 50%;
    padding-left: .5rem;
    padding-right: .5rem;
    align-items: center;
  }
</style>