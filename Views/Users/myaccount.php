<?= head_(); ?>
<?= header_(); ?>
<div id="s26-users-info-view" class="container-fluid s26-container" tabindex="0">
  <div class="row align-items">
    <aside class="sidebar s26-sidebar-user">
      <nav class="s26-navbar-user">
        <ul class="menu-user">
          <li @click.prevent="modal = 'info_user'" :class="modal == 'info_user' ? 'focus-btn-nav-user' : '' ">
            <a>
              <s26-icon icon="id-card"></s26-icon>
              <div class="lbl-li-user">
                Información personal
              </div>
            </a>
          </li>
          <li @click.prevent="payRoll" :class="modal == 'payroll' ? 'focus-btn-nav-user' : '' ">
            <a>
              <s26-icon icon="credit-card"></s26-icon>
              <div class="lbl-li-user">
                Rol de pagos
              </div>
            </a>
          </li>
          <li @click.prevent="my_sales" :class="modal == 'my_sales' ? 'focus-btn-nav-user' : '' ">
            <a>
              <s26-icon icon="shopping-bag"></s26-icon>
              <div class="lbl-li-user">
                Mis Ventas
              </div>
            </a>
          </li>
          <li @click.prevent="my_notes" :class="modal == 'my_notes' ? 'focus-btn-nav-user' : '' ">
            <a>
              <s26-icon icon="clipboard"></s26-icon>
              <div class="lbl-li-user">
                Mis Notas
              </div>
            </a>
          </li>
          <li @click.prevent="notifications" :class="modal == 'notifications' ? 'focus-btn-nav-user' : '' ">
            <a>
              <s26-icon icon="bell"></s26-icon>
              <div class="lbl-li-user">
                Notificaciones
              </div>
            </a>
          </li>
        </ul>
      </nav>
    </aside>
    <div class="main-user">
      <div class="content-info">
        <h1 class="text-center h3 mb-4">
          <?= $_SESSION['userData']['gender_id'] == 1 ? 'Bienvenido, ' : 'Bienvenida, ' ?>
          <?= $_SESSION['userData']['short_name'] ?>
        </h1>
        <div v-if="modal == 'info_user'">
          <div class="info-tarjet">
            <h2 class="h5 mb-4 fw-bold">Información basica</h2>
            <table class="table table-bordered">
              <tbody>
                <tr>
                  <th>Id</th>
                  <td class="text-start"><?= $_SESSION['userData']['id'] ?></td>
                </tr>
                <tr>
                  <th>Cédula</th>
                  <td class="text-start">
                    <?= $_SESSION['userData']['document'] ?>
                  </td>
                </tr>
                <tr>
                  <th>Nombres</th>
                  <td class="text-start"><?= $_SESSION['userData']['name'] ?></td>
                </tr>
                <tr>
                  <th>Apellidos</th>
                  <td class="text-start">
                    <?= $_SESSION['userData']['last_name'] ?>
                  </td>
                </tr>
                <tr>
                  <th>Fecha de nacimiento</th>
                  <td class="text-start">
                    <?= utf8_encode(
                      strftime(
                        '%A %d de %B de %Y',
                        strtotime(
                          $_SESSION['userData']['date_of_birth']
                        )
                      )
                    ) ?>
                  </td>
                </tr>
                <tr>
                  <th>Sexo</th>
                  <td class="text-start">
                    <?= $_SESSION['userData']['gender']['name'] ?>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
          <div class="info-tarjet">
            <h2 class="h5 mb-4 fw-bold">Información de contacto</h2>
            <table class="table table-bordered">

              <tbody>
                <tr>
                  <th>Correo Electrónico</th>
                  <td class="text-start text-lowercase"><?= $_SESSION['userData']['email'] ?></td>
                </tr>
                <tr>
                  <th>Teléfono</th>
                  <td class="text-start"><?= $_SESSION['userData']['phone'] ?></td>
                </tr>
              </tbody>
            </table>
          </div>
          <div class="info-tarjet">
            <h2 class="h5 mb-4 fw-bold">Información en la empresa</h2>
            <table class="table table-bordered">
              <tbody>
                <tr>
                  <th>Rol</th>
                  <td class="text-start">
                    <?= $_SESSION['userData']['role']['name'] ?>
                    <?= ($_SESSION['userData']['role_id'] == 1 && $_SESSION['userData']['user_root'] == 1) ? ' - Super Usuario' : '' ?>
                  </td>
                </tr>
                <tr>
                  <th>Establecimiento</th>
                  <td class="text-start">
                    <?= $_SESSION['userData']['establishment']['tradename'] ?>
                  </td>
                </tr>
                <tr>
                  <th>N° de establecimiento</th>
                  <td class="text-start">
                    <?= str_pad(
                      $_SESSION['userData']['establishment']['n_establishment'],
                      3,
                      '0',
                      STR_PAD_LEFT
                    )
                    ?>
                  </td>
                </tr>
                <tr>
                  <th>Teléfono comercial</th>
                  <td class="text-start"><?= $_SESSION['userData']['establishment']['phone'] ?></td>
                </tr>
                <tr>
                  <th>Afiliado al IESS</th>
                  <td class="text-start">
                    <?= $_SESSION['userData']['insurance'] ? 'si' : 'no' ?>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
        <div v-if="modal == 'payroll'">
          <div class="info-tarjet">
            <h2 class="h5 mb-4 fw-bold">Información de rol de pagos</h2>
            <table class="table table-bordered">
              <tbody>
                <tr>
                  <th>Tipo de cuenta</th>
                  <td class="text-start"> {{ payRoll_arr.account_type == 1 ? 'ahorros' : 'corriente'  }} </td>
                </tr>
                <tr>
                  <th>Cuenta bancaria</th>
                  <td class="text-start"> {{ payRoll_arr.bank_account }} </td>
                </tr>
                <tr>
                  <th>Entidad bancaria</th>
                  <td class="text-start"> {{ payRoll_arr.banking_entity }} </td>
                </tr>
                <tr>
                  <th>Salario mensual aproximado</th>
                  <td class="text-start"> {{ payRoll_arr.monthly_payment }} </td>
                </tr>
                <tr>
                  <th>Día de pago</th>
                  <td class="text-start"> {{ payRoll_arr.payment_date }} </td>
                </tr>
              </tbody>
            </table>
          </div>
          <div class="info-tarjet">
            <div class="row">
              <div class="col-7">
                <h2 class="h5 mb-4 fw-bold">Rol de pagos</h2>
              </div>
              <div class="col-5">
                <div class="row">
                  <label for="inputPassword" class="col-sm-5 col-form-label text-end">Filtrar por fecha:</label>
                  <div class="col-sm-7">
                    <s26-date-picker id="date" enable="range" size="sm" v-model="filter.date" @change="payments_records"></s26-date-picker>
                  </div>
                </div>
              </div>
            </div>
            <s26-table :fields="fields" :rows="rows" @get="payments_records" v-model="perPage" id info>
              <template v-slot:body>
                <tr v-for="item in items" :key="item.id">
                  <td class="length-int">{{ item.id }}</td>
                  <td class="length-action">{{ item.amount_date }}</td>
                  <td class="length-description">{{ item.description }}</td>
                  <td class="length-action">{{ item.amount }}</td>
                  <td class="length-action">{{ item.payment_method.name }}</td>
                  <td class="length-action">
                    <button class="btn-info-tbl" @click="idRow = parseInt(item.id)">
                      <s26-icon icon="info"></s26-icon>
                    </button>
                  </td>
                </tr>
              </template>
            </s26-table>
          </div>
          <!-- Modal Ver-->
          <transition name="slide-fade">
            <s26-info-payroll v-model="idRow" v-if="idRow != null">
            </s26-info-payroll>
          </transition>
        </div>
        <div v-if="modal == 'my_sales'"></div>
        <div v-if="modal == 'my_notes'">
          <div class="info-tarjet">
            <h2 class="h5 mb-4 fw-bold">
              Mis Notas
              <button class="btn btn-primary btn-sm ms-4" @click="idNote = parseInt(0)">Nuevo</button>
            </h2>
            <div class="row">
              <div class="col-6 mb-3" v-for="item in items" :key="item.id">
                <div class="card s26-card-notes" :style="'border-left: .4rem solid' + item.color ">
                  <div class="card-body">
                    <h3 class="card-title h6 fw-bold"> {{ item.name }} </h3>
                    <pre class="card-text">{{ item.note }}</pre>
                    <div class="card-buttons">
                      <button class="btn-icon" @click="del_note = parseInt(item.id)">
                        <s26-icon icon="trash-alt" class="text-danger icon-sm"></s26-icon>
                      </button>
                      <button class="btn-icon" @click="idNote = parseInt(item.id)">
                        <s26-icon icon="edit" class="text-primary icon-sm"></s26-icon>
                      </button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-- Modal Nuevo-->
          <transition name="slide-fade">
            <s26-form-notes v-model="idNote" v-if="idNote != null" @update="my_notes">
            </s26-form-notes>
          </transition>

          <!-- Modal Eliminar -->
          <transition name="slide-fade">
            <s26-delete v-model="del_note" @update="my_notes" v-if="del_note !== null" :post_delete="'users/delNote/' + del_note"></s26-delete>
          </transition>
        </div>
        <div v-if="modal == 'notifications'">
          <div class="info-tarjet">
            <h2 class="h5 mb-4 fw-bold">
              Notificaciones
              <button class="btn btn-primary btn-sm ms-4" @click="idNotification = 0">
                Nuevo
              </button>
              <button class="btn btn-warning btn-sm" @click="notifications">
                <s26-icon icon="sync"></s26-icon>
              </button>
            </h2>
            <div class="row">
              <div class="col-12 mb-3" v-for="item in items" :key="item.id">
                <div class="card s26-card-notification">
                  <div class="card-body">
                    <div class="icon-notification">
                      <div class="mx-auto" :style="icon[item.icon]">
                        <s26-icon :icon="item.icon" class="mx-auto"></s26-icon>
                      </div>
                    </div>
                    <div class="body-notification">
                      <div class="row">
                        <div class="col-6 fs-5 fw-bold"> {{ item.name }} </div>
                        <div class="col-6 text-end">
                          <div class="d-inline text-secondary">
                            {{ item.issued_by.name }}
                          </div>
                          <template v-if="item.issued_by.id == idUser">
                            <template v-if="item.user_id != idUser ">
                              <s26-icon icon="angle-right" class="text-secondary"></s26-icon>
                              <div class="d-inline text-secondary">
                                {{ item.userData }}
                              </div>
                            </template>
                          </template>
                        </div>
                        <div class="col-12 ntf-description">
                          {{ item.description }}
                        </div>
                        <div class="col-10 text-secondary">
                          Vence el: {{ item.expiration_date_xl }}
                        </div>
                        <div class="col-2 container-options-ntf">
                          <a :href="item.url" target="_blank" class="btn btn-link btn-sm btn-del-ntf" v-if="item.url != ''">
                            <s26-icon icon="link"></s26-icon>
                          </a>
                          <button class="btn btn-danger btn-sm btn-del-ntf" v-if="item.issued_by.id == idUser" @click="del_notification = item.id">
                            <s26-icon icon="trash-alt"></s26-icon>
                          </button>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-- Modal Nuevo-->
          <transition name="slide-fade">
            <s26-form-notifications v-model="idNotification" v-if="idNotification !== null" @update="notifications">
            </s26-form-notifications>
          </transition>

          <!-- Modal Eliminar -->
          <transition name="slide-fade">
            <s26-delete v-model="del_notification" @update="notifications" v-if="del_notification !== null" :post_delete="'users/delNotification/' + del_notification"></s26-delete>
          </transition>
        </div>
        <span class="text-info">
          Informa al gerente o administrador del establecimiento para reportar errores o solicitar actualización de información
        </span>
      </div>
    </div>
  </div>
</div>
<script>
  const idUser = <?= $_SESSION['idUser']; ?>;
  const create_notifications_users = <?= $_SESSION['userData']['create_notifications_users']; ?>
</script>
<?= footer_(); ?>