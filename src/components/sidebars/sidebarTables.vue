<template>
  <aside class="sidebar" :class="{ hideSidebar: !value }">
    <button
      @click="showSidebar"
      class="btn-show-sidebar"
      :style="btnShowSidebar"
    >
      <s26-icon icon="angle-double-right"></s26-icon>
    </button>
    <div class="container-sidebar">
      <header class="sidebar-head mb-4">
        <h1><s26-icon :icon="icon"></s26-icon> {{ title }}</h1>
        <slot name="header"> </slot>
      </header>
      <main>
        <div class="container accordion">
          <h2 class="title">Buscar</h2>
          <slot name="search"> </slot>
        </div>
        <div class="container accordion">
          <h3 class="title">Información</h3>
          <slot name="info"></slot>
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
        <div class="container accordion" v-if="url_export && url_export !== ''">
          <h4 class="title">Exportar</h4>
          <div class="container">
            <button
              type="button"
              class="btn btn-success form-control mb-2"
              @click="exportData('excel')"
            >
              <s26-icon icon="file-excel"></s26-icon>
              Excel
            </button>
            <button
              type="button"
              class="btn btn-warning form-control mb-2"
              @click="exportData('print')"
            >
              <s26-icon icon="print"></s26-icon>
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
        <s26-icon icon="angle-double-left"></s26-icon>
      </button>
    </div>
  </aside>
</template>
<script>
export default {
  props: {
    title: String,
    icon: String,
    value: {},
    url_export: String,
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
};
</script>