<template>
  <div :id="'modal-multiple' + id" class="s26-modal" tabindex="-1">
    <div
      :class="[
        'modal-dialog',
        size ? 'modal-' + size : '',
        'modal-dialog-centered',
      ]"
    >
      <div class="s26-modal-content">
        <div
          v-if="!header_none"
          :class="['modal-multiple-header', header_class ? header_class : '']"
        >
          <h1 class="modal-title h4 s26-text-blue">{{ title }}</h1>
          <div class="proccess-bar" v-if="levels.length > 1">
            <div
              class="level"
              v-for="(level, index) in levels"
              :key="index"
            ></div>
          </div>
          <transition name="slide-fade" mode="out-in">
            <div
              class="modal-sub-title"
              :key="levels[level_select]"
              v-if="levels.length > 1"
            >
              {{ levels[level_select] }}.
            </div>
          </transition>
          <div class="actions-s26-modal">
            <button type="button" class="btn btn-link btn-action" v-if="expand">
              <s26-icon icon="expand"></s26-icon>
            </button>
            <button
              type="button"
              class="btn btn-link btn-action"
              @click="hideModal"
            >
              <s26-icon icon="times"></s26-icon>
            </button>
          </div>
        </div>
        <div
          v-if="!body_none"
          :class="[
            'modal-body modal-multiple-body p-0',
            body_class ? body_class : '',
          ]"
          :style="body_style"
        >
          <form :id="id" @submit.prevent>
            <transition-group name="fade" mode="out-in">
              <div
                v-show="level_select == 0"
                class="container-level container-level-0 row"
                key="level-0"
              >
                <slot name="level-0"></slot>
              </div>
              <div
                v-if="levels.length >= 2"
                v-show="level_select == 1"
                class="container-level container-level-1 row"
                key="level-1"
              >
                <slot name="level-1"></slot>
              </div>
              <div
                v-if="levels.length >= 3"
                v-show="level_select == 2"
                class="container-level container-level-2 row"
                key="level-2"
              >
                <slot name="level-2"></slot>
              </div>
              <div
                v-if="levels.length >= 4"
                v-show="level_select == 3"
                class="container-level container-level-3 row"
                key="level-3"
              >
                <slot name="level-3"></slot>
              </div>
              <div
                v-if="levels.length >= 5"
                v-show="level_select == 4"
                class="container-level container-level-4 row"
                key="level-4"
              >
                <slot name="level-4"></slot>
              </div>
            </transition-group>
          </form>
        </div>
        <div
          v-if="!footer_none"
          :class="[
            'modal-footer modal-multiple-footer',
            footer_class ? footer_class : '',
          ]"
        >
          <div class="row w-100">
            <div class="col px-0">
              <transition name="fade">
                <button
                  type="button"
                  class="btn btn-outline-info"
                  @click="prevLevel"
                  v-if="this.level_select > 0"
                >
                  Regresar
                </button>
              </transition>
            </div>
            <div class="col px-0">
              <transition name="fade" mode="out-in">
                <button
                  type="button"
                  class="btn btn-info float-end"
                  @click="nextLevel"
                  v-if="
                    !readOnly ||
                    (this.level_select + 1 < this.levels.length && readOnly)
                  "
                  :key="
                    this.level_select + 1 === this.levels.length && !readOnly
                      ? 'Guardar'
                      : 'Siguiente'
                  "
                >
                  {{
                    this.level_select + 1 === this.levels.length && !readOnly
                      ? "Guardar"
                      : "Siguiente"
                  }}
                </button>
              </transition>
              <button
                v-if="!readOnly"
                type="button"
                class="btn btn-outline-danger float-end mx-1"
                @click="$emit('onReset')"
              >
                Resetear
              </button>
            </div>
          </div>
          <slot name="footer"> </slot>
        </div>
        <slot name="subModal"></slot>
      </div>
    </div>
  </div>
</template>
<script>
export default {
  props: {
    id: String,
    size: String,
    header_none: Boolean,
    header_class: String,
    body_none: Boolean,
    body_class: String,
    body_style: String,
    footer_none: Boolean,
    footer_class: String,
    prevent_global_close: Boolean,
    levels: Array,
    title: String,
    readOnly: Boolean,
    form: String,
    expand: Boolean,
  },
  data() {
    return {
      level_select: 0,
    };
  },
  created() {
    if (!this.prevent_global_close) {
      setTimeout(() => {
        $("#modal-multiple" + this.id).on("click", (e) => {
          this.hideModal();
        });
        $(".s26-modal-content").click(function (e) {
          e.stopPropagation();
        });
      }, 100);
    }
    setTimeout(() => {
      $(`.level:nth-child(1)`).addClass("check");
      $(`.container-level-${this.level_select} input:eq(0)`).focus();
    }, 100);
  },
  methods: {
    hideModal() {
      this.$emit("hideModal");
    },
    nextLevel() {
      if (
        !s26.val_form(this.id + " .container-level-" + this.level_select) &&
        !this.readOnly
      ) {
        this.$alertify.error(
          "Error, verifica que todos los campos con (*) sean correctos"
        );
      } else {
        if (this.level_select < this.levels.length - 1) {
          this.level_select += 1;

          $(`.level:nth-child(${this.level_select + 1})`).addClass("check");
          setTimeout(() => {
            $(`.container-level-${this.level_select} input:eq(0)`).focus();
          }, 600);
        } else {
          this.$emit("onSubmit");
        }
      }
    },
    prevLevel() {
      if (this.level_select > 0) {
        $(`.level:nth-child(${this.level_select + 1})`).removeClass("check");
        this.level_select -= 1;
        setTimeout(() => {
          $(`.container-level${this.level_select} input:eq(0)`).focus();
        }, 600);
      }
    },
  },
};
</script>
<style scoped>
.modal-multiple-header {
  display: flex;
  align-items: center;
  justify-content: center;
  flex-wrap: wrap;
  padding-top: 1rem;
  border: 0;
}
.modal-multiple-header .modal-title {
  font-weight: bold;
}
.modal-multiple-header .actions-s26-modal {
  position: absolute;
  right: 20px;
  top: 20px;
}

.actions-s26-modal .btn-action {
  color: #bebebe;
  transition: 0.3s;
  padding: 0 0.5rem;
}

.actions-s26-modal .btn-action:hover,
.actions-s26-modal .btn-action:active {
  color: var(--bs-secondary);
}
.proccess-bar {
  display: flex;
  align-items: center;
  justify-content: center;
  width: 100%;
  position: relative;
  padding: 0.5rem 0;
}
.proccess-bar .level {
  position: relative;
  display: flex;
  align-items: center;
}
.proccess-bar .level::after {
  content: "";
  background: #bebebe;
  height: 0.7rem;
  width: 0.7rem;
  border-radius: 50%;
  z-index: 1;
  transition: 0.8s;
}
.proccess-bar .level:not(.proccess-bar .level:first-child)::before {
  content: "";
  background: #bebebe;
  height: 0.13rem;
  width: 5rem;
  transition: 0.3s;
}

.proccess-bar .level.check:not(.proccess-bar .level:first-child)::before,
.proccess-bar .level.check::after {
  background-color: var(--bs-primary);
}

.modal-multiple-header .modal-sub-title {
  width: 100%;
  text-align: center;
  font-weight: bold;
  font-size: 1.1rem;
  opacity: 0.7;
  padding-top: 0.5rem;
}
.modal-multiple-footer {
  border: 0;
  padding-top: 0;
}
</style>
