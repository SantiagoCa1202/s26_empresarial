<div>
  <nav class="s26-navbar" :style="showBg">
    <ul class="menu" :style="showMenu">
      <!-- Titulo -->
      <li class="title-menu">
        <button class="btn-hide-menu" @click="menu(false)">
          <s26-icon icon="angle-double-left"></s26-icon>
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
          <a @click.prevent :class="[{ 'focus-menu': activeSubMenu == 1 }]">
            <s26-icon icon="home" class="menu-icon"></s26-icon>
            Inicio
            <s26-icon icon="chevron-right" class="icon-arrow"></s26-icon>
          </a>
          <ul :class="[{ activeSubMenu: activeSubMenu == 1 }, 'submenu']">
            <?php if (!empty($_SESSION['permits'][7]['r'])) { ?>
              <li>
                <a href="<?= BASE_URL ?>/sales" :class="focusSubMenu('sales')">
                  Ventas
                </a>
              </li>
            <?php } ?>
            <?php if (!empty($_SESSION['permits'][8]['r'])) { ?>
              <li>
                <a href="<?= BASE_URL ?>/salesCredits" :class="focusSubMenu('salesCredits')">
                  Créditos
                </a>
              </li>
            <?php } ?>
            <?php if (!empty($_SESSION['permits'][11]['r'])) { ?>
              <li>
                <a href="<?= BASE_URL ?>/calculator" :class="focusSubMenu('calculator')">
                  Calculadora
                </a>
              </li>
            <?php } ?>
            <?php if (!empty($_SESSION['permits'][12]['r'])) { ?>
              <li>
                <a href="<?= BASE_URL ?>/close_box" :class="focusSubMenu('close_box')">
                  Cierre de Caja
                </a>
              </li>
            <?php } ?>
          </ul>
        </li>
      <?php } ?>
      <?php if (!empty($_SESSION['permits'][13]['r'])) { ?>
        <li>
          <a href="<?= BASE_URL ?>/newSale" :class="focusSubMenu('newSale')">
            <s26-icon icon="shopping-bag" class="menu-icon"></s26-icon>
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
        !empty($_SESSION['permits'][5]['r']) ||
        !empty($_SESSION['permits'][45]['r'])
      ) {
      ?>
        <li class="item-submenu" @click="showSubMenu(2)">
          <a @click.prevent :class="[{ 'focus-menu': activeSubMenu == 2 }]">
            <s26-icon icon="box-open" class="menu-icon"></s26-icon>
            Productos
            <s26-icon icon="chevron-right" class="icon-arrow"></s26-icon>
          </a>
          <ul :class="[{ activeSubMenu: activeSubMenu == 2 }, 'submenu']">
            <?php if (!empty($_SESSION['permits'][4]['r'])) { ?>
              <li>
                <a href="<?= BASE_URL ?>/products" :class="focusSubMenu('products')">
                  Productos
                </a>
              </li>
            <?php } ?>
            <?php if (!empty($_SESSION['permits'][14]['r'])) { ?>
              <li>
                <a href="<?= BASE_URL ?>/productsEntries" :class="focusSubMenu('productsEntries')">
                  Entradas
                </a>
              </li>
            <?php } ?>
            <?php if (!empty($_SESSION['permits'][15]['r'])) { ?>
              <li>
                <a href="<?= BASE_URL ?>/productsOutlet" :class="focusSubMenu('productsOutlet')">
                  Salidas
                </a>
              </li>
            <?php } ?>
            <?php if (!empty($_SESSION['permits'][17]['r'])) { ?>
              <li>
                <a href="<?= BASE_URL ?>/productsDamageds" :class="focusSubMenu('productsDamageds')">
                  Averiado
                </a>
              </li>
            <?php } ?>
            <?php if (!empty($_SESSION['permits'][14]['r'])) { ?>
              <li>
                <a href="<?= BASE_URL ?>/productsSeries" :class="focusSubMenu('productsSeries')">
                  Series
                </a>
              </li>
            <?php } ?>
            <?php if (!empty($_SESSION['permits'][18]['r'])) { ?>
              <li>
                <a href="<?= BASE_URL ?>/stocktaking" :class="focusSubMenu('stocktaking')">
                  Control de Inventario
                </a>
              </li>
            <?php } ?>
            <?php if (!empty($_SESSION['permits'][19]['r'])) { ?>
              <li>
                <a href="<?= BASE_URL ?>/stock_information" :class="focusSubMenu('stock_information')">
                  Información de Stock
                </a>
              </li>
            <?php } ?>
            <?php if (!empty($_SESSION['permits'][6]['r'])) { ?>
              <li>
                <a href="<?= BASE_URL ?>/suppliers_orders" :class="focusSubMenu('suppliers_orders')">
                  Pedidos Proveedores
                </a>
              </li>
            <?php } ?>
            <?php if (!empty($_SESSION['permits'][5]['r'])) { ?>
              <li>
                <a href="<?= BASE_URL ?>/customer_orders" :class="focusSubMenu('customer_orders')">
                  Pedidos Clientes
                </a>
              </li>
            <?php } ?>
            <?php if (!empty($_SESSION['permits'][45]['r'])) { ?>
              <li>
                <a href="<?= BASE_URL ?>/categories" :class="focusSubMenu('categories')">
                  Categorias
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
          <a @click.prevent :class="[{ 'focus-menu': activeSubMenu == 3 }]">
            <s26-icon icon="chart-bar" class="menu-icon"></s26-icon>
            Transacciones
            <s26-icon icon="chevron-right" class="icon-arrow"></s26-icon>
          </a>
          <ul :class="[{ activeSubMenu: activeSubMenu == 3 }, 'submenu']">
            <?php if (!empty($_SESSION['permits'][20]['r'])) { ?>
              <li>
                <a href="<?= BASE_URL ?>/expenses" :class="focusSubMenu('expenses')">
                  Egresos
                </a>
              </li>
            <?php } ?>
            <?php if (!empty($_SESSION['permits'][21]['r'])) { ?>
              <li>
                <a href="<?= BASE_URL ?>/temporary_expenses" :class="focusSubMenu('temporary_expenses')">
                  Egresos Temporales
                </a>
              </li>
            <?php } ?>
            <?php if (!empty($_SESSION['permits'][22]['r'])) { ?>
              <li>
                <a href="<?= BASE_URL ?>/transfers" :class="focusSubMenu('transfers')">
                  Transferencias
                </a>
              </li>
            <?php } ?>
            <?php if (!empty($_SESSION['permits'][23]['r'])) { ?>
              <li>
                <a href="<?= BASE_URL ?>/deposits" :class="focusSubMenu('deposits')">
                  Depositos
                </a>
              </li>
            <?php } ?>
            <?php if (!empty($_SESSION['permits'][10]['r'])) { ?>
              <li>
                <a href="<?= BASE_URL ?>/external_income" :class="focusSubMenu('external_income')">
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
          <a @click.prevent :class="[{ 'focus-menu': activeSubMenu == 4 }]">
            <s26-icon icon="file-invoice-dollar" class="menu-icon"></s26-icon>
            Comprobantes
            <s26-icon icon="chevron-right" class="icon-arrow"></s26-icon>
          </a>
          <ul :class="[{ activeSubMenu: activeSubMenu == 4 }, 'submenu']">
            <?php if (!empty($_SESSION['permits'][24]['r'])) { ?>
              <li>
                <a href="<?= BASE_URL ?>/buys" :class="focusSubMenu('buys')">
                  Compras
                </a>
              </li>
            <?php } ?>
            <?php if (!empty($_SESSION['permits'][25]['r'])) { ?>
              <li>
                <a href="<?= BASE_URL ?>/creditNotes" :class="focusSubMenu('creditNotes')">
                  Notas de Crédito
                </a>
              </li>
            <?php } ?>
            <?php if (!empty($_SESSION['permits'][26]['r'])) { ?>
              <li>
                <a href="<?= BASE_URL ?>/withholdings" :class="focusSubMenu('withholdings')">
                  Retenciones
                </a>
              </li>
            <?php } ?>
          </ul>
        </li>
      <?php } ?>
      <?php if (!empty($_SESSION['permits'][3]['r'])) { ?>
        <li>
          <a href="<?= BASE_URL ?>/customers" :class="focusSubMenu('customers')">
            <s26-icon icon="users" class="menu-icon"></s26-icon>
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
          <a @click.prevent :class="[{ 'focus-menu': activeSubMenu == 5 }]">
            <s26-icon icon="wallet" class="menu-icon"></s26-icon>
            Wallet
            <s26-icon icon="chevron-right" class="icon-arrow"></s26-icon>
          </a>
          <ul :class="[{ activeSubMenu: activeSubMenu == 5 }, 'submenu']">
            <?php if (!empty($_SESSION['permits'][27]['r'])) { ?>
              <li>
                <a href="<?= BASE_URL ?>/wallet" :class="focusSubMenu('wallet')">
                  Wallet
                </a>
              </li>
            <?php } ?>
            <?php if (!empty($_SESSION['permits'][28]['r'])) { ?>
              <li>
                <a href="<?= BASE_URL ?>/estadisticas" :class="focusSubMenu('estadisticas')">
                  Estadísticas
                </a>
              </li>
            <?php } ?>
            <?php if (!empty($_SESSION['permits'][29]['r'])) { ?>
              <li>
                <a href="<?= BASE_URL ?>/bankAccounts" :class="focusSubMenu('bankAccounts')">
                  Cuentas Bancarias
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
          <a @click.prevent :class="[{ 'focus-menu': activeSubMenu == 6 }]">
            <s26-icon icon="receipt" class="menu-icon"></s26-icon>
            Cuentas
            <s26-icon icon="chevron-right" class="icon-arrow"></s26-icon>
          </a>
          <ul :class="[{ activeSubMenu: activeSubMenu == 6 }, 'submenu']">
            <?php if (!empty($_SESSION['permits'][30]['r'])) { ?>
              <li>
                <a href="<?= BASE_URL ?>/accounts_to_pay" :class="focusSubMenu('accounts_to_pay')">
                  Ctas. Por Pagar
                </a>
              </li>
            <?php } ?>
            <?php if (!empty($_SESSION['permits'][31]['r'])) { ?>
              <li>
                <a href="<?= BASE_URL ?>/accounts_to_receivable" :class="focusSubMenu('accounts_to_receivable')">
                  Ctas. Por Cobrar
                </a>
              </li>
            <?php } ?>
            <?php if (!empty($_SESSION['permits'][32]['r'])) { ?>
              <li>
                <a href="<?= BASE_URL ?>/accounts_info" :class="focusSubMenu('accounts_info')">
                  Info. de Cuentas
                </a>
              </li>
            <?php } ?>
            <?php if (!empty($_SESSION['permits'][33]['r'])) { ?>
              <li>
                <a href="<?= BASE_URL ?>/checkBooks" :class="focusSubMenu('checkBooks')">
                  Cheques
                </a>
              </li>
            <?php } ?>
            <?php if (!empty($_SESSION['permits'][34]['r'])) { ?>
              <li>
                <a href="<?= BASE_URL ?>/vouchers" :class="focusSubMenu('vouchers')">
                  Vouchers
                </a>
              </li>
            <?php } ?>
          </ul>
        </li>
      <?php } ?>
      <?php if (!empty($_SESSION['permits'][35]['r'])) { ?>
        <li>
          <a href="<?= BASE_URL ?>/tax_returns">
            <s26-icon icon="landmark" class="menu-icon"></s26-icon>
            Declaraciones
          </a>
        </li>
      <?php } ?>
      <?php
      if (
        !empty($_SESSION['permits'][36]['r']) ||
        !empty($_SESSION['permits'][37]['r']) ||
        !empty($_SESSION['permits'][46]['r']) ||
        !empty($_SESSION['permits'][47]['r']) ||
        !empty($_SESSION['permits'][40]['r']) ||
        !empty($_SESSION['permits'][41]['r']) ||
        !empty($_SESSION['permits'][38]['r']) ||
        !empty($_SESSION['permits'][39]['r']) ||
        !empty($_SESSION['permits'][42]['r'])
      ) {
      ?>
        <li class="item-submenu" @click="showSubMenu(7)">
          <a @click.prevent :class="[{ 'focus-menu': activeSubMenu == 7 }]">
            <s26-icon icon="store" class="menu-icon"></s26-icon>
            Mi Negocio
            <s26-icon icon="chevron-right" class="icon-arrow"></s26-icon>
          </a>
          <ul :class="[{ activeSubMenu: activeSubMenu == 7 }, 'submenu']">
            <?php if (!empty($_SESSION['permits'][36]['r'])) { ?>
              <li>
                <a href="<?= BASE_URL ?>/providers" :class="focusSubMenu('providers')">
                  Proveedores
                </a>
              </li>
            <?php } ?>
            <?php if (!empty($_SESSION['permits'][37]['r'])) { ?>
              <li>
                <a href="<?= BASE_URL ?>/debts" :class="focusSubMenu('debts')"> Deudas </a>
              </li>
            <?php } ?>
            <?php if (!empty($_SESSION['permits'][46]['r'])) { ?>
              <li>
                <a href="<?= BASE_URL ?>/photos" :class="focusSubMenu('photos')">
                  Fotos
                </a>
              </li>
            <?php } ?>
            <?php if (!empty($_SESSION['permits'][47]['r'])) { ?>
              <li>
                <a href="<?= BASE_URL ?>/files" :class="focusSubMenu('files')">
                  Archivos
                </a>
              </li>
            <?php } ?>
            <?php if (!empty($_SESSION['permits'][40]['r'])) { ?>
              <li>
                <a href="<?= BASE_URL ?>/documents" :class="focusSubMenu('documents')">
                  Info. de Comprobantes
                </a>
              </li>
            <?php } ?>
            <?php if (!empty($_SESSION['permits'][41]['r'])) { ?>
              <li>
                <a href="<?= BASE_URL ?>/establishments" :class="focusSubMenu('establishments')">
                  Establecimientos
                </a>
              </li>
            <?php } ?>
            <?php if (!empty($_SESSION['permits'][38]['r'])) { ?>
              <li>
                <a href="<?= BASE_URL ?>/goal_savings" :class="focusSubMenu('goal_savings')">
                  Ahorro Meta
                </a>
              </li>
            <?php } ?>
            <?php if (!empty($_SESSION['permits'][39]['r'])) { ?>
              <li>
                <a href="<?= BASE_URL ?>/catalogue" :class="focusSubMenu('catalogue')">
                  Catálogo
                </a>
              </li>
            <?php } ?>
            <?php if (!empty($_SESSION['permits'][42]['r'])) { ?>
              <li>
                <a href="<?= BASE_URL ?>/general_config" :class="focusSubMenu('general_config')">
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
        !empty($_SESSION['permits'][43]['r'])
      ) {
      ?>
        <li class="item-submenu" @click="showSubMenu(8)">
          <a @click.prevent :class="[{ 'focus-menu': activeSubMenu == 8 }]">
            <s26-icon icon="users" class="menu-icon"></s26-icon>
            Usuarios
            <s26-icon icon="chevron-right" class="icon-arrow"></s26-icon>
          </a>
          <ul :class="[{ activeSubMenu: activeSubMenu == 8 }, 'submenu']">
            <?php if (!empty($_SESSION['permits'][2]['r'])) { ?>
              <li>
                <a href="<?= BASE_URL ?>/users" :class="focusSubMenu('users')" @click="location">
                  Usuarios
                </a>
              </li>
            <?php } ?>
            <?php if (!empty($_SESSION['permits'][43]['r'])) { ?>
              <li>
                <a href="<?= BASE_URL ?>/roles" :class="focusSubMenu('roles')" @click="location">
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