<?= head_(); ?>
<?= header_(); ?>

<div id="s26-sales-credits-view" class="container-fluid s26-container" tabindex="0">
  <?php
  if (empty($_SESSION['permitsModule']['r'])) {
  ?>
    <p>Acceso Restringido</p>
  <?php } else { ?>
    <div class="row align-items">
      <s26-sidebar title="Créditos" icon="shopping-bag" @update="allRows" @reset="onReset" v-model="activeSidebar" :url_export="url_export">
        <template v-slot:search>
          <div class="container">
            <s26-form-input label="Id de Crédito" id="sale_id" type="number" v-model="filter.sale_id" maxlength="20" number @keyup="allRows">
            </s26-form-input>
            <s26-form-input label="N° de Documento" id="n_document" type="number" v-model="filter.n_document" maxlength="17" number @keyup="allRows">
            </s26-form-input>
            <s26-form-input label="Cliente" id="customer" type="number" v-model="filter.customer" maxlength="13" number @keyup="allRows">
            </s26-form-input>
            <?php if ($_SESSION['permits'][41]['r']) {  ?>
              <s26-select-establishment id="filter-establishment" all v-model="filter.establishment_id" @change="allRows"></s26-select-establishment>
            <?php } ?>
            <s26-select-type-document id="filter-type_doc_id" v-model="filter.type_doc_id" all type="buy"></s26-select-type-document>
            <s26-select-status all label="Estado" v-model="filter.status" @change="allRows" :options="['procesadas', 'anuladas']"></s26-select-status>
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
                <s26-tarjet-info title="Articulos" variant="warning" icon="shopping-bag">
                  {{ s26_data.info.total_products }}
                </s26-tarjet-info>
                <s26-tarjet-info title="P.V.P" variant="danger" icon="money-bill-wave">
                  <s26-icon icon="dollar-sign" class="text-danger"></s26-icon>
                  {{ $s26.currency(s26_data.info.total_pvp) }}
                </s26-tarjet-info>
                <s26-tarjet-info title="Descuento" variant="purple" icon="money-bill-wave">
                  <s26-icon icon="dollar-sign" class="s26-text-purple"></s26-icon>
                  {{ $s26.currency(s26_data.info.total_discount) }}
                </s26-tarjet-info>
                <s26-tarjet-info title="Total" variant="orange" icon="money-bill-wave">
                  <s26-icon icon="dollar-sign" class="s26-text-orange"></s26-icon>
                  {{ $s26.currency(s26_data.info.total_sale) }}
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
                  <tr v-for="(sale, index) in item.items" :key="index" :class="sale.status == 3 ? 'tr-warning' : sale.status != 1 ? 'tr-danger' : ''">
                    <td class="length-int">
                      {{ sale.id }}
                    </td>
                    <td class="length-status text-center">
                      {{ sale.total_products }}
                    </td>
                    <td class="length-status text-center">
                      <span>
                        <s26-icon icon="dollar-sign"></s26-icon>
                      </span>
                      {{ $s26.currency(sale.total_discount) }}
                    </td>
                    <td class="length-status text-center">
                      <span>
                        <s26-icon icon="dollar-sign"></s26-icon>
                      </span>
                      {{ $s26.currency(sale.total_sale) }}
                    </td>
                    <td class="length-status text-center">
                      <span>
                        <s26-icon icon="dollar-sign"></s26-icon>
                      </span>
                      {{ $s26.currency(sale.balance) }}
                    </td>
                    <td class="length-status text-center">
                      {{ sale.alias_document }}
                    </td>
                    <td class="length-description text-center">
                      {{ sale.n_document }}
                    </td>
                    <td class="length-int text-center">
                      <a href="#" class=" btn btn-link p-0" @click.prevent="$s26.getInfoRow(sale.customer_id, 'customers')">
                        {{ sale.customer }}
                      </a>
                    </td>
                    <td class="length-action text-center">
                      <a href="#" class="btn btn-link p-0" v-if="sale.status == 2" @click="setIdRow(sale.id, 'watch')">
                        <s26-icon icon="info"></s26-icon>
                      </a>
                      <s26-dropdown v-if="sale.status != 2">
                        <?php
                        if ($_SESSION['permitsModule']['r']) {
                        ?>
                          <li class="list-group-item border-0" @click="setIdRow(sale.id, 'watch')">
                            Ver
                          </li>
                        <?php
                        }
                        ?>
                        <?php
                        if ($_SESSION['permitsModule']['u']) {
                        ?>
                          <li class="list-group-item border-0" @click="setIdRow(sale.id, 'update')" v-if="sale.document_id == 1">
                            Editar
                          </li>
                        <?php
                        }
                        ?>
                        <?php
                        if ($_SESSION['permitsModule']['d']) {
                        ?>
                          <li class="list-group-item border-0" @click="cancelSale(sale)">
                            Anular
                          </li>
                        <?php
                        }
                        ?>
                      </s26-dropdown>
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
      <?php
      if ($_SESSION['permitsModule']['r']) {
      ?>
        <!-- Modal Ver-->
        <transition name="slide-fade">
          <s26-read-sale-credit v-model="action" :id="idRow" v-if="action == 'watch'">
          </s26-read-sale-credit>
        </transition>
      <?php
      }
      if ($_SESSION['permitsModule']['u'] || $_SESSION['permitsModule']['w']) {
      ?>
        <!-- Modal Nuevo-->
        <transition name="slide-fade">
          <s26-form-sale-credit v-model="action" :id="idRow" v-if="action == 'update'" @update="allRows" @cancel_sale="cancelSale"></s26-form-sale-credit>
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