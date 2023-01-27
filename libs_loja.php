<?php

require_once('const.php');

function connectDB() {
    $conn = mysqli_connect(DBHOSTNAME,DBUSERNAME,DBPASSWORD,DBDATABASE) or exit('Error:'.mysqli_connect_error());
    mysqli_set_charset($conn,'utf8');
    return $conn;
}

function closeDB($conn) {
    return mysqli_close($conn);
}

function getAllArtigos($conn, $lazyLoading = true) {

    if($lazyLoading)
    {
        $query = 'SELECT 
        artigo.ref AS artigo_ref,
        artigo.stock AS artigo_stock,
        artigo.descricao AS artigo_descricao,
        artigo.preco AS artigo_preco,
        artigo.categoria_id AS artigo_categoria_id
        FROM artigo';
    }else{
        $query = 'SELECT 
        artigo.ref AS artigo_ref,
        artigo.stock AS artigo_stock,
        artigo.descricao AS artigo_descricao,
        artigo.preco as artigo_preco,
        artigo.categoria_id AS artigo_categoria_id,
        categoria.id AS categoria_id,
        categoria.nome AS categoria_nome
        FROM artigo INNER JOIN categoria ON artigo.categoria_id = categoria.id';
    }
    $stmt = mysqli_prepare($conn, $query) or exit('Error:'.mysqli_error($conn));
    if($lazyLoading)  
        mysqli_stmt_bind_result($stmt, $artigo_ref, $artigo_stock, $artigo_descricao, $artigo_preco, $artigo_categoria_id) or exit('Error in mysqli_stmt_bind_result');
    else 
        mysqli_stmt_bind_result($stmt, $artigo_ref, $artigo_stock, $artigo_descricao, $artigo_preco, $artigo_categoria_id, $categoria_id, $categoria_nome) or exit('Error in mysqli_stmt_bind_result');
    mysqli_stmt_execute($stmt) or exit('Error:'.mysqli_stmt_error($stmt));
    $artigos = [];
    
    while(mysqli_stmt_fetch($stmt))
    {
        $artigo = [
            'ref' => $artigo_ref,
            'stock' => $artigo_stock,
            'descricao' => $artigo_descricao,
            'preco' => $artigo_preco,
            'categoria_id' => $artigo_categoria_id
        ];
        if(!$lazyLoading)
        {
            $artigo['categoria'] = [
                'id' => $categoria_id,
                'nome' => $categoria_nome
            ];
        }
        array_push($artigos, $artigo);
    }
    mysqli_stmt_close($stmt);
    return $artigos;
}

function getArtigoById($conn, $ref){
    $query = "SELECT 
        artigo.ref AS artigo_ref,
        artigo.stock AS artigo_stock,
        artigo.descricao AS artigo_descricao,
        artigo.preco AS artigo_preco,
        artigo.categoria_id AS artigo_categoria_id
        FROM artigo WHERE ref= $ref";
    $res = mysqli_query($conn, $query) or exit('Error:'.mysqli_error($conn));
    $row = mysqli_fetch_assoc($res);
    if($row === null) return false;
    $artigo = [
      'ref' => $row['artigo_ref'],
      'stock' => $row['artigo_stock'],
      'descricao' => $row['artigo_descricao'],
      'preco' => $row['artigo_preco'],
      'categoria_id' => $row['artigo_categoria_id'],
    ];
    mysqli_free_result($res);
    return $artigo;
}

