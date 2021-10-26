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
        </template>
        <template v-slot:search>
          <div class="container">
            <s26-form-input label="Código" id="ean_code" v-model="filter.variants" maxlength="13" @keyup="allRows" number autofocus></s26-form-input>
            <s26-form-input label="Sku" id="sku" v-model="filter.sku" maxlength="50" @keyup="allRows"></s26-form-input>
            <s26-form-input label="Producto" id="name" v-model="filter.product" maxlength="100" @keyup="allRows"></s26-form-input>
            <s26-form-input label="Modelo" id="model" v-model="filter.model" maxlength="100" @keyup="allRows"></s26-form-input>
            <s26-form-input label="Marca" id="trademark" v-model="filter.trademark" maxlength="100" @keyup="allRows"></s26-form-input>
            <s26-form-input label="P.V.P" id="pvp" v-model="filter.pvp" maxlength="100" @keyup="allRows" number></s26-form-input>
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
                <s26-tarjet-info title="Stock" variant="warning" icon="chart-line">
                  {{ s26_data.info.total_stock }}
                </s26-tarjet-info>
                <s26-tarjet-info title="Entradas" variant="purple" icon="sign-in-alt">
                  {{ s26_data.info.total_entries }}
                </s26-tarjet-info>
                <s26-tarjet-info title="Salidas" variant="orange" icon="sign-out-alt">
                  {{ s26_data.info.total_outputs }}
                </s26-tarjet-info>
                <?php
                if ($_SESSION['userData']['cost_products']) {
                ?>
                  <s26-tarjet-info title="Costo" variant="danger" icon="money-bill-wave">
                    <s26-icon icon="dollar-sign" class="text-danger"></s26-icon>
                    {{ $s26.currency(s26_data.info.total_cost) }}
                  </s26-tarjet-info>
                <?php
                }
                ?>
                <s26-tarjet-info title="Pvp" variant="secondary" icon="money-bill-wave">
                  <s26-icon icon="dollar-sign" class="text-secondary"></s26-icon>
                  {{ $s26.currency(s26_data.info.total_pvp) }}
                </s26-tarjet-info>
              </div>
            </div>
          </div>
        </template>
      </s26-sidebar>
      <s26-table :rows="s26_data.info.rows" @get="allRows" :sidebar="activeSidebar" v-model="filter.perPage" :loading_data="loading_data" action>
        <template v-slot:head>
          <th class="length-description">Producto</th>
          <th class="length-action">Modelo</th>
          <th class="length-action">Marca</th>
          <th class="length-description">Categoría</th>
          <th class="length-action text-center">Variantes</th>

          <?php
          if ($_SESSION['permits'][36]['r']) {
          ?>
            <th class="length-action text-center">Proveedor</th>
          <?php
          }
          ?>
          <th class="length-action text-center">Stock</th>
          <th class="length-action text-center">Estado</th>
        </template>
        <template v-slot:body v-if="!loading_data">
          <tr v-for="item in s26_data.items" :key="item.id">
            <td class="length-description">{{ item.name }}</td>
            <td class="length-action">{{ item.model }}</td>
            <td class="length-action">{{ item.trademark }}</td>
            <td class="length-description">{{ item.category }}</td>
            <td class="length-action ">
              <button type="button" class="btn btn-link w-100" @click="setIdRow(item.id, 'watch-variants')">
                <s26-icon icon="search"></s26-icon>
              </button>
            </td>
            <?php
            if ($_SESSION['permits'][36]['r']) {
            ?>
              <td class="length-action">
                <button type="button" class="btn btn-link w-100" @click="setIdRow(item.id, 'watch-providers')">
                  <s26-icon icon="search"></s26-icon>
                </button>
              </td>
            <?php
            }
            ?>
            <td class="length-action">
              <button type="button" :class="['btn btn-link w-100 fw-600 fs-6', parseInt(item.stock) <= parseInt(item.min_stock) ? 'text-danger' : '']" @click="setIdRow(item.id, 'watch-stock')">
                {{ item.stock }}
              </button>
            </td>
            <td :class="['length-action text-center', item.status == 1 ? 'text-success' : 'text-danger']">
              {{ item.status == 1 ? 'activo' : 'inactivo' }}
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
        <!-- Modal Ver Info Principal -->
        <transition name="slide-fade">
          <s26-read-single-product v-model="action" :id="idRow" v-if="action == 'watch-variants' || action == 'watch-providers' || action == 'watch-stock'">
          </s26-read-single-product>
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