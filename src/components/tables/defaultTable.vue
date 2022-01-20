<template>
  <section
    :class="[
      'main',
      { 'mainWidth-100': !sidebar },
      relative ? 'position-relative' : '',
      section_variants,
    ]"
  >
    <div :class="['s26-container-table', 'h-' + height]">
      <table class="s26-table" ref="s26-table">
        <thead>
          <tr>
            <th class="length-int" v-if="id">id</th>
            <template v-if="fields">
              <th
                v-for="field in fields"
                :key="field.id"
                :class="[field.class]"
              >
                {{ field.name }}
              </th>
            </template>
            <slot name="head" v-else></slot>
            <th class="length-action" v-if="action">acción</th>
            <th class="length-action" v-if="info">info</th>
          </tr>
          <slot name="tr"></slot>
        </thead>
        <tbody>
          <slot name="body"></slot>
          <template v-if="loading">
            <tr v-for="i in rows_load_data" :key="i" class="skeleton-table">
              <td colspan="20"></td>
            </tr>
          </template>
          <tr>
            <td
              class="font-weight-bold"
              colspan="25"
              v-if="rows == 0 && !loading_data"
            >
              Sin Registros
            </td>
          </tr>
        </tbody>
      </table>
      <transition name="fade">
        <button
          class="btn btn-outline-info float-end"
          v-if="value < rows"
          @click="loadMore"
        >
          Cargar Más...
        </button>
      </transition>
    </div>
  </section>
</template>
<script>
export default {
  props: {
    fields: Array,
    rows: { Number, String },
    sidebar: Boolean,
    value: {},
    action: Boolean,
    id: Boolean,
    info: Boolean,
    loading_data: Boolean,
    rows_load_data: {
      default: 9,
    },
    relative: Boolean,
    height: {
      default: "",
    },
    section_variants: {
      default: "",
    },
  },
  computed: {
    loading() {
      if (this.loading_data) {
        setTimeout(() => {
          $(".skeleton-table td").each(function (i) {
            var t = $(this);
            setTimeout(function () {
              t.addClass("animation");
            }, (i + 1) * 50);
          });
          $(".skeleton-table").each(function (i) {
            var t = $(this);
            setTimeout(function () {
              t.addClass("animation");
            }, (i + 1) * 50);
          });
        }, 10);
        return true;
      } else {
        return false;
      }
    },
  },
  methods: {
    loadMore() {
      let perPage = this.rows - this.value;
      this.$emit("input", perPage > 25 ? this.perPage + 25 : this.rows);
      this.$emit("get");
    },
  },
};
</script>
<style scoped>
.skeleton-table td {
  cursor: wait;
  position: relative;
  background-color: rgba(0, 0, 0, 0.08) !important;
  border: none;
  height: 26.06px;
  overflow: hidden;
}
.skeleton-table td.animation::before {
  content: "";
  position: absolute;
  height: 100%;
  width: 100%;
  background-image: linear-gradient(
    to right,
    rgb(216 216 216 / 8%) 0%,
    rgb(0 0 0 / 8%) 20%,
    rgba(0, 0, 0, 0.08) 40%,
    rgb(216 216 216 / 8%) 100%
  );
  background-size: 50% 40px;
  background-repeat: no-repeat;
  animation: shimmer 2s ease-in-out infinite;
  animation-direction: alternate;
  top: 0;
  left: 0;
}
.skeleton-table {
  opacity: 0;
}
.skeleton-table.animation {
  opacity: 1;
  animation: flicker 2s linear 1;
}
@keyframes shimmer {
  0% {
    background-position: -100% 0;
    background-size: 50% 40px;
  }
  100% {
    background-position: 200% 0;
    background-size: 50% 40px;
  }
}
@keyframes flicker {
  0% {
    opacity: 0;
  }
  100% {
    opacity: 1;
  }
}
</style>