<header id="s26-header" class="header-menu">
  <div class="s26-navbar-top">
    <div class="navbar-top-left s26-navbar-i">
      <button class="btn-icon text-white" v-on:click="menu(true)">
        <s26-icon icon="bars"></s26-icon>
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
        <a class="btn-icon text-white" href="<?= BASE_URL ?>/dashboard">
          <s26-icon icon="home"></s26-icon>
        </a>
      <?php } ?>
    </div>
    <div class="navbar-top-right s26-navbar-i">
      <div class="text-capitalize">
        <a href="<?= BASE_URL ?>/users/myaccount" class=" btn-icon-auto">
          <?= $_SESSION['userData']['short_name'] ?>
        </a>
      </div>
      <div>|</div>
      <div class="text-capitalize">
        <a href="<?= BASE_URL ?>/calendar" class=" btn-icon-auto"> {{ date }} </a>
      </div>
      <button class="btn-icon text-white">
        <s26-icon icon="bell"></s26-icon>
      </button>
      <div class="dropdown-options-user">
        <a @click.prevent="options_user = !options_user" class="btn-icon text-white">
          <s26-icon icon="user"></s26-icon>
        </a>
        <transition name="slide-fade">
          <div class="content-options-user" v-if="options_user">
            <div class="content-header">
              <div class="icon-user">
                <s26-icon icon="user"></s26-icon>
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
                    <a href="<?= BASE_URL ?>/users/myaccount">
                      <s26-icon icon="user-cog"></s26-icon>
                      <div class="lbl-li-user">Gestionar cuenta</div>
                    </a>
                  </li>
                  <li>
                    <a @click="on_dark_mode">
                      <s26-icon icon="moon"></s26-icon>
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
                      <s26-icon icon="cog"></s26-icon>
                      <div class="lbl-li-user">Configuración</div>
                    </a>
                  </li>
                  <li>
                    <a href="<?= BASE_URL ?>/logout">
                      <s26-icon icon="sign-out-alt"></s26-icon>
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