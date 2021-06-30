<template>
  <div :id="'s26-date-picker-' + id" class="s26-date-picker s26-popup">
    <div class="mb-3 w-100">
      <label for="id" class="form-label" v-if="label !== '' && label">
        {{ label }}
        <span class="text-danger" v-if="s26_required">
          <s26-icon icon="asterisk" class="icon_asterisk_required"></s26-icon>
        </span>
      </label>
      <div
        :id="id"
        class="form-control form-control-sm"
        tabindex="0"
        @click="active_date_picker"
        @keyup.enter="active_date_picker"
      >
        {{ value != "" ? value_date : "" }}
        <span
          v-if="date_range.length > 0 && value !== ''"
          @click="on_select_date(null)"
        >
          <s26-icon icon="times"></s26-icon>
        </span>
      </div>
      <input
        type="hidden"
        v-model="value"
        :s26-required="s26_required"
        date="true"
      />
      <p class="invalid-feedback" v-if="s26_required"></p>
    </div>
    <transition name="fade">
      <div
        :class="[
          'popup-date-picker',
          size ? 'popup-date-picker-' + size : '',
          'popup-date-picker-' + popup,
        ]"
        :style="position"
        v-if="isActive"
        tabindex="0"
        @keyup.alt.33="prevMonth"
        @keyup.alt.34="nextMonth"
        @keyup.esc="select_date(null)"
      >
        <transition name="fade" mode="out-in">
          <div class="s26-date-picker-header" v-if="showDate !== 'years'">
            <div class="s26-date-picker-header__prev">
              <button type="button" class="btn-piker" @click="prevMonth">
                <s26-icon icon="angle-left"></s26-icon>
              </button>
            </div>
            <div class="s26-date-picker-header__value">
              <div class="s26-slider">
                <button
                  type="button"
                  ref="btnCurrentDate"
                  @click="funcShowDate"
                >
                  {{ currentDate }}
                </button>
                <button type="button" @click="funcShowDate" v-if="slider">
                  {{ currentDate }}
                </button>
              </div>
            </div>
            <div class="s26-date-picker-header__next">
              <button type="button" class="btn-piker" @click="nextMonth">
                <s26-icon icon="angle-right"></s26-icon>
              </button>
            </div>
          </div>
        </transition>
        <transition name="fade" mode="out-in">
          <div class="s26-date-picker-weekdays" v-if="showDate == 'days'">
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
                  class="prev-date"
                  v-for="(day, index) in days.prevDate"
                  :key="index + 'prev'"
                  :date="day.fullDate"
                  tabindex="0"
                  @click="on_select_date(day.fullDate)"
                  @keyup.107="on_select_date(day.fullDate)"
                >
                  {{ day.day }}
                </div>
                <div
                  :class="[
                    today.day == day.fullDate ? 'today' : '',
                    disabled_date(day.fullDate) ? '' : 'date-disabled',
                  ]"
                  v-for="(day, index) in days.currentDate"
                  :key="index + 'curr'"
                  :date="day.fullDate"
                  tabindex="0"
                  @click="on_select_date(day.fullDate)"
                  @keyup.107="on_select_date(day.fullDate)"
                >
                  {{ day.day }}
                </div>
                <div
                  :class="[
                    'next-date',
                    disabled_date(day.fullDate) ? '' : 'date-disabled',
                  ]"
                  v-for="(day, index) in days.nextDate"
                  :key="index + 'next'"
                  :date="day.fullDate"
                  tabindex="0"
                  @click="on_select_date(day.fullDate)"
                  @keyup.107="on_select_date(day.fullDate)"
                >
                  {{ day.day }}
                </div>
              </div>
              <div class="days" v-if="slider">
                <div
                  class="prev-date"
                  v-for="(day, index) in days.prevDate"
                  :key="index + 'prev'"
                >
                  {{ day.day }}
                </div>
                <div
                  v-for="(day, index) in days.currentDate"
                  :key="index + 'curr'"
                >
                  {{ day.day }}
                </div>
                <div
                  class="next-date"
                  v-for="(day, index) in days.nextDate"
                  :key="index + 'next'"
                >
                  {{ day.day }}
                </div>
              </div>
            </div>
          </div>
        </transition>
        <transition name="fade" mode="out-in">
          <div class="s26-date-picker-months" v-if="showDate == 'months'">
            <div class="s26-slider">
              <div class="months">
                <div
                  :class="[
                    today.month == year + '-' + month.intMonth
                      ? 'month-active'
                      : '',
                    disabled_date(year + '-' + month.intMonth + '-01')
                      ? ''
                      : 'date-disabled',
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
          <div class="s26-date-picker-years" v-if="showDate == 'years'">
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
    </transition>
  </div>
