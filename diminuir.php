<?php
    session_start();

    require_once('libs_loja.php');
    if(auth() && $_POST['ref'])
    {
        $conn = connectDB();

        diminuir($conn, $_POST['ref']);
    
        header("Location:checkout.php");
    }else{
        header("Location: index.php");
    }


?>