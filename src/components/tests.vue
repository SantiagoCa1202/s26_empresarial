<template>
  <s26-modal-multiple
    id="formProvider"
    :title="(id == 0 ? 'Nuevo ' : 'Editar ') + 'test'"
    :levels="levels"
    body_style="min-height: 350px; height: 350px"
    @onReset="onReset"
    @onSubmit="onSubmit"
    @hideModal="hideModal"
  >
    <template v-slot:level-0>
      <div class="col-12">
        <s26-input-read label="Level" content="111111111111111"> </s26-input-read>
      </div>
    </template>
    <template v-slot:level-1>
      <div class="col-12">
        <s26-input-read label="Level" content="2222222222222222"> </s26-input-read>
      </div>
    </template>
    <template v-slot:level-2>
      <div class="col-12">
        <s26-input-read label="Level" content="3333333333333333"> </s26-input-read>
      </div>
    </template>
    <template v-slot:level-3>
      <div class="col-12">
        <s26-input-read label="Level" content="4444444444444444"> </s26-input-read>
      </div>
    </template>
    <template v-slot:level-4>
      <div class="col-12">
        <s26-input-read label="Level" content="555555555555555"> </s26-input-read>
      </div>
    </template>
  </s26-modal-multiple>
</template>

<script>
export default {
  props: {},
  data: function () {
    return {
      id: 0,
      levels: [
        "Información Comercial",
        "Contactos",
        "Dirección Actual",
        "Cuenta Bancaria",
        "Categorías",
      ],
    };
  },
  created() {},
  methods: {
    onSubmit() {},
    onReset() {},
    getCategories() {},
    selectCategory(id) {},
    hideModal() {
      this.$emit("input", null);
    },
  },
};
</script>

<style>
</style>