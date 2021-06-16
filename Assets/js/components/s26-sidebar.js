Vue.component("s26-sidebar", {
  props: {
    title: String,
    icon: String,
    value: {},
    url_export: Function,
  },
  data: function () {
    return {
      btnShowSidebar: { opacity: 0 },
    };
  },
  created() {},
  methods: {
    exportData(type) {
      let url = `${this.url_export}&type=${type}`;

      if (type == "excel") {
        window.location.href = url;
      } else {
        window.open(url);
      }
    },
    hideSidebar() {
      this.btnShowSidebar = { opacity: 1 };
      this.$emit("input", false);
    },
    showSidebar() {
      this.btnShowSidebar = { opacity: 0 };
      this.$emit("input", true);
    },
  },
  template: `
  <aside class="sidebar" :class="{ hideSidebar: !value }">
    <button
      @click="showSidebar"
      class="btn-show-sidebar"
      :style="btnShowSidebar"
    >
      <i class="fas fa-angle-double-right"></i>
    </button>
    <div class="container-sidebar">
      <header class="sidebar-head mb-4">
        <h1><i :class="'fas fa-' + icon"></i> {{ title }}</h1>
        <slot name="header"> </slot>
      </header>
      <main>
        <div class="container accordion">
          <h2 class="title">Buscar</h2>
          <slot name="search"> </slot>
        </div>
        <div class="container accordion">
          <h3 class="title">Informacíon</h3>
          <slot name="info"> </slot>
        </div>
        <div class="container accordion">
          <h4 class="title">Acciones</h4>
          <div class="container">
            <button
              type="button"
              class="btn btn-danger form-control mb-2"
              @click="$emit('reset')"
            >
              Resetear
            </button>
            <button
              type="button"
              class="btn btn-primary form-control mb-2"
              @click="$emit('update')"
            >
              Actualizar
            </button>
            <slot name="actions"> </slot>
          </div>
        </div>
        <div class="container accordion" v-if="this.url_export !== ''">
          <h4 class="title">Exportar</h4>
          <div class="container">
            <button
              type="button"
              class="btn btn-success form-control mb-2"
              @click="exportData('excel')"
            >
              <i class="far fa-file-excel"></i>
              Excel 
            </button>
            <button
              type="button"
              class="btn btn-warning form-control mb-2"
              @click="exportData('print')"
            >
              <i class="fas fa-print"></i>
              Imprimir
            </button>
            <slot name="export"></slot>
          </div>
        </div>
      </main>
      <footer>
        <slot name="footer"></slot>
      </footer>
      <button @click="hideSidebar" class="btn-times btn-hide-sidebar">
        <i class="fas fa-angle-double-left"></i>
      </button>
    </div>
  </aside>
  `,
});
