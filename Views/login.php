<?php head_(); ?>
<div id="s26-login">
  <!-- Container login -->
  <transition name="slide-fade">
    <div class="container-login" v-if="!reset_password">
      <h1 class="h3 mb-4 mt-3">¡Bienvenido de nuevo!</h1>
      <form @submit.prevent="onSubmit">
        <div class="mb-3">
          <label for="user" class="form-label">Usuario</label>
          <input type="text" class="form-control form-control-sm" id="user" v-model="form.user" autofocus>
        </div>
        <div class="mb-3 form-password">
          <label for="password" class="form-label">Contraseña</label>
          <input :type="!password ? 'text' : 'password'" class="form-control form-control-sm" id="password" v-model="form.password">
          <button type="button" class="btn btn-link" v-if="form.password != ''" @click="password = !password">Ver</button>
        </div>
        <div class="mb-3">
          <button type="submit" class="btn btn-success form-control">
            Iniciar Sesión
          </button>
        </div>
        <a class="btn btn-link form-control" @click.prevent="reset_password = true">
          ¿Has olvidado tu contraseña?
        </a>
      </form>
    </div>
  </transition>
  <!-- Container Reset Password -->
  <transition name="slide-fade">
    <div class="container-password-reset" v-if="reset_password">
      <h1 class="h3 mb-4 mt-3">Restablecer Contraseña</h1>
      <form @submit.prevent="onResetPassword">
        <div class="mb-4">
          <label for="email" class="form-label">Correo Electrónico</label>
          <input type="text" class="form-control form-control-sm" id="email" v-model="reset.email">
        </div>
        <div class="row">
          <div class="col-6">
            <button class="btn btn-outline-info form-control" @click.prevent="reset_password = false">
              Cancelar
            </button>
          </div>
          <div class="col-6">
            <button type="submit" class="btn btn-s26-success form-control">
              Restablecer
            </button>
          </div>
        </div>
      </form>
    </div>
  </transition>
  <transition name="fade">
    <s26-loader-spinner v-if="loading" />
  </transition>
</div>

<?php footer_(); ?>