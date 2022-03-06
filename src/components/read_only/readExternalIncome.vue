<template>
  <s26-modal-multiple
    id="readExternalIncome"
    title="Información de Ingreso Externo"
    :levels="levels"
    body_style="min-height: 260px"
    @hideModal="hideModal"
    readOnly
    update
    @update="infoData(id)"
  >
    <template v-slot:level-0>
      <div class="col-sm-12">
        <s26-input-read
          label="Razón Social"
          :content="form.tradename"
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
      <div class="col-12" v-if="id !== 0">
        <span class="fw-bold">Creado el:</span>
        {{ $s26.formatDate(form.created_at, "xl") }}
      </div>
    </template>
    <template v-slot:level-1>
      <div
        class="col-12"
        v-for="(item, index) in form.external_incomes_amount"
        :key="index"
      >
        <div class="row variants">
          <div class="col-6 text-primary">
            <a
              href="#"
              class="text-decoration-none"
              @click.prevent="
                $s26.getInfoRow(item.bank_account_id, 'bankAccounts')
              "
            >
              {{ item.bank_account }}
            </a>
            <span v-if="item.bank_account_id == 0"> En Caja Matriz </span>
          </div>
          <div
            :class="[
              'col-6 text-end',
              item.status == 1 ? 'text-success' : 'text-danger',
            ]"
          >
            {{ item.status == 1 ? "Activo" : "Inactivo" }}
          </div>
          <div class="col-6 fs-5 fw-bold my-2">
            {{ item.account == 1 ? "Costo" : "Ganancia" }}
          </div>
          <div class="col-6 fs-5 fw-bold text-end my-2">
            <span>
              <s26-icon icon="dollar-sign"></s26-icon>
            </span>
            {{ $s26.currency(item.amount) }}
          </div>
          <div class="col-12" v-if="id !== 0">
            <span class="fw-bold">Creado el:</span>
            {{ $s26.formatDate(item.created_at, "xl") }}
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
      form: {},
      levels: ["Información de Ingreso Externo", "Información de Importe"],
    };
  },
  created() {
    if (this.id !== 0 && this.id !== null) this.infoData(this.id);
  },
  methods: {
    infoData(id) {
      this.axios
        .get("/externalIncomes/getExternalIncome/" + id)
        .then((res) => (this.form = res.data))
        .catch((err) => console.log(err));
    },
    hideModal() {
      this.$emit("input", null);
      $s26.delete_cookie("id", "externalIncomes");
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
  color: var(--s26-blue);
}
</style>