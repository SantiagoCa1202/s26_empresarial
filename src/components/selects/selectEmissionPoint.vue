<template>
  <div :id="'s26-custom-select-' + id" class="s26-custom-select mb-3">
    <label :for="id" class="form-label w-100">
      Punto de Emisión
      <span class="text-danger" v-if="s26_required">
        <s26-icon icon="asterisk" class="icon_asterisk_required"></s26-icon>
      </span>
    </label>
    <div
      :id="id"
      class="form-control form-control-sm s26-select-value"
      tabindex="0"
      @click="$s26.activeSelect"
      @keypress.13="$s26.activeSelect"
    >
      <div :title="select">
        {{ select }}
        <span v-if="print" class="text-primary ms-1">
          <s26-icon icon="receipt"></s26-icon>
        </span>
      </div>
      <s26-icon icon="angle-down" class="icon-angle-down"></s26-icon>
    </div>
    <div class="s26-select-container">
      <div class="w-100 p-2 pb-0">
        <s26-input-search v-model="search" @search="allRows" />
      </div>
      <div class="s26-select-container-options">
        <div
          :class="['s26-select-options', value == 0 ? 'focus' : '']"
          tabindex="0"
          @click="$emit('input', 0)"
          @keyup.13="$emit('input', 0)"
        >
          Solicitud de Venta
        </div>
        <div
          :class="[
            's26-select-options s26-align-y-center',
            all_info
              ? value.id == option.id
                ? 'focus'
                : ''
              : value == option.id
              ? 'focus'
              : '',
          ]"
          tabindex="0"
          v-for="option in options"
          :key="option.id"
          @click="$emit('input', all_info ? option : option.id)"
          @keyup.13="$emit('input', all_info ? option : option.id)"
          v-show="showRows(option.document_id)"
        >
          {{ option.document }} - {{ option.n_point.padStart(3, "0") }} -
          {{ option.sequential_numbering.padStart(9, "0") }}
          <span v-if="option.print" class="text-primary ms-1">
            <s26-icon icon="receipt"></s26-icon>
          </span>
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
      int="true"
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
    type: {
      type: String,
      default: "all",
    },
    all_info: Boolean,
  },
  data: function () {
    return {
      selected: "",
      options: [],
      search: "",
      perPage: 50,
      rows: 0,
      print: false,
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
      if (this.value != 0) {
        let val = this.all_info ? this.value.id : this.value;
        this.axios
          .get("/documents/getDocument/" + val)
          .then((res) => {
            this.selected =
              res.data.alias +
              " - " +
              res.data.n_point.padStart(3, "0") +
              " - " +
              res.data.sequential_numbering.padStart(9, "0");
            this.print = res.data.print == 1 ? true : false;
          })
          .catch((err) => console.log(err));
        return this.selected;
      } else {
        this.print = false;
        return "Solicitud de Venta";
      }
    },
  },
  methods: {
    allRows() {
      const params = {
        name: this.search,
        perPage: this.perPage,
      };
      this.axios
        .get("/documents/getDocuments/", {
          params,
        })
        .then((res) => {
          this.options = res.data.items;
          this.rows = res.data.info.count;
        })
        .catch((err) => console.log(err));
    },
    showRows(doc) {
      if (
        (this.type == "buy" && doc <= 5) ||
        (this.type == "declarable" && doc >= 2 && doc <= 8) ||
        this.type == "all"
      )
        return true;

      return false;
    },
    loadMore() {
      let perPage = this.rows - this.perPage;
      this.perPage = perPage > 25 ? this.perPage + 25 : this.rows;
      this.allRows();
    },
  },
};
</script>
