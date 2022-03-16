<?= head_(); ?>
<?= header_(); ?>

<div id="s26-newSale-view" class="container-fluid s26-container" tabindex="0">
  <?php
  if ($_SESSION['permitsModule']['w'] != 1) {
  ?>
    <s26-warnings title="¡Lo Sentimos!<br />No Tienes Acceso A Este Modulo." sub_title="Comunicate con tu supervisor para mas información." icon="exclamation-triangle" variant_icon="text-danger">
    </s26-warnings>
  <?php } else if ($_SESSION['userData']['box']['status'] == 1) { ?>
    <s26-newsale-default>
    </s26-newsale-default>
  <?php
  } else {
  ?>

    <s26-warnings title="¡Lo Sentimos!<br />La Caja Se Encuentra Deshabilitada." sub_title="Comunicate con tu supervisor para mas información." icon="broadcast-tower">
    </s26-warnings>
  <?php
  }
  ?>
</div>
<?= footer_(); ?>