<template>
  <div :class="'mb-' + mb">
    <label class="form-label" v-if="label">
      {{ label }}
      <span class="text-danger" v-if="s26_required">
        <s26-icon icon="asterisk" class="icon_asterisk_required"></s26-icon>
      </span>
    </label>
    <textarea
      :id="id"
      :class="['form-control resize-none']"
      :rows="rows"
      v-bind:value="value"
      v-on:input="$emit('input', $event.target.value)"
      @keyup="$emit('keyup')"
      :autofocus="autofocus"
      :s26-required="s26_required"
      :maxlength="maxlength"
      :placeholder="placeholder"
    ></textarea>
    <p class="invalid-feedback" v-if="s26_required">{{ message }}</p>
  </div>
</template>
<script>
export default {
  props: {
    value: {},
    id: String,
    label: String,
    rows: [Number, String],
    message: {
      type: String,
      default: "",
    },
    s26_required: Boolean,
    autofocus: Boolean,
    maxlength: {
      default: "",
    },
    mb: {
      default: "3",
    },
    placeholder: {
      default: "",
    },
  },
};
</script>
