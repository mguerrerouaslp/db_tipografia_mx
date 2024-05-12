<?php
session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION["usuario"])) {
    // Redirigir a la página de inicio de sesión si no ha iniciado sesión
    header("Location: login.php");
    exit();
}

include 'conn.php';

if (!$conexion) {
    die("Error al conectar a la base de datos: " . mysqli_connect_error());
}

$usuario = $_SESSION["usuario"];
$consulta_perfil = "SELECT id, name, lastname, foto FROM usuarios WHERE email = '$usuario'";
$resultado_perfil = mysqli_query($conexion, $consulta_perfil);

if (!$resultado_perfil) {
    die("Error al obtener información del perfil del usuario: " . mysqli_error($conexion));
}

if (mysqli_num_rows($resultado_perfil) == 0) {
    // El usuario no existe en la base de datos
    die("Usuario no encontrado en la base de datos.");
}

// Consulta para obtener los registros de la tabla fonts asociados al usuario
$consulta_registros = "SELECT ID, typo, year_reg FROM fonts WHERE userid = (SELECT id FROM usuarios WHERE email = '$usuario')";
$resultado_registros = mysqli_query($conexion, $consulta_registros);

$fila_perfil = mysqli_fetch_assoc($resultado_perfil);

require_once 'upload_module.php'; // Incluye el módulo

// Verificar si se ha enviado el formulario de captura de font
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Capturar los campos del formulario
    $typo = $_POST['typo'];
    $year_reg = $_POST['year_reg'];
    $full_name = $fila_perfil['name'] . ' ' . $fila_perfil['lastname'];
    $full_name_rol = implode(', ', $_POST['full_name_rol']);
    $full_name2 = $_POST['full_name2'];
    $full_name2_rol = implode(', ', $_POST['full_name2_rol']);
    $certif = $_POST['certif'];
    $urlArchivo = $_POST['sample'];
    $description = $_POST['description'];
    $distribution = implode(', ', $_POST['distribution']);
    $classification = $_POST['classification'];
    $vars_num = $_POST['vars_num'];
    $vars_name = $_POST['vars_name'];
    $format = implode(', ', $_POST['format']);
    $axes_num = $_POST['axes_num'];
    $num_characters = $_POST['num_characters'];
    $lang = $_POST['lang'];
    $type = $_POST['type'];
    $studio = $_POST['studio'];
    $publisher = $_POST['publisher'];
    $client = $_POST['client'];
    $event = $_POST['event'];
    $status = $_POST['status'];
    $url = $_POST['url'];
    $featured = $_POST['featured'];
    $keywords = $_POST['keywords'];
    $keywords_array = explode(',', $keywords);
    $date_recorded = date('Y-m-d'); // Obtener la fecha actual

    if (isset($_FILES['uploadedfile'])) {
        // Si se ha enviado un archivo, maneja la carga y obtén la URL
        $result = handleFileUpload($_FILES['uploadedfile']);
        $urlArchivo = "uploads/fonts/" . basename($_FILES['uploadedfile']['name']);

                // Insertar los datos en la tabla fonts
                $consulta_insertar = "INSERT INTO fonts (userid, typo, year_reg, full_name, full_name_rol, full_name2, full_name2_rol, certif, sample, description, distribution, classification, vars_num, vars_name, format, num_characters, lang, type, studio, publisher, client, event, status, url, featured, keywords, date_recorded)
                    VALUES ('{$fila_perfil['id']}', '$typo', '$year_reg', '$full_name', '$full_name_rol', '$full_name2', '$full_name2_rol', '$certif', '$urlArchivo', '$description', '$distribution', '$classification', '$vars_num', '$vars_name', '$format', '$num_characters', '$lang', '$type', '$studio',
                      '$publisher', '$client', '$event', '$status', '$url', '$featured', '$keywords', '$date_recorded')";
                                   if (mysqli_query($conexion, $consulta_insertar)) {
                                         $font_id = mysqli_insert_id($conexion);

                                         require_once 'insert_keywords.php';
                                             $success_message = "<div id=\"contact-success\">Tipografía registrada correctamente.</div>";
                                           } else {
                                             $error_message = "<div id=\"contact-warning\">Error al registrar la Tipografía</div>";
                                           }

                                   }
                                   }
