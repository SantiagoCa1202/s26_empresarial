<template>
  <div :id="'s26-custom-select-' + id" class="s26-custom-select mb-3">
    <label :for="id" class="form-label w-100">
      {{ multiple ? "Proveedores" : "Proveedor" }}
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
          :class="[
            's26-select-options',
            value === '' || value.length == 0 ? 'focus' : '',
          ]"
          tabindex="0"
          @click="selectNull(!multiple ? '' : [])"
          @keyup.13="selectNull(!multiple ? '' : [])"
        >
          {{ all ? "Todos" : "-- seleccionar --" }}
        </div>
        <div
          :class="['s26-select-options', value === 0 ? 'focus' : '']"
          tabindex="0"
          @click="selectNull(0)"
          @keyup.13="selectNull(0)"
          v-if="is_null"
        >
          Sin Proveedor
        </div>
        <div
          :class="[
            's26-select-options s26-align-y-center',
            value == option.id ? 'focus' : '',
            selecteds.indexOf(option.id) > -1 ? 'focus' : '',
          ]"
          tabindex="0"
          v-for="option in options"
          :key="option.id"
          @click="!multiple ? change(option.id) : selectMultiple(option.id)"
          @keyup.13="!multiple ? change(option.id) : selectMultiple(option.id)"
        >
          {{ option.tradename }} -
          {{ option.alias }}
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
    multiple: Boolean,
    is_null: Boolean,
  },
  data: function () {
    return {
      selected: "",
      selecteds: [],
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
      if (!this.multiple) $(`div.s26-select-container`).hide("200");
      if (
        (!this.multiple && this.value > 0) ||
        (this.multiple && this.value.length > 0)
      ) {
        let id = !this.multiple ? this.value : this.value[0];
        this.axios
          .get("/providers/getProvider/" + id)
          .then((res) => (this.selected = res.data.alias))
          .catch((err) => console.log(err));
        return `${this.selected} ${
          this.multiple && this.value.length > 1
            ? " +" + (this.value.length - 1)
            : ""
        }`;
      } else if (this.value === 0) {
        return "Sin Proveedor";
      } else if (this.value === "" || this.value.length == 0) {
        return this.all ? "Todos" : "-- seleccionar --";
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
        .get("/providers/getProviders/", {
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
      $s26.create_cookie("id", this.value, "providers");
      window.open(BASE_URL + "/providers", "_blank");
    },
    selectMultiple(id) {
      if (this.selecteds.indexOf(id) > -1) {
        let i = this.selecteds.indexOf(id);
        this.selecteds.splice(i, 1);
      } else {
        this.selecteds.push(id);
      }
      this.change(this.selecteds);
    },
    selectNull(val = "") {
      $(`div.s26-select-container`).hide("200");
      this.change(val);
      this.selecteds = [];
    },
    change(val) {
      this.$emit("input", val);
      this.perPage = 50;
      this.$emit("change");
    },
  },
};
</script>