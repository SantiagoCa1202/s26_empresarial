<template>
  <div class="mb-4">
    <label class="form-label" v-if="label">
      {{ label }}
      <span class="text-danger" v-if="s26_required">
        <s26-icon icon="asterisk" class="icon_asterisk_required"></s26-icon>
      </span>
    </label>
    <editor
      api-key="kajkxm4oxed3l6r6d5o523m8x22ypgoqqejvdcto8wc3hekk"
      :init="{
        width: '100%',
        height: height,
        menubar: false,
        plugins: [
          'advlist autolink lists link image charmap print preview anchor',
          'searchreplace visualblocks code fullscreen',
          'insertdatetime media table paste code help wordcount',
        ],
        toolbar:
          'insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | print preview media fullpage | forecolor backcolor emoticons',
      }"
      :id="id"
      :class="['form-control resize-none']"
      :rows="rows"
      v-bind:value="value"
      v-on="inputListeners"
      v-model="val"
      :s26-required="s26_required"
    />
    <p class="invalid-feedback" v-if="s26_required">{{ message }}</p>
  </div>
</template>
<script>
import Editor from "@tinymce/tinymce-vue";

export default {
  props: {
    height: {},
    value: {},
    id: String,
    label: String,
    rows: [Number, String],
    message: {
      type: String,
      default: "",
    },
    s26_required: Boolean,
  },
  components: {
    editor: Editor,
  },
  data() {
    return {
      val: "",
    };
  },
  computed: {
    inputListeners: function () {
      var vm = this;
      return Object.assign({}, this.$listeners, {
        input: function (event) {
          vm.$emit("input", vm.val);
        },
      });
    },
  },
};
</script>