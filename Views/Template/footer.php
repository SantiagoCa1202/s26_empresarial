<script type="text/javascript">
  const BASE_URL = "<?= BASE_URL ?>";
  const IP_ADRESS = "<?= !empty($_SESSION['userData']['establishment']['ip_adress']) ? $_SESSION['userData']['establishment']['ip_adress'] : '' ?>";
  const _iva = "<?= _iva ?>";
  const _iva_ = "<?= _iva_ ?>";
  const _iva__ = "<?= _iva__ ?>";
  const info_estab = <?= !empty($_SESSION['userData']['establishment']) ? json_encode($_SESSION['userData']['establishment']) : json_encode([]); ?>;
</script>
<script src="<?= BASE_URL ?>/dist/bundle.js"></script>

</body>

</html>