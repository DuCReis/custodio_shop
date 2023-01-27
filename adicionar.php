<?php
    session_start();
    require_once('libs_loja.php');
    
    if(auth() && isset($_POST['ref']))
    {
        $conn = connectDB();

        adicionar($conn, $_POST['ref']);
    
        header("Location:viewproduct.php?id={$_POST["ref"]}");
    }else{
        header("location: index.php");
    }


?>