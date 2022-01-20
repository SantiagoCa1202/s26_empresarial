<?= head_(); ?>
<?= header_(); ?>

<div id="s26-documents-view" class="container-fluid s26-container" tabindex="0">
  <?php
  if (empty($_SESSION['permitsModule']['r'])) {
  ?>
    <p>Acceso Restringido</p>
  <?php } else { ?>
    <div class="row align-items">
      <s26-sidebar :title="authorizations ? 'Autorizaciones' : 'Documentos'" icon="receipt" @update="authorizations ? getAuthorizations() : allRows()" @reset="onReset" v-model="activeSidebar" :url_export="url_export">
        <template v-slot:header>
          <?php
          if ($_SESSION['permitsModule']['w']) {
          ?>
            <button type="button" class="btn btn-info w-100 mb-2" @click="setIdRow(0, 'update')">
              Nuevo
            </button>
          <?php
          }
          ?>
          <button type="button" class="btn btn-outline-primary w-100 mb-2" @click="changeAuth()">
            {{ authorizations ? 'Regresar' : 'Gestionar Autorizaciones' }}
          </button>
        </template>
        <template v-slot:search>
          <div class="container" v-if="!authorizations">
            <s26-select-type-document id="filter-type_doc_id" v-model="filter.type_doc_id" all @change="allRows"></s26-select-type-document>
            <s26-form-input label="N° de Punto" id="n_point" type="number" v-model="filter.n_point" @keyup="allRows"></s26-form-input>
            <s26-select-status all label="Estado" v-model="filter.status" @change="allRows"></s26-select-status>
          </div>
          <div class="container" v-else>
            <s26-form-input label="N° de Autorización" id="authorization" type="number" v-model="filter.n_authorization" @keyup="getAuthorizations"></s26-form-input>
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
      <s26-table :fields="fields" :rows="s26_data.info.count" @get="authorizations ? getAuthorizations() : allRows()" :sidebar="activeSidebar" v-model="filter.perPage" id action>
        <template v-slot:body>
          <tr v-for="item in s26_data.items" :key="item.id">
            <td class="length-int">{{ item.id }}</td>
            <td class="length-int">{{ item.document }}</td>
            <td class="length-action">
              {{ item.n_point.padStart(3,'0') }}
            </td>
            <template v-if="!authorizations">
              <td class="length-int">{{ item.sequential_numbering }}</td>
              <td :class="[
                'length-status text-center',
                item.status == 1 ? 'text-success' : 'text-danger',
              ]">
                {{ item.status == 1 ? "Activo" : "Inactivo" }}
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
                  if ($_SESSION['permitsModule']['r']) {
                  ?>
                    <li class="list-group-item border-0" @click="setIdRow(item.id, 'new_auth')">
                      Nueva Aut.
                    </li>
                  <?php
                  }
                  ?>
                </s26-dropdown>
              </td>
            </template>
            <template v-else>
              <td class="length-description">{{ item.authorization }}</td>
              <td class="length-status text-center">
                {{ item.from_ }}
              </td>
              <td class="length-status text-center">
                {{ item.to_ }}
              </td>
              <td class="length-status text-center">
                {{ $s26.formatDate(item.authorization_date) }}
              </td>
              <td class="length-status text-center">
                {{ $s26.formatDate(item.expiration_date) }}
              </td>
              <td class="length-action">
                <a href="" @click.prevent="setIdRow(item.id, 'update_auth')">
                  <s26-icon icon="edit"></s26-icon>
                </a>
              </td>
            </template>
          </tr>
        </template>

      </s26-table>
      <?php
      if ($_SESSION['permitsModule']['r']) {
      ?>
        <!-- Modal Ver-->
        <transition name="slide-fade">
          <s26-read-document v-model="action" :id="idRow" v-if="action == 'watch'">
          </s26-read-document>
        </transition>
      <?php
      }
      if ($_SESSION['permitsModule']['u'] || $_SESSION['permitsModule']['w']) {
      ?>
        <!-- Modal Nueva Autorizacion-->
        <transition name="slide-fade">
          <s26-form-document v-model="action" :id="idRow" v-if="action == 'update'" @update="allRows"></s26-form-document>
        </transition>
        <!-- Modal Nueva Autorizacion-->
        <transition name="slide-fade">
          <s26-form-authorization v-model="action" :id="idRow" v-if="action == 'new_auth' || action == 'update_auth' " @update="authorizations ? getAuthorizations() : allRows()"></s26-form-authorization>
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