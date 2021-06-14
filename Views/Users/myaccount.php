<?= head_(); ?>
<?= header_(); ?>
<div id="s26-users-info-view" class="container-fluid s26-container" tabindex="0">
  <div class="row align-items">
    <aside class="sidebar s26-sidebar-user">
      <nav class="s26-navbar-user">
        <ul class="menu-user">
          <li @click.prevent="modal = 'info_user'" :class="modal == 'info_user' ? 'focus-btn-nav-user' : '' ">
            <a>
              <i class="fa fa-id-card icon-sm"></i>
              <div class="lbl-li-user">
                Información personal
              </div>
            </a>
          </li>
          <li @click.prevent="payRoll" :class="modal == 'payroll' ? 'focus-btn-nav-user' : '' ">
            <a>
              <i class="fa fa-credit-card icon-sm"></i>
              <div class="lbl-li-user">
                Rol de pagos
              </div>
            </a>
          </li>
          <li @click.prevent="my_sales" :class="modal == 'my_sales' ? 'focus-btn-nav-user' : '' ">
            <a>
              <i class="fa fa-shopping-bag icon-sm"></i>
              <div class="lbl-li-user">
                Mis Ventas
              </div>
            </a>
          </li>
          <li @click.prevent="my_notes" :class="modal == 'my_notes' ? 'focus-btn-nav-user' : '' ">
            <a>
              <i class="fas fa-clipboard icon-sm"></i>
              <div class="lbl-li-user">
                Mis Notas
              </div>
            </a>
          </li>
          <li @click.prevent="notifications" :class="modal == 'notifications' ? 'focus-btn-nav-user' : '' ">
            <a>
              <i class="fa fa-bell icon-sm"></i>
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
                    <button class="btn-info-tbl" @click="idRow = item.id">
                      <i class="fa fa-info"></i>
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
              <button class="btn btn-primary btn-sm ms-4" @click="idNote = 0">Nuevo</button>
            </h2>
            <div class="row">
              <div class="col-6 mb-3" v-for="item in items" v-key="item.id">
                <div class="card s26-card-notes" :style="'border-left: .4rem solid' + item.color ">
                  <div class="card-body">
                    <h3 class="card-title h6 fw-bold"> {{ item.name }} </h3>
                    <pre class="card-text">{{ item.note }}</pre>
                    <div class="card-buttons">
                      <button class="btn-icon">
                        <i class="fas fa-trash-alt text-danger icon-sm" @click="del_note = item.id"></i>
                      </button>
                      <button class="btn-icon">
                        <i class="fas fa-edit text-primary icon-sm" @click="idNote = item.id"></i>
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
                <i class="fas fa-sync"></i>
              </button>
            </h2>
            <div class="row">
              <div class="col-12 mb-3" v-for="item in items" v-key="item.id">
                <div class="card s26-card-notification">
                  <div class="card-body">
                    <div class="icon-notification">
                      <div class="mx-auto" :style="icon[item.icon]">
                        <i :class="['fas', 'fa-' + item.icon, 'mx-auto']"></i>
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
                              <i class="text-secondary fas fa-angle-right"></i>
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
                            <i class="fas fa-link"></i>
                          </a>
                          <button class="btn btn-danger btn-sm btn-del-ntf" v-if="item.issued_by.id == idUser" @click="del_notification = item.id">
                            <i class="fas fa-trash-alt"></i>
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
<style>
  .s26-custom-select {
    position: relative;
    cursor: pointer;
  }

  .s26-custom-select .s26-select-value {
    position: relative;
    display: flex;
    align-items: center;
  }

  .s26-select-value .icon-sort-down-select {
    position: absolute;
    right: 10px;
    transition: .3s;
  }

  .s26-select-value .icon-sort-down-select i {
    font-size: 1.2rem;
  }

  .icon-sort-down-select.active {
    transform: rotate(-180deg) translateY(-2px);
  }

  .s26-select-container {
    background: #fff;
    position: absolute;
    width: 100%;
    height: 0px;
    margin-top: 5px;
    top: 55px !important;
    border-radius: .2rem;
    overflow: auto;
    transition: .2s;
  }

  .s26-select-container.active {
    height: 168px;
    box-shadow: 0 1px 3px 2px rgba(0, 0, 0, .15);
    border: 1px solid #ced4da;
    z-index: 1060;
  }

  .s26-select-container-input-search {
    width: 100%;
    padding: .5rem;
    position: sticky;
    top: 0;
    background: #fff;
    display: flex;
    align-items: center;
  }

  .s26-select-container-input-search .s26-btn-search {
    position: absolute;
    border: none;
    background: none;
    right: 10px;
    color: rgba(0, 0, 0, .5);
    transition: .2s;
  }

  .s26-select-container-input-search .s26-btn-search:hover,
  .s26-select-container-input-search .s26-btn-search:focus {
    color: var(--bs-primary);
    outline: none;
  }

  .s26-select-options {
    padding: .3rem .8rem;
  }

  .s26-select-options:hover,
  .s26-select-options:focus,
  .s26-select-options.focus {
    background-color: var(--bs-info);
    color: #fff;
    transition: .2s;
  }
