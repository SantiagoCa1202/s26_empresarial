<template>
  <div :id="'s26-custom-select-' + id" class="s26-custom-select mb-3">
    <label :for="id" class="form-label w-100" v-if="label">
      Color
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
      <div class="d-flex">
        <span class="me-2" :style="{ color: '#' + color }">
          <s26-icon
            :class="select == 'blanco' ? 'border' : ''"
            icon="palette"
          ></s26-icon>
        </span>
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
          :class="['s26-select-options', value == 0 ? 'focus' : '']"
          tabindex="0"
          @click="$emit('input', 0)"
          @keyup.13="$emit('input', 0)"
        >
          {{ all ? "Todos" : "-- seleccionar --" }}
        </div>
        <div
          :class="[
            's26-select-options s26-align-y-center',
            value == option.id ? 'focus' : '',
          ]"
          tabindex="0"
          v-for="option in options"
          :key="option.id"
          @click="selectMultiple(option.id)"
          @keyup.13="$emit('input', option.id)"
        >
          <span
            :class="['btn-icon me-2', option.name == 'blanco' ? 'border' : '']"
            :style="{
              background: '#' + option.hexadecimal,
              color: option.name == 'blanco' ? '#243a46' : '#fff',
            }"
          >
            <s26-icon icon="palette"></s26-icon>
          </span>
          {{ option.name }}
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
    multiple: Boolean,
    label: Boolean,
  },
  data: function () {
    return {
      selected: "",
      color: "",
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
      if (this.value != 0) {
        this.axios
          .get("/system/getColor/" + this.value)
          .then((res) => {
            this.selected = res.data.name;
            this.color = res.data.hexadecimal;
          })
          .catch((err) => console.log(err));
        return this.selected;
      } else {
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
        .get("/system/getColors/", {
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
    selectMultiple(id) {
      if (this.multiple) {
        if (this.selecteds.indexOf(id) > -1) {
          let i = this.selecteds.indexOf(id);
          this.selecteds.splice(i, 1);
        } else {
          this.selecteds.push(id);
        }
        this.change(this.selecteds);
      } else {
        this.$emit("input", id);
      }
    },
    change(val) {
      this.$emit("input", val);
      this.perPage = 50;
      this.$emit("change");
    },
  },
};
</script>