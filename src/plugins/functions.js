let val_inputs = () => {
  setTimeout(() => {
    $("input[number]:not(:password)").keypress(function(e) {
      if (e.keyCode == 46) return false;
      if (isNaN(this.value + String.fromCharCode(e.charCode))) return false;
    });
    $("input[money]:not(:password)").keypress(function(e) {
      if (isNaN(this.value + String.fromCharCode(e.charCode))) return false;
    });
    $("input[text]").keypress(function(e) {
      if (
        (e.keyCode < 97 || e.keyCode > 122) &&
        e.keyCode !== 32 &&
        e.keyCode !== 96 &&
        e.keyCode !== 186 &&
        e.keyCode !== 241
      )
        return false;
    });
    $("input[email]").keypress(function(e) {
      if (
        (e.keyCode < 97 || e.keyCode > 122) &&
        (e.keyCode < 48 || e.keyCode > 57) &&
        e.keyCode !== 32 &&
        e.keyCode !== 45 &&
        e.keyCode !== 95 &&
        e.keyCode !== 96 &&
        e.keyCode !== 186 &&
        e.keyCode !== 64 &&
        e.keyCode !== 46 &&
        e.keyCode !== 241
      )
        return false;
    });

    $("input").keypress(function(e) {
      if (e.keyCode == 13) return false;
    });
  }, 100);
};
function buildFormData(formData, data, parentKey) {
  if (
    data &&
    typeof data === "object" &&
    !(data instanceof Date) &&
    !(data instanceof File)
  ) {
    Object.keys(data).forEach((key) => {
      buildFormData(
        formData,
        data[key],
        parentKey ? `${parentKey}[${key}]` : key
      );
    });
  } else {
    const value = data == null ? "" : data;

    formData.append(parentKey, value);
  }
}

let json_to_formData = (form) => {
  const formData = new FormData();
  buildFormData(formData, form);
  return formData;
};

let show_loader_points = () => {
  $("body").append(`<div class="s26-loading-points"></div>`);
  $(".s26-loading-points").append(`<div class="points-loader"></div>`);
  $(".s26-loading-points").css({
    display: "flex",
  });
  setTimeout(() => {
    $(".s26-loading-points").css({
      opacity: "1",
    });
  }, 100);
};

let hide_loader_points = () => {
  $(".s26-loading-points").css({
    opacity: "0",
  });
  setTimeout(() => {
    $(".s26-loading-points").css({
      display: "none",
    });
    $(".s26-loading-points").remove();
  }, 100);
};

let url_get = (url, params) => {
  let params_get = "";
  for (let param in params) {
    if (Array.isArray(params[param])) {
      for (let i = 0; i < params[param].length; i++) {
        params_get += `${param}[]=${params[param][i]}&`;
      }
    } else {
      params_get += `${param}=${params[param]}&`;
    }
  }
  params_get = params_get.substring(0, params_get.length - 1);
  let url_get = `${BASE_URL}${url}?${params_get}`;
  return url_get;
};

let startFromZero = (arr) => {
  let newArr = [];
  let count = 0;

  for (let i in arr) {
    newArr[count++] = arr[i];
  }

  return newArr;
};

let formatDate = (date, size = "sm") => {
  let new_date = new Date(date);
  new_date.setDate(new_date.getDate() + 1);
  if (size == "sm") {
    return new_date.toLocaleDateString();
  } else if (size == "md") {
    let options = {
      year: "numeric",
      month: "long",
      day: "numeric",
    };
    return new_date.toLocaleDateString("es-ES", options);
  } else if (size == "xl") {
    return new Intl.DateTimeFormat("es-ES", {
      dateStyle: "full",
      timeStyle: "short",
      calendar: "ecuador",
    }).format(new_date);
  }
};

let currency = function(number) {
  return new Intl.NumberFormat("es-Es", {
    minimumFractionDigits: 2,
  }).format(number);
};

let validEmail = (email) => {
  var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
  return re.test(email);
};

let val_date = (sDate) => {
  var val_date = sDate.split("-");
  var d = val_date[2];
  var m = val_date[1];
  var y = val_date[0];
  return (
    m > 0 &&
    m < 13 &&
    y > 0 &&
    y < 32768 &&
    d > 0 &&
    d <= new Date(y, m, 0).getDate()
  );
};

