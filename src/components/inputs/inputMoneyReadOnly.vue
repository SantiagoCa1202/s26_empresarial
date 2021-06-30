<template>
  <div :class="['mb-4 ', variant ? variant : '']">
    <label class="form-label" v-if="label"> {{ label }} </label>
    <div class="input-group input-group-sm">
      <span class="input-group-text s26-text-blue" id="basic-addon1">
        <s26-icon icon="dollar-sign"></s26-icon>
      </span>
      <div class="form-control text-end s26-text-blue">{{ content }}</div>
    </div>
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