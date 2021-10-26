<?= head_(); ?>
<?= header_(); ?>
<div id="s26-categories-view" class="container-fluid s26-container" tabindex="0">
  <?php
  if (empty($_SESSION['permitsModule']['r'])) {
  ?>
    <p>Acceso Restringido</p>
  <?php } else { ?>
    <div class="row align-items">
      <s26-sidebar title="Categorías" icon="box-tissue" @update="allRows" @reset="onReset" v-model="activeSidebar" :url_export="url_export">
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
            <s26-form-input label="Nombre" size="sm" id="name" type="text" v-model="filter.name" maxlength="100" @keyup="allRows" text></s26-form-input>
            <s26-form-input label="Descripción" size="sm" id="description" type="text" v-model="filter.description" maxlength="100" @keyup="allRows" text></s26-form-input>
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
      <section :class="['main px-0', { 'mainWidth-100': !activeSidebar }]">
        <div class="s26-container-table">
          <div class="row mx-3">
            <div :class="['col-sm-12 mb-3', item.status == 2 ? 'opacity-50' : '']" v-for="item in s26_data.items" :key="item.id">
              <div class="tarjet-category">
                <div class="category-header row mx-0">
                  <div class="col-10 s26-align-y-center">
                    <div class="btn-icon s26-align-center me-3" :style="'background-color:' + item.color">
                      <s26-icon :icon="item.icon.class" class="text-white"></s26-icon>
                    </div>
                    <h1 class="fs-5 fw-bold m-0"> {{ item.name }} </h1>
                  </div>
                  <div :class="['col-2 text-end', item.status == 1 ? 'text-success' : 'text-danger']">
                    {{ item.status == 1 ? "Activo" : "Inactivo" }}
                  </div>
                </div>
                <div class="category-body row mx-0">
                  <div :class="['col-3 s26-align-y-center p-2 pointer row mx-0 global-hover', sub.id == idSubRow ? 'global-focus' : '', sub.status == 2 ? 'opacity-50' : '']" v-for="sub in item.subcategories.items" :key="sub.id" @click="item.status == 1 ? setSubCategory(sub.id) : ''">
                    <div class="btn-icon s26-align-center me-3 col-3" :style="'background-color:' + sub.color">
                      <s26-icon :icon="sub.icon.class" class="text-white"></s26-icon>
                    </div>
                    <div class="col-9">
                      <h1 class="fs-6 fw-600 m-0"> {{ sub.name }} </h1>
                    </div>
                    <div class="col-8" v-if="level == 'subcategory' && idSubRow == sub.id"></div>
                    <div class="col-4 row mx-0 px-0 bg-white rounded" v-if="level == 'subcategory' && idSubRow == sub.id">
                      <?php
                      if ($_SESSION['permitsModule']['u']) {
                      ?>
                        <div class="col-6 s26-align-center">
                          <button type="button" class="btn btn-link" @click="setIdRow(sub.id, 'updateSubcategory')">
                            <s26-icon icon='edit'></s26-icon>
                          </button>
                        </div>
                      <?php
                      }
                      if ($_SESSION['permitsModule']['d']) {
                      ?>
                        <div class="col-6 s26-align-center">
                          <button type="button" class="btn btn-link text-danger" @click="setIdRow(sub.id, 'deleteSubCategory')">
                            <s26-icon icon='trash-alt'></s26-icon>
                          </button>
                        </div>
                      <?php
                      }
                      ?>
                    </div>
                  </div>
                  <div class="col-12 s26-align-y-center p-1" v-if="item.subcategories.length == 0">
                    <button type="button" class="btn btn-primary w-100" @click="setIdRow(item.id, 'subcategory')">
                      <s26-icon icon="plus" class="text-white"></s26-icon>
                      Añadir Sub Categorias
                    </button>
                  </div>

                </div>
                <div class="category-footer row mx-0">
                  <div class="col-10"></div>
                  <div class="col-2 row mx-0 px-0">
                    <?php
                    if ($_SESSION['permitsModule']['w']) {
                    ?>
                      <div class="col-3 s26-align-center">
                        <button type="button" class="btn btn-link" @click="setIdRow(item.id, 'subcategory')">
                          <s26-icon icon='plus'></s26-icon>
                        </button>
                      </div>
                    <?php
                    }
                    if ($_SESSION['permitsModule']['r']) {
                    ?>
                      <div class="col-3 s26-align-center">
                        <button type="button" class="btn btn-link" style="color: #fcbf12" @click="setIdRow(item.id, 'watch')">
                          <s26-icon icon='eye'></s26-icon>
                        </button>
                      </div>
                    <?php
                    }
                    if ($_SESSION['permitsModule']['u']) {
                    ?>
                      <div class="col-3 s26-align-center">
                        <button type="button" class="btn btn-link" @click="setIdRow(item.id, 'update')">
                          <s26-icon icon='edit'></s26-icon>
                        </button>
                      </div>
                    <?php
                    }
                    if ($_SESSION['permitsModule']['d']) {
                    ?>
                      <div class="col-3 s26-align-center">
                        <button type="button" class="btn btn-link text-danger" @click="setIdRow(item.id, 'delete')">
                          <s26-icon icon='trash-alt'></s26-icon>
                        </button>
                      </div>
                    <?php
                    }
                    ?>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>
      <!-- <s26-table :fields="fields" :rows="s26_data.info.count" @get="allRows" :sidebar="activeSidebar" v-model="filter.perPage" action>
        <template v-slot:body>
          <tr v-for="item in s26_data.items" :key="item.id">
            <td class="length-action fs-5">
              <div class="btn-icon s26-align-center" :style="'background-color:' + item.color">
                <s26-icon :icon="item.icon.class" class="text-white"></s26-icon>
              </div>
            </td>
            <td class="length-description">
              {{ item.name }}
            </td>
            <td class="length-description">
              {{ item.description }}
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
          <tr>hola</tr>
        </template>
      </s26-table> -->
      <?php
      if ($_SESSION['permitsModule']['r']) {
      ?>
        <!-- Modal Ver-->
        <transition name="slide-fade">
          <s26-read-category v-model="action" :id="idRow" v-if="action == 'watch'">
          </s26-read-category>
        </transition>
      <?php
      }
      if ($_SESSION['permitsModule']['u'] || $_SESSION['permitsModule']['w']) {
      ?>
        <!-- Modal Nuevo-->
        <transition name="slide-fade">
          <s26-form-category v-model="action" :id="idRow" v-if="action == 'update' || action == 'subcategory' || action == 'updateSubcategory'" @update="allRows"></s26-form-category>
        </transition>
      <?php

      }
      ?>
      <?php
      if ($_SESSION['permitsModule']['d']) {
      ?>
        <!-- Modal Eliminar -->
        <transition name="slide-fade">
          <s26-delete v-model="action" @update="allRows" v-if="action == 'delete' || action == 'deleteSubCategory'" :post_delete="url_delete + idRow"></s26-delete>
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

<style>
  .tarjet-category {
    background: #fff;
    height: 185px;
    padding: .8rem;
    box-shadow: 0 2px 8px 2px rgb(93 130 170 / 21%);
    border-radius: .4rem;
    color: var(--s26-blue);
  }

  .category-header {
    height: 20%;
  }

  .category-footer {
    height: 15%;
    align-items: center;
    padding-left: .5rem;
    padding-right: .5rem;
  }

  .category-body {
    height: 65%;
    overflow: auto;
    padding: 1.2rem;
    align-items: center;
  }
</style>