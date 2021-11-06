<?= head_(); ?>
<?= header_(); ?>

<div id="s26-productsSeries-view" class="container-fluid s26-container" tabindex="0">
  <?php
  if (empty($_SESSION['permitsModule']['r'])) {
  ?>
    <p>Acceso Restringido</p>
  <?php } else { ?>
    <div class="row align-items">
      <s26-sidebar title="Series" icon="server" @update="allRows" @reset="onReset" v-model="activeSidebar" :url_export="url_export">
        <template v-slot:search>
          <div class="container">
            <s26-form-input label="Serie" id="serie" v-model="filter.serie" maxlength="100" @keyup="allRows" autofocus></s26-form-input>
            <s26-form-input label="Nombre" id="name" v-model="filter.name" maxlength="100" @keyup="allRows"></s26-form-input>
            <s26-select-buys label="N° de Doc." id="form-document_id" v-model="filter.document_id" @change="allRows" is_null assign all></s26-select-buys>
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
      <s26-table :fields="fields" :rows="s26_data.info.count" @get="allRows" :sidebar="activeSidebar" v-model="filter.perPage" id action>
        <template v-slot:body>
          <tr v-for="item in s26_data.items" :key="item.id">
            <td class="length-int">{{ item.id }}</td>
            <td class="length-description">{{ item.name }} - {{ item.model }} - {{ item.trademark }}</td>
            <td class="length-action">{{ item.serie }}</td>
            <td class="length-description">
              <span v-if="item.document_id == -1 || item.document_id == 0 ">
                {{ item.document_id == -1 ? 'Por Asignar' : item.document_id == 0 ? 'Sin Documento' : '' }}
              </span>
              <div  class="text-primary" v-if="item.document_id > 0" @click="getDocument(item.document_id)">
                <span class="fw-bold">
                  {{ item.document.alias }}:
                </span>
                {{ item.n_document }}
              </div>
            </td>
            <td :class="[
                'length-status',
                item.status == 1 ? 'text-success' : 'text-danger',
              ]">
              {{ item.status == 1 ? "Activo" : "Inactivo" }}
            </td>
            <td class="length-action">
              <s26-dropdown>
                <?php
                if ($_SESSION['permitsModule']['u']) {
                ?>
                  <li class="list-group-item border-0" @click="setIdRow(item.id, 'update')">
                    Editar
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
      if ($_SESSION['permitsModule']['u'] || $_SESSION['permitsModule']['w']) {
      ?>
        <!-- Modal Nuevo-->
        <transition name="slide-fade">
          <s26-form-product-serie v-model="action" :id="idRow" v-if="action == 'update'" @update="allRows"></s26-form-product-serie>
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