<template>
  <div :class="['mb-4 ', variant ? variant : '']">
    <label class="form-label" v-if="label"> {{ label }} </label>
    <div
      :class="[
        'form-control form-control-sm textarea-read',
        rows ? 'row-' + rows : '',
      ]"
      v-html="content"
    ></div>
  </div>
</template>
<script>
export default {
  props: {
    label: String,
    content: String,
    variant: String,
    rows: String,
  },
};
</script>
<style scoped>
.textarea-read {
  word-break: break-all;
  overflow: auto;
}
.textarea-read.row-2 {
  height: 4rem;
}
.textarea-read.row-3 {
  height: 6rem;
}
.textarea-read.row-4 {
  height: 8rem;
}
.textarea-read.row-5 {
  height: 10rem;
}
</style>