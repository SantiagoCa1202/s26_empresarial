<?= head_(); ?>
<?= header_(); ?>
<div id="s26-photos-view" class="container-fluid s26-container" tabindex="0">
  <?php
  if (empty($_SESSION['permitsModule']['r'])) {
  ?>
    <p>Acceso Restringido</p>
  <?php } else { ?>
    <div class="row align-items">
      <s26-sidebar title="Fotos" icon="images" @update="allRows" @reset="onReset" v-model="activeSidebar">
        <template v-slot:header>
          <?php
          if ($_SESSION['permitsModule']['w']) {
          ?>
            <button type="button" class="btn btn-info form-control mb-2" @click="activeUploadPhoto = true">
              Subir Fotos
            </button>
          <?php
          }
          ?>
        </template>
        <template v-slot:search>
          <div class="container">
            <s26-form-input label="Nombre" size="sm" id="name" type="text" v-model="filter.name" maxlength="100" @keyup="allRows"></s26-form-input>
            <s26-select-status all label="Estado" v-model="filter.status" @change="allRows"></s26-select-status>
            <s26-date-picker id="date" enable="range" size="sm" v-model="filter.date" @change="allRows" label="fecha"></s26-date-picker>
            <button :class="['btn mt-2 w-100', filter.favorites == 1 ? 'btn-warning' : 'btn-outline-warning']" @click="filterFavorites">Favoritos</button>
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
      <section :class="['main', { 'mainWidth-100': !activeSidebar }]">
        <div class="s26-container-table">
          <div class="row mx-0">
            <div class="col-6 col-md-3 my-2 px-2 container-img" v-for="item in items" :key="item.id" @click="">
              <img class="rounded w-100 min-img hover-none pointer" :src="item.href" tabindex="0" @click="setIdRow(item.id, 'watch')" />
              <div :class="['row mx-0 bg-white shadow-sm rounded', item.status == 2 ? 'border-bottom border-2 border-danger' : '']">
                <?php
                if ($_SESSION['permitsModule']['r']) {
                ?>
                  <div class="col-2 s26-align-center">
                    <button type="button" class="btn btn-link" style="color: #fcbf12" @click="setIdRow(item.id, 'watch')">
                      <s26-icon icon='eye'></s26-icon>
                    </button>
                  </div>
                <?php
                }
                if ($_SESSION['permitsModule']['u']) {
                ?>
                  <div class="col-2 s26-align-center">
                    <button type="button" class="btn btn-link" @click="setIdRow(item.id, 'update')">
                      <s26-icon icon='edit'></s26-icon>
                    </button>
                  </div>
                <?php
                }
                if ($_SESSION['permitsModule']['d']) {
                ?>
                  <div class="col-2 s26-align-center">
                    <button type="button" class="btn btn-link text-danger" @click="setIdRow(item.id, 'delete')">
                      <s26-icon icon='trash-alt'></s26-icon>
                    </button>
                  </div>
                <?php
                }
                if ($_SESSION['permitsModule']['r']) {
                ?>
                  <div class="col-2 s26-align-center">
                    <a :href="item.href" class="btn btn-link" :download="item.src">
                      <s26-icon icon='arrow-alt-circle-down' class="fs-6"></s26-icon>
                    </a>
                  </div>
                <?php
                }
                if ($_SESSION['permitsModule']['u']) {
                ?>
                  <div class="col-4">
                    <button type="button" class="btn btn-link float-end" @click="addToFavorites(item.id)">
                      <s26-icon icon='star' class="text-warning fs-6" v-if="item.favorites == 1">
                      </s26-icon>
                      <s26-icon icon='star' class="text-secondary" v-else>
                      </s26-icon>
                    </button>
                  </div>
                <?php
                }
                ?>
              </div>
            </div>
          </div>
          <button type="button" class="btn btn-outline-primary mt-3 mx-2 float-end" @click="loadMore" v-if="perPage < rows">
            Cargar Más...
          </button>
          <div v-if="rows == 0" class="w-100 h-75 text-secondary fs-1 fw-bold s26-align-center pointer" @click="activeUploadPhoto = true">
            Subir Fotos
          </div>
        </div>
      </section>
      <?php
      if ($_SESSION['permitsModule']['r']) {
      ?>
        <!-- Modal Ver-->
        <transition name="slide-fade">
          <s26-read-photo v-model="action" :id="idRow" v-if="action == 'watch'">
          </s26-read-photo>
        </transition>
      <?php
      }
      if ($_SESSION['permitsModule']['w']) {
      ?>
        <!-- Modal Nuevo-->
        <transition name="slide-fade">
          <s26-upload-photos v-if="activeUploadPhoto" v-model="activeUploadPhoto" @update="allRows" />
        </transition>
      <?php
      }
      if ($_SESSION['permitsModule']['u']) {
      ?>
        <!-- Modal Editar-->
        <transition name="slide-fade">
          <s26-form-photo v-if="action == 'update'" v-model="action" :id="idRow" @update="allRows" />
        </transition>
      <?php
      }
      if ($_SESSION['permitsModule']['d']) {
      ?>
        <!-- Modal Eliminar -->
        <transition name="slide-fade">
          <s26-delete v-model="action" @update="allRows" v-if="action == 'delete'" :post_delete="'photos/delPhoto/' + idRow"></s26-delete>
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