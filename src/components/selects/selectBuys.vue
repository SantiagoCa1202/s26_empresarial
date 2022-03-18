<template>
  <div :id="'s26-custom-select-' + id" class="s26-custom-select mb-3">
    <label :for="id" class="form-label w-100">
      {{ label }}
      <span class="text-danger" v-if="s26_required">
        <s26-icon icon="asterisk" class="icon_asterisk_required"></s26-icon>
      </span>
      <a @click="getRow" class="text-primary float-end pointer" v-if="!all">
        <s26-icon icon="link"></s26-icon>
      </a>
    </label>
    <div
      :id="id"
      class="form-control form-control-sm s26-select-value"
      tabindex="0"
      @click="$s26.activeSelect"
      @keypress.13="$s26.activeSelect"
    >
      <div>
        {{ select }}
      </div>
      <s26-icon icon="angle-down" class="icon-angle-down"></s26-icon>
    </div>
    <div class="s26-select-container">
      <div class="w-100 p-2 pb-0">
        <s26-input-search v-model="search" @search="allRows" />
      </div>
      <div class="s26-select-container-options">
        <div
          :class="['s26-select-options', value === '' ? 'focus' : '']"
          tabindex="0"
          @click="$emit('input', '')"
          @keyup.13="$emit('input', '')"
        >
          {{ all ? "Todos" : "-- seleccionar --" }}
        </div>
        <div
          :class="['s26-select-options', value === 0 ? 'focus' : '']"
          tabindex="0"
          @click="$emit('input', 0)"
          @keyup.13="$emit('input', 0)"
          v-if="is_null"
        >
          Sin Documento
        </div>
        <div
          :class="['s26-select-options', value === -1 ? 'focus' : '']"
          tabindex="0"
          @click="$emit('input', -1)"
          @keyup.13="$emit('input', -1)"
          v-if="assign"
        >
          Por Asignar
        </div>
        <div
          :class="[
            's26-select-options s26-align-y-center',
            value == option.id ? 'focus' : '',
          ]"
          tabindex="0"
          v-for="option in options"
          :key="option.id"
          @click="$emit('input', option.id)"
          @keyup.13="$emit('input', option.id)"
        >
          {{ option.type_doc.alias }} - {{ option.n_document }}
        </div>
      </div>
      <div class="actions-select pt-1 px-2">
        <button
          v-if="rows > perPage"
          type="button"
          class="btn-icon text-primary"
          @click="loadMore"
          @keypress.13="loadMore"
        >
          <s26-icon icon="plus"></s26-icon>
        </button>
        <button
          type="button"
          class="btn-icon text-warning"
          @click="allRows"
          @keypress.13="allRows"
        >
          <s26-icon icon="sync-alt"></s26-icon>
        </button>
      </div>
    </div>
    <input
      type="hidden"
      :s26-required="s26_required"
      select="true"
      v-model="value"
    />
    <p class="invalid-feedback" v-if="s26_required"></p>
  </div>
</template>
<script>
export default {
  props: {
    id: String,
    value: {},
    all: Boolean,
    s26_required: Boolean,
    label: {
      type: String,
      default: "Comprobantes",
    },
    establishment_id: {
      type: String,
      default: "",
    },
    type_doc: {},
    is_null: Boolean,
    assign: Boolean,
  },
  data: function () {
    return {
      selected: "",
      options: [],
      search: "",
      perPage: 50,
      rows: 0,
    };
  },
  mounted: function () {
    this.allRows();
  },
  computed: {
    select: function () {
      $(`div.s26-select-container`).hide("200");
      this.perPage = 50;
      this.$emit("change");
      if (this.value > 0) {
        this.axios
          .get("/buys/getBuy/" + this.value)
          .then(
            (res) =>
              (this.selected = `${res.data.type_doc.alias} - ${res.data.n_document}`)
          )
          .catch((err) => console.log(err));
        return this.selected;
      } else if (this.value === 0) {
        return "Sin Documento";
      } else if (this.value === -1) {
        return "Por Asignar";
      } else if (this.value === "") {
        return this.all ? "Todos" : "-- seleccionar --";
      }
    },
  },
  watch: {
    establishment_id: function () {
      if (this.establishment_id) {
        this.allRows();
      }
    },
  },
  methods: {
    allRows() {
      const params = {
        n_document: this.search,
        establishment_id: this.establishment_id,
        perPage: this.perPage,
        type_doc_id: this.type_doc,
      };
      this.axios
        .get("/buys/getBuys/", {
          params,
        })
        .then((res) => {
          this.options = res.data.items;
          this.rows = res.data.info.count;
        })
        .catch((err) => console.log(err));
    },
    loadMore() {
      let perPage = this.rows - this.perPage;
      this.perPage = perPage > 25 ? this.perPage + 25 : this.rows;
      this.allRows();
    },
    getRow() {
      $s26.create_cookie("id", this.value, "buys");
      window.open(BASE_URL + "/buys", "_blank");
    },
  },
};
</script>
