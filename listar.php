<?php
session_start();
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Listagem de Roupas</title>
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
	$artigos = getAllArtigos($conn, false);
	?>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
  <div class="container-fluid">
    <a class="navbar-brand" href="listar.php">BackOffice</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNavDropdown">
      <ul class="navbar-nav">
        <?php
        if(auth() && isAdmin()){ ?>
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="backencomenda.php">Encomendas</a>
        </li>
        <?php
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
              <a class="dropdown-item" aria-current="page" href="index.php">FrontOffice</a>
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

	<div class="container mt-4">
		<h1 class="bg-dark text-white p-3">Listagem de Artigos</h1>
		<table class="table table-dark table-striped">
			<thead>
				<tr>
				    <th scope="col">Ref</th>
					<th scope="col">Stock</th>
                    <th scope="col">Descricao</th>
                    <th scope="col">Preço</th>
                    <th scope="col">Categoria</th>
					<th scope="col">Acões</th>
				</tr>
			</thead>
			<?php
			foreach ($artigos as $artigo) {
				?>
				<tr>
					<td><?php echo $artigo['ref']; ?></td>
                    <td><?php echo $artigo['stock']; ?></td>
                    <td><?php echo $artigo['descricao']; ?></td>
                    <td><?php echo $artigo['preco']; ?></td>
					<td><?php echo $artigo['categoria']['nome']; ?></td>
					<td>
						<form style="display:inline" action="edit_artigo.php" method="post">
							<input type="hidden" name="ref" value="<?php echo $artigo['ref'];?>">
						  <button type="submit" class="btn btn-primary">
							<span class="material-icons">mode_edit</span>
							</button>
						</form>
						<form style="display:inline" action="delete_artigo.php" method="get">
							<input type="hidden" name="ref" value="<?php echo $artigo['ref']; ?>">
						  <button type="submit" class="btn btn-danger">
							  <span class="material-icons">delete_forever</span>
							</button>
						</form>
					</td>
				</tr>
				
				<?php
			}
			closeDB($conn);
			?>
		</table>
	</div>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
</body>
</html>