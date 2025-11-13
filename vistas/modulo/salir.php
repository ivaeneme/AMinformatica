<?php
session_destroy();
echo "
<script>
  fncSweetAlert('success', 'Sesión cerrada correctamente.');
  setTimeout(() => {
    window.location.href = '{$url}index.php';
  }, 1800);
</script>
";
