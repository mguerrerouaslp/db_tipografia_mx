<?php
session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION["usuario"])) {
    // Redirigir a la página de inicio de sesión si no ha iniciado sesión
    header("Location: login");
    exit();
}

// Incluir el archivo de conexión a la base de datos
include 'conn.php';

// Definir variables para almacenar los valores del formulario
$name = $description = $ciudad = $sitio_web = '';
$error = '';

// Procesar el formulario cuando se envíe
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recuperar los datos del formulario
    $name = $_POST['name'];
    $description = $_POST['description'];
    $ciudad = $_POST['ciudad'];
    $sitio_web = $_POST['sitio_web'];
    $is_mx = isset($_POST['is_mx']) ? 1 : 0;

    // Verificar si la fundidora ya existe
    $consulta_existencia = "SELECT * FROM foundries WHERE name = '$name'";
    $resultado_existencia = mysqli_query($conexion, $consulta_existencia);

    if (mysqli_num_rows($resultado_existencia) > 0) {
        $error = "La fundidora '$name' ya está registrada.";
    } else {


        // Insertar los datos en la base de datos
        $consulta = "INSERT INTO foundries (name, description, ciudad, sitio_web, is_mx) VALUES ('$name', '$description', '$ciudad', '$sitio_web', $is_mx)";
        if (mysqli_query($conexion, $consulta)) {
            // Redirigir a la misma página después de la inserción
            $success_message = "<div id=\"contact-success\">Fundidora registrada correctamente.</div>";
        } else {
            $error_message = "<div id=\"contact-warning\">Error al registrar la fundidora</div>";
        }
    }
}
?>

<?php include 'header_nologo.php' ?>

<!-- Portada
================================================== -->
<section id="contact">
    <div class="container">

        <div class="entry" style="padding-top: 120px;" >

            <div class="desktop-3 tablet-12 columns">
                <div class="page-desc">
                  <h4></h4>
                    <span class="date">UASLP - CAVD</span>&#160;<i class="bi bi-arrow-right-circle"></i>
                </div><!-- // .box-meta -->
            </div><!-- // .desktop-3 -->

            <div class="desktop-6 tablet-12 nested columns">
                <div class="desktop-6 tablet-12 columns">
                        <div class="box-content">
                            <h2>Registrar una Fundidora</h2>
                            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                            <fieldset>    
                            <?php
                                if (isset($success_message)) {
                                    echo $success_message;
                                }
                                if (isset($error_message)) {
                                    echo $error_message;
                                }
                            ?>

                                <label for="name">Nombre:</label><br>
                                <input type="text" id="name" name="name" value="<?php echo $name; ?>"><br><br>

                                <label for="description">Descripción:</label><br>
                                <textarea id="description" name="description"><?php echo $description; ?></textarea><br><br>

                                <label for="ciudad">Ciudad:</label><br>
                                <input type="text" id="ciudad" name="ciudad" value="<?php echo $ciudad; ?>"><br><br>

                                <label for="is_mx">Marca si es una fundidora mexicana:</label>
                                <input type="checkbox" id="is_mx" name="is_mx" value="1"><br><br>

                                <label for="sitio_web">Sitio web:</label><br>
                                <input type="text" id="sitio_web" name="sitio_web" value="<?php echo $sitio_web; ?>"><br><br>

                                <input type="submit" class="button full" value="Registrar Fundidora">
                            </fieldset>
                            </form>

                          </div><!-- // .box-content -->
              </div><!-- // .desktop-9 -->
              <div class="clear"></div>
              </div><!-- // .desktop-9 .nested -->
              <div class="clear"></div>
          </div><!-- // .entry -->
      </div><!-- // .container -->
    </section><!-- // section#contact -->
    <?php include 'footer.php' ?>