</style>
<script>
  const idUser = <?= $_SESSION['idUser']; ?>;
  const create_notifications_users = <?= $_SESSION['userData']['create_notifications_users']; ?>
</script>
<script>
  let S26UsersInfoView = new Vue({
    el: "#s26-users-info-view",
    data: function() {
      return {
        fields: [{
            name: "fecha",
            class: "length-description",
          },
          {
            name: "descripción",
            class: "length-description",
          },

          {
            name: "importe",
            class: "length-int",
          },
          {
            name: "forma de pago",
            class: "length-description",
          },
        ],
        filter: {
          date: ''
        },
        icon: {
          building: {
            background: '#3A86FA',
            color: '#fff'
          },
          user: {
            background: '#20c997',
            color: '#fff'
          }
        },
        payRoll_arr: {},
        modal: 'notifications',
        items: [],
        rows: 0,
        idRow: null,
        idNote: null,
        idNotification: null,
        del_note: null,
        del_notification: null,
        perPage: 25,
        idUser: idUser
      };
    },
    created() {
      this.notifications()
    },
    methods: {
      payRoll() {
        this.modal = 'payroll'

        axios
          .get("/users/getPayroll/0")
          .then((res) => {
            this.payRoll_arr = res.data;
            this.payments_records();
          })
          .catch((err) => {
            console.log(err);
          });
      },
      payments_records() {
        const params = {
          date: this.filter.date,
          perPage: this.perPage
        }
        axios
          .get("/users/getPayRecords/", {
            params
          })
          .then((res) => {
            console.log(res)
            this.items = res.data.items;
            this.rows = res.data.info.count;
          })
          .catch((err) => {
            console.log(err);
          });
      },
      my_notes() {
        this.modal = 'my_notes'
        axios
          .get("/users/getMyNotes/")
          .then((res) => {
            console.log(res)
            this.items = res.data.items;
            this.rows = res.data.info.count;
          })
          .catch((err) => {
            console.log(err);
          });
      },
      notifications() {
        this.modal = 'notifications'
        axios
          .get("/users/getNotifications/")
          .then((res) => {
            console.log(res)
            this.items = res.data.items;
            this.rows = res.data.info.count;
          })
          .catch((err) => {
            console.log(err);
          });
      },
    },
  });
  Vue.component("s26-info-payroll", {
    props: {
      value: {
        type: String,
        required: true,
      },
      id: {
        type: String,
        required: true,
      },
    },
    data: function() {
      return {
        form: {
          payment_method: {
            name: '',
          }
        },
      };
    },
    created() {
      if (this.value !== 0 && this.value !== null) {
        this.infoData(this.value);
      }
      setTimeout(() => {
        $(".s26-modal").on("click", (e) => {
          this.hideModal();
        });
        $(".s26-modal-content").click(function(e) {
          e.stopPropagation();
        });
      }, 100);
    },
    methods: {
      infoData(id) {
        axios
          .get("/users/getPayRecord/" + id)
          .then((res) => {
            this.form = res.data;
          })
          .catch((err) => {
            console.log(err);
          });
      },

      hideModal() {
        this.$emit("input", null);
      },
    },
    template: `
    <div id="formPyroll" 
      class="s26-modal" 
      tabindex="-1"
    >
      <div class="s26-modal-dialog s26-modal-dialog-centered">
        <div class="s26-modal-content">
          <div class="modal-header">
            <h5 class="modal-title">
              Información de pago
            </h5>
            <button type="button" class="btn-close" @click="hideModal"></button>
          </div>
          <div class="modal-body">
            <div class="row">
              <div class="col-12 col-sm-6">
                <div class="mb-4">
                  <label class="form-label">Id</label>
                  <div class="form-control form-control-sm">{{ form.id }}</div>
                </div>
              </div>
              <div class="col-12 col-sm-6">
                <div class="mb-4">
                  <label class="form-label">Fecha</label>
                  <div class="form-control form-control-sm">
                    {{ form.amount_date }}
                  </div>
                </div>
              </div>
              
              <div class="col-12 col-sm-6">
                <label class="form-label">Importe / Valor</label>
                <div class="input-group input-group-sm mb-3">
                  <span class="input-group-text">$</span>
                  <div class="form-control">
                    {{ form.amount }}
                  </div>
                </div>
              </div>
              <div class="col-12 col-sm-6">
                <div class="mb-4">
                  <label class="form-label">Forma de pago</label>
                  <div class="form-control form-control-sm">
                    {{ form.payment_method.name }}
                  </div>
                </div>
              </div>
              <div class="col-12">
                <div class="mb-4">
                  <label class="form-label">Descripción</label>
                  <div class="form-control form-control-sm">
                    {{ form.description }}
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  `,
  });
  Vue.component("s26-form-notes", {
    props: {
      value: {
        type: String,
        required: true,
      },
      id: {
        type: String,
        required: true,
      },
    },
    data: function() {
      return {
        form: {
          id: '',
          name: '',
          note: '',
          color: '#ffc107',
          created_at: '',
        }
      };
    },
    created() {
      if (this.value !== 0 && this.value !== null) {
        this.infoData(this.value);
      }
      setTimeout(() => {
        $(".s26-modal").on("click", (e) => {
          this.hideModal();
        });
        $(".s26-modal-content").click(function(e) {
          e.stopPropagation();
        });
      }, 100);
    },
    methods: {
      infoData(id) {
        axios
          .get("/users/getMyNote/" + id)
          .then((res) => {
            this.form = res.data;
            let date = new Date(res.data.created_at);
            this.form.created_at = new Intl.DateTimeFormat("es-ES", {
              dateStyle: "full",
              timeStyle: "short",
              calendar: "ecuador",
            }).format(date);
          })
          .catch((err) => {
            console.log(err);
          });
      },
      onSubmit() {
        this.form.id = this.value;
        show_loader_points();
        axios
          .post("/users/setNote", this.form)
          .then((res) => {
            if (res.data.type == 1) {
              this.onReset();
              alertify.success(res.data.msg);
            } else if (res.data.type == 2) {
              alertify.success(res.data.msg);
            } else {
              alertify.error(res.data.msg);
            }
            hide_loader_points();
            this.$emit("update");
          })
          .catch((e) => {
            console.log(e);
          });
      },
      onReset() {
        this.form.name = '';
        this.form.note = '';
        this.form.color = '#ffc107';
      },

      hideModal() {
        this.$emit("input", null);
      },
    },
    template: `
    <div id="formNotes" 
      class="s26-modal" 
      tabindex="-1"
    >
      <div class="s26-modal-dialog s26-modal-dialog-centered">
        <div class="s26-modal-content">
          <div class="modal-header">
            <h5 class="modal-title">
              {{ value !== 0 ? 'Editar Nota' : 'Nueva Nota' }}
            </h5>
            <button type="button" class="btn-close" @click="hideModal"></button>
          </div>
          <div class="modal-body">
            <form @submit.prevent="onSubmit">
              <div class="row">
                <div class="col-12">
                  <s26-form-input 
                    label="Título" 
                    size="sm" 
                    id="form-name" 
                    type="text" 
                    v-model="form.name" 
                    maxlength="100" 
                    text 
                  >
                  </s26-form-input>
                </div>
                <div class="col-12">
                  <div class="mb-3">
                    <label class="form-label">Descripción</label>
                    <textarea class="form-control resize-none" cols="30" rows="10" v-model="form.note"></textarea>
                  </div>
                </div>
                <div class="col-12">
                  <div class="row">
                    <label class="col-sm-6 col-form-label">Seleccionar Color:</label>
                    <div class="col-sm-6">
                      <input type="color" class="form-control form-control-color float-end" v-model="form.color" title="Choose your color">
                    </div>
                  </div> 
                </div>
                <div class="col-12 mb-4"v-if="id !== 0"> {{form.created_at}} </div>
              </div>
              <button type="button" class="btn btn-outline-danger" v-if="value == 0" @click="onReset" >Resetear</button>
              <button type="button" class="btn btn-outline-danger" v-if="value !== 0" @click="infoData(value)" >Deshacer</button>
              <button type="submit" class="btn btn-s26-success" > {{ value == 0 ? 'Añadir' : 'Guardar' }}</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  `,
  });
  Vue.component("s26-form-select-user", {
    props: {
      label: String,
      id: String,
      message: {
        type: String,
        default: "",
      },
      size: String,
      placeholder: String,
      variant: {
        type: String,
        default: "",
      },
      value: {},
      s26_required: Boolean,
    },
    data: function() {
      return {
        isActive: false,
        selected: '',
        options: [],
        search: '',
        perPage: 50,
        rows: 0,
        position: {
          top: "0",
        },
      };
    },
    created() {
      val_inputs();
      setTimeout(() => {
        $(`html, .s26-modal, .s26-modal-content, .s26-popup:not(#s26-custom-select-${this.id})`).on("click", (e) => {
          this.isActive = false;
        });
        $(`#s26-custom-select-${this.id}`).click(function(e) {
          e.stopPropagation();
        });
      }, 100);
    },
    methods: {
      allRows() {
        const params = {
          name: this.search,
          perPage: this.perPage,
        };
        axios
          .get("/users/getUsers/", {
            params,
          })
          .then((res) => {
            console.log(res);
            this.options = res.data.items;
            this.rows = res.data.info.count;

          })
          .catch((err) => {
            console.log(err);
          });
      },
      activeSelect() {
        this.isActive = !this.isActive

        if (this.isActive) {
          let s26SelectUser = document.getElementById(this.id);
          this.position.top = s26SelectUser.getBoundingClientRect().bottom >= 500 ?
            "-148px" :
            "55px";

          setTimeout(() => {
            $('.s26-select-container').addClass('active')
          }, 100);

          this.allRows()

          $('.s26-select-container-input-search input').focus()
          $('.s26-select-container').animate({
            scrollTop: 0
          }, 0);
        }
      },
      selectOption(id, value) {
        this.isActive = false
        this.search = '';
        this.perPage = 50;
        this.$emit('input', id);
        this.selected = value;
      },
      loadMore() {
        this.perPage = this.perPage + 25;
        this.allRows();
      }
    },
    template: `
    <div :id="'s26-custom-select-' + id" class="s26-custom-select s26-popup mb-3">
      <label :for="id" class="form-label" v-if="label"> {{ label }} </label>
      <div 
        :id="id" 
        :class="['form-control form-control-' + size,'s26-select-value', variant ]"
        tabindex="0"
        @click="activeSelect"
        @keyup.13="activeSelect"
      >
      
      <div> {{ value != 0 ? selected : '-- seleccionar --' }} </div>
      <span :class="['icon-sort-down-select', {active: isActive}]">
          <i class="fas fa-sort-down"></i>
        </span>
      </div>
        <transition name="fade">
        <div 
          v-if="isActive"
          class="s26-select-container"  
          :style="position"
        >
          <div class="s26-select-container-input-search">
            <input 
              type="text" 
              text
              class="form-control form-control-sm" 
              placeholder="Buscar..."
              v-model="search"
              @keyup.107="allRows"
            />
            <transition name="fade">
              <button v-if="search != ''" title="buscar (+)" type="button" class="s26-btn-search"   @click="allRows">
                <i class="fas fa-search"></i>
              </button>
            </transition>
          </div>
          <div class="s26-select-container-options">
            <div 
              :class="['s26-select-options', value == 0 ? 'focus' : '']" 
              tabindex="0" 
              @click="$emit('input', 0)"
            >
              -- seleccionar --
            </div>
            <div 
              :class="['s26-select-options', value == option.id ? 'focus' : '']" 
              tabindex="0" 
              v-for="option in options" :key="option.id"
              @click="selectOption(option.id, option.name)"
            >
              {{ option.name }} - {{ option.document }}
            </div>
            <button 
              v-if="perPage < rows"
              type="button" 
              class="btn btn-link" 
              @click="loadMore"
            >
              Cargar Mas..
            </button>
          </div>
        </div>
        </transition>

      <p class="invalid-feedback" v-if="s26_required">{{ message }} </p>
    </div>
  `,
  });

  Vue.component("s26-form-notifications", {
    props: {
      value: {
        type: String,
        required: true,
      }
    },
    data: function() {
      return {
        form: {
          idUser: 0,
          name: '',
          description: '',
          url: '',
          expiration_date: '',
        },
        filter: {
          name: ''
        },
        msg_error: '',
        create_notifications_users: create_notifications_users
      };
    },
    created() {
      setTimeout(() => {
        $(".s26-modal").on("click", (e) => {
          this.hideModal();
        });
        $(".s26-modal-content").click(function(e) {
          e.stopPropagation();
        });
      }, 100);
    },
    methods: {
      onSubmit() {
        $("[s26-required]").removeClass("is-invalid");
        if (this.form.name == "") {
          $("#form-name").addClass("is-invalid").focus();
          this.msg_error = "Es necesario ingresar un nombre.";
          return false;
        }
        if (this.form.description == "") {
          $("#form-description").addClass("is-invalid").focus();
          this.msg_error = "Es necesario ingresar una descripción.";
          return false;
        }
        if (this.form.expiration_date == "") {
          $("#form-expiration_date").addClass("is-invalid").focus();
          this.msg_error = "Es necesario ingresar una fecha.";
          return false;
        }
        show_loader_points();
        axios
          .post("/users/setNotification", this.form)
          .then((res) => {
            console.log(res)
            if (res.data.type == 1) {
              this.onReset();
              alertify.success(res.data.msg);
            } else if (res.data.type == 2) {
              alertify.success(res.data.msg);
            } else {
              alertify.error(res.data.msg);
            }
            hide_loader_points();
            this.$emit("update");
          })
          .catch((e) => {
            console.log(e);
          });
      },
      onReset() {
        for (let form in this.form) {
          this.form[form] = "";
        }
      },

      hideModal() {
        this.$emit("input", null);
      },
    },
    template: `
    <div id="formNotes" 
      class="s26-modal" 
      tabindex="-1"
    >
      <div class="s26-modal-dialog s26-modal-dialog-centered">
        <div class="s26-modal-content">
          <div class="modal-header">
            <h5 class="modal-title">
              Nueva Notificación
            </h5>
            <button type="button" class="btn-close" @click="hideModal"></button>
          </div>
          <div class="modal-body">
            <form @submit.prevent="onSubmit">
              <div class="row">
                <div class="col-12" v-if="create_notifications_users == 1">
                  <s26-form-select-user 
                    label="Seleccionar Usuario" 
                    size="sm" 
                    id="form-select_user" 
                    v-model="form.idUser" 
                    search="filter.name"
                  >
                  </s26-form-select-user>
                </div>
                <div class="col-12">
                  <s26-form-input 
                    label="Nombre" 
                    size="sm" 
                    id="form-name" 
                    type="text" 
                    v-model="form.name" 
                    maxlength="100" 
                    text 
                    s26_required
                    :message="msg_error"
                  >
                  </s26-form-input>
                </div>
                <div class="col-12 mb-3">
                  <label class="form-label">Descripción</label>
                  <textarea 
                    id="form-description"
                    class="form-control 
                    resize-none" 
                    cols="30"rows="5" 
                    v-model="form.description"
                    s26-required
                    :message="msg_error"
                  ></textarea>
                  <p class="invalid-feedback">{{ msg_error }} </p>
                </div>
                <div class="col-12">
                  <s26-form-input 
                    label="Url" 
                    size="sm" 
                    id="form-url" 
                    type="text" 
                    v-model="form.url" 
                    maxlength="1000" 
                    text
                  >
                  </s26-form-input>
                </div>
                <div class="col-12 mb-3">
                  <s26-date-picker 
                    id="form-expiration_date"
                    enable="unique" 
                    size="sm" 
                    v-model="form.expiration_date" 
                    label="Fecha de Vencimiento" 
                    s26_required
                    :message="msg_error"
                    select_all_dates
                  ></s26-date-picker>
                </div>
              </div>
              <button type="button" class="btn btn-outline-danger" v-if="value == 0" @click="onReset" >Resetear</button>
              <button type="button" class="btn btn-outline-danger" v-if="value !== 0" @click="infoData(value)" >Deshacer</button>
              <button type="submit" class="btn btn-s26-success" > {{ value == 0 ? 'Añadir' : 'Guardar' }}</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  `,
  });
