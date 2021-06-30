import Vue from "vue";
import "/Assets/css/style_view/perfilUser.css";

let element = !!document.getElementById("s26-users-info-view");
if (element) {
  new Vue({
    el: "#s26-users-info-view",
    data: function() {
      return {
        fields: [
          {
            name: "fecha",
            class: "length-description",
          },
          {
            name: "descripción",
            class: "length-description",
          },

          {
            name: "importe",
            class: "length-int",
          },
          {
            name: "forma de pago",
            class: "length-description",
          },
        ],
        filter: {
          date: "",
        },
        icon: {
          building: {
            background: "#3A86FA",
            color: "#fff",
          },
          user: {
            background: "#20c997",
            color: "#fff",
          },
        },
        payRoll_arr: {},
        modal: "info_user",
        items: [],
        rows: 0,
        idRow: null,
        idNote: null,
        idNotification: null,
        del_note: null,
        del_notification: null,
        perPage: 25,
        idUser: idUser,
      };
    },
    methods: {
      payRoll() {
        this.modal = "payroll";

        this.axios
          .get("/users/getPayroll/0")
          .then((res) => {
            this.payRoll_arr = res.data;
            this.payments_records();
          })
          .catch((err) => {
            console.log(err);
          });
      },
      payments_records() {
        const params = {
          date: this.filter.date,
          perPage: this.perPage,
        };
        this.axios
          .get("/users/getPayRecords/", {
            params,
          })
          .then((res) => {
            this.items = res.data.items;
            this.rows = res.data.info.count;
          })
          .catch((err) => {
            console.log(err);
          });
      },
      my_notes() {
        this.modal = "my_notes";
        this.axios
          .get("/users/getMyNotes/")
          .then((res) => {
            this.items = res.data.items;
            this.rows = res.data.info.count;
          })
          .catch((err) => {
            console.log(err);
          });
      },
      notifications() {
        this.modal = "notifications";
        this.axios
          .get("/users/getNotifications/")
          .then((res) => {
            this.items = res.data.items;
            this.rows = res.data.info.count;
          })
          .catch((err) => {
            console.log(err);
          });
      },
    },
  });
}
