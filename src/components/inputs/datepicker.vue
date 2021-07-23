<template>
  <div :id="'s26-datepicker-' + id" class="s26-datepicker mb-3">
    <label for="id" class="form-label w-100" v-if="label !== '' && label">
      {{ label }}
      <span class="text-danger" v-if="s26_required">
        <s26-icon icon="asterisk" class="icon_asterisk_required"></s26-icon>
      </span>
    </label>
    <div
      :id="id"
      class="form-control form-control-sm s26-date-value"
      tabindex="0"
      @click="active_datepicker"
      @keyup.enter="active_datepicker"
    >
      {{ value_date }}
      <s26-icon
        @click="value.length > 0 ? on_select_date(null) : ''"
        :icon="value.length > 0 ? 'times' : 'calendar-alt'"
        class="icon-date"
      ></s26-icon>
    </div>
    <input
      type="hidden"
      v-model="value"
      :s26-required="s26_required"
      date="true"
    />
    <p class="invalid-feedback" v-if="s26_required"></p>
    <div
      :id="'popup-datepicker-' + id"
      :class="['popup-datepicker', 'popup-' + popup]"
      tabindex="0"
      @keyup.alt.33="prevMonth"
      @keyup.alt.34="nextMonth"
      @keyup.esc="on_select_date(null)"
    >
      <transition name="fade" mode="out-in">
        <div class="s26-datepicker-header" v-if="showDate !== 'years'">
          <div class="s26-datepicker-header__prev">
            <button type="button" class="btn-piker" @click="prevMonth">
              <s26-icon icon="angle-left"></s26-icon>
            </button>
          </div>
          <div class="s26-datepicker-header__value">
            <div class="s26-slider">
              <button type="button" @click="funcShowDate">
                {{ currentDate }}
              </button>
              <button type="button" v-if="slider">
                {{ currentDate }}
              </button>
            </div>
          </div>
          <div class="s26-datepicker-header__next">
            <button type="button" class="btn-piker" @click="nextMonth">
              <s26-icon icon="angle-right"></s26-icon>
            </button>
          </div>
        </div>
      </transition>
      <transition name="fade" mode="out-in">
        <div class="s26-datepicker-weekdays" v-if="showDate == 'days'">
          <div class="s26-slider">
            <div class="weekdays">
              <div>Dom</div>
              <div>Lun</div>
              <div>Mar</div>
              <div>Mié</div>
              <div>Jue</div>
              <div>Vie</div>
              <div>Sáb</div>
            </div>
            <div class="weekdays" v-if="slider">
              <div>Dom</div>
              <div>Lun</div>
              <div>Mar</div>
              <div>Mié</div>
              <div>Jue</div>
              <div>Vie</div>
              <div>Sáb</div>
            </div>
          </div>
          <div class="s26-slider">
            <div class="days">
              <div
                class="day"
                v-for="(day, index) in days.prevDate"
                :key="index + 'prev'"
                tabindex="0"
                @click="on_select_date(day.fullDate)"
                @keyup.107="on_select_date(day.fullDate)"
              >
                <div
                  :class="[
                    'prev-date',
                    dates.days.indexOf(day.fullDate) > -1
                      ? 'day-with-data'
                      : '',
                  ]"
                  :date="day.fullDate"
                >
                  {{ day.day }}
                </div>
              </div>
              <div
                class="day"
                v-for="(day, index) in days.currentDate"
                :key="index + 'curr'"
                tabindex="0"
                @click="on_select_date(day.fullDate)"
                @keyup.107="on_select_date(day.fullDate)"
              >
                <div
                  :class="[
                    today.day == day.fullDate ? 'today' : '',
                    disabled_date(day.fullDate) ? '' : 'date-disabled',
                    dates.days.indexOf(day.fullDate) > -1
                      ? 'day-with-data'
                      : '',
                  ]"
                  :date="day.fullDate"
                >
                  {{ day.day }}
                </div>
              </div>
              <div
                class="day"
                v-for="(day, index) in days.nextDate"
                :key="index + 'next'"
                tabindex="0"
                @click="on_select_date(day.fullDate)"
                @keyup.107="on_select_date(day.fullDate)"
              >
                <div
                  :class="[
                    'next-date',
                    disabled_date(day.fullDate) ? '' : 'date-disabled',
                    dates.days.indexOf(day.fullDate) > -1
                      ? 'day-with-data'
                      : '',
                  ]"
                  :date="day.fullDate"
                >
                  {{ day.day }}
                </div>
              </div>
            </div>
            <div class="days" v-if="slider">
              <div
                class="day"
                v-for="(day, index) in days.prevDate"
                :key="index + 'prev'"
              >
                <div class="prev-date">
                  {{ day.day }}
                </div>
              </div>
              <div
                class="day"
                v-for="(day, index) in days.currentDate"
                :key="index + 'curr'"
              >
                <div>
                  {{ day.day }}
                </div>
              </div>
              <div
                class="day"
                v-for="(day, index) in days.nextDate"
                :key="index + 'next'"
              >
                <div class="next-date">
                  {{ day.day }}
                </div>
              </div>
            </div>
          </div>
        </div>
      </transition>
      <transition name="fade" mode="out-in">
        <div class="s26-datepicker-months h-75" v-if="showDate == 'months'">
          <div class="s26-slider h-100">
            <div class="months h-100">
              <div
                :class="[
                  today.month == year + '-' + month.intMonth ? 'today' : '',
                  disabled_date(year + '-' + month.intMonth + '-01')
                    ? ''
                    : 'date-disabled',
                  dates.months.indexOf(year + '-' + month.intMonth) > -1
                    ? 'day-with-data'
                    : '',
                ]"
                v-for="(month, index) in months"
                :key="index"
                tabindex="0"
                @click="go_date(month.intMonth, year)"
                @keyup.enter="go_date(month.intMonth, year)"
              >
                {{ month.strMonth }}
              </div>
            </div>
            <div class="months" v-if="slider">
              <div v-for="(month, index) in months" :key="index + 'next'">
                {{ month.strMonth }}
              </div>
            </div>
          </div>
        </div>
      </transition>
      <transition name="fade" mode="out-in">
        <div class="s26-datepicker-years" v-if="showDate == 'years'">
          <div class="years">
            <div
              :class="[today.year == year ? 'year-active' : '']"
              v-for="(year, index) in years"
              :key="index"
              tabindex="0"
              @click="go_date(null, year)"
              @keyup.enter="go_date(null, year)"
            >
              {{ year }}
            </div>
          </div>
        </div>
      </transition>
    </div>
  </div>