</script>
<style>
  .s26-sidebar-user {
    position: absolute;
    top: 0;
    left: 0;
    padding-top: 60px;
    padding-left: .5rem;
    padding-right: .5rem;
    background: #fff;
    width: 20%;
    height: 100vh;
    box-shadow: 0px -3px 10px #bebebe;

  }

  .s26-sidebar-user nav {

    color: #3c4043 !important;
  }

  .focus-btn-nav-user {
    background: #5a859d;
  }

  .s26-theme .s26-navbar-user li.focus-btn-nav-user a {
    color: #fff;
  }

  .s26-sidebar-user .icon-sm {
    width: 30px;
    height: 30px;
    padding: .25rem;
    font-size: 1.25rem;
    background: none;
  }

  .s26-sidebar-user .s26-navbar-user div.lbl-li-user {
    font-size: .9rem;
  }

  .main-user {
    margin-left: 20%;
    width: 80%;
    height: 100%;
    padding: 1.5rem;
  }

  .main-user .content-info {
    height: 100%;
    border: 1px solid #bebebe;
    border-radius: .5rem;
    background-color: #fff;
    padding: 1.5rem;
    overflow: auto;
    position: relative;
  }

  .info-tarjet {
    position: relative;
    width: 100%;
    height: auto;
    border: 1px solid #bebebe;
    border-radius: .5rem;
    padding: 2rem;
    margin-bottom: 1rem;
  }

  .info-tarjet .main {
    position: relative;
    padding: 0;
  }

  .btn-info-tbl {
    font-size: .9rem;
    cursor: pointer;
    border: none;
    background: none;
    transition: 0.3s;
    text-decoration: none;
  }

  .btn-info-tbl:hover {
    color: var(--bs-info)
  }

  .s26-card-notes .card-body {
    height: 170px;
    overflow: hidden;
    white-space: nowrap;
    text-overflow: ellipsis;
    position: relative;
  }

  .card-buttons {
    position: absolute;
    background: #fff;
    bottom: 5px;
    right: 5px;
  }

  .s26-card-notification .card-body {
    height: 120px;
    display: flex;
    padding: .5rem 1rem;
  }

  .icon-notification {
    height: 100%;
    width: 10%;
    margin-right: 1rem;
    display: flex;
    align-items: center;
  }

  .icon-notification div {
    width: 55px;
    height: 55px;
    display: flex;
    align-items: center;
    border-radius: 50%;
  }

  .icon-notification i {
    font-size: 1.8rem
  }

  .body-notification {
    width: 90%;

  }

  .body-notification .ntf-description {
    height: 47px;
    overflow: hidden;
  }

  .container-options-ntf {
    display: flex;
    align-items: center;
    justify-content: flex-end;
  }
</style>
<?= footer_(); ?>