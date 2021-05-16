Vue.component("s26-form-input", {
  props: {
    label: String,
    id: String,
    message: {
      type: String,
      default: "",
    },
    type: String,
    size: String,
    maxlength: String,
    minlength: String,
    placeholder: String,
    variant: {
      type: String,
      default: "",
    },
    value: {},
    text: Boolean,
    number: Boolean,
    email: Boolean,
    s26_required: Boolean,
    autofocus: Boolean,
    autocomplete: String,
  },
  data: function () {
    return {};
  },
  created() {},
  methods: {},
  template: `
    <div class="mb-3">
      <label :for="id" class="form-label" v-if="label"> {{ label }} </label>
      <input 
        :type="type" 
        :class="['form-control form-control-' + size, variant ]"
        :id="id" 
        :maxlength="maxlength" 
        :minlength="minlength" 
        :placeholder="placeholder"
        v-bind:value="value"
        v-on:input="$emit('input', $event.target.value)"  
        @keyup="$emit('keyup')"
        :text="text"
        :number="number"
        :email="email"
        :s26-required="s26_required"
        :autofocus="autofocus"
        :autocomplete="autocomplete"
      >
      <p class="invalid-feedback" v-if="s26_required">{{ message }} </p>
    </div>
  `,
});