</template>
<script>
export default {
  props: {
    id: String,
    enable: String,
    label: String,
    s26_required: Boolean,
    select_all_dates: Boolean,
    value: {},
    dates: {
      type: Object,
      default: () => {
        return {
          days: [],
          months: [],
        };
      },
    },
    $today: Boolean,
    min: String,
    max: String,
  },
  data: function () {
    return {
      currentDate: "",
      days: {
        prevDate: [],
        currentDate: [],
        nextDate: [],
      },
      months: [
        { strMonth: "Ene", intMonth: "01" },
        { strMonth: "Feb", intMonth: "02" },
        { strMonth: "Mar", intMonth: "03" },
        { strMonth: "Abr", intMonth: "04" },
        { strMonth: "May", intMonth: "05" },
        { strMonth: "Jun", intMonth: "06" },
        { strMonth: "Jul", intMonth: "07" },
        { strMonth: "Ago", intMonth: "08" },
        { strMonth: "Sep", intMonth: "09" },
        { strMonth: "Oct", intMonth: 10 },
        { strMonth: "Nov", intMonth: 11 },
        { strMonth: "Dic", intMonth: 12 },
      ],
      years: [],
      date_range: [],
      today: {},
      month: "",
      year: "",
      date: new Date(),
      showDate: "days",
      slider: false,
      popup: "bottom",
    };
  },
  mounted() {
    for (let y = new Date().getFullYear(); y >= 1900; y--) {
      this.years.push(y);
    }
    //FECHA ACTUAL
    this.today.day = `${new Date().getFullYear()}-${this.intPadStart(
      new Date().getMonth() + 1
    )}-${this.intPadStart(new Date().getDate())}`;
    this.today.month = `${new Date().getFullYear()}-${this.intPadStart(
      new Date().getMonth() + 1
    )}`;
    this.today.year = `${new Date().getFullYear()}`;

    if (this.$today) {
      this.$emit("input", this.today.day);
    }
  },
  computed: {
    value_date: function () {
      if (this.value == "") return;
      if (this.enable == "unique") {
        return $s26.formatDate(`${this.value}`);
      } else if (this.enable == "range" || this.enable == "multiple") {
        if (this.value.length == 1) {
          return $s26.formatDate(this.value[0]);
        } else if (this.value.length > 1) {
          return (
            $s26.formatDate(this.value[0]) +
            " ~ " +
            $s26.formatDate(this.value[this.value.length - 1])
          );
        }
      }
    },
  },
  methods: {
    renderCalendar() {
      // PRIMER DIA DEL MES SELECCIONADO
      this.date.setDate(1);
      // NUMERO DEL MES SELECCIONADO
      this.month = this.date.getMonth();
      // AÑO SELECCIONADO
      this.year = this.date.getFullYear();

      // ULTIMO DIA DEL MES SELECCIONADO
      const lastDay = new Date(this.year, this.month + 1, 0).getDate();
      //ULTIMO DIA DEL MES ANTERIOR AL SELECCIONADO
      const prevLastDay = new Date(this.year, this.month, 0).getDate();
      //UBICACION DE DIA DEL MES SELECCIONADO
      const firstDayIndex = this.date.getDay();
      //UBICACION DE DIA DEL MES ANTERIOR AL SELECCIONADO
      const lastDayIndex = new Date(this.year, this.month + 1, 0).getDay();
      //DIAS SIGUIENTES AL MES SELECCIONADO
      const nextDays = 7 - lastDayIndex - 1;

      //CONTENEDOR DE FECHAS//////////////////////////

      //MES PASADO
      this.days.prevDate = [];
      for (let x = firstDayIndex; x > 0; x--) {
        this.days.prevDate.push({
          day: prevLastDay - x + 1,
          fullDate: `${this.year}-${this.intPadStart(
            this.month
          )}-${this.intPadStart(prevLastDay - x + 1)}`,
        });
      }

      //MES PRESENTE
      this.days.currentDate = [];
      for (let i = 1; i <= lastDay; i++) {
        this.days.currentDate.push({
          day: i,
          fullDate: `${this.year}-${this.intPadStart(
            this.month + 1
          )}-${this.intPadStart(i)}`,
        });
      }

      //MES SIGUIENTE
      this.days.nextDate = [];
      for (let j = 1; j <= nextDays; j++) {
        this.days.nextDate.push({
          day: j,
          fullDate: `${this.year}-${this.intPadStart(
            this.month + 2
          )}-${this.intPadStart(j)}`,
        });
      }

      // FECHA ACTUAL SELECCIONADA
      // JUN 2021 // 2021
      this.currentDate =
        this.showDate == "days"
          ? `${this.months[this.month].strMonth} ${this.year}`
          : (this.currentDate = this.year);

      setTimeout(() => this.select_date(), 50);
    },

    prevMonth() {
      this.slider = true;
      $(".s26-slider").addClass("active-s26-slider_from");

      if (this.showDate == "days") {
        this.date.setMonth(this.date.getMonth() - 1);
      } else if (this.showDate == "months") {
        this.date.setFullYear(this.date.getFullYear() - 1);
      }

      this.renderCalendar();

      setTimeout(() => {
        $(".s26-slider").removeClass("active-s26-slider_from");
        this.slider = false;
      }, 250);
    },

    nextMonth(e) {
      this.slider = true;
      $(".s26-slider").addClass("active-s26-slider_to");

      if (this.showDate == "days") {
        this.date.setMonth(this.date.getMonth() + 1);
      } else if (this.showDate == "months") {
        this.date.setFullYear(this.date.getFullYear() + 1);
      }

      this.renderCalendar();

      setTimeout(() => {
        $(".s26-slider").removeClass("active-s26-slider_to");
        this.slider = false;
      }, 250);
    },

    funcShowDate() {
      if (this.showDate == "days") {
        this.showDate = "months";
      } else if (this.showDate == "months") {
        this.showDate = "years";
      }
      this.renderCalendar();
    },

    go_date(month, year) {
      if (month !== null && this.disabled_date(`${year}-${month}-1`)) {
        this.date.setMonth(month - 1);
        this.showDate = "days";
      } else {
        this.showDate = "months";
      }
      this.date.setFullYear(year);
      this.renderCalendar();
    },

    select_date(n = "") {
      this.date_range = this.value;
      $(`.days div`).removeClass("day-active");
      if (n == null) {
        this.$emit("input", this.enable == "unique" ? "" : []);
      } else if (this.enable == "unique") {
        if (this.value !== n && n != "") {
          this.$emit("input", n);
          $(`div.popup-datepicker`).hide("200");
        }
        setTimeout(() => {
          $(`.days .day div[date='${this.value}']`).addClass("day-active");
        }, 25);
      } else if (this.enable == "range") {
        if (this.value.length == 2 && n != "") {
          this.$emit("input", []);
          this.date_range = [];
        }

        if (n != "") this.date_range.push(n);

        $(`.days .day div[date='${this.date_range[0]}']`).addClass(
          "day-active"
        );
        if (this.date_range.length == 2) {
          this.date_range.sort();

          let from = new Date(this.date_range[0]);
          let to = new Date(this.date_range[1]);

          while (to.getTime() >= from.getTime()) {
            from.setDate(from.getDate() + 1);

            $(
              `.days .day div[date='${from.getFullYear()}-${this.intPadStart(
                from.getMonth() + 1
              )}-${this.intPadStart(from.getDate())}']`
            ).addClass("day-active");
          }
        }
        this.$emit("input", this.date_range);
      } else if (this.enable == "multiple") {
        if (!this.value.includes(n) && n != "") {
          this.date_range.push(n);
        } else if (this.value.includes(n)) {
          let supr = this.value.indexOf(n);
          this.date_range.splice(supr, 1);
        }

        for (let i = 0; i < this.value.length; i++) {
          $(`.days .day div[date='${this.date_range[i]}']`).addClass(
            "day-active"
          );
        }
        this.date_range.sort();
        this.$emit("input", this.date_range);
      }
      if (n !== "") this.$emit("change");
    },
    disabled_date(data) {
      if (!this.select_all_dates) {
        let currentDate = new Date(this.today.day);
        let date = new Date(data);
        return date.getTime() <= currentDate.getTime() ? true : false;
      } else {
        return true;
      }
    },
    intPadStart: (n) => (n < 10 ? `0${n}` : n),
    active_datepicker(e) {
      this.renderCalendar();

      let datepicker = $(e.target).closest("div.s26-datepicker");
      let datepicker_value = $(datepicker).children("div.s26-date-value");
      let location = datepicker_value[0].getBoundingClientRect();
      let datepicker_container = $(datepicker).children("div.popup-datepicker");

      let left = location.left;
      if (location.width < 260) {
        left = location.left + (location.width - 260) / 2;
      }

      $(datepicker_container)
        .width(location.width)
        .css({ left: left })
        .toggle(200);
      $(`div.popup-datepicker`).not(datepicker_container).hide("200");
      let position = 0;
      if (location.top > 0 && location.top < 300) {
        position = location.top + 45;
        this.popup = "bottom";
      } else if (location.top > 340) {
        this.popup = "top";
        position = location.top - 305;
      }
      $(datepicker_container).css({ top: position });

      $("*")
        .not("div.popup-datepicker *")
        .on("scroll", () => {
          $("div.popup-datepicker").hide("200");
        });
      $(window).on("resize", () => {
        $("div.popup-datepicker").hide("200");
      });
      $("html, .s26-modal, .s26-modal-content").on("click", () => {
        $(`div.popup-datepicker`).hide("200");
      });
      $("div.s26-datepicker").on("click", (e) => {
        e.stopPropagation();
      });
    },
    on_select_date(n) {
      if (this.disabled_date(n) && n !== "") this.select_date(n);
    },
  },
};
</script>
<style scoped>
/* CUSTOM DATEPICKER S26 - CALENDAR */

