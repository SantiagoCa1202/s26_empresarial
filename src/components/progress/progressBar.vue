<template>
  <div class="progress" :style="[`height: ${height}`, '']">
    <div
      :class="['progress-bar', variant != '' ? `bg-${variant}` : '']"
      role="progressbar"
      :style="{ width: `${percentage}%`, background: background }"
      aria-valuenow="25"
      aria-valuemin="0"
      aria-valuemax="100"
    >
      {{ label }}
    </div>
  </div>
</template>

<script>
export default {
  props: {
    height: {
      type: String,
      Default: "",
    },
    variant: {
      type: String,
      default: "",
    },
    label: {
      type: String,
      default: "",
    },
    background: {
      type: String,
      default: "",
    },
    percentage: {
      type: Number,
      default: 0,
    },
  },
};
</script>

<style>
</style>