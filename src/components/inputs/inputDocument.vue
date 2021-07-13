<template>
  <div class="mb-3 s26-form-group">
    <label :for="id" class="form-label" v-if="label">
      {{ label }}
      <span class="text-danger" v-if="s26_required">
        <s26-icon icon="asterisk" class="icon_asterisk_required"></s26-icon>
      </span>
    </label>
    <span
      v-if="length && value.length > 0"
      :class="[
        'fw-bold float-end',
        value.length - 2 == 15 ? 'text-success' : 'text-danger',
      ]"
    >
      {{ length ? value.length - 2 : "" }}
    </span>
    <div class="input-document">
      <div class="n3">
        <input
          type="text"
          :class="[
            'form-control',
            'form-control-' + size,
            'text-center',
            'n_establishment',
          ]"
          placeholder="001"
          maxlength="3"
          minlength="3"
          @keyup="write"
          number
          v-model="n_establishment"
          :s26-required="s26_required"
        />
      </div>
      <div class="n3">
        <input
          type="text"
          :class="[
            'form-control',
            'form-control-' + size,
            'text-center',
            'n_point',
          ]"
          placeholder="001"
          maxlength="3"
          minlength="3"
          @keyup="write"
          number
          v-model="n_point"
          :s26-required="s26_required"
        />
      </div>
      <div class="n_doc">
        <input
          type="text"
          :class="[
            'form-control',
            'form-control-' + size,
            'text-center',
            'n_document',
          ]"
          placeholder="000058795"
          maxlength="9"
          minlength="9"
          @keyup="write"
          number
          v-model="n_document"
          :s26-required="s26_required"
        />
      </div>
    </div>
    <p class="invalid-feedback" v-if="s26_required || validate"></p>
  </div>
</template>
<script>
export default {
  props: {
    label: String,
    id: String,
    size: String,
    variant: {
      type: String,
      default: "",
    },
    value: {},
    s26_required: Boolean,
    autofocus: Boolean,
    length: Boolean,
    validate: Boolean,
  },
  data: function () {
    return {
      n_establishment: "",
      n_point: "",
      n_document: "",
    };
  },
  created() {
    setTimeout(() => {
      s26.val_inputs();
    }, 100);
  },
  methods: {
    write() {
      if (this.n_establishment.length == 3) {
        $(".n_point").focus();
      }
      if (this.n_point.length == 3) {
        $(".n_document").focus();
      }

      let n_document = `${this.n_establishment}-${this.n_point}-${this.n_document}`;

      this.$emit("input", n_document);
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
  width: 40%;
}
</style>