.s26-datepicker {
  position: relative;
  cursor: pointer;
  -webkit-user-select: none;
  -moz-user-select: none;
  -ms-user-select: none;
  user-select: none;
}

.icon-date {
  position: absolute;
  font-size: 1rem;
  right: 10px;
  color: rgba(0, 0, 0, 0.2);
  transition: 0.3s;
}

.icon-date:hover {
  color: var(--s26-blue);
}

.popup-datepicker {
  display: none;
  background: #fff;
  position: fixed;
  height: 290px;
  border-radius: 0.5rem;
  box-shadow: 0 0 9px 0 #80808078;
  z-index: 1060;
  min-width: 260px;
}

.popup-bottom::before {
  position: absolute;
  content: "";
  width: 0.91428571em;
  height: 0.91428571em;
  background: #fff;
  -webkit-transform: rotate(45deg);
  transform: rotate(45deg);
  z-index: 2;
  border-left: 1px solid #dedede;
  border-top: 1px solid #dedede;
  top: -0.50714286em;
  left: 50%;
  right: auto;
  bottom: auto;
}

.popup-top::before {
  position: absolute;
  content: "";
  width: 0.91428571em;
  height: 0.91428571em;
  background: #fff;
  -webkit-transform: rotate(45deg);
  transform: rotate(45deg);
  z-index: 2;
  border-right: 1px solid #dedede;
  border-bottom: 1px solid #dedede;
  bottom: -0.50714286em;
  left: 50%;
  right: auto;
  top: auto;
}

