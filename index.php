<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Index</title>
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
<body>

<?php
	require_once('libs_loja.php');
	$conn = connectDB();
    $artigos = getAllArtigos($conn, false);
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
          <?php
          if(isAdmin())
          { ?>
            <li>
              <a class="dropdown-item" aria-current="page" href="listar.php">Backoffice</a>
            </li> <?php
          }
          ?>
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

<div class="container mt-4"><?php
    if(isset($_GET['categoria']))
    {
        $categorias = getCategoriaById($conn, false ,$_GET['categoria']);
        ?>
        <div class="row row-cols-1 row-cols-md-3 g-4">
        <?php
        foreach ($categorias as $artigo) {
        ?>
            <a href="viewproduct.php?id=<?php echo $artigo['ref']; ?>">
                <div class="col">
                    <div class="card h-100">
                        <img src="/img/<?php echo $artigo['ref'].'.jpg'; ?>" class="card-img-top">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo $artigo['descricao']; ?></h5>
                            <p><?php echo $artigo['preco']; echo '€'?></p>
                        </div>
                    </div>
                </div>
            </a>    
        <?php 
        }
        ?></div><?php
    }else{
        ?>

        <div class="row row-cols-1 row-cols-md-3 g-4">
        <?php
        foreach ($artigos as $artigo) {
        ?>
            <a href="viewproduct.php?id=<?php echo $artigo['ref']; ?>">
                <div class="col">
                    <div class="card h-100">
                        <img src="/img/<?php echo $artigo['ref'].'.jpg'; ?>" class="card-img-top">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo $artigo['descricao']; ?></h5>
                                <h6><?php echo $artigo['preco']; echo '€'?></h6>
                            </div>
                    </div>
                </div>
            </a>
        <?php 
        }
      ?></div> <?php
        closeDB($conn);
    } ?>

</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
</body>
</html>