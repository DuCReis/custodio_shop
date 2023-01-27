<?php
    session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrinho de Compras</title>
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
<body class="bg-dark">
    <?php

        require_once('libs_loja.php');
        
        $conn = connectDB();
    
        $lista = listarCarrinho();
        $catego = getAllCategorias($conn);

        ?>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
      <a class="navbar-brand" href="index.php">Custódio Shop</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNavDropdown">
        <ul class="navbar-nav">
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              Categoria
            </a>
            <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
      <?php foreach($catego as $categ)
            { ?>
              <li><a class="dropdown-item" href="index.php?categoria=<?php echo $categ['id'];?>"><?php echo $categ['nome'];?></a></li>
      <?php } ?>
            </ul>
          </li>
          <?php
          if(auth()){ ?>
          <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="encomenda.php">Encomendas</a>
          </li>
          <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="checkout.php">
                Carrinho <span class="badge bg-secondary"><?php echo $_SESSION['carrinho']['nitems'] ?></span>
            </a>
          </li> <?php
          } ?>
        </ul>
        <ul class="navbar-nav ms-auto">
          <li class="nav-item ">
          <?php
          if(!auth())
          { ?>
            <a class="nav-link active" aria-current="page" href="login.php">Login</a>
            <?php
          }else{ ?>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              <?php echo $_SESSION['user']['nome']; ?>
            </a>
            <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
              <li>
                <a class="dropdown-item" aria-current="page" href="logout.php">Logout</a>
              </li>
            </ul>
          </li>
            <?php
          } ?>
          </li>
        </ul>
      </div>
    </div>
</nav>
<?php
if(auth())
{ ?>  
<div class="container mt-4">
	<h1 class="bg-dark text-white p-3">Carrinho de Compras</h1>
		<table class="table table-dark table-striped">
			<thead>
				<tr>
          <th scope="col">Artigo</th>
				  <th scope="col">Ref</th>
					<th scope="col">Quantidade</th>
          <th scope="col">Preço</th>
          <th scope="col">Ações</th>
				</tr>
			</thead>
			<?php
			foreach ($lista as $list) {
				?>
				<tr>
          <td><?php echo $list['descricao']; ?></td>
					<td><?php echo $list['ref']; ?></td>
            <td><?php echo $list['quantidade']; ?></td>
              <td><?php echo $list['preco']; ?>€</td>
                <td>
                  <form style="display:inline" action="diminuir.php" method="post">
							      <input type="hidden" name="ref" value="<?php echo $list['ref'];?>">
						        <button type="submit" class="btn btn-primary">-</button>
						      </form> <?php
                  if(verificaMaxStock($conn, $list['ref']))
                  { ?>
                    <form style="display:inline" action="adicionar2.php" method="post">
							      <input type="hidden" name="ref" value="<?php echo $list['ref']; ?>">
						        <button type="submit" class="btn btn-primary">+</button>
						      </form> <?php
                  } ?>
                </td>
				      </tr>
				
				<?php
			}
			closeDB($conn);
			?>
		</table>
    <div class="text-white">Total: <?php echo $_SESSION['carrinho']['total'] ?>€</div> <?php
    if($_SESSION['carrinho']['total'] > 0)
    { ?>
      <div>						      
        <form style="display:inline" action="adicionarencomenda.php" method="post">
          <button type="submit" class="btn btn-success">Encomendar</button>
        </form>
      </div> <?php
    } ?>      
	</div>
<?php
}else{
  header("Location: index.php");
} ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
</body>
</html>