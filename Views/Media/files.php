<?= head_(); ?>
<?= header_(); ?>

<div id="s26-files-view" class="container-fluid s26-container" tabindex="0">
  <?php
  if (empty($_SESSION['permitsModule']['r'])) {
  ?>
    <p>Acceso Restringido</p>
  <?php } else { ?>
    <div class="row align-items">
      <s26-sidebar title="Archivos" icon="folder-open" @update="allRows" @reset="onReset" v-model="activeSidebar">
        <template v-slot:header>
          <?php
          if ($_SESSION['permitsModule']['w']) {
          ?>
            <button type="button" class="btn btn-info form-control mb-2" @click="activeUploadFile = true">
              Subir Archivo
            </button>
          <?php
          }
          ?>
        </template>
        <template v-slot:search>
          <div class="container">
            <s26-form-input label="Id" size="sm" id="Id" type="text" v-model="filter.id" maxlength="11" @keyup="allRows" number autofocus></s26-form-input>
            <s26-form-input label="Nombre" size="sm" id="name" type="text" v-model="filter.name" maxlength="100" @keyup="allRows" text></s26-form-input>
            <s26-select-status all label="Estado" v-model="filter.status" @change="allRows"></s26-select-status>
            <s26-date-picker id="date" enable="range" size="sm" v-model="filter.date" @change="allRows" label="fecha" :dates="s26_data.dates"></s26-date-picker>
            <button :class="['btn mt-2 w-100', filter.favorites == 1 ? 'btn-warning' : 'btn-outline-warning']" @click="filterFavorites">Favoritos</button>
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
            <td :class="['length-action text-center', 
              item.type == 'pdf' ? 'text-danger' : '',
              item.type == 'excel' ? 'text-success' : ''
            ]">
              <s26-icon :icon="'file-' + item.type" class="fs-6">
              </s26-icon>
            </td>
            <td class="length-description">{{ item.name }}</td>
            <td class="length-description">{{ item.description }}</td>
            <td class="length-action text-center">
              <a @click.prevent="addToFavorites(item.id)">
                <s26-icon icon='star' class="text-warning fs-6" v-if="item.favorites == 1">
                </s26-icon>
                <s26-icon icon='star' class="text-secondary" v-else>
                </s26-icon>
              </a>
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
                  <li class="list-group-item border-0">
                    <a :href="item.href" target="_blank">
                      Ver
                    </a>
                  </li>
                  <li class="list-group-item border-0">
                    <a :href="item.href" target="_blank" :download="item.name">
                      Descargar
                    </a>
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
      if ($_SESSION['permitsModule']['u'] || $_SESSION['permitsModule']['w']) {
      ?>
        <!-- Modal Nuevo-->
        <transition name="slide-fade">
          <s26-upload-files v-if="activeUploadFile" v-model="activeUploadFile" @update="allRows"></s26-upload-files>
        </transition>
      <?php
      }
      if ($_SESSION['permitsModule']['u']) {
      ?>
        <!-- Modal Editar-->
        <transition name="slide-fade">
          <s26-form-file v-if="action == 'update'" v-model="action" :id="idRow" @update="allRows" />
        </transition>
      <?php
      }
      if ($_SESSION['permitsModule']['d']) {
      ?>
        <!-- Modal Eliminar -->
        <transition name="slide-fade">
          <s26-delete v-model="action" @update="allRows" v-if="action == 'delete'" :post_delete="'files/delFile/' + idRow"></s26-delete>
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