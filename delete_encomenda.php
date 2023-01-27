<?php
require_once('libs_loja.php');
$conn = connectDB();
deleteEncomendaById($conn, $_GET['ref']);
header('location: backencomenda.php');
?>