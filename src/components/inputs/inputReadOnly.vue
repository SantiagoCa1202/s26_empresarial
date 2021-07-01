<template>
  <div :class="['mb-3 pointer', variant ? variant : '']">
    <label class="form-label w-100" v-if="label">
      {{ label }}
      <a
        @click="getRow"
        class="text-primary float-end pointer"
        v-if="link && link != ''"
      >
        <s26-icon icon="link"></s26-icon>
      </a>
    </label>
    <div class="form-control form-control-sm">{{ content }}</div>
  </div>
</template>
<script>
export default {
  props: {
    label: String,
    content: {},
    variant: String,
    link: String,
  },
  methods: {
    getRow() {
      let strLink = this.link.split(",");
      s26.create_cookie("id", strLink[1], strLink[0]);
      window.open(BASE_URL + "/" + strLink[0], "_blank");
    },
  },
};
</script>