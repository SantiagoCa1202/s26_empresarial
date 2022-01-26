<template>
  <div class="mb-3">
    <label class="form-label">
      {{ label }}
      <span class="text-danger" v-if="s26_required">
        <s26-icon icon="asterisk" class="icon_asterisk_required"></s26-icon>
      </span>
    </label>
    <select
      :id="id"
      class="form-select form-select-sm"
      v-bind="$attrs"
      v-bind:value="value"
      v-on="inputListeners"
      @change="$emit('change')"
      :s26-required="s26_required"
      :autofocus="autofocus"
      int
    >
      <option value="">{{ all ? "Todos" : "-- Seleccionar --" }}</option>
      <option value="1">{{ options[0] }}</option>
      <option value="2">{{ options[1] }}</option>
    </select>
    <p class="invalid-feedback" v-if="s26_required">Seleccione un estado</p>
  </div>
</template>
<script>
export default {
  inheritAttrs: false,
  props: {
    value: {},
    all: Boolean,
    id: String,
    message: String,
    autofocus: Boolean,
    s26_required: Boolean,
    label: String,
    options: {
      type: Array,
      default: () => {
        return ["Activo", "Inactivo"];
      },
    },
  },
  computed: {
    inputListeners: function () {
      var vm = this;
      return Object.assign({}, this.$listeners, {
        input: function (event) {
          vm.$emit("input", event.target.value);
        },
      });
    },
  },
};
</script>