</template>
<script>
export default {
  props: {
    id: String,
    enable: String,
    size: String,
    label: String,
    s26_required: Boolean,
    message: {
      type: String,
      default: "",
    },
    select_all_dates: Boolean,
    value: {},
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
      value_date: "",
      showDate: "days",
      isActive: false,
      slider: false,
      calendar_mode: "unico",
      position: {
        top: "0",
      },
      popup: "bottom",
    };
  },
  mounted() {
    for (let y = new Date().getFullYear(); y >= 1900; y--) {
      this.years.push(y);
    }
    this.today.day = `${new Date().getFullYear()}-${this.intPadStart(
      new Date().getMonth() + 1
    )}-${this.intPadStart(new Date().getDate())}`;
    this.today.month = `${new Date().getFullYear()}-${this.intPadStart(
      new Date().getMonth() + 1
    )}`;
    this.today.year = `${new Date().getFullYear()}`;
    this.renderCalendar();
  },
  created() {
    setTimeout(() => {
      this.on_select_date(this.value[0]);
      $(
        `html, .s26-modal, .s26-modal-content, .s26-popup:not(#s26-date-picker-${this.id})`
      ).on("click", (e) => {
        this.isActive = false;
      });
      $(`#s26-date-picker-${this.id}`).click(function (e) {
        e.stopPropagation();
      });
    }, 100);
  },
  methods: {
    renderCalendar() {
      this.date.setDate(1);

      this.month = this.date.getMonth();
      this.year = this.date.getFullYear();
      const lastDay = new Date(this.year, this.month + 1, 0).getDate();

      const prevLastDay = new Date(this.year, this.month, 0).getDate();

      const firstDayIndex = this.date.getDay();

      const lastDayIndex = new Date(this.year, this.month + 1, 0).getDay();

      const nextDays = 7 - lastDayIndex - 1;

      this.days.prevDate = [];
      this.days.currentDate = [];
      this.days.nextDate = [];

      for (let x = firstDayIndex; x > 0; x--) {
        this.days.prevDate.push({
          day: prevLastDay - x + 1,
          fullDate: `${this.year}-${this.intPadStart(
            this.month
          )}-${this.intPadStart(prevLastDay - x + 1)}`,
        });
      }
      for (let i = 1; i <= lastDay; i++) {
        this.days.currentDate.push({
          day: i,
          fullDate: `${this.year}-${this.intPadStart(
            this.month + 1
          )}-${this.intPadStart(i)}`,
        });
      }

      for (let j = 1; j <= nextDays; j++) {
        this.days.nextDate.push({
          day: j,
          fullDate: `${this.year}-${this.intPadStart(
            this.month + 2
          )}-${this.intPadStart(j)}`,
        });
      }

      this.currentDate =
        this.showDate == "days"
          ? `${this.months[this.month].strMonth} ${this.year}`
          : (this.currentDate = this.year);
      this.select_date();
    },

    prevMonth(e) {
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
        setTimeout(() => {
          this.disabled_date(`${this.today.month}-1`);
        }, 100);
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
      setTimeout(() => {
        this.$refs.btnCurrentDate.focus();
      }, 250);
      this.date.setFullYear(year);
      this.renderCalendar();
    },

    select_date(n = "") {
      $(`.days div`).removeClass("day-active");
      if (n == null) {
        this.date_range = [];
      } else if (this.enable == "unique") {
        if (!this.date_range.includes(n) && n != "") {
          this.date_range.splice(0, 1);
          this.date_range.push(n);
        }
        setTimeout(() => {
          $(`.days div[date='${this.date_range[0]}']`).addClass("day-active");
        }, 250);
      } else if (this.enable == "range") {
        if (this.date_range.length == 2 && n != "") {
          this.date_range = [];
        }
        if (n != "") this.date_range.push(n);

        setTimeout(() => {
          $(`.days div[date='${this.date_range[0]}']`).addClass("day-active");
          if (this.date_range.length == 2) {
            let from = new Date(this.date_range[0]);
            let to = new Date(this.date_range[1]);

            while (to.getTime() >= from.getTime()) {
              from.setDate(from.getDate() + 1);

              $(
                `.days div[date='${from.getFullYear()}-${this.intPadStart(
                  from.getMonth() + 1
                )}-${this.intPadStart(from.getDate())}']`
              ).addClass("day-active");
            }
            while (to.getTime() < from.getTime()) {
              to.setDate(to.getDate() + 1);

              $(
                `.days div[date='${to.getFullYear()}-${this.intPadStart(
                  to.getMonth() + 1
                )}-${this.intPadStart(to.getDate())}']`
              ).addClass("day-active");
            }
          }
        }, 100);
      } else if (this.enable == "multiple") {
        if (!this.date_range.includes(n)) {
          if (n != "") this.date_range.push(n);
        } else {
          let supr = this.date_range.indexOf(n);
          this.date_range.splice(supr, 1);
        }

        setTimeout(() => {
          for (let i = 0; i < this.date_range.length; i++) {
            $(`.days div[date='${this.date_range[i]}']`).addClass("day-active");
          }
        }, 50);
      }
      if (n !== "") {
        this.$emit("input", this.date_range);
        this.$emit("change");
      }
    },
    disabled_date(data) {
      if (!this.calendar && !this.select_all_dates) {
        let currentDate = new Date(this.today.day);
        let date = new Date(data);
        if (date.getTime() <= currentDate.getTime()) {
          return true;
        } else {
          return false;
        }
      } else {
        return true;
      }
    },
    intPadStart(n) {
      if (n < 10) {
        return `0${n}`;
      } else {
        return n;
      }
    },
    active_date_picker() {
      this.isActive = this.isActive ? false : true;
      this.renderCalendar();
      let s26DatePicker = document.getElementById(this.id);
      if (s26DatePicker.getBoundingClientRect().bottom >= 350) {
        this.position.top = "-300px";
        this.popup = "top";
      } else {
        this.position.top = 0;
        this.popup = "bottom";
      }
    },
    val_date(n) {
      if (this.date_range.length == 1) {
        let date_one = new Date(this.date_range[0]);
        date_one.setDate(date_one.getDate() + 1);

        this.value_date =
          this.enable == "unique"
            ? `${date_one.toLocaleDateString()}`
            : `${date_one.toLocaleDateString()}  ~ `;
      } else if (this.date_range.length == 2) {
        let date_two = new Date(this.date_range[1]);
        date_two.setDate(date_two.getDate() + 1);
        this.value_date = `${
          this.value_date
        } ${date_two.toLocaleDateString()}  `;
      } else if (n == null) {
        this.value_date = "";
      }
    },
    on_select_date(n) {
      if (!this.disabled_date(n) && n !== "") {
        return false;
      } else {
        this.select_date(n);
        this.val_date(n);
      }
    },
  },
};
</script>