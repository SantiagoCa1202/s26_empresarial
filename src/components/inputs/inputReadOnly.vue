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
        variant_input,
      ]"
    >
      {{ money ? $s26.currency(content) : content }}
    </div>

    <i
      :class="[
        'form-icon',
        money ? 'form-icon-start' : '',
        percentage || search ? 'form-icon-end' : '',
      ]"
      :style="label ? '' : 'top: .4rem'"
    >
      <s26-icon icon="dollar-sign" v-if="money"></s26-icon>
      <s26-icon icon="percentage" v-if="percentage"></s26-icon>
      <s26-icon icon="search" v-if="search"></s26-icon>
    </i>
  </div>
</template>
<script>
export default {
  props: {
    label: String,
    content: {},
    variant: String,
    variant_input: {
      type: String,
      default: "",
    },
    link: String,
    money: Boolean,
    percentage: Boolean,
    search: Boolean,
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