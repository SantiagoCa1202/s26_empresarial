<template>
  <div :class="['mb-3 pointer', variant ? variant : '']">
    <label class="form-label" v-if="label"> {{ label }} </label>
    <div class="form-control form-control-sm">{{ content }}</div>
  </div>
</template>
<script>
export default {
  props: {
    label: String,
    content: String,
    variant: String,
  },
};
</script>