<template>
  <div :class="'mb-' + mb">
    <label :for="id" class="form-label" v-if="label">
      {{ label }}
    </label>
    <div
      :class="[
        's26-container-search',
        focus ? 'focus' : '',
        rounded ? 's26-rounded' : '',
      ]"
    >
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
          @blur="blur"
          :list="list.length > 0 ? 'search_list' : ''"
        />
        <datalist id="search_list" v-if="list.length > 0">
          <option :value="item" v-for="item in list" :key="item"></option>
        </datalist>
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
      <span
        v-if="length"
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
    </div>
    <p class="invalid-feedback" v-if="s26_required">{{ message }}</p>
  </div>
</template>
<script>
export default {
  props: {
    label: String,
    id: {
      type: String,
      default: "",
    },
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
    strictlength: String,
    rounded: Boolean,
    mb: {
      default: "3",
    },
    list: {
      type: Array,
      default: () => [],
    },
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
    blur() {
      this.focus = false;
      this.$emit("blur");
    },
  },
};
</script>
<style scoped>
.s26-container-search {
  border-bottom: 1px solid #ced4da;
  display: flex;
  align-items: center;
  transition: 0.3s;
  height: 2em;
}

.s26-container-search.focus {
  border-color: var(--s26-info);
  box-shadow: 0 3px 6px 0px rgb(0 0 0 / 12%) !important;
}

.s26-container-search button {
  display: flex;
  text-align: center;
  text-decoration: none;
  align-items: center;
  cursor: pointer;
  -webkit-user-select: none;
  -moz-user-select: none;
  user-select: none;
  font-size: 0.85rem;
  border: 0;
  background: none;
  color: #bebebe;
  transition: 0.3s;
}

.s26-container-search .s26-btn {
  width: 12%;
  display: flex;
  align-items: center;
  justify-content: center;
}

.s26-container-search .s26-btn-search button:hover,
.s26-container-search .s26-btn-search button:focus {
  color: var(--bs-primary);
  outline: none;
}

.s26-container-search .s26-btn-cancel button:hover,
.s26-container-search .s26-btn-cancel button:focus {
  color: var(--bs-danger);
  outline: none;
}

.s26-container-search .s26-input-search {
  width: 90%;
}

.s26-container-search .s26-input-search input {
  border: 0;
  width: 100%;
  height: 100%;
  padding: 0.35rem 0.5rem;
  background: none;
}

.s26-container-search .s26-input-search input:focus {
  outline: none;
}

.s26-container-search.s26-rounded:not(.focus) {
  border: 1px solid #bebebe6e;
}
.s26-container-search.s26-rounded {
  border-radius: 50px;
  border: 0 solid #bebebe00;
  padding: 0.5rem;
}
[list]::-webkit-calendar-picker-indicator {
  display: none !important;
}
</style>
