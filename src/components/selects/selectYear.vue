<template>
  <div :id="'s26-custom-select-' + id" class="s26-custom-select mb-3">
    <label :for="id" class="form-label w-100">
      Año
      <span class="text-danger" v-if="s26_required">
        <s26-icon icon="asterisk" class="icon_asterisk_required"></s26-icon>
      </span>
    </label>
    <div
      :id="id"
      class="form-control form-control-sm s26-select-value"
      tabindex="0"
      @click="$s26.activeSelect"
      @keypress.13="$s26.activeSelect"
    >
      <div>
        {{ value != 0 ? value : all ? "Todos" : "-- seleccionar --" }}
      </div>
      <s26-icon icon="angle-down" class="icon-angle-down"></s26-icon>
    </div>
    <div class="s26-select-container">
      <div class="select-year s26-select-container-options">
        <div
          :class="['s26-select-options text-center', value == 0 ? 'focus' : '']"
          tabindex="0"
          @click="select_option(0)"
          @keyup.13="select_option(0)"
        >
          {{ all ? "Todos" : "-- seleccionar --" }}
        </div>
        <div
          :class="[
            's26-select-options s26-align-center',
            value == option ? 'focus' : '',
          ]"
          tabindex="0"
          v-for="option in options"
          :key="option"
          @click="select_option(option)"
          @keyup.13="select_option(option)"
        >
          {{ option }}
        </div>
      </div>
    </div>
    <input
      type="hidden"
      :s26-required="s26_required"
      int="true"
      v-model="value"
    />
    <p class="invalid-feedback" v-if="s26_required"></p>
  </div>
</template>
<script>
export default {
  props: {
    id: String,
    value: {},
    all: Boolean,
    s26_required: Boolean,
    checkbook: Boolean,
  },
  data: function () {
    return {
      selected: "",
      options: [],
      search: "",
      rows: 0,
    };
  },
  mounted() {
    const currentTime = new Date();
    const year = currentTime.getFullYear();

    for (let i = year; i > 1990; i--) {
      this.options.push(i);
    }
  },
  methods: {
    select_option(val) {
      $(`div.s26-select-container`).hide("200");
      this.$emit("input", val);
      this.$emit("change");
    },
  },
};
</script>
<style>
.select-year.s26-select-container-options {
  height: 270px;
}
</style>