.s26-datepicker-header {
  width: 100%;
  display: flex;
  align-items: center;
}

.s26-datepicker-header button {
  font-weight: 800;
  font-size: 1.1rem;
  outline: none;
  height: 36px;
  border: 0;
  margin: auto;
  display: block;
  background: none;
  transition: 0.3s;
  color: var(--s26-blue);
}

.s26-datepicker-header button svg {
  filter: drop-shadow(0 0.1rem 0.07rem rgb(0 0 0 / 50%));
  color: var(--s26-blue);
}

.s26-datepicker-header button:hover,
.s26-datepicker-header button:focus,
.s26-datepicker-header button svg:hover,
.s26-datepicker-header button svg:focus {
  color: var(--bs-primary);
  border: 0;
  outline: none;
}

.s26-datepicker-header__prev,
.s26-datepicker-header__next {
  padding: 0.5rem;
  width: 20%;
}

.s26-datepicker-header .s26-datepicker-header__value {
  padding: 0.5rem;
  width: 80%;
  overflow: hidden;
}

.s26-datepicker-header button:active {
  font-size: 1.3rem;
  transition: 0.01s;
}

.s26-datepicker-weekdays,
.s26-datepicker-months {
  overflow: hidden;
  width: 100%;
  position: absolute;
}

.weekdays {
  width: 100%;
  height: 1rem;
  display: flex;
  align-items: center;
  padding: 0 0.5rem;
}

