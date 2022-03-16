<?= head_(); ?>
<?= header_(); ?>

<div id="s26-wallet-view" class="container-fluid s26-container" tabindex="0">
  <?php
  if (empty($_SESSION['permitsModule']['w'])) {
  ?>
    <p>Acceso Restringido</p>
  <?php } else { ?>
    <s26-wallet>
    </s26-wallet>
  <?php
  }
  ?>
</div>
<?= footer_(); ?>