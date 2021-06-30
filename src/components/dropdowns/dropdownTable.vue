<template>
  <div
    class="s26-nav-dropdown"
    @click="isActive = true"
    @focusout="isActive = false"
    tabindex="0"
  >
    <div>
      <s26-icon icon="ellipsis-v"></s26-icon>
    </div>
    <div class="s26-list-dropdown" :class="isActive == true ? 'show' : 'hide'">
      <slot> </slot>
    </div>
  </div>
</template>
<script>
export default {
  props: {},
  data: function () {
    return {
      isActive: false,
    };
  },
  created() {},
  methods: {},
};
</script>