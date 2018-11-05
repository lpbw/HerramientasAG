<?
session_start();
unset($_SESSION);
session_destroy();
?>
<script>
document.location = 'index.php';
</script>