function getCategoriaById($conn, $lazyLoading = true, $categoria ) {

    if($lazyLoading)
    {
        $query = "SELECT 
        artigo.ref AS artigo_ref,
        artigo.stock AS artigo_stock,
        artigo.descricao AS artigo_descricao,
        artigo.preco AS artigo_preco,
        artigo.categoria_id AS artigo_categoria_id
        FROM artigo WHERE artigo.categoria_id=$categoria";
    }else{
        $query = "SELECT 
        artigo.ref AS artigo_ref,
        artigo.stock AS artigo_stock,
        artigo.descricao AS artigo_descricao,
        artigo.preco as artigo_preco,
        artigo.categoria_id AS artigo_categoria_id,
        categoria.id AS categoria_id,
        categoria.nome AS categoria_nome
        FROM artigo INNER JOIN categoria ON artigo.categoria_id = categoria.id WHERE artigo.categoria_id = $categoria";
    }
    $stmt = mysqli_prepare($conn, $query) or exit('Error:'.mysqli_error($conn));
    if($lazyLoading)  
        mysqli_stmt_bind_result($stmt, $artigo_ref, $artigo_stock, $artigo_descricao, $artigo_preco, $artigo_categoria_id) or exit('Error in mysqli_stmt_bind_result');
    else 
        mysqli_stmt_bind_result($stmt, $artigo_ref, $artigo_stock, $artigo_descricao, $artigo_preco, $artigo_categoria_id, $categoria_id, $categoria_nome) or exit('Error in mysqli_stmt_bind_result');
    mysqli_stmt_execute($stmt) or exit('Error:'.mysqli_stmt_error($stmt));
    $artigos = [];
    
    while(mysqli_stmt_fetch($stmt))
    {
        $artigo = [
            'ref' => $artigo_ref,
            'stock' => $artigo_stock,
            'descricao' => $artigo_descricao,
            'preco' => $artigo_preco,
            'categoria_id' => $artigo_categoria_id
        ];
        if(!$lazyLoading)
        {
            $artigo['categoria'] = [
                'id' => $categoria_id,
                'nome' => $categoria_nome
            ];
        }
        array_push($artigos, $artigo);
    }
    mysqli_stmt_close($stmt);
    return $artigos;
}

function getAllCategorias($conn) {

        $query = 'SELECT 
        id,
        nome
        FROM categoria';

    $stmt = mysqli_prepare($conn, $query) or exit('Error:'.mysqli_error($conn)); 
    mysqli_stmt_bind_result($stmt, $id, $nome) or exit('Error in mysqli_stmt_bind_result');
    mysqli_stmt_execute($stmt) or exit('Error:'.mysqli_stmt_error($stmt));
    $catego = [];
    
    while(mysqli_stmt_fetch($stmt))
    {
        $categ = [
            'id' => $id,
            'nome' => $nome
        ];
        array_push($catego, $categ);
    }
    mysqli_stmt_close($stmt);
    return $catego;
}

function getUserByEmail($conn, $email){
    $query = "SELECT 
        user.id AS id_user,
        user.nome AS nome_user,
        user.email AS email_user,
        user.password AS password_user,
        user.perm AS perm_user
        FROM user WHERE email= '$email'";
    $res = mysqli_query($conn, $query) or exit('Error:'.mysqli_error($conn));
    $row = mysqli_fetch_assoc($res);
    if($row === null) return false;
    $info = [
      'id' => $row['id_user'],
      'nome' => $row['nome_user'],
      'email' => $row['email_user'],
      'password' => $row['password_user'],
      'perm' => $row['perm_user'],
    ];
    mysqli_free_result($res);
    return $info;
}

function getAllUsers($conn){
    $query = 'SELECT 
    user.id, AS id_user,
    user.nome AS nome_user,
    user.email AS email_user,
    user.password AS password_user,
    user.perm AS perm_user
    FROM user';

    $stmt = mysqli_prepare($conn, $query) or exit('Error:'.mysqli_error($conn)); 
    mysqli_stmt_bind_result($stmt, $id_user, $nome_user, $email_user, $password_user, $perm_user) or exit('Error in mysqli_stmt_bind_result');
    mysqli_stmt_execute($stmt) or exit('Error:'.mysqli_stmt_error($stmt));
    $users = [];

    while(mysqli_stmt_fetch($stmt))
    {
        $user = [
            'id' => $id_user,
            'nome' => $nome_user,
            'email' => $email_user,
            'password_user' => $password_user,
            'perm' => $perm_user
        ];
        array_push($users, $user);
    }
    mysqli_stmt_close($stmt);
    return $users;
}

