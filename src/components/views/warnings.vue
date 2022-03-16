<template>
  <div class="row s26-align-center h-100 box-disabled s26-text-blue">
    <div class="col-12">
      <div class="row">
        <div class="col-12 fs-4 fw-600 text-center" v-html="title"></div>
        <div :class="['col-12 text-center', variant_icon]">
          <span class="icon">
            <s26-icon :icon="icon"></s26-icon>
          </span>
        </div>
        <div class="col-12 text-center" v-html="sub_title"></div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    title: {
      type: String,
      default: "",
    },
    sub_title: {
      type: String,
      default: "",
    },
    icon: {
      type: String,
      default: "exclamation-triangle",
    },
    variant_icon: {
      type: String,
      default: "",
    },
  },
};
</script>

<style>
.box-disabled .icon {
  font-size: 5.5rem;
}
</style>