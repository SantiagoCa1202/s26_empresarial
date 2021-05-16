Vue.component("s26-select-status", {
  inheritAttrs: false,
  props: {
    value: {},
    all: Boolean,
    id: String,
    message: String,
    autofocus: Boolean,
    s26_required: Boolean,
  },
  computed: {
    inputListeners: function () {
      var vm = this;
      // `Object.assign` merges objects together to form a new object
      return Object.assign(
        {},
        // We add all the listeners from the parent
        this.$listeners,
        // Then we can add custom listeners or override the
        // behavior of some listeners.
        {
          // This ensures that the component works with v-model
          input: function (event) {
            vm.$emit("input", event.target.value);
          },
        }
      );
    },
  },
  template: `
    <div class="mb-4">
      <label class="form-label">Estado</label>
      <select
        :id="id"
        class="form-select form-select-sm"
        v-bind="$attrs"
        v-bind:value="value"
        v-on="inputListeners"
        @change="$emit('change')"
        :s26-required="s26_required"
        :autofocus="autofocus"
      >
        <option value="">{{ all ? "Todos" : "Seleccionar --" }}</option>
        <option value="1">Activo</option>
        <option value="2">Inactivo</option>
      </select>
      <p class="invalid-feedback" v-if="s26_required">
        Seleccione un estado
      </p>
    </div>
  `,
});