.weekdays div {
  font-weight: 400;
  letter-spacing: 0.1rem;
  width: calc(100% / 7);
  display: flex;
  justify-content: center;
  align-items: center;
  color: rgba(0, 0, 0, 0.38);
}

.days,
.months {
  width: 100%;
  display: flex;
  flex-wrap: wrap;
  padding: 0 0.5rem;
}

.days .day {
  width: calc(100% / 7);
  height: 2.3rem;
  display: flex;
  justify-content: center;
  align-items: center;
  transition: background-color 0.3s;
  padding: 0.25rem 0.15rem;
}
.days .day div {
  font-size: 1rem;
  width: 100%;
  height: 100%;
  display: flex;
  justify-content: center;
  align-items: center;
  border-radius: 2rem;
}
.days .day div:hover:not(.date-disabled) {
  background: rgba(0, 0, 0, 0.1);
}

.days .day div:focus:not(.date-disabled) {
  background: var(--bs-primary);
  color: var(--bs-white);
}

.prev-date,
.next-date {
  opacity: 0.5;
}

.today {
  border: 1px solid var(--bs-primary);
  color: var(--bs-primary);
  box-shadow: 0 0 0.3rem rgba(0, 0, 0, 0.5);
  font-weight: 800;
}

.day-active {
  background: var(--bs-primary);
  color: var(--bs-white);
}
.day-with-data {
  text-decoration: underline;
  font-weight: 600;
}
.day-with-data:not(.day-active.day-with-data, .today) {
  color: var(--bs-warning);
}
.months div {
  font-size: 1rem;
  font-weight: 700;
  width: calc(100% / 3);
  height: 2.1rem;
  display: flex;
  justify-content: center;
  align-items: center;
  transition: background-color 0.3s;
  border-radius: 0.4rem;
}

.months div:hover:not(.date-disabled),
.months div:focus:not(.date-disabled) {
  background-color: rgba(0, 0, 0, 0.08);
}

.month-active {
  background-color: var(--bs-primary);
  color: var(--bs-white);
}

.date-disabled {
  opacity: 0.5;
}

.years {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  overflow: auto;
}

.years::-webkit-scrollbar,
.container-sidebar::-webkit-scrollbar {
  width: 1px;
  height: 8px;
}

.years div {
  display: block;
  margin: auto;
  width: 100%;
  height: 40px;
  line-height: 40px;
  text-align: center;
  align-items: center;
  font-size: 1.1rem;
  opacity: 0.5;
  transition: 0.2s;
}

.years div:hover,
.years div:focus {
  font-size: 1.5rem;
  background: rgba(0, 0, 0, 0.05);
  opacity: 1;
}

div.year-active {
  font-size: 1.8rem;
  font-weight: 700;
  color: var(--bs-primary);
  text-shadow: 0 0.09rem 0.13rem rgb(0 0 0 / 50%);
  opacity: 1;
}

.s26-slider {
  display: inline-flex;
  width: 100%;
}

.active-s26-slider_to {
  width: 200%;
  animation: slider_to 0.25s infinite linear;
}

.active-s26-slider_from {
  width: 200%;
  animation: slider_from 0.25s infinite linear;
}

@keyframes slider_to {
  to {
    transform: translateX(-50%);
  }
}

@keyframes slider_from {
  from {
    transform: translateX(-50%);
  }
}
</style>