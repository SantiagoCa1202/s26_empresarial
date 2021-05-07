<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
</head>

<body>
  <div class="cont-princ-slider">
    <div class="slier-prin">
      <div class="juegos_DWG">01</div>
    </div>
  </div>
  <style>
    
.cont-princ-slider {
  height: 100px;
  overflow: hidden;
  width: 150px;
}

.slier-prin {
  display: inline-flex;
  -moz-animation: slider 5s infinite linear;
  -webkit-animation: slider 5s infinite linear;
  animation: slider .5s infinite linear;
  height: 100%;
  width: auto;
}

.juegos_DWG {
  background: #CCC;
  border: 1px solid #FFF;
  color: #FFF;
  font-family: Arial;
  font-size: 16px;
  line-height: 100px;
  height: 100%;
  flex: 0 0 auto;
  text-align: center;
  width: 150px;
}

@keyframes slider {
  to {
    -moz-transform: translateX(-50%);
    -webkit-transform: translateX(-50%);
    transform: translateX(-50%);
  }
}
  </style>
  <script>
    var slider = document.querySelector(".slier-prin");

slider.innerHTML += slider.innerHTML;
  </script>
</body>

</html>