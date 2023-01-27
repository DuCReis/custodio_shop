<?php
require_once('libs_loja.php');
$conn = connectDB();
deleteArtigoById($conn, $_GET['ref']);
header('location: listar.php');
?>