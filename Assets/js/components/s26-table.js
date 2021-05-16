Vue.component("s26-table", {
  props: {
    fields: Array,
    rows: Number,
    sidebar: Boolean,
    value: {},
  },
  data: function () {
    return {};
  },
  mounted() {},
  methods: {
    load_more() {
      if (this.value < this.rows) {
        this.$emit("input", this.value + 1);
      }
      this.$emit("get");
    },
  },
  template: `
    <section class="main" :class="{ 'mainWidth-100': !sidebar }">
      <div class="s26-container-table">
        <table class="s26-table" ref="s26-table">
          <thead>
            <tr>
              <th class="length-int">id</th>
              <th v-for="field in fields" :key="field.id" :class="[field.class]">
                {{ field.name }}
              </th>
              <th class="length-action">acción</th>
            </tr>
          </thead>
          <tbody>
            <slot name="body"></slot>
            <tr>
              <td class="font-weight-bold" colspan="25" v-if="rows == 0">
                Sin Registros
              </td>
            </tr>
            
          </tbody>
        </table>
        <transition name="fade" >
          <button 
            class="btn btn-outline-info float-end" 
            v-if="value < rows"  
            @click="load_more"
          >
            Cargar Más...
          </button>
        </transition>
      </div>
    </section>
  `,
});
