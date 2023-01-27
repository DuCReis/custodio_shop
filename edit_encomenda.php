
  <?php
  session_start();
  require_once('libs_loja.php');
  $conn = connectDB(); 

  if(isset($_POST['action'])&& $_POST['action']== 'save') {
    $estado = htmlentities($_POST['estado']); 
       editEncomenda($conn, [
        'id' => $_POST['id'],
        'estado' => $estado
        ]);
    header('location: backencomenda.php');
    closeDB($conn);
    }else{
    ?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Editar Encomenda</title>
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
    $encomenda = getEncomendaById($conn, $_POST['id'], false);
    $categorias = getAllCategorias($conn);
    ?>
    <div class="container mt-4">
        <h1 class="bg-dark p-3">Editar Encomenda - id: <?php echo $encomenda['id']; ?></h1>
        <hr>
        <table class="table table-striped">
            <form action="" method="post">
            <div class="mb-3">
                <div>
                    <label for="user_id" class="form-label"><?php echo $encomenda['user']['nome']; ?></label> 
                </div>
            </div>
            <div class="mb-3">
                <div>
                    <label for="data_hora" class="form-label">Data e Hora: <?php echo $encomenda['data_hora']; ?></label> 
                </div>
            </div>
            <div class="mb-3">
                <div>
                    <label for="estado" class="form-label">Estado</label> 
                </div> 
                <select name="estado" id="estado">
                    <option value="preparar">preparar</option>
                    <option value="enviar">enviar</option>
                    <option value="finalizado">finalizado</option>
                </select>
            </div>
            <div class="mb-3">
                <div>
                    <label for="total" class="form-label">Total: <?php echo $encomenda['total']; ?>€</label>
            </div>
                <input type="hidden" name="action" value="save">
                <input type="hidden" name="id" value="<?php echo $_POST['id']; ?> ">
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