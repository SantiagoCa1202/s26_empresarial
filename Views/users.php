<?= head_(); ?>
<?= header_(); ?>
<div id="s26-users-view" class="container-fluid s26-container" tabindex="0">
  <div class="row align-items">
    <s26-sidebar title="Usuarios" icon="user" @update="allRows" @reset="onReset" v-model="activeSidebar">
      <template v-slot:header>
        <button type="button" class="btn btn-s26-info form-control mb-2" @click="setIdRow(0, 'update')">
          Nuevo
        </button>
      </template>
      <template v-slot:search>
        <div class="container">
          <s26-form-input label="Id" size="sm" id="Id" type="text" v-model="filter.id" maxlength="11" @keyup="allRows" number autofocus></s26-form-input>
          <s26-form-input label="Cédula" size="sm" id="document" type="text" v-model="filter.document" maxlength="10" @keyup="allRows" number></s26-form-input>
          <s26-form-input label="Nombres" size="sm" id="name" type="text" v-model="filter.name" maxlength="100" @keyup="allRows" text></s26-form-input>
          <s26-select-role all v-model="filter.role" @change="allRows"></s26-select-role>
          <s26-select-establishment all v-model="filter.establishment" @change="allRows"></s26-select-establishment>
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
              <li class="list-group-item border-0" @click="setIdRow(item.id, 'update')">
                Editar
              </li>
              <li class="list-group-item border-0" @click="setIdRow(item.id, 'delete')">
                Eliminar
              </li>
            </s26-dropdown>
          </td>
        </tr>
      </template>
    </s26-table>
    <!-- Modal Nuevo-->
    <transition name="slide-fade">
      <s26-form-user v-model="action" :id="idRow" v-if="action == 'update'" @update="allRows"></s26-form-user>
    </transition>
    <!-- Modal Eliminar -->
    <transition name="slide-fade">
      <s26-delete v-model="action" @update="allRows" v-if="action == 'delete'" :post_delete="'users/delUser/' + idRow"></s26-delete>
    </transition>
  </div>
</div>
<script src="<?= asset('js/components/forms/form-user.js') ?>"></script>

<?= data_style('users'); ?>
<?= footer_(); ?>