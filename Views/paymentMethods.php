<?= head_(); ?>
<?= header_(); ?>

<div id="s26-payment-methods-view" class="container-fluid s26-container" tabindex="0">
  <?php
  if (empty($_SESSION['permitsModule']['r'])) {
  ?>
    <p>Acceso Restringido</p>
  <?php } else { ?>
    <div class="row align-items">
      <s26-sidebar title="Formas de Pago" icon="money-bill-wave" @update="allRows" @reset="onReset" v-model="activeSidebar">
        <template v-slot:search>
          <div class="container">
            <s26-form-input label="Nombre" id="filter-name" type="text" v-model="filter.name" maxlength="100">
            </s26-form-input>
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
            <div :class="['mb-3', !activeSidebar ? 'col-sm-4' : 'col-sm-6']" v-for="item in s26_data.items" :key="item.id">
              <div class="tarjet-payment-methods row mx-0 p-3">
                <div class="col-10">
                  <h1 class="fs-5 fw-bold m-0">
                    {{ item.name }}
                  </h1>
                </div>
                <div class="col-2 text-end">
                  <a href="#" :class="[item.status == 1 ? 'text-success' : 'text-danger', 'text-decoration-none', 'pointer']" @dblclick.prevent="onSubmit(item.id, item.status == 1 ? 2 : 1)">
                    {{ item.status == 1 ? 'Activo' : 'Inactivo' }}
                  </a>
                </div>
              </div>
            </div>
            <div class="col-12 rounded shadow-sm bg-white py-2 text-center s26-text-blue fw-bold" v-if="s26_data.info.count == 0">
              Sin Registros
            </div>
          </div>
        </div>
      </section>
      <?php
      if ($_SESSION['permitsModule']['u'] || $_SESSION['permitsModule']['w']) {
      ?>
        <!-- Modal Nuevo-->
        <transition name="slide-fade">
          <s26-form-payment-method v-model="action" :id="idRow" v-if="action == 'update'" @update="allRows"></s26-form-payment-method>
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
  .tarjet-payment-methods {
    background: #fff;
    height: 55px;
    box-shadow: 0 2px 8px 2px rgb(93 130 170 / 21%);
    border-radius: .4rem;
    color: var(--s26-blue);

  }
</style>