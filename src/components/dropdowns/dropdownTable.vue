<template>
  <div class="s26-nav-dropdown" tabindex="0">
    <div @click="active" :class="['s26-button-dropdown', label_variant]">
      <s26-icon :icon="icon ? icon : 'ellipsis-v'" v-if="!content"></s26-icon>
      {{ content ? content : "" }}
    </div>
    <div :style="{ width: width + 'px' }" :class="['s26-list-dropdown']">
      <slot> </slot>
    </div>
  </div>
</template>
<script>
export default {
  props: {
    content: String,
    icon: String,
    width: {
      default: 90,
    },
    label_variant: {
      type: String,
      default: "",
    },
  },
  data: function () {
    return {
      isActive: false,
    };
  },
  created() {},
  methods: {
    active(e) {
      let dropdown = $(e.target).closest("div.s26-nav-dropdown");
      let dropdown_button = $(dropdown).children("div.s26-button-dropdown");
      let location = dropdown_button[0].getBoundingClientRect();
      let dropdown_list = $(dropdown).children("div.s26-list-dropdown");
      let left = location.left + (location.width - this.width) / 2;
      $(dropdown_list).css({ left: left }).toggle();

      $(`div.s26-list-dropdown`).not(dropdown_list).hide("200");

      let dropdown_list_location = dropdown_list[0].getBoundingClientRect();

      let position = 0;
      if (
        location.top > 0 &&
        location.top < dropdown_list_location.height + 100
      ) {
        position = location.top + 20;
      } else if (location.top > dropdown_list_location.height + 25) {
        position = location.top - (dropdown_list_location.height + 10);
      }
      $(dropdown_list).css({ top: position });

      $(window).on("resize", () => {
        $("div.s26-list-dropdown").hide("200");
      });
      $("html, .s26-modal, .s26-modal-content").on("click", () => {
        $(`div.s26-list-dropdown`).hide("200");
      });
      $("div.s26-nav-dropdown").on("click", (e) => {
        e.stopPropagation();
      });
    },
  },
};
</script>
<style scoped>
.s26-nav-dropdown .s26-list-dropdown {
  position: fixed;
  display: none;
  z-index: 1070;
  border: 1px solid #ccd2db;
  box-shadow: 0 2px 3px 0 #ccd2db;
  border-radius: 0.28571429rem;
}

.s26-nav-dropdown .s26-list-dropdown li {
  text-align: left;
  padding: 0.5rem;
}

.s26-nav-dropdown .s26-list-dropdown li:hover {
  background: rgba(0, 0, 0, 0.1);
}
</style>