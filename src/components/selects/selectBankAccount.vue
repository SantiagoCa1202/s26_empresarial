<template>
  <div :id="'s26-custom-select-' + id" class="s26-custom-select mb-3">
    <label :for="id" class="form-label">
      Cuenta Bancaria
      <span class="text-danger" v-if="s26_required">
        <s26-icon icon="asterisk" class="icon_asterisk_required"></s26-icon>
      </span>
    </label>
    <div
      :id="id"
      class="form-control form-control-sm s26-select-value"
      tabindex="0"
      @click="activeSelect(!isActive)"
      @keyup.13="activeSelect(!isActive)"
    >
      <div>
        {{ value != 0 && value ? selected : all ? "Todos" : "Seleccionar --" }}
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
            @click="selectOption(0, all ? 'Todos' : '-- seleccionar --')"
            @keyup.13="selectOption(0, all ? 'Todos' : '-- seleccionar --')"
          >
            {{ all ? "Todos" : "Seleccionar --" }}
          </div>
          <div
            :class="['s26-select-options', value == option.id ? 'focus' : '']"
            tabindex="0"
            v-for="option in options"
            :key="option.id"
            @click="selectOption(option.id, option.bank_entity.bank_entity)"
            @keyup.13="selectOption(option.id, option.bank_entity)"
          >
            {{ option.bank_entity.bank_entity }}
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
    <input type="hidden" :s26-required="s26_required" int v-model="value" />
    <p class="invalid-feedback" v-if="s26_required">
      Seleccione una Cuenta Bancaria
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
    checkbook: Boolean,
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
      if (this.value !== 0 && this.value) {
        this.selectRow(this.value);
      }
      $(
        `html, .s26-modal, .s26-modal-content, .s26-popup:not(#s26-custom-select-${this.id})`
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
        checkbook: this.checkbook,
      };
      this.axios
        .get("/bankAccounts/getBankAccounts/", {
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
        .get("/bankAccounts/getBankAccount/" + id)
        .then((res) => {
          this.selectOption(res.data.id, res.data.bank_entity.bank_entity);
        })
        .catch((err) => {
          console.log(err);
        });
    },
    activeSelect(active = true) {
      this.isActive = active;

      if (this.isActive) {
        let s26SelectBank = document.getElementById(this.id);
        this.position.top =
          s26SelectBank.getBoundingClientRect().bottom >= 500
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
      this.$emit("change");
      this.selected = value;
    },
    loadMore() {
      let perPage = this.rows - this.perPage;
      this.perPage = perPage > 25 ? this.perPage + 25 : this.rows;
      this.allRows();
    },
  },
};
</script>