function auth(){
    return isset($_SESSION['user']);
}

function adicionar($conn, $ref){

    $artigo = getArtigoById($conn, $ref);

    for($i = 0; $i < $_SESSION['carrinho']['nitems']; $i++)
    {
        if($_SESSION['carrinho'][$i]['ref'] == $ref)
        {
            $_SESSION['carrinho'][$i]['quantidade']++;
            $_SESSION['carrinho']['total'] += $_SESSION['carrinho'][$i]['preco'];
            return true;
        }
    }

    $_SESSION['carrinho'][$_SESSION['carrinho']['nitems']] = [
        'ref' => $ref,
        'quantidade' => 1,
        'preco' => $artigo['preco'],
        'descricao' => $artigo['descricao']
    ];

    $_SESSION['carrinho']['nitems']++;
    $_SESSION['carrinho']['total'] += $artigo['preco'];

}

function diminuir($conn, $ref){

    $artigo = getArtigoById($conn, $ref);

    for($i = 0; $i < $_SESSION['carrinho']['nitems']; $i++)
    {
        if($_SESSION['carrinho'][$i]['ref'] == $ref)
        {
            if($_SESSION['carrinho'][$i]['quantidade'] > 1)
            {
                $_SESSION['carrinho'][$i]['quantidade']--;
                $_SESSION['carrinho']['total'] -= $_SESSION['carrinho'][$i]['preco'];
                return true;
            }elseif($_SESSION['carrinho'][$i]['quantidade'] == 1){
                $_SESSION['carrinho']['total'] -= $_SESSION['carrinho'][$i]['preco'];
                $_SESSION['carrinho']['nitems']--;
                array_splice($_SESSION['carrinho'], $i + 2, 1 );
                return true;
            }
        }
    }

}

function listarCarrinho(){
    $lista = [];

    for($i = 0; $i < $_SESSION['carrinho']['nitems']; $i++)
    {
        $list = [
            'ref' => $_SESSION['carrinho'][$i]['ref'],
            'quantidade' => $_SESSION['carrinho'][$i]['quantidade'],
            'preco' => $_SESSION['carrinho'][$i]['preco'],
            'descricao' => $_SESSION['carrinho'][$i]['descricao']
        ];
        array_push($lista, $list);
    }

    return $lista;
}

function iniCarrinho(){
    return $_SESSION['carrinho'] = ['total' => 0, 'nitems' => 0];
}

function isEmptyCarrinho(){
    return isset($_SESSION['carrinho']['nitems']) && $_SESSION['carrinho']['nitems'] === 0;
}

function insertEncomenda($conn, $encomenda){
    $stmt = mysqli_prepare($conn,'INSERT INTO encomenda (user_id,data_hora,estado,total) VALUES (?,now(),?,?)')
    or exit('Error: '.mysqli_error($conn));     
    mysqli_stmt_bind_param($stmt,'isi',$encomenda['user_id'],$encomenda['estado'], $encomenda['total'])
    or exit('Error in mysqli_stmt_bind_param');
    $res = mysqli_stmt_execute($stmt) or exit('Error: '.mysqli_stmt_error($stmt));
    $id = mysqli_stmt_insert_id($stmt);

    mysqli_stmt_close($stmt);

    if($res === false)
    {
        return $res;
    }else{
        return $id;
    }

}

function insertEncomendaList($conn, $stmt, $encomenda){     
    mysqli_stmt_bind_param($stmt,'iii',$encomenda['artigo_ref'],$encomenda['encomenda_id'], $encomenda['quantidade'])
    or exit('Error in mysqli_stmt_bind_param');
    $res = mysqli_stmt_execute($stmt) or exit('Error: '.mysqli_stmt_error($stmt));
    return $res;
}

function updateStock($conn, $stmt, $ref){
    mysqli_stmt_bind_param($stmt,'ii',$ref['stock'],$ref['artigo_ref'])
    or exit('Error in mysqli_stmt_bind_param');
    $res = mysqli_stmt_execute($stmt) or exit('Error: '.mysqli_stmt_error($stmt));
    return $res;
}

