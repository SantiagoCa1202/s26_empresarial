<template>
  <div
    class="s26-tarjet"
    :class="'s26-tarjet-' + variant"
    @click="$emit('click')"
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
  },
  data: function () {
    return {};
  },
  created() {},
  methods: {},
};
</script>