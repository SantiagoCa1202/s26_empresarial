<template>
  <div class="mb-3 s26-form-group">
    <label :for="id" class="form-label" v-if="label">
      {{ label }}
      <span class="text-danger" v-if="s26_required">
        <s26-icon icon="asterisk" class="icon_asterisk_required"></s26-icon>
      </span>
    </label>
    <span
      v-if="length && value.length > 0"
      :class="[
        'fw-bold float-end',
        value.length == maxlength ||
        value.length == minlength ||
        strictlength_func()
          ? 'text-success'
          : 'text-danger',
      ]"
    >
      {{ length ? value.length : "" }}
    </span>
    <input
      :type="type"
      :class="[
        'form-control form-control-' + size,
        money ? 'form-control-dollars' : '',
        variant,
      ]"
      :id="id"
      :maxlength="maxlength"
      :minlength="minlength"
      :strictlength="strictlength"
      :placeholder="placeholder"
      v-bind:value="value"
      v-on:input="$emit('input', $event.target.value)"
      @keyup="$emit('keyup')"
      @keyup.enter="$emit('enter')"
      :text="text"
      :number="number"
      :email="email"
      :s26-required="s26_required"
      :autofocus="autofocus"
      :autocomplete="autocomplete"
      :name="name"
      :money="money"
      :validate="validate"
      :disabled="disabled"
      :title="title"
    />
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

    <p class="invalid-feedback" v-if="s26_required || validate">
      {{ message }}
    </p>
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
    type: {
      type: String,
      default: "text",
    },
    size: {
      type: String,
      default: "sm",
    },
    maxlength: String,
    minlength: String,
    strictlength: String,
    placeholder: String,
    variant: {
      type: String,
      default: "",
    },
    value: {
      default: "",
    },
    name: String,
    text: Boolean,
    number: Boolean,
    email: Boolean,
    money: Boolean,
    percentage: Boolean,
    search: Boolean,
    s26_required: Boolean,
    autofocus: Boolean,
    autocomplete: String,
    length: Boolean,
    validate: Boolean,
    disabled: Boolean,
    title: String,
  },
  created() {
    setTimeout(() => {
      $s26.val_inputs();
    }, 100);
  },
  methods: {
    strictlength_func() {
      if (this.strictlength != "" && this.strictlength) {
        let lengthArr = this.strictlength.split(",");

        if (lengthArr.indexOf(this.value.length.toString()) == -1) {
          return false;
        } else {
          return true;
        }
      }
    },
  },
};
</script>