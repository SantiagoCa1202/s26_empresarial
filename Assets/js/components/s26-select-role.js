Vue.component("s26-select-role", {
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
  data() {
    return {
      items: [],
    };
  },
  created() {
    this.allRows();
  },
  methods: {
    allRows() {
      axios
        .get("/roles/getRoles/")
        .then((res) => {
          this.items = res.data.items;
        })
        .catch((err) => {
          console.log(err);
        });
    },
  },
  template: `
    <div class="mb-4">
      <label class="form-label">Rol</label>
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
        <option value="">{{ all ? "Todos" : "-- seleccionar --" }}</option>
        <option v-for="(item, index) in items" :key="index" :value="item.id">{{ item.name }}</option>
      </select>
      <p class="invalid-feedback" v-if="s26_required">
        Seleccione un rol
      </p>
    </div>
  `,
});
