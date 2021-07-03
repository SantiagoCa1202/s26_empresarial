<?= head_(); ?>
<?= header_(); ?>

<div id="s26-establishments-view" class="container-fluid s26-container" tabindex="0">
  <?php
  if (empty($_SESSION['permitsModule']['r'])) {
  ?>
    <p>Acceso Restringido</p>
  <?php } else { ?>
    <div class="row align-items">
      <s26-sidebar title="Establecimientos" icon="building" @update="allRows" @reset="onReset" v-model="activeSidebar" :url_export="url_export">
        <template v-slot:search>
          <div class="container">
            <s26-form-input label="N° de Establecimiento" size="sm" id="n_establishment" type="text" v-model="filter.n_establishment" maxlength="11" @keyup="allRows" number autofocus></s26-form-input>
            <s26-form-input label="Nombre Comercial" size="sm" id="tradename" type="text" v-model="filter.tradename" maxlength="100" @keyup="allRows"></s26-form-input>
            <s26-form-input label="Ciudad" size="sm" id="city" type="text" v-model="filter.city" maxlength="100" @keyup="allRows" text></s26-form-input>
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
                    {{ rows }}
                  </span>
                </s26-tarjet-info>
              </div>
            </div>
          </div>
        </template>
      </s26-sidebar>
      <s26-table :fields="fields" :rows="rows" @get="allRows" :sidebar="activeSidebar" v-model="perPage" id action>
        <template v-slot:body>
          <tr v-for="item in items" :key="item.id">
            <td class="length-int">{{ item.id }}</td>
            <td class="length-action text-center">{{ item.n_establishment }}</td>
            <td class="length-description">
              {{ item.tradename }}
            </td>
            <td class="length-int">{{ item.city }}</td>
            <td class="length-int">{{ item.phone }}</td>
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
          <s26-read-establishment v-model="action" :id="idRow" v-if="action == 'watch'">
          </s26-read-establishment>
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