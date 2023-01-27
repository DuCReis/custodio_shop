<?php
    session_start();
    require_once('libs_loja.php');
    
    if(auth() && isset($_POST['ref']))
    {
        $conn = connectDB();

        adicionar($conn, $_POST['ref']);
    
        header("Location:checkout.php");
    }else{
        header("Location:index.php");
    }
?>