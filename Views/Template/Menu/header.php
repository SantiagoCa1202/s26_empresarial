<header id="s26-header" class="header-menu">
  <div class="s26-navbar-top">
    <div class="navbar-top-left">
      <button class="btn-icon" v-on:click="menu(true)">
        <i class="fas fa-bars"></i>
      </button>
      <a class="btn-s26-dash"> S26 Empresarial </a>
      <div>|</div>
      <div class="text-capitalize">
        <?= $_SESSION['userData']['establishment']['tradename'] ?>
        -
        <?= str_pad(
          $_SESSION['userData']['establishment']['n_establishment'],
          3,
          "0",
          STR_PAD_LEFT
        )
        ?>
      </div>
    </div>
    <div class="navbar-top-center">
      <a class="btn-icon" href="dashboard">
        <i class="fas fa-home"></i>
      </a>
    </div>
    <div class="navbar-top-right">
      <div class="text-capitalize">
        <?= $_SESSION['userData']['short_name'] ?>
        -
        <?= $_SESSION['userData']['role']['id'] ?>
      </div>
      <div>|</div>
      <div class="text-capitalize"> {{ date }} </div>
      <button class="btn-icon">
        <i class="fas fa-bell"></i>
      </button>
      <a href="<?= BASE_URL ?> /logout" class="btn-icon">
        <i class="fas fa-power-off"></i>
      </a>
    </div>
    <?php require_once('nav.php') ?>
  </div>
</header>
<?= data_style('header'); ?>