let val_form = (form) => {
  let invalidInput = (input, string) => {
    $(input)
      .addClass("is-invalid")
      .focus()
      .siblings("p")
      .empty()
      .append(string);
  };

  let val_date = (sDate) => {
    var val_date = sDate.split("-");
    var d = val_date[2];
    var m = val_date[1];
    var y = val_date[0];
    return (
      m > 0 &&
      m < 13 &&
      y > 0 &&
      y < 32768 &&
      d > 0 &&
      d <= new Date(y, m, 0).getDate()
    );
  };

  $(
    `#${form} [s26-required], #${form} [validate], #${form} [s26-pass-conf]`
  ).removeClass("is-invalid");
  for (
    let i = 0;
    i < $(`#${form} [s26-required], #${form} [validate]`).length;
    i++
  ) {
    let input = $(`#${form} [s26-required], #${form} [validate]`)[i];

    //REQUERIDO
    if ($(input).attr("s26-required") && input.value == "") {
      invalidInput(
        input,
        ` 
          <span class="fw-bold"> 
            ${$(input)
              .siblings("label")
              .text()} 
          </span> 
          Es obligatorio.
        `
      );
      return;
    }
    //TIPO DE DATO

    if ($(input).attr("date") && !val_date(input.value)) {
      invalidInput(input, `El formato de fecha es incorrecto.`);
      return;
    }

    if (
      $(input).attr("number") &&
      input.value != "" &&
      isNaN(input.value) &&
      ($(input).attr("validate") || $(input).attr("s26-required"))
    ) {
      invalidInput(
        input,
        ` 
            <span class="fw-bold"> 
              ${$(input)
                .siblings("label")
                .text()} 
            </span> 
            debe contener solo  
            <span class="fw-bold"> 
              Números
            </span> 
            .
          `
      );
      return;
    }

    if (
      $(input).attr("text") &&
      input.value != "" &&
      ($(input).attr("validate") || $(input).attr("s26-required"))
    ) {
      let text = "abcdefghijklmnñopqrstuvwxyzABCDEFGHIJKLMNÑOPQRSTUVWXYZ ";
      for (let s = 0; s < input.value.length; s++) {
        if (text.indexOf(input.value.charAt(s), 0) == -1) {
          invalidInput(
            input,
            ` 
                <span class="fw-bold"> 
                  ${$(input)
                    .siblings("label")
                    .text()} 
                </span> 
                debe contener solo  
                <span class="fw-bold"> 
                  Letras
                </span>.
              `
          );
          return;
        }
      }
    }

    if (
      $(input).attr("money") &&
      input.value != "" &&
      ($(input).attr("validate") || $(input).attr("s26-required"))
    ) {
      let money = /^(?!0\.00)([1-9]\d{0,7})*(\.\d\d)?$|^(?!0\.00)\d{0,1}(\.\d{0,2})$/;
      if (!money.test(input.value)) {
        invalidInput(input, `Formato de Moneda Inválido`);
        return;
      }
    }

    if (
      $(input).attr("email") &&
      input.value != "" &&
      ($(input).attr("validate") || $(input).attr("s26-required"))
    ) {
      let re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
      if (!re.test(input.value)) {
        invalidInput(
          input,
          ` 
            <span class="fw-bold"> 
              ${$(input)
                .siblings("label")
                .text()} 
            </span> 
            incorrecto.
            <span class="fw-bold s26-text-blue text-lowercase"> 
              ejemplo@s26empresarial.com
            </span> 
          `
        );
        return;
      }
    }

    if (
      $(input).attr("minlength") &&
      input.value.length < $(input).attr("minlength") &&
      input.value != "" &&
      ($(input).attr("validate") || $(input).attr("s26-required"))
    ) {
      invalidInput(
        input,
        ` 
             <span class="fw-bold"> 
              ${$(input)
                .siblings("label")
                .text()} 
            </span> 
            debe contener mínimo 
            <span class="fw-bold"> 
              ${$(input).attr("minlength")} 
            </span> 
            caracteres.
          `
      );
      return;
    }

    if (
      $(input).attr("maxlength") &&
      input.value.length > $(input).attr("maxlength") &&
      input.value != "" &&
      ($(input).attr("validate") || $(input).attr("s26-required"))
    ) {
      invalidInput(
        input,
        ` 
            <span class="fw-bold"> 
              ${$(input)
                .siblings("label")
                .text()} 
            </span> 
            debe contener máximo 
            <span class="fw-bold"> 
              ${$(input).attr("maxlength")} 
            </span> 
            caracteres.
          `
      );
      return;
    }

    if (
      $(input).attr("strictlength") &&
      input.value != "" &&
      ($(input).attr("validate") || $(input).attr("s26-required"))
    ) {
      let lengthArr = $(input)
        .attr("strictlength")
        .split(",");
      if (lengthArr.indexOf(input.value.length.toString()) == -1) {
        invalidInput(
          input,
          ` 
                <span class="fw-bold"> 
                  ${$(input)
                    .siblings("label")
                    .text()} 
                </span> 
                debe contener
                <span>
                  ${$(input).attr("strictlength")}
                </span>
                caracteres.
              `
        );
        return;
      }
    }

    if ($(input).attr("int") && parseInt(input.value) < 1) {
      invalidInput(
        input,
        ` 
          <span class="fw-bold"> 
            ${$(input)
              .siblings("label")
              .text()} 
          </span> 
          debe contener
          <span>
            ${$(input).attr("strictlength")}
          </span>
          caracteres.
        `
      );
      return;
    }
  }
  return true;
};

///////////////// CREADOR DE COOKIE
let create_cookie = (name, id, path = "") => {
  document.cookie =
    `${name}=` + encodeURIComponent(id) + `; path=/s26_empresarial/${path}`;
};
///////////////// ELIMINAR COOKIE
let delete_cookie = (name, path = "") => {
  document.cookie = `${name}=;max-age=2; path=/s26_empresarial/${path};`;
};
///////////////// LEER COOKIE
let readCookie = (name) => {
  let nameEQ = name + "=";
  let ca = document.cookie.split(";");
  for (let i = 0; i < ca.length; i++) {
    let c = ca[i];
    while (c.charAt(0) == " ") c = c.substring(1, c.length);
    if (c.indexOf(nameEQ) == 0) {
      return decodeURIComponent(c.substring(nameEQ.length, c.length));
    }
  }
  return null;
};

export default {
  val_inputs,
  val_form,
  val_date,
  json_to_formData,
  create_cookie,
  delete_cookie,
  readCookie,
  show_loader_points,
  hide_loader_points,
  url_get,
  startFromZero,
  validEmail,
  formatDate,
  currency,
};
