Vue.component("s26-dropdown", {
  props: {},
  data: function () {
    return {
      isActive: false,
    };
  },
  created() {},
  methods: {},
  template: `
    <div
      class="s26-nav-dropdown"
      @click="isActive = true"
      @focusout="isActive = false"
      tabindex="0"
    >
      <div>
        <i class="fas fa-ellipsis-v"></i>
      </div>
      <div class="s26-list-dropdown" :class="isActive == true ? 'show' : 'hide'">
        <slot> </slot>
      </div>
    </div>
  `,
});
