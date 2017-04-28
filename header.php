<nav class="navbar navbar-inverse">
  <div class="container-fluid">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="#"><img src="img/ucm.png" width="60"  /></a>
    </div>
    <div class="collapse navbar-collapse" id="myNavbar">
      <ul class="nav navbar-nav">
        <li id="nav-piezas"><a href="Tareas.php">Base de datos</a></li>
        <!--<li id="nav-db"><a href="Fichas.php">Fichas</a></li>--> 
         <?php if($_SESSION['perfil']>80){?><li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Admin<span class="caret"></span></a>
          <ul class="dropdown-menu">
            <li><a href="Admin-user.php">Usuarios</a></li>
            <li><a href="Admin-label.php">Desplegables</a></li>
          </ul>
        </li><?php }?>
        <li id="nav-analytics"><a href="Analysis.php">Analysis</a></li>
      </ul>
        
      <ul class="nav navbar-nav navbar-right">
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><?php  echo $_SESSION['username']; ?> <span class="caret"></span></a>
          <ul class="dropdown-menu">
            <li><a href="changePass.php">Cambiar contraseña</a></li>
            <li role="separator" class="divider"></li>
            <li><a href="logout.php"><span class="glyphicon glyphicon-off"></span> Cerrar sesión</a></li>
          </ul>
        </li>
      </ul>
    </div>
  </div>
</nav>
