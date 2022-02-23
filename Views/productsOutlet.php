<?= head_(); ?>
<?= header_(); ?>

<div id="s26-productOutlet-view" class="container-fluid s26-container" tabindex="0">
  <?php
  if (empty($_SESSION['permitsModule']['r'])) {
  ?>
    <p>Acceso Restringido</p>
  <?php } else { ?>
    <div class="row align-items">
      <s26-sidebar title="Salidas" icon="sign-out-alt" @update="allRows" @reset="onReset" v-model="activeSidebar" :url_export="url_export">
        <template v-slot:search>
          <div class="container">
            <s26-form-input label="Código" id="code" v-model="filter.code" maxlength="13" @keyup="allRows" number autofocus></s26-form-input>
            <s26-form-input label="Nombre" id="name" v-model="filter.name" maxlength="100" @keyup="allRows"></s26-form-input>
            <s26-form-input label='Id de Venta' id='sale_id' type='number' v-model='filter.sale_id' maxlength='20' number @keyup="allRows">
            </s26-form-input>
            <s26-date-picker id="date" enable="range" size="sm" v-model="filter.date" @change="allRows" label="fecha" :dates="s26_data.dates"></s26-date-picker>
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
                <s26-tarjet-info title="Salidas" variant="purple" icon="sign-in-alt">
                  {{ s26_data.info.total_sales }}
                </s26-tarjet-info>
                <s26-tarjet-info title="Costo" variant="danger" icon="money-bill-wave">
                  <s26-icon icon="dollar-sign" class="text-danger"></s26-icon>
                  {{ $s26.currency(s26_data.info.total_cost) }}
                </s26-tarjet-info>
                <s26-tarjet-info title="PVP" variant="secondary" icon="money-bill-wave">
                  <s26-icon icon="dollar-sign" class="text-secondary"></s26-icon>
                  {{ $s26.currency(s26_data.info.total_pvp) }}
                </s26-tarjet-info>
                <s26-tarjet-info title="Descuento" variant="purple" icon="money-bill-wave">
                  <s26-icon icon="dollar-sign" class="s26-text-purple"></s26-icon>
                  {{ $s26.currency(s26_data.info.total_discount) }}
                </s26-tarjet-info>
                <s26-tarjet-info title="Total" variant="orange" icon="money-bill-wave">
                  <s26-icon icon="dollar-sign" class="s26-text-orange"></s26-icon>
                  {{ $s26.currency(s26_data.info.total) }}
                </s26-tarjet-info>
              </div>

            </div>
          </div>
        </template>
      </s26-sidebar>
      <section :class="['main px-0', { 'mainWidth-100': !activeSidebar }]">
        <div class="s26-container-table">
          <div class="row mx-3">

            <div class="col-12 row mx-0" v-for="(item, index) in s26_data.items" :key="index">
              <table class="s26-table">
                <thead v-show="index == 0">
                  <tr>
                    <th :class="field.class" v-for="field in fields" :key="field.name">
                      {{ field.name }}
                    </th>
                  </tr>
                </thead>
                <div class="col-12 fs-5 fw-bold s26-text-blue mb-3 py-2 thead-sticky">
                  {{ $s26.formatDate(item.date + ' 00:00:00', 'md') }}
                  <span class="float-end fs-6">
                    {{ item.items.length }}
                  </span>
                </div>
                <tbody>
                  <tr v-for="(pro, index) in item.items" :key="index" :class="pro.status == 1 ? '' : 'tr-danger'">
                    <td class="length-int">
                      <a href="#" class=" btn btn-link" @click.prevent="getInfo(pro.sale_id, 'sales')">
                        {{ pro.type }} - {{ pro.sale_id }}
                      </a>
                    </td>
                    <td class="length-int">
                      <a href="#" class=" btn btn-link" @click.prevent="getInfo(pro.product_id, 'products')">
                        {{ pro.ean_code }}
                      </a>
                    </td>
                    <td class="length-description" :title="pro.name + '/' + pro.trademark + '/' + pro.model + '/' + pro.sku">
                      {{ pro.name }} / {{ pro.trademark }} / {{ pro.model }} / {{ pro.sku }}
                    </td>
                    <td class="length-status text-center">
                      {{ pro.amount }}
                    </td>
                    <td class="length-status text-center" v-if="s26_data.access_cost == 1">
                      <span>
                        <s26-icon icon="dollar-sign"></s26-icon>
                      </span>
                      {{ $s26.currency(pro.cost) }}
                    </td>
                    <td class="length-status text-center">
                      <span>
                        <s26-icon icon="dollar-sign"></s26-icon>
                      </span>
                      {{ $s26.currency(pro.pvp) }}
                    </td>
                    <td class="length-status text-center">
                      <span>
                        <s26-icon icon="dollar-sign"></s26-icon>
                      </span>
                      {{ $s26.currency(pro.discount) }}
                    </td>
                    <td class="length-status text-center">
                      <span>
                        <s26-icon icon="dollar-sign"></s26-icon>
                      </span>
                      {{ $s26.currency(pro.amount * pro.pvp - pro.discount) }}
                    </td>
                  </tr>
                </tbody>
              </table>
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
    </div>
  <?php
  }
  ?>
</div>
<?= footer_(); ?>