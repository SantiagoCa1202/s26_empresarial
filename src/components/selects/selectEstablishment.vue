<template>
  <div :id="'s26-custom-select-' + id" class="s26-custom-select mb-3">
    <label :for="id" class="form-label w-100">
      Establecimiento
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
      @click="activeSelect(!isActive)"
      @keyup.13="activeSelect(!isActive)"
    >
      <div>
        {{
          value != 0 && value ? selected : all ? "Todos" : "-- seleccionar --"
        }}
      </div>
      <span :class="['icon-sort-down-select', { active: isActive }]">
        <s26-icon icon="sort-down"></s26-icon>
      </span>
    </div>
    <transition name="fade">
      <div
        v-if="isActive"
        class="s26-select-container active"
        :style="position"
      >
        <div class="w-100 p-1">
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
            :class="['s26-select-options', value == option.id ? 'focus' : '']"
            tabindex="0"
            v-for="option in options"
            :key="option.id"
            @click="selectOption(option.id, option.tradename)"
            @keyup.13="selectOption(option.id, option.tradename)"
          >
            {{ option.tradename }} - {{ option.n_establishment }}
          </div>
          <button
            v-if="perPage < rows"
            type="button"
            class="btn btn-link btn-sm"
            @click="loadMore"
          >
            Cargar Mas..
          </button>
        </div>
      </div>
    </transition>
    <input
      type="hidden"
      :s26-required="s26_required"
      int="true"
      v-model="value"
    />
    <p class="invalid-feedback" v-if="s26_required">
      Seleccione un Establecimiento
    </p>
  </div>
</template>
<script>
export default {
  props: {
    id: String,
    value: {},
    all: Boolean,
    s26_required: Boolean,
  },
  data: function () {
    return {
      isActive: false,
      selected: "",
      options: [],
      search: "",
      perPage: 50,
      rows: 0,
      position: {
        top: "0",
      },
    };
  },
  created() {
    setTimeout(() => {
      if (this.value != 0) {
        this.selectRow(this.value);
      }
      $(
        `html, .s26-modal, .s26-modal-content, .s26-custom-select:not(#s26-custom-select-${this.id})`
      ).on("click", (e) => {
        this.activeSelect(false);
      });
      $(`#s26-custom-select-${this.id}`).click(function (e) {
        e.stopPropagation();
      });
    }, 100);
  },
  methods: {
    allRows() {
      const params = {
        name: this.search,
        perPage: this.perPage,
      };
      this.axios
        .get("/establishments/getEstablishments/", {
          params,
        })
        .then((res) => {
          this.options = res.data.items;
          this.rows = res.data.info.count;
        })
        .catch((err) => {
          console.log(err);
        });
    },
    selectRow(id) {
      this.axios
        .get("/establishments/getEstablishment/" + id)
        .then((res) => {
          this.selectOption(res.data.id, res.data.tradename);
        })
        .catch((err) => {
          console.log(err);
        });
    },
    activeSelect(active = true) {
      this.isActive = active;

      if (this.isActive) {
        let s26SelectUser = document.getElementById(this.id);
        this.position.top =
          s26SelectUser.getBoundingClientRect().bottom + 170 >= 500
            ? "-148px"
            : "55px";

        this.allRows();
      }
    },
    selectOption(id, value) {
      this.isActive = false;
      this.search = "";
      this.perPage = 50;
      this.$emit("input", id);
      this.selected = value;
      this.$emit("change");
    },
    loadMore() {
      let perPage = this.rows - this.perPage;
      this.perPage = perPage > 25 ? this.perPage + 25 : this.rows;
      this.allRows();
    },
    getRow() {
      s26.create_cookie("id", this.value, "establishments");
      window.open(BASE_URL + "/establishments", "_blank");
    },
  },
};
</script>