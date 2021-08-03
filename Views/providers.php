<?= head_(); ?>
<?= header_(); ?>

<div id="s26-providers-view" class="container-fluid s26-container" tabindex="0">
  <?php
  if (empty($_SESSION['permitsModule']['r'])) {
  ?>
    <p>Acceso Restringido</p>
  <?php } else { ?>
    <div class="row align-items">
      <s26-sidebar title="Proveedores" icon="truck-loading" @update="allRows" @reset="onReset" v-model="activeSidebar" :url_export="url_export">
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
            <s26-form-input label="Ruc" size="sm" id="document" type="text" v-model="filter.document" maxlength="13" @keyup="allRows" number></s26-form-input>
            <s26-form-input label="Razón Social" size="sm" id="business_name" type="text" v-model="filter.business_name" maxlength="100" @keyup="allRows" text></s26-form-input>
            <s26-form-input label="Nombre Comercial" size="sm" id="tradename" type="text" v-model="filter.tradename" maxlength="100" @keyup="allRows" text></s26-form-input>

            <s26-select-cities size="sm" id="city" v-model="filter.city_id" @change="allRows" all>
            </s26-select-cities>
            <s26-select-status all label="Estado" v-model="filter.status" @change="allRows"></s26-select-status>
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
              </div>
            </div>
          </div>
        </template>
      </s26-sidebar>
      <s26-table :fields="fields" :rows="s26_data.info.count" @get="allRows" :sidebar="activeSidebar" v-model="filter.perPage" id action>
        <template v-slot:body>
          <tr v-for="item in s26_data.items" :key="item.id">
            <td class="length-int">{{ item.id }}</td>
            <td class="length-int">{{ item.document }}</td>
            <td class="length-description">
              {{ item.business_name }}
            </td>
            <td class="length-int">{{ item.mobile_seller }}</td>
            <td class="length-action">{{ item.alias }}</td>
            <td :class="[
                'length-status',
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
          <s26-read-provider v-model="action" :id="idRow" v-if="action == 'watch'">
          </s26-read-provider>
        </transition>
      <?php
      }
      if ($_SESSION['permitsModule']['u'] || $_SESSION['permitsModule']['w']) {
      ?>
        <!-- Modal Nuevo-->
        <transition name="slide-fade">
          <s26-form-provider v-model="action" :id="idRow" v-if="action == 'update'" @update="allRows"></s26-form-provider>
        </transition>
      <?php

      }
      ?>
      <?php
      if ($_SESSION['permitsModule']['d']) {
      ?>
        <!-- Modal Eliminar -->
        <transition name="slide-fade">
          <s26-delete v-model="action" @update="allRows" v-if="action == 'delete'" :post_delete="'providers/delProvider/' + idRow"></s26-delete>
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