?>
<?php include'header_nologo.php'?>
<!-- Portada
================================================== -->
<section id="contact" class="section page">
  <div class="container">

        <div class="entry">

          <div class="desktop-3 tablet-12 columns">
              <div class="page-desc" style="padding-top: 50px; ">
                <img src="<?php echo $fila_perfil['foto']; ?>" alt="Foto de perfil">
                <span class="date">UASLP <i class="bi bi-arrow-right-circle"></i></span><br><hr>
                <a href="profile">Revisa tu perfil</a><hr>
                <a href="update_profile">Actualizar perfil</a><hr>
                <a href="capture_font">Registra una tipografía</a><hr>
                <a href="logout">Cerrar sesión</a><hr>
                  <p>
                    <b>Producción tipográfica:</b><br><hr>
                    <?php if (mysqli_num_rows($resultado_registros) > 0) : ?>
                          <?php while ($fila_registro = mysqli_fetch_assoc($resultado_registros)) : ?>
                              <a href="view_typo.php?id=<?php echo $fila_registro['ID']; ?>"><?php echo $fila_registro['typo']; ?></a> - <?php echo $fila_registro['year_reg']; ?><br>
                          <?php endwhile; ?>
                    <?php else : ?>
                        <p>No se ha registrado producción tipográfica.</p>
                    <?php endif; ?>
                  </p>
              </div><!-- // .box-meta -->
          </div><!-- // .desktop-3 -->

            <div class="desktop-6 tablet-12 nested columns">
                <div class="desktop-6 tablet-12 columns">
                  <div class="box-content"><br>
                      <h2>Registro de una nueva fuente tipográfica</h2><br><br>



                        <form method="post" enctype="multipart/form-data" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                        <fieldset>
                        <?php
                          if (isset($success_message)) {
                              echo $success_message;
                          }
                          if (isset($error_message)) {
                              echo $error_message;
                          }
                      ?>
                        <!-- Campo de entrada para 'typo' -->
                        <br><label for="typo">Nombre de la tipografía:</label><br>
                        <input type="text" id="typo" name="typo" required><br>

                        <!-- Campo de entrada para 'year_reg' -->
                        <label for="year_reg">Año de registro:</label>
                        <input type="text" id="year_reg" name="year_reg" required><br>

                        <!-- Campo de entrada para 'full_name_rol' -->
                        <label for="full_name_rol">Rol del diseñador:</label>
                        <input type="checkbox" id="diseno" name="full_name_rol[]" value="diseño" required>
                        <label for="diseño">Diseño</label>
                        <input type="checkbox" id="scripting" name="full_name_rol[]" value="scripting">
                        <label for="scripting">Scripting</label>
                        <input type="checkbox" id="hinting" name="full_name_rol[]" value="hinting">
                        <label for="hinting">Hinting</label>
                        <input type="checkbox" id="postproduccion" name="full_name_rol[]" value="postproducción">
                        <label for="postproduccion">Postproducción</label><br>

                        <!-- Campo de entrada para 'full_name2' -->
                        <label for="full_name2">Créditos:</label>
                        <input type="text" id="full_name2" name="full_name2"><br>

                        <!-- Campo de entrada para 'sample' (para cargar la imagen) -->
                        <label for="uploadedfile">Muestra:</label>
                        <input type="file" name="uploadedfile" id="uploadedfile"><br><br>
                        <!-- Campo de entrada para 'description' -->
                        <label for="description">Descripción:</label>
                        <textarea id="description" name="description" rows="5" cols="50"></textarea>

                        <!-- Campo de entrada para 'distribution' -->
                        <label for="distribution">Distribución:</label>
                        <input type="checkbox" id="retail" name="distribution[]" value="Retail">
                        <label for="retail">Retail</label>
                        <input type="checkbox" id="custom" name="distribution[]" value="Custom">
                        <label for="custom">Custom</label>
                        <input type="checkbox" id="open_source" name="distribution[]" value="Open Source">
                        <label for="open_source">Open Source</label><br>
                        <input type="checkbox" id="gratuita" name="distribution[]" value="Gratuita">
                        <label for="gratuita">Gratuita</label><br>

                        <!-- Campo de entrada para 'classification' -->
                        <label for="classification">Clasificación:</label>
                        <select id="classification" name="classification" required>
                            <option value="Texto">Texto</option>
                            <option value="Títulos">Títulos</option>
                            <option value="Script">Script</option>
                            <option value="Experimental">Experimental</option>
                            <option value="Dingbats">Dingbats</option>
                            <option value="Patterns">Patterns</option>
                        </select>

                        <!-- Campo de entrada para 'vars_name' -->
                        <label for="vars_name">Familia(s):</label>
                        <input type="text" id="vars_name" name="vars_name" required>

                        <!-- Campo de entrada para 'vars_num' -->
                        <label for="vars_num">Estilos tipográficos:</label>
                        <input type="text" id="vars_num" name="vars_num" required>

                        <label>Formato:</label>
                        <input type="checkbox" id="ttf" name="format[]" value="ttf">
                        <label for="ttf">ttf</label>
                        <input type="checkbox" id="otf" name="format[]" value="otf">
                        <label for="otf">otf</label>
                        <input type="checkbox" id="woff" name="format[]" value="woff">
                        <label for="woff">woff</label>
                        <input type="checkbox" id="woff2" name="format[]" value="woff2">
                        <label for="woff">woff2</label><br>
                        <input type="checkbox" id="ttf-v" name="format[]" value="ttf (variable)">
                        <label for="ttf-v">ttf-variable</label>
                        <input type="checkbox" id="woff2-v" name="format[]" value="woff2 (variable)">
                        <label for="woff2-v">woff2-variable</label><br>

                        <label for="axes_num">Variable axis:</label>
                        <input type="text" id="axes_num" name="axes_num" >

                        <!-- Campo de entrada para 'num_characters' -->
                        <label for="num_characters">Número de caracteres:</label>
                        <select id="num_characters" name="num_characters" required>
                            <option value="entre 256 - 1000">Menor que 256</option>
                            <option value="entre 256 - 1000">Entre 256 y 1000</option>
                            <option value="entre 256 - 1000">Mayor que 1000</option>
                        </select>

                        <!-- Campo de entrada para 'type' -->
                        <label for="type">Set tipográfico:</label>
                        <select id="type" name="type" required>
                            <option value="Básico">Básico</option>
                            <option value="Extendido">Extendido</option>
                            <option value="Especializado">Especializado</option>
                        </select><br>

                        <!-- Campo de entrada para 'lang' -->
                        <label for="lang">Lenguajes:</label>
                        <input type="text" id="lang" name="lang">

                        <!-- Campo de entrada para 'studio' -->
                        <label for="studio">Estudio:</label>
                        <input type="text" id="studio" name="studio">

                        <!-- Campo de entrada para 'publisher' -->
                        <label for="publisher">Fundidora:</label>
                        <input type="text" id="publisher" name="publisher">

                        <!-- Campo de entrada para 'client' -->
                        <label for="client">Cliente:</label>
                        <input type="text" id="client" name="client">

                        <!-- Campo de entrada para 'event' -->
                        <label for="event">Evento:</label>
                        <input type="text" id="event" name="event">


                        <!-- Campo de entrada para 'status' -->
                        <label for="status">Estado:</label>
                        <select id="status" name="status" required>
                            <option value="disponible">disponible</option>
                            <option value="no_disponible">no disponible</option>
                        </select><br>
                        
                        <!-- Campo de entrada para 'url' -->
                        <label for="url">URL:</label>
                        <input type="url" id="url" name="url">

                        <!-- Campo de entrada para 'featured' -->
                        <label for="featured">Reconocimientos:</label>
                        <input type="text" id="featured" name="featured">

                        <!-- Campo de entrada para 'keywords' -->
                        <label for="keywords">Palabras clave (separadas por coma):</label>
                        <input type="text" id="keywords" name="keywords">

                        <!-- Botón de envío -->
                        <input type="submit" name="submit" class="button full" value="Registrar">
                        </fieldset>
                    </form>
                  </div><!-- // .box-content -->

          </div><!-- // .desktop-9 -->

          <div class="clear"></div>
      </div><!-- // .desktop-9 .nested -->

      <div class="clear"></div>
  </div><!-- // .entry -->


<?php include 'footer.php' ?>
