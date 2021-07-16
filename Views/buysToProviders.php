<?= head_(); ?>
<?= header_(); ?>
<div id="s26-buysToProviders-view" class="container-fluid s26-container" tabindex="0">
  <?php
  if (empty($_SESSION['permitsModule']['r'])) {
  ?>
    <p>Acceso Restringido</p>
  <?php } else { ?>
    <div class="row align-items">
      <s26-sidebar title="Compras" icon="shopping-bag" @update="allRows" @reset="onReset" v-model="activeSidebar" :url_export="url_export">
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
            <s26-form-input label="Ruc" size="sm" id="ruc" type="text" v-model="filter.ruc" maxlength="13" @keyup="allRows" number></s26-form-input>
            <s26-form-input label="Razón Social" size="sm" id="business_name" type="text" v-model="filter.business_name" maxlength="100" @keyup="allRows"></s26-form-input>
            <s26-form-input label="N° de Documento" size="sm" id="n_document" type="text" v-model="filter.n_document" maxlength="17" @keyup="allRows"></s26-form-input>
            <s26-form-input label="N° de Autorización" size="sm" id="n_authorization" type="text" v-model="filter.n_authorization" maxlength="50" @keyup="allRows" number></s26-form-input>
            <s26-select-establishment id="filter-establishment" all v-model="filter.establishment" @change="allRows"></s26-select-establishment>
            <s26-select-status all label="Estado" v-model="filter.status" @change="allRows"></s26-select-status>
            <s26-date-picker id="date" enable="range" size="sm" v-model="filter.date" @change="allRows" label="Fecha de Emisión"></s26-date-picker>
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
              </div>
            </div>
          </div>
        </template>
      </s26-sidebar>
      <s26-table :fields="fields" :rows="info.rows" @get="allRows" :sidebar="activeSidebar" v-model="perPage" action>
        <template v-slot:body>
          <tr v-for="item in items" :key="item.id">
            <td class="length-date">{{ $s26.formatDate(item.date_issue) }}</td>
            <td class="length-int">{{ item.n_document }}</td>
            <td class="length-description">
              {{ item.business_name }}
            </td>
            <td class="length-action">{{ $s26.currency(item.iva_) }}</td>
            <td class="length-action">{{ $s26.currency(item.total) }}</td>
            <td class="length-action">{{ item.type_doc.alias }}</td>
            <td class="length-action text-center">
              <a :href="item.file.href" v-if="item.file !== ''" target="_blank" :download="item.type_doc.name + '_' + item.n_document">
                <s26-icon icon="file-pdf" class="text-danger"></s26-icon>
              </a>
              <s26-icon icon="file-pdf" class="text-secondary" v-else></s26-icon>
            </td>
            <td class="length-action text-center">
              {{ item.establishment.n_establishment.padStart(3,'0') }}
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
          <s26-read-buytoproviders v-model="action" :id="idRow" v-if="action == 'watch'">
          </s26-read-buytoproviders>
        </transition>
      <?php
      }
      if ($_SESSION['permitsModule']['u'] || $_SESSION['permitsModule']['w']) {
      ?>
        <!-- Modal Nuevo-->
        <transition name="slide-fade">
          <s26-form-buytoproviders v-model="action" :id="idRow" v-if="action == 'update'" @update="allRows"></s26-form-buytoproviders>
        </transition>
      <?php
      }
      ?>
      <?php
      if ($_SESSION['permitsModule']['d']) {
      ?>
        <!-- Modal Eliminar -->
        <transition name="slide-fade">
          <s26-delete v-model="action" @update="allRows" v-if="action == 'delete'" :post_delete="'buysToProviders/delBuy/' + idRow"></s26-delete>
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