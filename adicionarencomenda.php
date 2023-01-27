<?php session_start();

    require_once("libs_loja.php");
    $conn = connectDB();
if(auth())
{
    if(!isEmptyCarrinho() && verificaStock($conn))
    {
        $id = insertEncomenda($conn, [
            'user_id' => $_SESSION['user']['id'],
            'estado' => 'preparar',
            'total' => $_SESSION['carrinho']['total']
        ]);
    
        $stmt = mysqli_prepare($conn,'INSERT INTO artigo_encomenda (artigo_ref,encomenda_id,quantidade) VALUES (?,?,?)') 
        or exit('Error: '.mysqli_error($conn)); 
    
        $stmt2 = mysqli_prepare($conn, "UPDATE artigo SET stock=? WHERE ref=?")
        or exit('Error: '.mysqli_error($conn));
    
        for($i = 0 ; $i < $_SESSION['carrinho']['nitems']; $i++)
        {
           insertEncomendaList($conn, $stmt, [
                'artigo_ref' => $_SESSION['carrinho'][$i]['ref'],
                'encomenda_id' => $id,
                'quantidade' => $_SESSION['carrinho'][$i]['quantidade']
            ]);
    
            $quantidade = getArtigoById($conn, $_SESSION['carrinho'][$i]['ref']);
            $quantidade['stock'] -= $_SESSION['carrinho'][$i]['quantidade'];
    
            updateStock($conn, $stmt2, [
                'stock' => $quantidade['stock'],
                'artigo_ref' => $_SESSION['carrinho'][$i]['ref']
            ]);
            unset($quantidade);
        }
    
        mysqli_stmt_close($stmt);
        mysqli_stmt_close($stmt2);
        unset($_SESSION['carrinho']);
        iniCarrinho();
    }
    header("Location: checkout.php");
    closeDB($conn);
}else{
    header("Location: index.php");
}
?>