function verificaStock($conn){
    for($i = 0; $i < $_SESSION['carrinho']['nitems']; $i++)
    {
        $stock = getArtigoById($conn, $_SESSION['carrinho'][$i]['ref']);

        if($stock['stock'] <= 0)
        {
            return false;
        }
        unset($stock);
    }
    return true;
}

function verificaMaxStock($conn, $ref){
    $stock = getArtigoById($conn, $ref);

    for($i = 0; $i < $_SESSION['carrinho']['nitems']; $i++)
    {
        if($_SESSION['carrinho'][$i]['ref'] == $ref)
        {
            return($stock['stock'] > $_SESSION['carrinho'][$i]['quantidade']);
        }
    }

    return true;

}

function listarAllEncomendasByUser($conn, $user){
    $query = "SELECT 
    id,
    user_id,
    data_hora,
    estado,
    total
    FROM encomenda WHERE user_id=$user";

    $stmt = mysqli_prepare($conn, $query) or exit('Error:'.mysqli_error($conn)); 
    mysqli_stmt_bind_result($stmt, $id, $user_id, $data_hora, $estado, $total) or exit('Error in mysqli_stmt_bind_result');
    mysqli_stmt_execute($stmt) or exit('Error:'.mysqli_stmt_error($stmt));
    $encomendas = [];

    while(mysqli_stmt_fetch($stmt))
    {
        $encomenda = [
            'id' => $id,
            'user_id' => $user_id,
            'data_hora' => $data_hora,
            'estado' => $estado,
            'total' => $total
        ];
        array_push($encomendas, $encomenda);
    }
    mysqli_stmt_close($stmt);
    return $encomendas;
}

function registar($conn, $user){
    $stmt = mysqli_prepare($conn,'INSERT INTO user (nome,email,password,perm) VALUES (?,?,?,?)')
    or exit('Error: '.mysqli_error($conn));     
    mysqli_stmt_bind_param($stmt,'ssss',$user['nome'],$user['email'],$user['password'],$user['perm'])
    or exit('Error in mysqli_stmt_bind_param');
    $res = mysqli_stmt_execute($stmt) or exit('Error: '.mysqli_stmt_error($stmt));
    mysqli_stmt_close($stmt);
    return $res;
}

function verificaEmail($conn, $email){
    return getUserByEmail($conn, $email);
}

function isAdmin(){
    return isset($_SESSION['user']['perm']) && $_SESSION['user']['perm'] === 'admin';
}

function editArtigo($conn, $artigo){
    $stmt = mysqli_prepare($conn, "UPDATE artigo SET descricao=?, categoria_id=?, stock=?, preco=? WHERE ref=?")
      or exit('Error: '.mysqli_error($conn));
    mysqli_stmt_bind_param($stmt,'siiii',$artigo['descricao'],$artigo['categoria_id'],$artigo['stock'],$artigo['preco'], $artigo['ref'])
      or exit('Error in mysqli_stmt_bind_param');
    $res = mysqli_stmt_execute($stmt) or exit('Error: '.mysqli_stmt_error($stmt));
    mysqli_stmt_close($stmt);
    return $res;
}

function deleteArtigoById($conn, $ref){
    $query = "DELETE FROM artigo WHERE ref = $ref";
    $res = mysqli_query($conn,$query) or exit('Erro:'.mysqli_error($conn));
    if($res === false) return false;
    else return true;
}

