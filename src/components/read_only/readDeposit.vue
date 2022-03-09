<template>
  <s26-modal-multiple
    id="readDeposit"
    title="Información de Deposito"
    :levels="levels"
    body_style="height: 450px"
    @hideModal="hideModal"
    readOnly
    update
    @update="infoData(id)"
  >
    <template v-slot:level-0>
      <div class="col-12">
        <s26-input-read
          label="Cuenta Destino"
          :content="form.bank_account"
          :link="'bankAccounts,' + form.bank_account_id"
        ></s26-input-read>
      </div>
      <div class="col-sm-12">
        <s26-textarea-read
          label="Descripción"
          :content="form.description"
          rows="6"
        >
        </s26-textarea-read>
      </div>
      <div class="col-6">
        <s26-input-read
          label="Total Depositado"
          :content="
            form.arr_boxes.reduce(
              (a, b) => a + (parseInt(b.deposit_amount) || 0),
              0
            )
          "
          money
        >
        </s26-input-read>
      </div>
      <div class="col-6">
        <s26-input-read
          label="Estado"
          :content="form.status == 1 ? 'Activo' : 'Inactivo'"
        >
        </s26-input-read>
      </div>
      <div class="col-12" v-if="id !== 0">
        <span class="fw-bold">Creado el:</span>
        {{ $s26.formatDate(form.created_at, "xl") }}
      </div>
    </template>
    <template v-slot:level-1>
      <div class="mb-3 col-12" v-for="box in form.arr_boxes" :key="box.id">
        <div class="tarjet-boxes">
          <div class="box-header row mx-0">
            <div class="col-10">
              <h1 class="fs-5 fw-bold m-0">
                {{ box.id }} -
                {{ box.name }}
              </h1>
            </div>
            <div
              :class="[
                'col-2 text-end',
                box.status == 1 ? 'text-success' : 'text-danger',
              ]"
            >
              {{ box.status == 1 ? "Activo" : "Inactivo" }}
            </div>
          </div>
          <div class="box-body row mx-0">
            <div class="col-6 text-center fs-6">
              Efectivo
              <br />
              Depositado
            </div>
            <div class="col-6 text-end">
              <s26-icon icon="dollar-sign" class="fs-2"></s26-icon>
              <span class="fs-2">
                {{ $s26.currency(box.deposit_amount) }}
              </span>
            </div>
          </div>
          <div class="box-footer row mx-0">
            <div class="col-9">
              <span class="text-secondary">
                {{ box.establishment }}
              </span>
            </div>
          </div>
        </div>
      </div>
    </template>
  </s26-modal-multiple>
</template>
<script>
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
      form: {
        arr_boxes: [],
      },
      levels: ["Información de Deposito", "Deposito Por Caja"],
    };
  },
  created() {
    if (this.id !== 0 && this.id !== null) this.infoData(this.id);
  },
  methods: {
    infoData(id) {
      this.axios
        .get("/deposits/getDeposit/" + id)
        .then((res) => (this.form = res.data))
        .catch((err) => console.log(err));
    },
    hideModal() {
      this.$emit("input", null);
      $s26.delete_cookie("id", "deposits");
    },
  },
};
</script>
<style>
.tarjet-boxes {
  background: #fff;
  height: 155px;
  padding: 0.5rem;
  box-shadow: 0 2px 8px 2px rgb(93 130 170 / 21%);
  border-radius: 0.4rem;
  color: var(--s26-blue);
}

.box-header,
.box-footer {
  height: 25%;
  align-items: center;
  padding-left: 0.5rem;
  padding-right: 0.5rem;
}

.box-body {
  height: 50%;
  padding-left: 0.5rem;
  padding-right: 0.5rem;
  align-items: center;
}
</style>