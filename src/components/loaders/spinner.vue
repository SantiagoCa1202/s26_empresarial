<template>
  <div class="pre-loading">
    <div class="s26-spinner">
      <div>
        <span class="title">S26 - Empresarial </span>
      </div>
      <div>
        <div
          class="spinner-grow text-info"
          style="width: 3rem; height: 3rem"
          role="status"
        ></div>
      </div>
      <div>
        <span> {{ info }} </span>
      </div>
    </div>
  </div>
</template>
<script>
export default {
  data: function () {
    return {
      arrInfo: [
        "Un momento porfavor",
        "Importando datos...",
        "Importando ventas...",
        "Importando compras...",
        "Importando productos...",
        "Importando clientes...",
      ],
      info: "",
    };
  },
  created() {
    this.changeInfo();
  },
  methods: {
    changeInfo() {
      this.info = this.arrInfo[0];
      let i = 1;
      let count = setInterval(() => {
        this.info = this.arrInfo[i++];
        if (i == 6) {
          i = 0;
        }
      }, 3000);
    },
  },
};
</script>