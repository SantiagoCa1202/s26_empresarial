<?= head_(); ?>
<?= header_(); ?>

<div id="s26-boxes-view" class="container-fluid s26-container" tabindex="0">
  <?php
  if (empty($_SESSION['permitsModule']['r'])) {
  ?>
    <p>Acceso Restringido</p>
  <?php } else { ?>
    <div class="row align-items">
      <s26-sidebar title="Cajas" icon="cash-register" @update="allRows" @reset="onReset" v-model="activeSidebar">
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
            <?php if ($_SESSION['userData']['access_boxes'] == 1) {  ?>
              <s26-select-box id="filter-box" all v-model="filter.id" @change="allRows">
              </s26-select-box>
              <s26-form-input label="Nombre" id="filter-name" type="text" v-model="filter.name" maxlength="100">
              </s26-form-input>
            <?php } ?>
            <?php if ($_SESSION['permits'][41]['r']) {  ?>
              <s26-select-establishment id="filter-establishment" all v-model="filter.establishment_id" @change="allRows"></s26-select-establishment>
            <?php } ?>
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
              </div>
            </div>
          </div>
        </template>
      </s26-sidebar>
      <section :class="['main px-0', { 'mainWidth-100': !activeSidebar }]">
        <div class="s26-container-table">
          <div class="row mx-3">
            <div :class="['mb-3', !activeSidebar ? 'col-sm-4' : 'col-sm-6']" v-for="box in s26_data.items" :key="box.id">
              <div class="tarjet-boxes">
                <div class="box-header row mx-0">
                  <div class="col-10">
                    <h1 class="fs-5 fw-bold m-0">
                      {{ box.id }} -
                      {{ box.name }}
                    </h1>
                  </div>
                  <div :class="['col-2 text-end', box.status == 1 ? 'text-success' : 'text-danger']">
                    {{ box.status == 1 ? 'Activo' : 'Inactivo' }}
                  </div>
                </div>
                <div class="box-body row mx-0">
                  <div class="col-6 text-center fs-6">
                    Efectivo
                    <br>
                    Contable
                  </div>
                  <div class="col-6 text-end">
                    <s26-icon icon="dollar-sign" class="fs-2"></s26-icon>
                    <span class="fs-2">
                      {{ $s26.currency(box.amount) }}
                    </span>
                  </div>
                  <div class="col-12 text-end text-secondary fs-6 opacity-50">
                    Por Efectivizar: 
                    <span class="fw-bold fs-6">
                      <s26-icon icon="dollar-sign"></s26-icon>
                      {{ $s26.currency(box.amount_pending) }}
                    </span>
                  </div>
                </div>
                <div class="box-footer row mx-0">
                  <div class="col-9">
                    <span class="text-secondary">
                      {{ box.establishment }}
                    </span>
                  </div>
                  <div class="col-3 row mx-0 px-0">
                    <?php
                    if ($_SESSION['permitsModule']['r']) {
                    ?>
                      <div class="col-4 s26-align-center">
                        <button type="button" class="btn btn-link" style="color: #fcbf12" @click="setIdRow(box.id, 'watch')">
                          <s26-icon icon='eye'></s26-icon>
                        </button>
                      </div>
                    <?php
                    }
                    if ($_SESSION['permitsModule']['u']) {
                    ?>
                      <div class="col-4 s26-align-center">
                        <button type="button" class="btn btn-link" @click="setIdRow(box.id, 'update')">
                          <s26-icon icon='edit'></s26-icon>
                        </button>
                      </div>
                    <?php
                    }
                    if ($_SESSION['permitsModule']['d']) {
                    ?>
                      <div class="col-4 s26-align-center">
                        <button type="button" class="btn btn-link text-danger" @click="setIdRow(box.id, 'delete')">
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
            <transition name="fade">
              <button class="btn btn-outline-info float-end" v-if="this.filter.perPage < s26_data.info.count" @click="loadMore">
                Cargar Más...
              </button>
            </transition>
            <div class="col-12 rounded shadow-sm bg-white py-2 text-center s26-text-blue fw-bold" v-if="s26_data.info.count == 0">
              Sin Registros
            </div>
          </div>
        </div>
      </section>
      <?php
      if ($_SESSION['permitsModule']['r']) {
      ?>
        <!-- Modal Ver-->
        <transition name="slide-fade">
          <s26-read-customer v-model="action" :id="idRow" v-if="action == 'watch'">
          </s26-read-customer>
        </transition>
      <?php
      }
      if ($_SESSION['permitsModule']['u'] || $_SESSION['permitsModule']['w']) {
      ?>
        <!-- Modal Nuevo-->
        <transition name="slide-fade">
          <s26-form-customer v-model="action" :id="idRow" v-if="action == 'update'" @update="allRows"></s26-form-customer>
        </transition>
      <?php
      }
      ?>
      <?php
      if ($_SESSION['permitsModule']['d']) {
      ?>
        <!-- Modal Eliminar -->
        <transition name="slide-fade">
          <s26-delete v-model="action" @update="allRows" v-if="action == 'delete'" :post_delete="'customers/delCustomer/' + idRow"></s26-delete>
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
  .tarjet-boxes {
    background: #fff;
    height: 155px;
    padding: .5rem;
    box-shadow: 0 2px 8px 2px rgb(93 130 170 / 21%);
    border-radius: .4rem;
    color: var(--s26-blue);
  }

  .box-header,
  .box-footer {
    height: 25%;
    align-items: center;
    padding-left: .5rem;
    padding-right: .5rem;
  }

  .box-body {
    height: 50%;
    padding-left: .5rem;
    padding-right: .5rem;
    align-items: center;
  }
</style>