<?php
require_once 'auth.php';

$auth = new Auth();
$auth->logout();
?>
<script>
    localStorage.removeItem('isLoggedIn');
    localStorage.removeItem('funcionarios');
    window.location.href = 'index.php';
</script>