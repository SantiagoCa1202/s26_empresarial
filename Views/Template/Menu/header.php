<header id="s26-header" class="header-menu">
  <div class="s26-navbar-top">
    <div class="navbar-top-left s26-navbar-i">
      <button class="btn-icon" v-on:click="menu(true)">
        <i class="fas fa-bars"></i>
      </button>
      <a class="btn-s26-dash btn-icon-auto"> S26 Empresarial </a>
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
    <div class="navbar-top-center s26-navbar-i">
      <?php if (!empty($_SESSION['permits'][1]['r'])) { ?>
        <a class="btn-icon" href="dashboard">
          <i class="fas fa-home"></i>
        </a>
      <?php } ?>
    </div>
    <div class="navbar-top-right s26-navbar-i">
      <div class="text-capitalize">
        <a href="<?= BASE_URL ?>/my_profile" class=" btn-icon-auto">
          <?= $_SESSION['userData']['short_name'] ?>
        </a>
      </div>
      <div>|</div>
      <div class="text-capitalize">
        <a href="<?= BASE_URL ?>/calendar" class=" btn-icon-auto"> {{ date }} </a>
      </div>
      <button class="btn-icon">
        <i class="fas fa-bell"></i>
      </button>
      <div class="dropdown-options-user">
        <a href="" @click.prevent="options_user = !options_user" class="btn-icon">
          <i class="fas fa-user"></i>
        </a>
        <transition name="slide-fade">
          <div class="content-options-user" v-if="options_user">
            <div class="content-header">
              <div class="icon-user">
                <i class="fas fa-user"></i>
              </div>
              <div class="tag-user">
                <div class="tag-user-name">
                  <?= $_SESSION['userData']['short_name'] ?>
                </div>
                <div class="tag-user-role">
                  <?= $_SESSION['userData']['role']['name'] ?>
                </div>
              </div>
            </div>
            <div class="content-body">
              <nav class="s26-navbar-user">
                <ul class="menu-user">
                  <li>
                    <a href="<?= BASE_URL ?>/my_profile">
                      <i class="fas fa-user-cog"></i>
                      <div class="lbl-li-user">Perfil</div>
                    </a>
                  </li>
                  <li>
                    <a @click="on_dark_mode">
                      <i class="fas fa-moon"></i>
                      <div class="lbl-li-user container-dark-mode">
                        <div class="lbl-dark-mode">
                          Modo oscuro
                        </div>
                        <div class="s26-checkbox-switch">
                          <input type="checkbox" v-model="dark_mode" />
                          <label></label>
                        </div>
                      </div>
                    </a>
                  </li>
                  <li>
                    <a href="<?= BASE_URL ?>/settings">
                      <i class="fas fa-cog"></i>
                      <div class="lbl-li-user">Configuración</div>
                    </a>
                  </li>
                  <li>
                    <a href="<?= BASE_URL ?>/logout">
                      <i class="fas fa-sign-out-alt"></i>
                      <div class="lbl-li-user">Cerrar Sesión</div>
                    </a>
                  </li>
                </ul>
              </nav>
              <div>s26 - empresarial © 2021</div>
            </div>
          </div>
        </transition>
      </div>

    </div>
    <?php require_once('nav.php') ?>
  </div>
</header>
<?= data_style('header'); ?>
<style>
  .header-menu div.dropdown-options-user {
    margin: 0;
    padding: 0;
    position: relative;
  }

  .content-options-user {
    position: absolute;
    width: 280px;
    top: 80%;
    right: 10%;
    border: 1px solid #d5d5d5;
    border-radius: .5rem;
    box-shadow: 0px 2px 6px #bebebe;
    padding: .5rem .5rem;

  }

  .content-header {
    border-bottom: 1px solid #bebebe;
    height: 60px;
    display: flex;
    padding: .5rem 0;
    margin-bottom: 1rem;
  }

  .icon-user,
  .tag-user {
    height: 100%;

  }

  .content-header .tag-user div {
    height: 50%;
  }

  .content-header .icon-user {

    display: flex;
    flex-wrap: wrap;
    align-items: center;
  }

  .content-header .icon-user i {
    font-size: 1.5rem;
  }

  .tag-user .tag-user-name {
    font-size: 1.2rem;
    font-weight: 600;
  }

  .tag-user .tag-user-role {
    opacity: .7;
  }

  .s26-navbar-user .menu-user {
    padding: 0;
  }

  .s26-navbar-user ul {
    list-style: none;
  }

  .s26-navbar-user ul li {
    width: 100%;
    padding: .5rem 1rem;
    border-radius: .5rem;
    margin-bottom: .5rem;
    transition: all .3s;
  }

  .s26-navbar-user ul li,
  .s26-navbar-user ul li a {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    text-decoration: none;
  }

  .s26-navbar-user ul li a i {
    font-size: 1.2rem;
    background-color: #e4e6eb;
    border-radius: 50px;
    width: 40px;
    height: 40px;
    padding: .65rem 0;
    text-align: center;
  }

  .s26-navbar-user ul li a .lbl-li-user {
    padding: 0 .8rem;
    font-size: 1rem;
    font-weight: 600;
  }

  .s26-navbar-user ul li:hover {
    background: rgb(36 58 70 / 7%);
    cursor: pointer;
  }

  li a div.lbl-li-user.container-dark-mode {
    display: flex;
    width: 162px;
    padding-right: 0;
    margin-right: 0;
  }


  .container-dark-mode div.lbl-dark-mode {
    padding: 0;
    margin: 0;
  }

  .container-dark-mode div.s26-checkbox-switch {
    margin: 0 .9rem;
  }
</style>