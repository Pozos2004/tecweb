<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
   "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="es" xml:lang="es">
<head>
  <meta http-equiv="content-type" content="text/html;charset=utf-8" />
  <title>Registro Completado</title>
  <style type="text/css">
    body {margin: 20px; background-color: #C4DF9B; font-family: Verdana, sans-serif;}
    h1 {color: #005825; border-bottom: 1px solid #005825;}
  </style>
</head>
<body>
  <h1>¡MUCHAS GRACIAS!</h1>
  <p>Hemos recibido tu registro al concurso de Tenis Mike&#174; “Chidos mis Tenis”.</p>

  <h2>Información Personal</h2>
  <ul>
    <li><strong>Nombre:</strong> <?php echo $_POST['name']; ?></li>
    <li><strong>Email:</strong> <?php echo $_POST['email']; ?></li>
    <li><strong>Teléfono:</strong> <?php echo $_POST['phone']; ?></li>
  </ul>
  <p><strong>Tu historia:</strong> <?php echo $_POST['story']; ?></p>

  <h2>Diseño Elegido</h2>
  <ul>
    <li><strong>Color:</strong> <?php echo $_POST['color']; ?></li>
    <?php
      if (!empty($_POST['features'])) {
        foreach ($_POST['features'] as $key => $value) {
          echo "<li><strong>Característica ".($key+1).":</strong> $value</li>";
        }
      }
    ?>
    <li><strong>Talla:</strong> <?php echo $_POST['size']; ?></li>
  </ul>

  <p>
    <a href="http://validator.w3.org/check?uri=referer">
      <img src="http://www.w3.org/Icons/valid-xhtml10" alt="Valid XHTML" height="31" width="88" />
    </a>
  </p>
</body>
</html>
