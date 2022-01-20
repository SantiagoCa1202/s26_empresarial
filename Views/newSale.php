<?= head_(); ?>
<?= header_(); ?>

<div id="s26-newSale-view" class="container-fluid s26-container" tabindex="0">
  <?php
  if (empty($_SESSION['permitsModule']['w'])) {
  ?>
    <p>Acceso Restringido</p>
  <?php } else { ?>
    <s26-newsale-default>
    </s26-newsale-default>
  <?php
  }
  ?>
</div>
<?= footer_(); ?>