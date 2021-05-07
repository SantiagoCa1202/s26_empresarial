<div>
  <nav class="s26-navbar" v-bind:style="showBg">
    <ul class="menu" v-bind:style="showMenu">
      <!-- Titulo -->
      <li class="title-menu">
        <button class="btn-hide-menu" v-on:click="menu(false)">
          <i class="fas fa-angle-double-left"></i>
        </button>
        S26 Empresarial
      </li>
      <!-- CATEGORIAS -->
      <li class="item-submenu" v-on:click="showSubMenu(1)">
        <a href="" v-on:click.prevent v-bind:class="[{ 'focus-menu': activeSubMenu == 1 }]">
          <i class="fas fa-home menu-icon"></i>
          Inicio
          <i class="fas fa-chevron-right icon-arrow"></i>
        </a>
        <ul v-bind:class="[{ activeSubMenu: activeSubMenu == 1 }, 'submenu']">
          <li>
            <a href="sales" v-bind:class="focusSubMenu('sales')">Ventas</a>
          </li>
          <li>
            <a href="credits" v-bind:class="focusSubMenu('credits')">
              Creditos
            </a>
          </li>
          <li>
            <a href="returns" v-bind:class="focusSubMenu('returns')">
              Devoluciones
            </a>
          </li>
          <li>
            <a href="external_income" v-bind:class="focusSubMenu('external_income')">
              Ingresos Externos
            </a>
          </li>
          <li>
            <a href="calculator" v-bind:class="focusSubMenu('calculator')">
              Calculadora
            </a>
          </li>
          <li>
            <a href="close_box" v-bind:class="focusSubMenu('close_box')">
              Cierre de Caja
            </a>
          </li>
        </ul>
      </li>
      <li>
        <a href="new_sale" v-bind:class="focusSubMenu('new_sale')">
          <i class="fas fa-shopping-bag menu-icon"></i>
          Facturación
        </a>
      </li>
      <li class="item-submenu" v-on:click="showSubMenu(2)">
        <a href="" v-on:click.prevent v-bind:class="[{ 'focus-menu': activeSubMenu == 2 }]">
          <i class="fas fa-box-open menu-icon"></i>
          Productos
          <i class="fas fa-chevron-right icon-arrow"></i>
        </a>
        <ul v-bind:class="[{ activeSubMenu: activeSubMenu == 2 }, 'submenu']">
          <li>
            <a href="products" v-bind:class="focusSubMenu('products')">
              Productos
            </a>
          </li>
          <li>
            <a href="products_entry" v-bind:class="focusSubMenu('products_entry')">
              Entradas
            </a>
          </li>
          <li>
            <a href="products_outlet" v-bind:class="focusSubMenu('products_outlet')">
              Salidas
            </a>
          </li>
          <li>
            <a href="products_returned" v-bind:class="focusSubMenu('products_returned')">
              Devoluciones
            </a>
          </li>
          <li>
            <a href="products_damaged" v-bind:class="focusSubMenu('products_damaged')">
              Averiado
            </a>
          </li>
          <li>
            <a href="stocktaking" v-bind:class="focusSubMenu('stocktaking')">
              Control de Inventario
            </a>
          </li>
          <li>
            <a href="stock_information" v-bind:class="focusSubMenu('stock_information')">
              Información de Stock
            </a>
          </li>
          <li>
            <a href="suppliers_orders" v-bind:class="focusSubMenu('suppliers_orders')">
              Pedidos Proveedores
            </a>
          </li>
          <li>
            <a href="customer_orders" v-bind:class="focusSubMenu('customer_orders')">
              Pedidos Clientes
            </a>
          </li>
        </ul>
      </li>
      <li>
        <a href="transactions" v-bind:class="focusSubMenu('transactions')">
          <i class="fas fa-chart-bar menu-icon"></i>
          Transacciones
        </a>
      </li>
      <li class="item-submenu" v-on:click="showSubMenu(3)">
        <a href="" v-on:click.prevent v-bind:class="[{ 'focus-menu': activeSubMenu == 3 }]">
          <i class="fas fa-file-invoice-dollar menu-icon"></i>
          Comprobantes
          <i class="fas fa-chevron-right icon-arrow"></i>
        </a>
        <ul v-bind:class="[{ activeSubMenu: activeSubMenu == 3 }, 'submenu']">
          <li>
            <a href="purchases_to_providers" v-bind:class="focusSubMenu('purchases_to_providers')">
              Compras
            </a>
          </li>
          <li>
            <a href="credit_notes" v-bind:class="focusSubMenu('credit_notes')">
              Notas de Credito
            </a>
          </li>
          <li>
            <a href="withholdings" v-bind:class="focusSubMenu('withholdings')">
              Retenciones
            </a>
          </li>
        </ul>
      </li>
      <li>
        <a href="customers" v-bind:class="focusSubMenu('customers')">
          <i class="fas fa-users menu-icon"></i>
          Clientes
        </a>
      </li>
      <li class="item-submenu" v-on:click="showSubMenu(4)">
        <a href="" v-on:click.prevent v-bind:class="[{ 'focus-menu': activeSubMenu == 4 }]">
          <i class="fas fa-wallet menu-icon"></i>
          Wallet
          <i class="fas fa-chevron-right icon-arrow"></i>
        </a>
        <ul v-bind:class="[{ activeSubMenu: activeSubMenu == 4 }, 'submenu']">
          <li>
            <a href="wallet" v-bind:class="focusSubMenu('wallet')">
              Wallet
            </a>
          </li>
          <li>
            <a href="estadisticas" v-bind:class="focusSubMenu('estadisticas')">
              Estadísticas
            </a>
          </li>
          <li>
            <a href="bank_entity" v-bind:class="focusSubMenu('bank_entity')">
              Info. Entidades Bancarias
            </a>
          </li>
        </ul>
      </li>
      <li class="item-submenu" v-on:click="showSubMenu(5)">
        <a href="" v-on:click.prevent v-bind:class="[{ 'focus-menu': activeSubMenu == 5 }]">
          <i class="fas fa-receipt menu-icon"></i>
          Cuentas
          <i class="fas fa-chevron-right icon-arrow"></i>
        </a>
        <ul v-bind:class="[{ activeSubMenu: activeSubMenu == 5 }, 'submenu']">
          <li>
            <a href="accounts_to_pay" v-bind:class="focusSubMenu('accounts_to_pay')">
              Ctas. Por Pagar
            </a>
          </li>
          <li>
            <a href="accounts_to_receivable" v-bind:class="focusSubMenu('accounts_to_receivable')">
              Ctas. Por Cobrar
            </a>
          </li>
          <li>
            <a href="accounts_info" v-bind:class="focusSubMenu('accounts_info')">
              Info. de Cuentas
            </a>
          </li>
          <li>
            <a href="checks" v-bind:class="focusSubMenu('checks')">
              Cheques
            </a>
          </li>
          <li>
            <a href="vouchers" v-bind:class="focusSubMenu('vouchers')">
              Vouchers
            </a>
          </li>
        </ul>
      </li>
      <li>
        <a href="tax_returns">
          <i class="fas fa-landmark menu-icon"></i>
          Declaraciones
        </a>
      </li>
      <li class="item-submenu" v-on:click="showSubMenu(6)">
        <a href="" v-on:click.prevent v-bind:class="[{ 'focus-menu': activeSubMenu == 6 }]">
          <i class="fas fa-store menu-icon"></i>
          Mi Negocio
          <i class="fas fa-chevron-right icon-arrow"></i>
        </a>
        <ul v-bind:class="[{ activeSubMenu: activeSubMenu == 6 }, 'submenu']">
          <li>
            <a href="provider" v-bind:class="focusSubMenu('provider')">
              Proveedores
            </a>
          </li>
          <li>
            <a href="debts" v-bind:class="focusSubMenu('debts')"> Deudas </a>
          </li>
          <li>
            <a href="images" v-bind:class="focusSubMenu('images')">
              Imagenes
            </a>
          </li>
          <li>
            <a href="info_vouchers" v-bind:class="focusSubMenu('info_vouchers')">
              Info. de Comprobantes
            </a>
          </li>
          <li>
            <a href="establishments" v-bind:class="focusSubMenu('establishments')">
              Establecimientos
            </a>
          </li>
          <li>
            <a href="general_config" v-bind:class="focusSubMenu('general_config')">
              Configuración General
            </a>
          </li>
        </ul>
      </li>
      <li class="item-submenu" v-on:click="showSubMenu(7)">
        <a v-on:click.prevent v-bind:class="[{ 'focus-menu': activeSubMenu == 7 }]">
          <i class="fas fa-users menu-icon"></i>
          Usuarios
          <i class="fas fa-chevron-right icon-arrow"></i>
        </a>
        <ul v-bind:class="[{ activeSubMenu: activeSubMenu == 7 }, 'submenu']">
          <li>
            <a href="users" v-bind:class="focusSubMenu('users')" @click="location">
              Usuarios
            </a>
          </li>
          <li>
            <a href="roles" v-bind:class="focusSubMenu('roles')" @click="location">
              Roles
            </a>
          </li>
        </ul>
      </li>
    </ul>
  </nav>
</div>