<?= head_(); ?>
<div id="s26-roles-view" class="container-fluid s26-container" tabindex="0">
  <?php
  if (empty($_SESSION['permitsModule']['r'])) {
  ?>
    <p>Acceso Restringido</p>
  <?php
  } else {
  ?>
    <div class="row align-items">
      <s26-sidebar title="Roles" icon="user-tag" @update="allRows" @reset="onReset" v-model="activeSidebar">
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
            <s26-form-input label="Nombre" size="sm" id="name" type="text" v-model="filter.name" maxlength="100" @keyup="allRows" text></s26-form-input>
            <s26-form-input label="Descripción" size="sm" id="description" type="text" v-model="filter.description" maxlength="100" @keyup="allRows" text></s26-form-input>
            <s26-select-status all v-model="filter.status" @change="allRows"></s26-select-status>
            <s26-date-picker enable="range" size="sm" v-model="filter.date" @change="allRows"></s26-date-picker>
          </div>
        </template>
        <template v-slot:info>
          <div class="container">
            <div class="row">
              <div class="col-12">
                <s26-tarjet-info title="Registros" variant="primary" icon="list-ul" :content="rows" />
              </div>
            </div>
          </div>
        </template>
      </s26-sidebar>
      <s26-table :fields="fields" :rows="rows" @get="allRows" :sidebar="activeSidebar" v-model="perPage">
        <template v-slot:body>
          <tr v-for="item in items" :key="item.id">
            <td class="length-int">{{ item.id }}</td>
            <td class="length-description">{{ item.name }}</td>
            <td class="length-description">{{ item.description }}</td>
            <td :class="[
                'length-status',
                item.status == 1 ? 'text-success' : 'text-danger',
              ]">
              {{ item.status == 1 ? "Activo" : "Inactivo" }}
            </td>
            <td class="length-action">
              <s26-dropdown v-if="item.id != 1">
                <?php
                if ($_SESSION['permitsModule']['u']) {
                ?>
                  <li class="list-group-item border-0" @click="setIdRow(item.id, 'update')">
                    Editar
                  </li>
                  <li class="list-group-item border-0" @click="setIdRow(item.id, 'permits')">
                    Permisos
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
      if ($_SESSION['permitsModule']['u'] || $_SESSION['permitsModule']['w']) {
      ?>
        <!-- Modal Nuevo Rol-->
        <transition name="slide-fade">
          <s26-form-role v-model="action" :id="idRow" v-if="action == 'update'" @update="allRows"></s26-form-role>
        </transition>
      <?php
      }
      ?>
      <?php
      if ($_SESSION['permitsModule']['u']) {
      ?>
        <!-- Modal Permisos de Rol-->
        <transition name="slide-fade">
          <s26-roles-permits v-model="action" :id="idRow" @update="allRows" v-if="action == 'permits'"></s26-roles-permits>
        </transition>
      <?php
      }
      ?>
      <?php
      if ($_SESSION['permitsModule']['d']) {
      ?>
        <!-- Modal Eliminar -->
        <transition name="slide-fade">
          <s26-delete v-model="action" @update="allRows" v-if="action == 'delete'" :post_delete="'roles/delRol/' + idRow"></s26-delete>
        </transition>
      <?php
      }
      ?>
    </div>
    <?= data_style('roles'); ?>
  <?php
  }
  ?>
</div>
<?= header_(); ?>
<?= footer_(); ?>