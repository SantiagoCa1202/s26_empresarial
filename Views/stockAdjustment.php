<?= head_(); ?>
<?= header_(); ?>

<div id="s26-stock-adjustment-view" class="container-fluid s26-container" tabindex="0">
  <?php
  if (empty($_SESSION['permitsModule']['r'])) {
  ?>
    <p>Acceso Restringido</p>
  <?php } else { ?>
    <div class="row align-items">
      <s26-sidebar title="Ajuste de Stock" icon="sliders-h" @update="allRows" @reset="onReset" v-model="activeSidebar">
        <template v-slot:header>
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
      <s26-table :fields="fields" :rows="s26_data.info.count" @get="allRows" :sidebar="activeSidebar" v-model="filter.perPage">
        <template v-slot:body>
          <tr v-for="item in s26_data.items" :key="item.id">
            <td class="length-int">
              <a href="#" class=" btn btn-link p-0" @click.prevent="getInfo(item.product_id, 'products')">
                {{ item.ean_code }}
              </a>
            </td>
            <td class="length-description">
              {{ item.name }} / {{ item.model }} / {{ item.trademark }} / {{ item.sku }}
            </td>
            <td class="length-status text-center">
              <s26-icon icon="dollar-sign"></s26-icon>
              {{ $s26.currency(item.pvp) }}
            </td>
            <td class="length-status text-center">{{ item.amount }}</td>
          </tr>
        </template>
      </s26-table>
    </div>
  <?php
  }
  ?>
</div>
<?= footer_(); ?>