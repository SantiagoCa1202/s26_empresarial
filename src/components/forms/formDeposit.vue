<template>
  <s26-modal-multiple
    id="formDeposit"
    :title="(id == 0 ? 'Nuevo ' : 'Editar ') + 'Deposito'"
    :levels="levels"
    body_style="min-height: 400px;"
    @onReset="onReset"
    @onSubmit="onSubmit"
    @hideModal="hideModal"
    ref="modal_multiple"
  >
    <template v-slot:level-0>
      <div class="col-12">
        <s26-select-bank-account
          id="form-bank_account_id"
          v-model="form.bank_account_id"
          s26_required
        >
        </s26-select-bank-account>
      </div>
      <div class="col-12">
        <s26-editor
          id="form-description"
          label="Descripción"
          :height="value == 0 ? 200 : 180"
          v-model="form.description"
        ></s26-editor>
      </div>
      <div class="col-6" v-if="permit_establishment && id == 0">
        <s26-select-establishment
          id="form-establishment"
          v-model="form.establishment_id"
          s26_required
          @change="getBoxes"
        >
        </s26-select-establishment>
      </div>
      <div class="col">
        <s26-select-status
          label="Estado"
          id="form-status"
          v-model="form.status"
          s26_required
        >
        </s26-select-status>
      </div>
      <div class="col-12" v-if="id !== 0">
        <span class="fw-bold">Creado el:</span>
        {{ $s26.formatDate(form.created_at, "xl") }}
      </div>
    </template>
    <template v-slot:level-1>
      <div class="col-12" v-for="(item, index) in form.arr_boxes" :key="index">
        <div class="row variants">
          <div class="col-12 mb-2">
            <span class="fw-600">
              {{ item.name }} / {{ item.establishment }}
            </span>
            <button
              type="button"
              :class="[
                'btn btn-link text-decoration-none border-0 p-0 float-end',
                item.status == 1 ? 'text-success' : 'text-danger',
              ]"
              @click="item.status = item.status == 1 ? 2 : 1"
            >
              {{ item.status == 1 ? "Activo" : "Inactivo" }}
            </button>
          </div>
          <div class="col" v-if="item.amount > 0">
            <s26-input-read label="Efectivo" :content="item.amount" money>
            </s26-input-read>
          </div>
          <div class="col-6" v-if="item.amount > 0 && item.status == 1">
            <s26-form-input
              label="Monto"
              type="tel"
              v-model="item.deposit_amount"
              money
              s26_required
              @keyup="val_amount(index)"
            >
            </s26-form-input>
          </div>
          <div
            class="col-12 text-center fw-600 text-danger"
            v-if="item.amount <= 0"
          >
            Caja Sin Fondos
          </div>
        </div>
      </div>
    </template>
  </s26-modal-multiple>
</template>
<script>
const def_form = () => {
  return {
    id: "",
    bank_account_id: "",
    description: "",
    establishment_id: "",
    status: "",
    created_at: "",
    arr_boxes: [],
  };
};
export default {
  props: {
    value: {
      type: String,
      required: true,
    },
    id: {
      type: Number,
      required: true,
    },
  },
  data: function () {
    return {
      form: def_form(),
      permit_establishment: $permit_establishment,
      levels: ["Información de Deposito", "Deposito Por Caja"],
    };
  },
  created() {
    if (this.id !== 0 && this.id !== null) this.infoData(this.id);
    !this.permit_establishment && this.id == 0 ? this.getBoxes() : null;
  },
  methods: {
    infoData(id) {
      this.axios
        .get("/deposits/getDeposit/" + id)
        .then((res) => {
          this.form = res.data;
          for (const i in this.form.arr_boxes) {
            if (Object.hasOwnProperty.call(this.form.arr_boxes, i)) {
              const box = this.form.arr_boxes[i];
              this.getBox(box.box_id, i);
            }
          }
        })
        .catch((err) => console.log(err));
    },

    onSubmit() {
      this.form.id = this.id;
      const val_amount = this.form.arr_boxes.reduce(
        (a, b) => a + (parseFloat(b.deposit_amount) || 0),
        0
      );
      if (val_amount <= 0) {
        this.$alertify.error("El deposito debe ser mayor a 0");
        return;
      }

      this.$alertify.confirm(
        `Desea ${this.id == 0 ? "Ingresar " : "Actualizar"} Deposito?.`,
        () => {
          let formData = $s26.json_to_formData(this.form);
          $s26.show_loader_points();
          this.axios
            .post("/deposits/setDeposit", formData)
            .then((res) => {
              if (res.data.type == 1) {
                this.onReset();
                this.$alertify.success(res.data.msg);
              } else if (res.data.type == 2) {
                this.$alertify.success(res.data.msg);
              } else {
                this.$alertify.error(res.data.msg);
              }
              $s26.hide_loader_points();
              this.$emit("update");
            })
            .catch((e) => console.log(e));
        },
        () => this.$alertify.error("Acción Cancelada")
      );
    },
    onReset() {
      if (this.id !== 0 && this.id) {
        this.infoData(this.id);
      } else {
        this.form = def_form();
      }
      $("[s26-required]").removeClass("is-invalid");
      this.$refs.modal_multiple.start();
    },
    hideModal() {
      this.$emit("input", null);
    },
    getBoxes() {
      const params = {
        establishment_id: this.form.establishment_id,
        status: 1,
        cash: 1,
        perPage: 1000000,
      };
      this.axios
        .get("/boxes/getBoxes/", {
          params,
        })
        .then((res) => {
          this.form.arr_boxes = res.data.items;
        })
        .catch((err) => console.log(err));
    },
    getBox(box_id, i) {
      const params = {
        id: box_id,
        cash: 1,
      };
      this.axios
        .get("/boxes/getBox/", { params })
        .then((res) => {
          const deposit_amount =
            this.form.arr_boxes[i].status == 1
              ? parseFloat(this.form.arr_boxes[i].deposit_amount)
              : 0;
          this.form.arr_boxes[i].amount =
            parseFloat(res.data.amount) + deposit_amount;
        })
        .catch((err) => console.log(err));
    },
    val_amount(i) {
      let box = this.form.arr_boxes[i];

      if (parseFloat(box.deposit_amount) > parseFloat(box.amount)) {
        box.deposit_amount = box.amount;
        this.$alertify.error(
          "El monto a depositar no puede ser mayor al efectivo contable en caja"
        );
      }
    },
  },
};
</script>
<style scoped>
.variants {
  overflow: hidden;
  box-shadow: 0 10px 5px -6px rgb(93 130 170 / 21%) !important;
  border: 1px solid #dee2e6 !important;
  border-radius: 0.25rem !important;
  margin-bottom: 1rem !important;
  padding-top: 0.5rem !important;
  padding-bottom: 0.5rem !important;
}
</style>