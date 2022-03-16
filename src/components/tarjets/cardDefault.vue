<template>
  <div class="s26-card">
    <div class="header mb-2">
      <div class="row">
        <h1 class="h5 fw-600" v-if="title != ''">{{ title }}</h1>
        <slot name="header"></slot>
      </div>
    </div>
    <div class="body">
      <div class="row">
        <slot name="body"></slot>
      </div>
    </div>
    <div class="footer">
      <div class="row">
        <slot name="footer"></slot>
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
  },
};
</script>


<style>
.s26-card {
  width: 100%;
  background: #fff;
  height: auto;
  padding: 0.5rem;
  box-shadow: 0 2px 8px 2px rgb(93 130 170 / 21%);
  border-radius: 0.4rem;
  color: var(--s26-blue);
}

.s26-card .header,
.s26-card .footer {
  align-items: center;
  padding: 0.5rem;
  padding: 0.5rem;
}

.s26-card .body {
  padding-left: 0.5rem;
  padding-right: 0.5rem;
  align-items: center;
}
</style>