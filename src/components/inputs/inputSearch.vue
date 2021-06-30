<template>
  <div class="mb-3">
    <label :for="id" class="form-label" v-if="label">
      {{ label }}
    </label>
    <div :class="['s26-container-search', focus ? 'focus' : '']">
      <div class="s26-btn s26-btn-search">
        <button
          type="button"
          @click="update"
          @focus="focus = true"
          @blur="focus = false"
        >
          <s26-icon icon="search"></s26-icon>
        </button>
      </div>
      <div class="s26-input-search">
        <input
          type="text"
          :id="id"
          :maxlength="maxlength"
          :minlength="minlength"
          :placeholder="placeholder ? placeholder : 'Buscar...'"
          v-bind:value="value"
          v-on:input="$emit('input', $event.target.value)"
          @keyup="$emit('keyup')"
          @keyup.13="update"
          @keyup.27="cancel"
          :text="text"
          :number="number"
          :email="email"
          :s26-required="s26_required"
          :autofocus="autofocus"
          :autocomplete="autocomplete"
          :name="name"
          @focus="focus = true"
          @blur="focus = false"
        />
      </div>
      <div class="s26-btn s26-btn-cancel">
        <transition name="fade">
          <button
            type="button"
            @click="cancel"
            @focus="focus = true"
            @blur="focus = false"
            v-if="value !== ''"
          >
            <s26-icon icon="times"></s26-icon>
          </button>
        </transition>
      </div>
    </div>
    <p class="invalid-feedback" v-if="s26_required">{{ message }}</p>
  </div>
</template>
<script>
export default {
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
    name: String,
    text: Boolean,
    number: Boolean,
    email: Boolean,
    s26_required: Boolean,
    autofocus: Boolean,
    autocomplete: String,
    length: Boolean,
  },
  data: function () {
    return {
      focus: false,
    };
  },
  methods: {
    update() {
      this.$emit("update");
      this.$emit("search");
    },
    cancel() {
      this.$emit("input", "");
      this.$emit("update");
      this.$emit("search");
    },
  },
};
</script>
