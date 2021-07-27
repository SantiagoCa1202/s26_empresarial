<template>
  <div class="mb-3 s26-form-group">
    <label class="form-label" v-if="label">
      {{ label }}
    </label>
    <div class="input-document">
      <div class="n3">
        <div class="form-control form-control-sm text-center n_establishment">
          {{ n_establishment }}
        </div>
      </div>
      <div class="n3">
        <div class="form-control form-control-sm text-center n_point">
          {{ n_point }}
        </div>
      </div>
      <div class="n_doc">
        <div class="form-control form-control-sm text-center n_document">
          {{ n_document }}
        </div>
      </div>
      {{ return_document }}
    </div>
  </div>
</template>
<script>
export default {
  props: {
    label: String,
    content: {},
    variant: String,
    link: String,
    money: Boolean,
  },
  data: function () {
    return {
      n_establishment: "",
      n_point: "",
      n_document: "",
    };
  },
  computed: {
    return_document: function () {
      if (this.content != "") {
        let arrDoc = this.content.split("-");
        this.n_establishment = arrDoc[0];
        this.n_point = arrDoc[1];
        this.n_document = arrDoc[2];
      } else {
        this.n_establishment = "";
        this.n_point = "";
        this.n_document = "";
      }
    },
  },
};
</script>
<style scoped>
.input-document {
  width: 100%;
  display: flex;
}
.input-document .n3:nth-child(2) {
  margin-left: 0.5rem;
  margin-right: 0.5rem;
}
.n3 {
  width: 25%;
}
.n_doc {
  width: 75%;
}
</style>