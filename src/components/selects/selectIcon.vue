<template>
  <div :id="'s26-custom-select-' + id" class="s26-custom-select s26-popup mb-3">
    <label :for="id" class="form-label" v-if="label"> {{ label }} </label>
    <div
      :id="id"
      :class="[
        'form-control form-control-' + size,
        's26-select-value',
        variant,
      ]"
      tabindex="0"
      @click="activeSelect"
      @keyup.13="activeSelect"
    >
      <div class="row mx-0">
        <div class="col-4 s26-align-y-center">
          <s26-icon
            :icon="
              value == 1 || value == '' ? 'project-diagram' : info_icon.icon
            "
            class="option-icon text-secondary"
          ></s26-icon>
        </div>
        <div :class="['s26-align-y-center fw-bold', 'col-8']">
          {{ value == 1 || value == "" ? "default" : info_icon.name }}
        </div>
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
            :class="[
              's26-select-options row mx-0',
              value == option.id ? 'focus' : '',
            ]"
            tabindex="0"
            v-for="option in options"
            :key="option.id"
            @click="selectOption(option.id, option.name, option.class)"
          >
            <div class="col-3">
              <s26-icon
                :icon="option.class"
                class="option-icon text-secondary"
              ></s26-icon>
            </div>
            <div class="col-9 s26-align-y-center fw-bold">
              {{ option.name }}
            </div>
          </div>
          <button
            v-if="perPage < rows"
            type="button"
            class="btn btn-link"
            @click="loadMore"
          >
            Cargar Mas..
          </button>
        </div>
      </div>
    </transition>
    <p class="invalid-feedback" v-if="s26_required">{{ message }}</p>
  </div>
</template>
<script>
export default {
  props: {
    label: String,
    id: String,
    message: {
      type: String,
      default: "",
    },
    size: String,
    placeholder: String,
    variant: {
      type: String,
      default: "",
    },
    value: {},
    s26_required: Boolean,
  },
  data: function () {
    return {
      isActive: false,
      options: [],
      info_icon: {
        selected: "",
        icon: "",
      },
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
      };
      this.axios
        .get("/users/getIcons/", {
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
    getIcon(id) {
      this.axios
        .get("/users/getIcon/" + id)
        .then((res) => {
          this.info_icon.icon = res.data.class;
          this.info_icon.name = res.data.name;
        })
        .catch((err) => {
          console.log(err);
        });
    },
    activeSelect(active = true) {
      this.isActive = active;

      if (this.isActive) {
        let s26SelectIcon = document.getElementById(this.id);
        this.position.top =
          s26SelectIcon.getBoundingClientRect().bottom + 170 >= 500
            ? "-148px"
            : "55px";

        setTimeout(() => {
          $(".s26-select-container-input-search input").focus();
        }, 100);

        this.allRows();
      } else {
        this.search = "";
      }
    },
    selectOption(id, value, icon) {
      this.isActive = false;
      this.search = "";
      this.perPage = 50;
      this.$emit("input", id);
      this.info_icon.icon = icon;
      this.info_icon.name = value;
    },
    loadMore() {
      let perPage = this.rows - this.perPage;
      this.perPage = perPage > 25 ? this.perPage + 25 : this.rows;
      this.allRows();
    },
  },
};
</script>
