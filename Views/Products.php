<?= head_(); ?>
<?= header_(); ?>
<div id="s26-products-view" class="container-fluid s26-container" tabindex="0">
  <?php
  if (empty($_SESSION['permitsModule']['r'])) {
  ?>
    <p>Acceso Restringido</p>
  <?php } else { ?>
    <div class="row align-items">
      <s26-sidebar title="Productos" icon="boxes" @update="allRows" @reset="onReset" v-model="activeSidebar" :url_export="url_export">
        <template v-slot:header>
          <?php
          if ($_SESSION['permitsModule']['w']) {
          ?>
            <button type="button" class="btn btn-info form-control mb-2" @click="setIdRow(0, 'val_code')">
              Nuevo
            </button>
          <?php
          }
          ?>
          <?php
          if ($_SESSION['userData']['transfer_products']) {
          ?>
            <button type="button" class="btn btn-outline-info form-control mb-2" @click="setIdRow(0, 'transfer')">
              Transferir Productos
            </button>
          <?php
          }
          ?>
          <?php
          if ($_SESSION['userData']['group_products']) {
          ?>
            <button type="button" class="btn btn-outline-info form-control mb-2" @click="setIdRow(0, 'group')">
              Agrupar Productos
            </button>
          <?php
          }
          ?>
        </template>
        <template v-slot:search>
          <div class="container">
            <s26-form-input label="Id" size="sm" id="Id" type="text" v-model="filter.id" maxlength="11" @keyup="allRows" number autofocus></s26-form-input>
            <s26-form-input label="Código Auxiliar" size="sm" id="auxiliary_code" type="text" v-model="filter.auxiliary_code" maxlength="13" @keyup="allRows" number></s26-form-input>
            <s26-form-input label="Código Ean" size="sm" id="ean_code" type="text" v-model="filter.ean_code" maxlength="13" @keyup="allRows" number></s26-form-input>
            <s26-form-input label="Producto" size="sm" id="name" type="text" v-model="filter.name" maxlength="100" @keyup="allRows"></s26-form-input>
            <s26-form-input label="Serie" size="sm" id="serie" type="text" v-model="filter.serie" maxlength="100" @keyup="allRows"></s26-form-input>
            <s26-form-input label="Marca" size="sm" id="trademark" type="text" v-model="filter.trademark" maxlength="100" @keyup="allRows"></s26-form-input>
            <?php
            if ($_SESSION['permits'][36]['r']) {
            ?>
              <s26-select-provider id="provider" v-model="filter.provider" @change="allRows" all></s26-select-provider>
            <?php
            }
            ?>
            <s26-select-category id="category" v-model="filter.category" @change="allRows" all></s26-select-category>

            <s26-select-status all label="Estado" v-model="filter.status" @change="allRows"></s26-select-status>

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
                    {{ info.rows }}
                  </span>
                </s26-tarjet-info>
                <s26-tarjet-info title="Stock" variant="warning" icon="chart-line">
                  {{ info.total_stock }}
                </s26-tarjet-info>
                <?php
                if ($_SESSION['userData']['cost_products']) {
                ?>
                  <s26-tarjet-info title="Costo" variant="danger" icon="money-bill-wave">
                    <s26-icon icon="dollar-sign" class="text-danger"></s26-icon>
                    {{ $s26.currency(info.total_cost) }}
                  </s26-tarjet-info>
                <?php
                }
                ?>
                <s26-tarjet-info title="Pvp" variant="secondary" icon="money-bill-wave">
                  <s26-icon icon="dollar-sign" class="text-secondary"></s26-icon>
                  {{ $s26.currency(info.total_pvp) }}
                </s26-tarjet-info>
              </div>
            </div>
          </div>
        </template>
      </s26-sidebar>
      <s26-table :rows="info.rows" @get="allRows" :sidebar="activeSidebar" v-model="perPage" :loading_data="loading_data" action>
        <template v-slot:head>
          <th class="length-int">Código</th>
          <th class="length-description">Producto</th>
          <th class="length-action">Modelo</th>
          <th class="length-action">Marca</th>
          <?php
          if ($_SESSION['permits'][36]['r']) {
          ?>
            <th class="length-action">Proveedor</th>
          <?php
          }
          ?>
          <th class="length-action">Categoría</th>
          <th class="length-action text-center">Stock</th>
          <?php
          if ($_SESSION['userData']['cost_products']) {
          ?>
            <th class="length-action">Costo</th>
          <?php
          }
          ?>
          <th class="length-action">PVP</th>
          <th class="length-action text-center">Estado</th>
        </template>
        <template v-slot:body v-if="!loading_data">
          <tr v-for="item in items" :key="item.id">
            <td class="length-int">{{ item.ean_code }}</td>
            <td class="length-description">
              {{ item.name }}
            </td>
            <td class="length-action">{{ item.model }}</td>
            <td class="length-action">{{ item.trademark }}</td>
            <?php
            if ($_SESSION['permits'][36]['r']) {
            ?>
              <td class="length-action">
                <s26-dropdown v-if="item.providers.length > 0" :content="`${item.providers[0]['alias']} ${item.providers.length > 1 ? '+' + (item.providers.length - 1) : ''}`" width="120">
                  <li v-for="provider in item.providers" :key="provider.id" class="list-group-item border-0" @click="getProvider(provider.id)">
                    {{ provider.alias }}
                  </li>
                </s26-dropdown>
              </td>
            <?php
            }
            ?>
            <td class="length-action">{{ item.category.name }}</td>
            <td class="length-action text-center">
              <?php
              if ($_SESSION['userData']['user_access'] == 2) {
              ?>
                {{ item.establishment.stock }}
              <?php
              } else if ($_SESSION['userData']['user_access'] == 1) {
              ?>
                <s26-dropdown v-if="item.establishment.length > 0" :content="item.stock" width="150">
                  <li v-for="estab_stock in item.establishment" :key="estab_stock.id" class="list-group-item border-0">
                    <span class="fw-bold">
                      {{ (estab_stock.establishment.n_establishment).padStart(3, '0') }}:
                    </span>
                    <span class="float-end">
                      {{ estab_stock.stock }}
                    </span>
                  </li>
                </s26-dropdown>
              <?php
              }
              ?>
            </td>
            <?php
            if ($_SESSION['userData']['cost_products']) {
            ?>
              <td class="length-action">{{ $s26.currency(item.cost) }}</td>
            <?php
            }
            ?>
            <td class="length-action" title="Click para ver la lista de precios">
              <s26-dropdown :content="item.pvp_1" width="150">
                <li class="list-group-item border-0">
                  <span class="fw-bold">Pvp 1:</span>
                  {{ $s26.currency(item.pvp_1) }}
                </li>
                <li class="list-group-item border-0">
                  <span class="fw-bold">Pvp 2:</span>
                  {{ $s26.currency(item.pvp_2) }}
                </li>
                <li class="list-group-item border-0">
                  <span class="fw-bold">Pvp 3:</span>
                  {{ $s26.currency(item.pvp_3) }}
                </li>
                <li class="list-group-item border-0">
                  <span class="fw-bold">Pvp Dis:</span>
                  {{ $s26.currency(item.pvp_distributor) }}
                </li>
                <li class="list-group-item border-0">
                  <span class="fw-bold">Pvp Oferta:</span>
                  {{ $s26.currency(item.pvp_offer) }}
                </li>
              </s26-dropdown>
            </td>
            <td class="length-action text-center">
              <s26-dropdown :content="item.status == 1 ? 'activo' : 'inactivo'" width="150" :label_variant="item.status == 1 ? 'text-success' : 'text-danger'">
                <li v-for="estab_stock in item.establishment" :key="estab_stock.id" class="list-group-item border-0">
                  <span class="fw-bold">
                    {{ (estab_stock.establishment.n_establishment).padStart(3, '0') }}:
                  </span>
                  <span :class="['float-end', estab_stock.status == 1 ? 'text-success' : 'text-danger']">
                    {{ estab_stock.status == 1 ? 'activo' : 'inactivo' }}
                  </span>
                </li>
              </s26-dropdown>

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
                <?php
                if ($_SESSION['permitsModule']['d']) {
                ?>
                  <li class="list-group-item border-0" @click="setIdRow(item.id, 'delete')">
                    Eliminar
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
          <s26-read-product v-model="action" :id="idRow" v-if="action == 'watch'">
          </s26-read-product>
        </transition>
      <?php
      }
      if ($_SESSION['permitsModule']['u'] || $_SESSION['permitsModule']['w']) {
      ?>
        <!-- Modal Validar Codigo-->
        <transition name="slide-fade">
          <s26-form-val-code v-model="action" :id="idRow" v-if="action == 'val_code'" @get_code="get_code"></s26-form-val-code>
        </transition>
        <!-- Modal Nuevo-->
        <transition name="slide-fade">
          <s26-form-product v-model="action" :id="idRow" v-if="action == 'new_product'" @update="allRows" :code="code"></s26-form-product>
        </transition>
        <!-- Modal Cantidad-->
        <transition name="slide-fade">
          <s26-form-amount-product v-model="action" :id="code" v-if="action == 'amount'" @update="allRows"></s26-form-amount-product>
        </transition>
        <!-- Modal Transferir Productos-->
        <!-- <transition name="slide-fade">
          <s26-form-transfer-products v-model="action" v-if="action == 'transfer'" @update="allRows"></s26-form-transfer-products>
        </transition> -->
      <?php
      }
      if ($_SESSION['permitsModule']['d']) {
      ?>
        <!-- Modal Eliminar -->
        <transition name="slide-fade">
          <s26-delete v-model="action" @update="allRows" v-if="action == 'delete'" :post_delete="'products/delProduct/' + idRow"></s26-delete>
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