<link rel='stylesheet' type='text/css' href='css/menu_style.css'>

<div align="right" style="background-color:#000">
<div>
<ul id="menu">
  <li><a href="index.php">Inicio</a></li>
  <li><a href="evaluar2empresa.php?id=<?=$_SESSION['nro']?>">Aplicar Evaluaci&oacute;n</a></li>
  <li><a href="resultadosempresa.php?id=<?=$_SESSION['nro']?>">Ver Resultados</a></li>
  <li><a href="evaluar2empresaiso.php?id=<?=$_SESSION['nro']?>">Aplicar Evaluaci&oacute;n ISO 55000</a></li>
  <li><a href="resultadosempresaiso.php?id=<?=$_SESSION['nro']?>">Ver Resultados ISO 55000</a></li>
  <li><a href="evaluar2empresaLARC.php?id=<?=$_SESSION['nro']?>">Aplicar Evaluaci&oacute;n LARC</a></li>
  <li><a href="resultadosempresaLARC.php?id=<?=$_SESSION['nro']?>">Ver Resultados LARC</a></li>
  <li><a href="logout.php"><strong>Salir</strong></a></li>
</ul>
</div>
<div style="width:250px; display:block;"><img src="images/SHEN negro_small.png"/></div>
</div>


<div style=" text-align:right; font-size:13px"><strong>Bienvenido: 
<?=$_SESSION['admin']?></strong></div>


