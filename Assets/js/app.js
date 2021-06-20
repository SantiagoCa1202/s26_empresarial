// let BASE_URL = "http://localhost/s26_empresarial";
//let BASE_URL = "http://192.168.0.104/s26_empresarial";
let BASE_URL = "http://192.168.100.6/s26_empresarial";
axios.defaults.baseURL = BASE_URL;

function val_inputs() {
  setTimeout(() => {
    $("input[number]:not(:password)").keypress(function (e) {
      if (isNaN(this.value + String.fromCharCode(e.charCode))) return false;
    });
    $("input[text]").keypress(function (e) {
      if (
        (e.keyCode < 97 || e.keyCode > 122) &&
        e.keyCode !== 32 &&
        e.keyCode !== 96 &&
        e.keyCode !== 186 &&
        e.keyCode !== 241
      )
        return false;
    });
    $("input[email]").keypress(function (e) {
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
    $("input").keypress(function (e) {
      if (e.keyCode == 13) return false;
    });
  }, 100);
}
function show_loader_points() {
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
}

function hide_loader_points() {
  $(".s26-loading-points").css({
    opacity: "0",
  });
  setTimeout(() => {
    $(".s26-loading-points").css({
      display: "none",
    });
    $(".s26-loading-points").remove();
  }, 100);
}
function readCookie(name) {
  var nameEQ = name + "=";
  var ca = document.cookie.split(";");

  for (var i = 0; i < ca.length; i++) {
    var c = ca[i];
    while (c.charAt(0) == " ") c = c.substring(1, c.length);
    if (c.indexOf(nameEQ) == 0) {
      return decodeURIComponent(c.substring(nameEQ.length, c.length));
    }
  }

  return null;
}

function url_get(url, params) {
  let params_get = "";
  for (param in params) {
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
}

function json_to_formData(form) {
  let formData = new FormData();

  for (let i in form) {
    formData.append(i, form[i]);
    if (typeof form[i] === "object") {
      for (var e = 0; e < form[i].length; e++) {
        let object = form[i][e];
        formData.append("files[" + e + "]", object);
      }
    }
  }
  return formData;
}

function startFromZero(arr) {
  var newArr = [];
  var count = 0;

  for (var i in arr) {
    newArr[count++] = arr[i];
  }

  return newArr;
}

val_inputs();
loadProgressBar();
