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
	$encomendas = listarAllEncomendas($conn, false);
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
		<h1 class="bg-dark text-white p-3">Encomendas</h1>
		<table class="table table-dark table-striped">
			<thead>
				<tr>
				    <th scope="col">Id</th>
					<th scope="col">User</th>
                    <th scope="col">Data/hora</th>
                    <th scope="col">Estado</th>
                    <th scope="col">Total</th>
					<th scope="col">Acões</th>
				</tr>
			</thead>
			<?php
			foreach ($encomendas as $encomenda) {
				?>
				<tr>
					<td><?php echo $encomenda['id']; ?></td>
                    <td><?php echo $encomenda['user']['nome']; ?></td>
                    <td><?php echo $encomenda['data_hora']; ?></td>
                    <td><?php echo $encomenda['estado']; ?></td>
					<td><?php echo $encomenda['total']; ?></td>
					<td>
						<form style="display:inline" action="edit_encomenda.php" method="post">
							<input type="hidden" name="id" value="<?php echo $encomenda['id'];?>">
						  <button type="submit" class="btn btn-primary">
							<span class="material-icons">mode_edit</span>
							</button>
						</form>
						<form style="display:inline" action="delete_encomenda.php" method="get">
							<input type="hidden" name="ref" value="<?php echo $encomenda['id']; ?>">
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