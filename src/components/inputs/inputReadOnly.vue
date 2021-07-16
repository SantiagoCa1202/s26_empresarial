<template>
  <div :class="['mb-3 pointer', variant ? variant : '', 's26-form-group']">
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
    <div
      :class="[
        'form-control form-control-sm',
        money ? 'form-control-dollars' : '',
      ]"
    >
      {{ money ? $s26.currency(content) : content }}
    </div>
    <i v-if="money" class="form-icon-dollar">
      <s26-icon icon="dollar-sign"></s26-icon>
    </i>
  </div>
</template>
<script>
export default {
  props: {
    label: String,
    content: {},
    variant: String,
    link: String,
    money: Boolean,
  },
  methods: {
    getRow() {
      let strLink = this.link.split(",");
      $s26.create_cookie("id", strLink[1], strLink[0]);
      window.open(BASE_URL + "/" + strLink[0], "_blank");
    },
  },
};
</script>