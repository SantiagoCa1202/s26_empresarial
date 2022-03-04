<template>
  <div
    :class="[
      's26-tarjet',
      `s26-tarjet-${variant}`,
      disabled ? 'disabled' : '',
      'pointer',
    ]"
    @click="emit"
  >
    <div class="s26-tarjet-icon">
      <div class="circle-icon">
        <s26-icon :icon="icon"></s26-icon>
      </div>
    </div>
    <div class="s26-tarjet-info">
      <h4 class="title">{{ title }}</h4>
      <div>
        {{ content ? content : "" }}
        <slot></slot>
      </div>
    </div>
  </div>
</template>
<script>
export default {
  props: {
    title: String,
    content: {},
    variant: String,
    icon: String,
    disabled: {
      type: Boolean,
    },
  },
  data: function () {
    return {};
  },
  created() {},
  methods: {
    emit() {
      this.$emit("click"), this.$emit("change");
    },
  },
};
</script>
<style scoped>
.s26-tarjet.disabled {
  opacity: 0.6;
}
</style>