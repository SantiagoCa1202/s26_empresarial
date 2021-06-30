<template>
  <div :id="'modal-' + id" class="s26-modal" tabindex="-1">
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
          :class="['modal-header border-0', header_class ? header_class : '']"
        >
          <slot name="header"></slot>
          <button type="button" class="btn-close" @click="hideModal"></button>
        </div>
        <div
          v-if="!body_none"
          :class="['modal-body', body_class ? body_class : '']"
          :style="style_body"
        >
          <slot name="body"></slot>
        </div>
        <div
          v-if="!footer_none"
          :class="['modal-footer border-0', footer_class ? footer_class : '']"
        >
          <slot name="footer"></slot>
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
    footer_none: Boolean,
    footer_class: String,
    prevent_global_close: Boolean,
    style_body: String,
  },
  created() {
    if (!this.prevent_global_close) {
      setTimeout(() => {
        $("#modal-" + this.id).on("click", (e) => {
          this.hideModal();
        });
        $(".s26-modal-content").click(function (e) {
          e.stopPropagation();
        });
      }, 100);
    }
  },
  methods: {
    hideModal() {
      this.$emit("hideModal");
    },
  },
};
</script>
<style scoped>
.modal-body.h-auto {
  height: auto;
  max-height: none;
}
</style>