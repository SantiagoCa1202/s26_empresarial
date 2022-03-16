<template>
  <s26-card>
    <template v-slot:header>
      <h1 class="h5 fw-600">
        {{ title }}
        <span class="float-end">
          <s26-icon icon="dollar-sign"></s26-icon>
          {{ $s26.currency(parseFloat(values.cost) + parseFloat(values.gain)) }}
        </span>
      </h1>
    </template>
    <template v-slot:body>
      <div class="col-12 mb-3">
        <label for="" class="w-100">
          Costo
          <span class="float-end">
            <s26-icon icon="dollar-sign"></s26-icon>
            {{ $s26.currency(values.cost) }}
          </span>
        </label>
        <s26-progress-bar
          :percentage="percentage.cost"
          :variant="variant.cost"
          :background="background.cost"
        ></s26-progress-bar>
      </div>
      <div class="col-12 mb-3">
        <label for="" class="w-100">
          Ganancia
          <span class="float-end">
            <s26-icon icon="dollar-sign"></s26-icon>
            {{ $s26.currency(values.gain) }}
          </span>
        </label>
        <s26-progress-bar
          :percentage="percentage.gain"
          :variant="variant.gain"
          :background="background.gain"
        ></s26-progress-bar>
      </div>
    </template>
    <template v-slot:footer>
      <slot name="footer"></slot>
    </template>
  </s26-card>
</template>

<script>
export default {
  props: {
    title: {
      type: String,
      default: "",
    },
    values: {
      type: Object,
      default: () => {
        return {
          cost: 0,
          gain: 0,
        };
      },
    },
    variant: {
      type: Object,
      default: () => {
        return {
          cost: "",
          gain: "",
        };
      },
    },
    background: {
      type: Object,
      default: () => {
        return {
          cost: "",
          gain: "",
        };
      },
    },
  },
  computed: {
    percentage: function () {
      let cost = 0;
      let gain = 0;
      if (this.values.cost > 0 || this.values.gain > 0) {
        cost =
          this.values.gain > 0
            ? (this.values.cost * 100) / this.values.gain
            : 100;
        gain =
          this.values.cost > 0
            ? (this.values.gain * 100) / this.values.cost
            : 100;
      }

      return {
        cost: cost,
        gain: gain,
      };
    },
  },
  data: function () {
    return {};
  },
};
</script>
