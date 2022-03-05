<?= head_(); ?>
<?= header_(); ?>

<div id="s26-stocktaking-view" class="container-fluid s26-container" tabindex="0">
  <?php
  if (empty($_SESSION['permitsModule']['r'])) {
  ?>
    <p>Acceso Restringido</p>
  <?php } else { ?>
    <div class="row align-items">
      <s26-sidebar title="Inventario" icon="boxes" @update="allRows" @reset="onReset" v-model="activeSidebar" :url_export="url_export">
        <template v-slot:header>
          <?php
          if ($_SESSION['permitsModule']['w'] || $_SESSION['permitsModule']['u']) {
          ?>
            <button type="button" class="btn btn-primary form-control mb-2" @click="prepareInventory">
              Preparar
            </button>
            <button type="button" class="btn btn-outline-info form-control mb-2" @click="setIdRow(0, 'add')">
              Agregar
            </button>
            <button type="button" class="btn btn-outline-warning form-control mb-2" @click="recalculateStock">
              Recalcular Stock
            </button>
          <?php
          }
          ?>
          <?php
          if ($_SESSION['permitsModule']['d']) {
          ?>
            <button type="button" class="btn btn-outline-danger form-control mb-2" @click="resetInventory">
              Resetear
            </button>
          <?php
          }
          ?>
        </template>
        <template v-slot:search>
          <div class="container">
            <s26-form-input label="Código" id="ean_code" v-model="filter.ean_code" maxlength="13" @keyup="allRows" number autofocus></s26-form-input>
            <s26-form-input label="Sku" id="sku" v-model="filter.sku" maxlength="50" @keyup="allRows"></s26-form-input>
            <s26-form-input label="Producto" id="name" v-model="filter.product" maxlength="100" @keyup="allRows"></s26-form-input>
            <s26-form-input label="Modelo" id="model" v-model="filter.model" maxlength="100" @keyup="allRows"></s26-form-input>
            <s26-form-input label="Marca" id="trademark" v-model="filter.trademark" maxlength="100" @keyup="allRows"></s26-form-input>
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
      <s26-table :fields="fields" :rows="s26_data.info.count" @get="allRows" :sidebar="activeSidebar" v-model="filter.perPage" action>
        <template v-slot:body>
          <tr v-for="item in s26_data.items" :key="item.id" :class="item.accountant == item.stock ? 'tr-success' : 'tr-danger'">
            <td class="length-int">
              <a href="#" class=" btn btn-link p-0" @click.prevent="$s26.getInfoRow(item.product_id, 'products')">
                {{ item.ean_code }}
              </a>
            </td>
            <td class="length-description">
              {{ item.name }} / {{ item.model }} / {{ item.trademark }} / {{ item.sku }}
            </td>
            <td class="length-action text-center">
              <s26-icon icon="dollar-sign"></s26-icon>
              {{ $s26.currency(item.pvp) }}
            </td>
            <td class="length-action text-center">{{ item.stock }}</td>
            <td class="length-action text-center">{{ item.accountant }}</td>
            <td class="length-action">
              <span>
                <s26-icon icon="info"></s26-icon>
              </span>
            </td>
          </tr>
        </template>
      </s26-table>
      <?php
      if ($_SESSION['permitsModule']['u'] || $_SESSION['permitsModule']['w']) {
      ?>
        <!-- Modal Nuevo-->
        <transition name="slide-fade">
          <s26-form-stocktaking v-model="action" :id="idRow" v-if="action == 'add'" @update="allRows"></s26-form-stocktaking>
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