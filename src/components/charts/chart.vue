<script>
import { Line, mixins } from "vue-chartjs";
const { reactiveProp } = mixins;

export default {
  extends: Line,
  mixins: [reactiveProp],
  props: {
    height: {
      type: Number,
    },
    options: {
      type: Object,
    },
    labels: {
      type: Array,
    },
    datasets: {
      type: Array,
    },
  },
  data: function () {
    return {};
  },
  mounted() {
    this.$refs.canvas.parentNode.style.height = `${this.height}px`;
    const labels = this.months ? this.arr_months : this.labels;
    this.renderChart(this.chartData, this.options);
  },
};
</script>

<style>
</style>