function listarAllEncomendas($conn, $lazyLoading = true){

    if($lazyLoading)
    {
        $query = "SELECT 
        encomenda.id AS encomenda_id,
        encomenda.user_id AS encomenda_user_id,
        encomenda.data_hora AS encomenda_data_hora,
        encomenda.estado AS encomenda_estado,
        encomenda.total AS encomenda_total
        FROM encomenda";
    }else{
        $query = "SELECT 
        encomenda.id AS encomenda_id,
        encomenda.user_id AS encomenda_user_id,
        encomenda.data_hora AS encomenda_data_hora,
        encomenda.estado AS encomenda_estado,
        encomenda.total AS encomenda_total,
        user.id AS id_user,
        user.nome AS user_nome
        FROM encomenda INNER JOIN user ON encomenda.user_id = user.id";
    }


    $stmt = mysqli_prepare($conn, $query) or exit('Error:'.mysqli_error($conn)); 
    if($lazyLoading)
    {
        mysqli_stmt_bind_result($stmt, $encomenda_id, $encomenda_user_id, $encomenda_data_hora, $encomenda_estado, $encomenda_total) or exit('Error in mysqli_stmt_bind_result');
    }else{
        mysqli_stmt_bind_result($stmt, $encomenda_id, $encomenda_user_id, $encomenda_data_hora, $encomenda_estado, $encomenda_total, $id_user, $user_nome) or exit('Error in mysqli_stmt_bind_result');
    }
    
    mysqli_stmt_execute($stmt) or exit('Error:'.mysqli_stmt_error($stmt));
    $encomendas = [];

    while(mysqli_stmt_fetch($stmt))
    {
        $encomenda = [
            'id' => $encomenda_id,
            'user_id' => $encomenda_user_id,
            'data_hora' => $encomenda_data_hora,
            'estado' => $encomenda_estado,
            'total' => $encomenda_total,
        ];
        if(!$lazyLoading)
        {
            $encomenda['user'] = [
                'id' => $id_user,
                'nome' => $user_nome,
            ];
        }
        array_push($encomendas, $encomenda);
    }
    mysqli_stmt_close($stmt);
    return $encomendas;
}

function editEncomenda($conn, $encomenda)
{
    $stmt = mysqli_prepare($conn, "UPDATE encomenda SET estado=? WHERE id=?")
    or exit('Error: '.mysqli_error($conn));
    mysqli_stmt_bind_param($stmt,'si',$encomenda['estado'], $encomenda['id'])
    or exit('Error in mysqli_stmt_bind_param');
    $res = mysqli_stmt_execute($stmt) or exit('Error: '.mysqli_stmt_error($stmt));
    mysqli_stmt_close($stmt);
    return $res;
}

function getEncomendaById($conn, $id, $lazyLoading = true){

    if($lazyLoading)
    {
        $query = "SELECT 
        encomenda.id AS encomenda_id,
        encomenda.user_id AS encomenda_user_id,
        encomenda.data_hora AS encomenda_data_hora,
        encomenda.estado AS encomenda_estado,
        encomenda.total AS encomenda_total
        FROM encomenda WHERE id= $id";
    }else{
        $query = "SELECT 
        encomenda.id AS encomenda_id,
        encomenda.user_id AS encomenda_user_id,
        encomenda.data_hora AS encomenda_data_hora,
        encomenda.estado AS encomenda_estado,
        encomenda.total AS encomenda_total,
        user.id AS id_user,
        user.nome AS nome_user
        FROM encomenda INNER JOIN user ON encomenda.user_id = user.id WHERE encomenda.id= $id";
    }

        
    $res = mysqli_query($conn, $query) or exit('Error:'.mysqli_error($conn));
    $row = mysqli_fetch_assoc($res);
    if($row === null) return false;
    $encomenda = [
      'id' => $row['encomenda_id'],
      'user_id' => $row['encomenda_user_id'],
      'data_hora' => $row['encomenda_data_hora'],
      'estado' => $row['encomenda_estado'],
      'total' => $row['encomenda_total'],
    ];
    if(!$lazyLoading)
    {
        $encomenda['user'] = [
            'id_user' => $row['id_user'],
            'nome' => $row['nome_user'],
        ];
    }
    mysqli_free_result($res);
    return $encomenda;
}

function deleteEncomendaById($conn, $ref){
    $query = "DELETE FROM encomenda WHERE id = $ref";
    $res = mysqli_query($conn,$query) or exit('Erro:'.mysqli_error($conn));
    if($res === false) return false;
    else return true;
}