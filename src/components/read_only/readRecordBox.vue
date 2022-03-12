<template>
  <s26-modal-multiple
    id="readRecordBox"
    title="Historial de Cierres de Caja"
    :levels="levels"
    body_style="min-height: 260px"
    @hideModal="hideModal"
    readOnly
    size="xl"
  >
    <template v-slot:level-0>
      <s26-table
        :rows="records.length"
        :fields="fields_records"
        relative
        height="auto"
      >
        <template v-slot:body>
          <tr v-for="record in records" :key="record.id">
            <td class="length-action text-center">{{ record["100"] }}</td>
            <td class="length-action text-center">{{ record["50"] }}</td>
            <td class="length-action text-center">{{ record["20"] }}</td>
            <td class="length-action text-center">{{ record["10"] }}</td>
            <td class="length-action text-center">{{ record["5"] }}</td>
            <td class="length-action text-center">{{ record["1"] }}</td>
            <td class="length-action text-center">{{ record["0.50"] }}</td>
            <td class="length-action text-center">{{ record["0.25"] }}</td>
            <td class="length-action text-center">{{ record["0.10"] }}</td>
            <td class="length-action text-center">{{ record["0.05"] }}</td>
            <td class="length-action text-center">{{ record["0.01"] }}</td>
            <td
              :class="[
                'length-date',
                'fw-600',
                record.adjusted_amount >= 0 ? 'text-success' : 'text-danger',
              ]"
            >
              <s26-icon icon="dollar-sign"></s26-icon>
              {{ $s26.currency(record.adjusted_amount) }}
            </td>
            <td class="length-date text-center">
              {{ $s26.formatDate(record.created_at, "sm2") }}
            </td>
          </tr>
        </template>
      </s26-table>
    </template>
  </s26-modal-multiple>
</template>
<script>
export default {
  props: {
    value: {
      type: String,
      required: true,
    },
    id: {
      type: Number,
      required: true,
    },
  },
  data: function () {
    return {
      fields_records: [
        {
          name: "$100",
          class: "length-action text-center",
        },
        {
          name: "$50",
          class: "length-action text-center",
        },
        {
          name: "$20",
          class: "length-action text-center",
        },
        {
          name: "$10",
          class: "length-action text-center",
        },
        {
          name: "$5",
          class: "length-action text-center",
        },
        {
          name: "$1",
          class: "length-action text-center",
        },
        {
          name: "$0.50",
          class: "length-action text-center",
        },
        {
          name: "$0.25",
          class: "length-action text-center",
        },
        {
          name: "$0.10",
          class: "length-action text-center",
        },
        {
          name: "$0.05",
          class: "length-action text-center",
        },
        {
          name: "$0.01",
          class: "length-action text-center",
        },
        {
          name: "Ajuste",
          class: "length-date",
        },
        {
          name: "Creado El",
          class: "length-date text-center",
        },
      ],

      records: [],
      levels: ["Historial de Cierre de Caja"],
    };
  },
  created() {
    if (this.id !== 0 && this.id !== null) this.infoData(this.id);
  },
  methods: {
    infoData(id) {
      this.axios
        .get("/boxes/getBoxAdjustment/" + id)
        .then((res) => (this.records = res.data))
        .catch((err) => console.log(err));
    },
    hideModal() {
      this.$emit("input", null);
      $s26.delete_cookie("id", "boxes");
    },
  },
};
</script>