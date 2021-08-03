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
            <s26-form-input label="Código Auxiliar" size="sm" id="auxiliary_code" type="text" v-model="filter.auxiliary_code" maxlength="13" @keyup="allRows" number></s26-form-input>
            <s26-form-input label="Código Ean" size="sm" id="ean_code" type="text" v-model="filter.ean_code" maxlength="13" @keyup="allRows" number></s26-form-input>
            <s26-form-input label="Producto" size="sm" id="name" type="text" v-model="filter.name" maxlength="100" @keyup="allRows"></s26-form-input>
            <s26-form-input label="Serie" size="sm" id="serie" type="text" v-model="filter.serie" maxlength="100" @keyup="allRows"></s26-form-input>
            <s26-form-input label="Marca" size="sm" id="trademark" type="text" v-model="filter.trademark" maxlength="100" @keyup="allRows"></s26-form-input>
            <s26-select-provider id="provider" v-model="filter.provider" @change="allRows" all></s26-select-provider>
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
                <s26-tarjet-info title="Entradas" variant="purple" icon="sign-in-alt">
                  {{ info.total_entries }}
                </s26-tarjet-info>
                <s26-tarjet-info title="Salidas" variant="orange" icon="sign-out-alt">
                  {{ info.total_outputs }}
                </s26-tarjet-info>
                <s26-tarjet-info title="Costo" variant="danger" icon="money-bill-wave">
                  <s26-icon icon="dollar-sign" class="text-danger"></s26-icon>
                  {{ info.total_cost }}
                </s26-tarjet-info>
                <s26-tarjet-info title="Pvp" variant="secondary" icon="money-bill-wave">
                  <s26-icon icon="dollar-sign" class="text-secondary"></s26-icon>
                  {{ info.total_pvp }}
                </s26-tarjet-info>
              </div>
            </div>
          </div>
        </template>
      </s26-sidebar>
      <s26-table :fields="fields" :rows="info.rows" @get="allRows" :sidebar="activeSidebar" v-model="perPage" action>
        <template v-slot:body>
          <tr v-for="item in items" :key="item.id">
            <td class="length-int">{{ item.ean_code }}</td>
            <td class="length-description">
              {{ item.name }}
            </td>
            <td class="length-action">{{ item.serie }}</td>
            <td class="length-action">{{ item.trademark }}</td>
            <td class="length-action">{{ item.provider.alias }}</td>
            <td class="length-action">{{ item.category.name }}</td>
            <td :class="['length-action text-center fw-bold', item.stock <= item.min_stock ? 'text-warning' : '',item.stock == 0 ? 'text-danger' : '']">{{ item.stock }}</td>
            <td class="length-action">{{ item.cost }}</td>
            <td class="length-action">{{ item.pvp_1 }}</td>
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
        <!-- Modal Nuevo-->
        <transition name="slide-fade">
          <s26-form-product v-model="action" :id="idRow" v-if="action == 'update'" @update="allRows"></s26-form-product>
        </transition>
      <?php
      }
      ?>
      <?php
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