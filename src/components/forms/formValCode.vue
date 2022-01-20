<template>
  <s26-modal body_class="h-auto">
    <template v-slot:header>
      <h5 class="modal-title">Validar Código</h5>
    </template>
    <template v-slot:body>
      <s26-form-input
        id="input-val_code"
        type="tel"
        v-model="code"
        maxlength="13"
        number
        s26_required
        placeholder="Ingresa un Codigo Ean-13"
        @enter="val_code"
      >
      </s26-form-input>
    </template>
    <template v-slot:footer>
      <button class="btn btn-outline-danger" @click="$emit('input', null)">
        Cancelar
      </button>
      <button class="btn btn-outline-secondary" @click="generate_code">
        Generar Código
      </button>
      <button class="btn btn-primary" @click="val_code">Validar</button>
    </template>
  </s26-modal>
</template>

<script>
export default {
  data: function () {
    return {
      code: "",
    };
  },
  mounted() {
    $("#input-val_code").focus();
  },
  methods: {
    val_code() {
      let val_code = document.querySelector("#input-val_code");
      let classList_val_code = val_code.classList;
      let warning = document.querySelector("#input-val_code ~ p");
      classList_val_code.remove("is-invalid");

      if (
        this.code !== "" &&
        (this.code.length == 12 || this.code.length == 13)
      ) {
        if (this.val_ean13()) {
          this.axios
            .get("/products/searchProduct/" + this.code)
            .then((res) => {
              this.$emit("input", res.data == 0 ? "new_product" : "amount");
              this.$emit(
                "get_code",
                res.data == 0 ? this.code : res.data[0]["id"]
              );
            })
            .catch((err) => console.log(err));
        }
      } else {
        classList_val_code.add("is-invalid");
        warning.innerHTML =
          "Ingrese un Código válido o genere un código automático";
      }
    },
    hideModal() {
      this.$emit("input", null);
    },
    val_ean13() {
      let arr = this.code.split(""),
        pair = 0,
        odd = 0;

      for (let i = 0; i < 12; i++) {
        if (i % 2) {
          pair += parseInt(arr[i]);
        } else {
          odd += parseInt(arr[i]);
        }
      }
      let sum = odd + 3 * pair;
      let control = Math.ceil(sum / 10) * 10 - sum;
      if (this.code.length == 12) {
        this.code += control;
        return true;
      } else if (this.code.length == 13) {
        if (arr[12] == control) {
          return true;
        } else {
          this.$alertify.error(
            "Dígito de Control Erroneo, verifique o genere un código automático"
          );
        }
      }
    },
    generate_code() {
      let code = Math.floor(
        Math.random() * (999999999999 - 100000000000 + 1) + 100000000000
      );

      this.code = code.toString();
    },
  },
};
</script>