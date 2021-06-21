<?= head_(); ?>
<?= header_(); ?>

<div id="s26-users-view" class="container-fluid s26-container" tabindex="0">
  <?php
  if (empty($_SESSION['permitsModule']['r'])) {
  ?>
    <p>Acceso Restringido</p>
  <?php } else { ?>
    <div class="row align-items">
      <s26-sidebar title="Usuarios" icon="user" @update="allRows" @reset="onReset" v-model="activeSidebar" :url_export="url_export">
        <template v-slot:header>
          <?php
          if ($_SESSION['permitsModule']['w']) {
          ?>
            <button type="button" class="btn btn-s26-info form-control mb-2" @click="setIdRow(0, 'update')">
              Nuevo
            </button>
          <?php
          }
          ?>
        </template>
        <template v-slot:search>
          <div class="container">
            <s26-form-input label="Id" size="sm" id="Id" type="text" v-model="filter.id" maxlength="11" @keyup="allRows" number autofocus></s26-form-input>
            <s26-form-input label="Cédula" size="sm" id="document" type="text" v-model="filter.document" maxlength="10" @keyup="allRows" number></s26-form-input>
            <s26-form-input label="Nombres" size="sm" id="name" type="text" v-model="filter.name" maxlength="100" @keyup="allRows" text></s26-form-input>
            <s26-select-role all v-model="filter.role" @change="allRows"></s26-select-role>
            <s26-select-establishment all v-model="filter.establishment" @change="allRows"></s26-select-establishment>
            <s26-select-status all lbl="Estado" v-model="filter.status" @change="allRows"></s26-select-status>
            <s26-date-picker id="date" enable="range" size="sm" v-model="filter.date" @change="allRows" label="fecha"></s26-date-picker>
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
            <td class="length-description">
              {{ item.name }} {{ item.last_name }}
            </td>
            <td class="length-description">{{ item.email }}</td>
            <td class="length-int">{{ item.phone }}</td>
            <td class="length-action">{{ item.role.name }}</td>
            <td class="length-action text-center">
              {{ item.establishment.n_establishment.padStart(3, 0) }}
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
                  <li class="list-group-item border-0" v-if="item.btnUp" @click="item.btnUp ? setIdRow(item.id, 'update') : ''">
                    Editar
                  </li>
                <?php
                }
                ?>
                <?php
                if ($_SESSION['permitsModule']['d']) {
                ?>
                  <li class="list-group-item border-0" v-if="item.btnDel" @click="item.btnDel ? setIdRow(item.id, 'delete') : ''">
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
          <s26-watch-user v-model="action" :id="idRow" v-if="action == 'watch'">
          </s26-watch-user>
        </transition>
      <?php
      }
      ?>
      <?php
      if ($_SESSION['permitsModule']['u'] || $_SESSION['permitsModule']['w']) {
        if (
          $_SESSION['userData']['user_root'] == 1 &&
          $_SESSION['userData']['role_id'] == 1 ||
          $_SESSION['userData']['user_root'] == 0 &&
          $_SESSION['userData']['role_id'] == 1
        ) {
      ?>
          <!-- Modal Nuevo-->
          <transition name="slide-fade">
            <s26-form-user v-model="action" :id="idRow" v-if="action == 'update'" @update="allRows"></s26-form-user>
          </transition>
      <?php
        }
      }
      ?>
      <?php
      if ($_SESSION['permitsModule']['d']) {
      ?>
        <!-- Modal Eliminar -->
        <transition name="slide-fade">
          <s26-delete v-model="action" @update="allRows" v-if="action == 'delete'" :post_delete="'users/delUser/' + idRow"></s26-delete>
        </transition>
      <?php
      }
      ?>
    </div>
    <?= data_style('users'); ?>
  <?php
  }
  ?>
</div>

<?= footer_(); ?>