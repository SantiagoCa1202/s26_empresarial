<div>
  <nav class="s26-navbar" :style="showBg">
    <ul class="menu" :style="showMenu">
      <!-- Titulo -->
      <li class="title-menu">
        <button class="btn-hide-menu" @click="menu(false)">
          <i class="fas fa-angle-double-left"></i>
        </button>
        S26 Empresarial
      </li>
      <!-- CATEGORIAS -->
      <?php
      if (
        !empty($_SESSION['permits'][7]['r']) ||
        !empty($_SESSION['permits'][8]['r']) ||
        !empty($_SESSION['permits'][9]['r']) ||
        !empty($_SESSION['permits'][11]['r']) ||
        !empty($_SESSION['permits'][12]['r'])
      ) {
      ?>
        <li class="item-submenu" @click="showSubMenu(1)">
          <a href="" @click.prevent :class="[{ 'focus-menu': activeSubMenu == 1 }]">
            <i class="fas fa-home menu-icon"></i>
            Inicio
            <i class="fas fa-chevron-right icon-arrow"></i>
          </a>
          <ul :class="[{ activeSubMenu: activeSubMenu == 1 }, 'submenu']">
            <?php if (!empty($_SESSION['permits'][7]['r'])) { ?>
              <li>
                <a href="sales" :class="focusSubMenu('sales')">
                  Ventas
                </a>
              </li>
            <?php } ?>
            <?php if (!empty($_SESSION['permits'][8]['r'])) { ?>
              <li>
                <a href="credits" :class="focusSubMenu('credits')">
                  Créditos
                </a>
              </li>
            <?php } ?>
            <?php if (!empty($_SESSION['permits'][9]['r'])) { ?>
              <li>
                <a href="returns" :class="focusSubMenu('returns')">
                  Devoluciones
                </a>
              </li>
            <?php } ?>
            <?php if (!empty($_SESSION['permits'][11]['r'])) { ?>
              <li>
                <a href="calculator" :class="focusSubMenu('calculator')">
                  Calculadora
                </a>
              </li>
            <?php } ?>
            <?php if (!empty($_SESSION['permits'][12]['r'])) { ?>
              <li>
                <a href="close_box" :class="focusSubMenu('close_box')">
                  Cierre de Caja
                </a>
              </li>
            <?php } ?>
          </ul>
        </li>
      <?php } ?>
      <?php if (!empty($_SESSION['permits'][13]['r'])) { ?>
        <li>
          <a href="new_sale" :class="focusSubMenu('new_sale')">
            <i class="fas fa-shopping-bag menu-icon"></i>
            Facturación
          </a>
        </li>
      <?php } ?>
      <?php
      if (
        !empty($_SESSION['permits'][4]['r']) ||
        !empty($_SESSION['permits'][14]['r']) ||
        !empty($_SESSION['permits'][15]['r']) ||
        !empty($_SESSION['permits'][16]['r']) ||
        !empty($_SESSION['permits'][17]['r']) ||
        !empty($_SESSION['permits'][18]['r']) ||
        !empty($_SESSION['permits'][19]['r']) ||
        !empty($_SESSION['permits'][6]['r']) ||
        !empty($_SESSION['permits'][5]['r'])
      ) {
      ?>
        <li class="item-submenu" @click="showSubMenu(2)">
          <a href="" @click.prevent :class="[{ 'focus-menu': activeSubMenu == 2 }]">
            <i class="fas fa-box-open menu-icon"></i>
            Productos
            <i class="fas fa-chevron-right icon-arrow"></i>
          </a>
          <ul :class="[{ activeSubMenu: activeSubMenu == 2 }, 'submenu']">
            <?php if (!empty($_SESSION['permits'][4]['r'])) { ?>
              <li>
                <a href="products" :class="focusSubMenu('products')">
                  Productos
                </a>
              </li>
            <?php } ?>
            <?php if (!empty($_SESSION['permits'][14]['r'])) { ?>
              <li>
                <a href="products_entry" :class="focusSubMenu('products_entry')">
                  Entradas
                </a>
              </li>
            <?php } ?>
            <?php if (!empty($_SESSION['permits'][15]['r'])) { ?>
              <li>
                <a href="products_outlet" :class="focusSubMenu('products_outlet')">
                  Salidas
                </a>
              </li>
            <?php } ?>
            <?php if (!empty($_SESSION['permits'][16]['r'])) { ?>
              <li>
                <a href="products_returned" :class="focusSubMenu('products_returned')">
                  Devoluciones
                </a>
              </li>
            <?php } ?>
            <?php if (!empty($_SESSION['permits'][17]['r'])) { ?>
              <li>
                <a href="products_damaged" :class="focusSubMenu('products_damaged')">
                  Averiado
                </a>
              </li>
            <?php } ?>
            <?php if (!empty($_SESSION['permits'][18]['r'])) { ?>
              <li>
                <a href="stocktaking" :class="focusSubMenu('stocktaking')">
                  Control de Inventario
                </a>
              </li>
            <?php } ?>
            <?php if (!empty($_SESSION['permits'][19]['r'])) { ?>
              <li>
                <a href="stock_information" :class="focusSubMenu('stock_information')">
                  Información de Stock
                </a>
              </li>
            <?php } ?>
            <?php if (!empty($_SESSION['permits'][6]['r'])) { ?>
              <li>
                <a href="suppliers_orders" :class="focusSubMenu('suppliers_orders')">
                  Pedidos Proveedores
                </a>
              </li>
            <?php } ?>
            <?php if (!empty($_SESSION['permits'][5]['r'])) { ?>
              <li>
                <a href="customer_orders" :class="focusSubMenu('customer_orders')">
                  Pedidos Clientes
                </a>
              </li>
            <?php } ?>
          </ul>
        </li>
      <?php } ?>
      <?php
      if (
        !empty($_SESSION['permits'][20]['r']) ||
        !empty($_SESSION['permits'][21]['r']) ||
        !empty($_SESSION['permits'][22]['r']) ||
        !empty($_SESSION['permits'][23]['r']) ||
        !empty($_SESSION['permits'][10]['r'])
      ) {
      ?>
        <li class="item-submenu" @click="showSubMenu(3)">
          <a href="" @click.prevent :class="[{ 'focus-menu': activeSubMenu == 3 }]">
            <i class="fas fa-chart-bar menu-icon"></i>
            Transacciones
            <i class="fas fa-chevron-right icon-arrow"></i>
          </a>
          <ul :class="[{ activeSubMenu: activeSubMenu == 3 }, 'submenu']">
            <?php if (!empty($_SESSION['permits'][20]['r'])) { ?>
              <li>
                <a href="expenses" :class="focusSubMenu('expenses')">
                  Egresos
                </a>
              </li>
            <?php } ?>
            <?php if (!empty($_SESSION['permits'][21]['r'])) { ?>
              <li>
                <a href="temporary_expenses" :class="focusSubMenu('temporary_expenses')">
                  Egresos Temporales
                </a>
              </li>
            <?php } ?>
            <?php if (!empty($_SESSION['permits'][22]['r'])) { ?>
              <li>
                <a href="transfers" :class="focusSubMenu('transfers')">
                  Transferencias
                </a>
              </li>
            <?php } ?>
            <?php if (!empty($_SESSION['permits'][23]['r'])) { ?>
              <li>
                <a href="deposits" :class="focusSubMenu('deposits')">
                  Depositos
                </a>
              </li>
            <?php } ?>
            <?php if (!empty($_SESSION['permits'][10]['r'])) { ?>
              <li>
                <a href="external_income" :class="focusSubMenu('external_income')">
                  Ingresos Externos
                </a>
              </li>
            <?php } ?>
          </ul>
        </li>
      <?php } ?>
      <?php
      if (
        !empty($_SESSION['permits'][24]['r']) ||
        !empty($_SESSION['permits'][25]['r']) ||
        !empty($_SESSION['permits'][26]['r'])
      ) {
      ?>
        <li class="item-submenu" @click="showSubMenu(4)">
          <a href="" @click.prevent :class="[{ 'focus-menu': activeSubMenu == 4 }]">
            <i class="fas fa-file-invoice-dollar menu-icon"></i>
            Comprobantes
            <i class="fas fa-chevron-right icon-arrow"></i>
          </a>
          <ul :class="[{ activeSubMenu: activeSubMenu == 4 }, 'submenu']">
            <?php if (!empty($_SESSION['permits'][24]['r'])) { ?>
              <li>
                <a href="purchases_to_providers" :class="focusSubMenu('purchases_to_providers')">
                  Compras
                </a>
              </li>
            <?php } ?>
            <?php if (!empty($_SESSION['permits'][25]['r'])) { ?>
              <li>
                <a href="credit_notes" :class="focusSubMenu('credit_notes')">
                  Notas de Crédito
                </a>
              </li>
            <?php } ?>
            <?php if (!empty($_SESSION['permits'][26]['r'])) { ?>
              <li>
                <a href="withholdings" :class="focusSubMenu('withholdings')">
                  Retenciones
                </a>
              </li>
            <?php } ?>
          </ul>
        </li>
      <?php } ?>
      <?php if (!empty($_SESSION['permits'][3]['r'])) { ?>
        <li>
          <a href="customers" :class="focusSubMenu('customers')">
            <i class="fas fa-users menu-icon"></i>
            Clientes
          </a>
        </li>
      <?php } ?>
      <?php
      if (
        !empty($_SESSION['permits'][27]['r']) ||
        !empty($_SESSION['permits'][28]['r']) ||
        !empty($_SESSION['permits'][29]['r'])
      ) {
      ?>
        <li class="item-submenu" @click="showSubMenu(5)">
          <a href="" @click.prevent :class="[{ 'focus-menu': activeSubMenu == 5 }]">
            <i class="fas fa-wallet menu-icon"></i>
            Wallet
            <i class="fas fa-chevron-right icon-arrow"></i>
          </a>
          <ul :class="[{ activeSubMenu: activeSubMenu == 5 }, 'submenu']">
            <?php if (!empty($_SESSION['permits'][27]['r'])) { ?>
              <li>
                <a href="wallet" :class="focusSubMenu('wallet')">
                  Wallet
                </a>
              </li>
            <?php } ?>
            <?php if (!empty($_SESSION['permits'][28]['r'])) { ?>
              <li>
                <a href="estadisticas" :class="focusSubMenu('estadisticas')">
                  Estadísticas
                </a>
              </li>
            <?php } ?>
            <?php if (!empty($_SESSION['permits'][29]['r'])) { ?>
              <li>
                <a href="bank_entity" :class="focusSubMenu('bank_entity')">
                  Info. Entidades Bancarias
                </a>
              </li>
            <?php } ?>
          </ul>
        </li>
      <?php } ?>
      <?php
      if (
        !empty($_SESSION['permits'][30]['r']) ||
        !empty($_SESSION['permits'][31]['r']) ||
        !empty($_SESSION['permits'][32]['r']) ||
        !empty($_SESSION['permits'][33]['r']) ||
        !empty($_SESSION['permits'][34]['r'])
      ) {
      ?>
        <li class="item-submenu" @click="showSubMenu(6)">
          <a href="" @click.prevent :class="[{ 'focus-menu': activeSubMenu == 6 }]">
            <i class="fas fa-receipt menu-icon"></i>
            Cuentas
            <i class="fas fa-chevron-right icon-arrow"></i>
          </a>
          <ul :class="[{ activeSubMenu: activeSubMenu == 6 }, 'submenu']">
            <?php if (!empty($_SESSION['permits'][30]['r'])) { ?>
              <li>
                <a href="accounts_to_pay" :class="focusSubMenu('accounts_to_pay')">
                  Ctas. Por Pagar
                </a>
              </li>
            <?php } ?>
            <?php if (!empty($_SESSION['permits'][31]['r'])) { ?>
              <li>
                <a href="accounts_to_receivable" :class="focusSubMenu('accounts_to_receivable')">
                  Ctas. Por Cobrar
                </a>
              </li>
            <?php } ?>
            <?php if (!empty($_SESSION['permits'][32]['r'])) { ?>
              <li>
                <a href="accounts_info" :class="focusSubMenu('accounts_info')">
                  Info. de Cuentas
                </a>
              </li>
            <?php } ?>
            <?php if (!empty($_SESSION['permits'][33]['r'])) { ?>
              <li>
                <a href="checks" :class="focusSubMenu('checks')">
                  Cheques
                </a>
              </li>
            <?php } ?>
            <?php if (!empty($_SESSION['permits'][34]['r'])) { ?>
              <li>
                <a href="vouchers" :class="focusSubMenu('vouchers')">
                  Vouchers
                </a>
              </li>
            <?php } ?>
          </ul>
        </li>
      <?php } ?>
      <?php if (!empty($_SESSION['permits'][35]['r'])) { ?>
        <li>
          <a href="tax_returns">
            <i class="fas fa-landmark menu-icon"></i>
            Declaraciones
          </a>
        </li>
      <?php } ?>
      <?php
      if (
        !empty($_SESSION['permits'][36]['r']) ||
        !empty($_SESSION['permits'][37]['r']) ||
        !empty($_SESSION['permits'][39]['r']) ||
        !empty($_SESSION['permits'][41]['r']) ||
        !empty($_SESSION['permits'][42]['r']) ||
        !empty($_SESSION['permits'][38]['r']) ||
        !empty($_SESSION['permits'][40]['r']) ||
        !empty($_SESSION['permits'][43]['r'])
      ) {
      ?>
        <li class="item-submenu" @click="showSubMenu(7)">
          <a href="" @click.prevent :class="[{ 'focus-menu': activeSubMenu == 7 }]">
            <i class="fas fa-store menu-icon"></i>
            Mi Negocio
            <i class="fas fa-chevron-right icon-arrow"></i>
          </a>
          <ul :class="[{ activeSubMenu: activeSubMenu == 7 }, 'submenu']">
            <?php if (!empty($_SESSION['permits'][36]['r'])) { ?>
              <li>
                <a href="provider" :class="focusSubMenu('provider')">
                  Proveedores
                </a>
              </li>
            <?php } ?>
            <?php if (!empty($_SESSION['permits'][37]['r'])) { ?>
              <li>
                <a href="debts" :class="focusSubMenu('debts')"> Deudas </a>
              </li>
            <?php } ?>
            <?php if (!empty($_SESSION['permits'][39]['r'])) { ?>
              <li>
                <a href="images" :class="focusSubMenu('images')">
                  Imagenes
                </a>
              </li>
            <?php } ?>
            <?php if (!empty($_SESSION['permits'][41]['r'])) { ?>
              <li>
                <a href="info_vouchers" :class="focusSubMenu('info_vouchers')">
                  Info. de Comprobantes
                </a>
              </li>
            <?php } ?>
            <?php if (!empty($_SESSION['permits'][42]['r'])) { ?>
              <li>
                <a href="establishments" :class="focusSubMenu('establishments')">
                  Establecimientos
                </a>
              </li>
            <?php } ?>
            <?php if (!empty($_SESSION['permits'][38]['r'])) { ?>
              <li>
                <a href="goal_savings" :class="focusSubMenu('goal_savings')">
                  Ahorro Meta
                </a>
              </li>
            <?php } ?>
            <?php if (!empty($_SESSION['permits'][40]['r'])) { ?>
              <li>
                <a href="catalogue" :class="focusSubMenu('catalogue')">
                  Catálogo
                </a>
              </li>
            <?php } ?>
            <?php if (!empty($_SESSION['permits'][43]['r'])) { ?>
              <li>
                <a href="general_config" :class="focusSubMenu('general_config')">
                  Configuración General
                </a>
              </li>
            <?php } ?>
          </ul>
        </li>
      <?php } ?>
      <?php
      if (
        !empty($_SESSION['permits'][2]['r']) ||
        !empty($_SESSION['permits'][44]['r'])
      ) {
      ?>
        <li class="item-submenu" @click="showSubMenu(8)">
          <a @click.prevent :class="[{ 'focus-menu': activeSubMenu == 8 }]">
            <i class="fas fa-users menu-icon"></i>
            Usuarios
            <i class="fas fa-chevron-right icon-arrow"></i>
          </a>
          <ul :class="[{ activeSubMenu: activeSubMenu == 8 }, 'submenu']">
            <?php if (!empty($_SESSION['permits'][2]['r'])) { ?>
              <li>
                <a href="users" :class="focusSubMenu('users')" @click="location">
                  Usuarios
                </a>
              </li>
            <?php } ?>
            <?php if (!empty($_SESSION['permits'][44]['r'])) { ?>
              <li>
                <a href="roles" :class="focusSubMenu('roles')" @click="location">
                  Roles
                </a>
              </li>
            <?php } ?>
          </ul>
        </li>
      <?php } ?>
    </ul>
  </nav>
</div>