<script type="text/javascript">
  const BASE_URL = "<?= BASE_URL ?>";
  const IP_ADRESS = "<?= !empty($_SESSION['userData']['establishment']['ip_adress']) ? $_SESSION['userData']['establishment']['ip_adress'] : '' ?>";
  const _iva = "<?= _iva ?>";
  const _iva_ = "<?= _iva_ ?>";
  const _iva__ = "<?= _iva__ ?>";
  const info_estab = <?= !empty($_SESSION['userData']['establishment']) ? json_encode($_SESSION['userData']['establishment']) : json_encode([]); ?>;
  const $permit_establishment = <?= !empty($_SESSION['userData']) ? $_SESSION['permits'][41]['r'] : 0 ?>;
  const $access_boxes = <?= !empty($_SESSION['userData']) ? $_SESSION['userData']['access_boxes'] : 0 ?>;
  const $cost_access = <?= !empty($_SESSION['userData']) ? $_SESSION['userData']['cost_access'] : 0 ?>;
</script>
<script src="<?= BASE_URL ?>/dist/bundle.js"></script>

</body>

</html>