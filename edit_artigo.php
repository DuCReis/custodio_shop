
  <?php
  require_once('libs_loja.php');
  $conn = connectDB(); 

  if(isset($_POST['action'])&& $_POST['action']== 'save') {
    $descricao = htmlentities($_POST['descricao']); 
    $categoriaa = htmlentities($_POST['categoria_id']);
    $stock = htmlentities($_POST['stock']);
    $preco = htmlentities($_POST['preco']);
       editArtigo($conn, [
        'ref' => $_POST['ref'],
        'descricao' => $descricao,
        'categoria_id' => $categoriaa,
        'stock' => $stock,
        'preco' => $preco 
        ]);
    header('location: listar.php');
    closeDB($conn);
    }else{
    ?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Editar Artigo</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css"
	      rel="stylesheet"
				integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl"
				crossorigin="anonymous">
	<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet"> 
    <style>
    @import url('https://fonts.googleapis.com/css2?family=Roboto&display=swap');
    </style> 
</head>
<body class="bg-dark text-white">
  <?php
    $artigo = getArtigoById($conn, $_POST['ref']);
    $categorias = getAllCategorias($conn);
    ?>
    <div class="container mt-4">
        <h1 class="bg-dark p-3">Editar Artigo - Ref: <?php echo $artigo['ref']; ?></h1>
        <hr>
        <table class="table table-striped">
            <form action="" method="post">
            <div class="mb-3">
                <div>
                    <label for="descricao" class="form-label">Descrição</label> 
                </div>
                <input type="text" name="descricao" id="descricao" value="<?php echo $artigo['descricao']; ?>" size="100">
            </div>
            <div class="mb-3">
                <div>
                    <label for="preco" class="form-label">Preço</label> 
                </div>
                <input type="text" name="preco" id="preco" value="<?php echo $artigo['preco']; ?>" size="100">
            </div>
            <div class="mb-3">
                <div>
                    <label for="categoria" class="form-label">Categoria</label> 
                </div> 
                <select name="categoria_id" id="categoria_id">
                    <?php
                    foreach ($categorias as $categoria) {
                        if($artigo['categoria_id'] != $categoria['id']){
                            echo "<option value=\"{$categoria['id']}\">{$categoria['nome']}</option>";
                        }else{
                        echo "<option value=\"{$categoria['id']}\" selected>{$categoria['nome']}</option>";
                        }
                    }
                    ?>
                </select>
            </div>
            <div class="mb-3">
                <div>
                    <label for="stock" class="form-label">stock</label> 
                </div>
                <input type="text" name="stock" id="stock" value="<?php echo $artigo['stock']; ?>" size="10">
            </div>
                <input type="hidden" name="action" value="save">
                <input type="hidden" name="ref" value="<?php echo $_POST['ref']; ?> ">
            <div>
                <button type="submit" class="btn btn-outline-success" value="update">Editar</button>
                <button type="reset" class="btn btn-outline-warning" value="Limpar">Limpar</button>
            </div>
        </form>
        </table>
    </div>   
    <?php
    }
  ?>
</body>
</html>