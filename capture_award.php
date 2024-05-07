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


$error = '';
$success_message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recuperar los datos del formulario
    $award = $_POST['award'] ?? '';
    $cat_award = $_POST['cat_award'] ?? '';
    $full_name = $_POST['full_name'] ?? '';
    $typo_award = $_POST['typo_award'] ?? '';
    $year_award = $_POST['year_award'] ?? '';
    $region = $_POST['region'] ?? '';
    $source = $_POST['source'] ?? '';

    // Asegúrate de construir correctamente la consulta SQL
    $consulta_existencia = "SELECT * FROM fonts_award WHERE award = '$award' AND cat_award = '$cat_award' AND full_name = '$full_name' AND typo_award = '$typo_award' AND source = '$source' AND region = '$region'";
    $resultado_existencia = mysqli_query($conexion, $consulta_existencia);

    if (mysqli_num_rows($resultado_existencia) > 0) {
        $error = "La tipografía '$full_name' ya ha sido registrada con este premio y año.";
    } else {
        // Insertar los datos en la base de datos
        $consulta = "INSERT INTO fonts_award (award, cat_award, full_name, typo_award, year_award, source, region) VALUES ('$award', '$cat_award', '$full_name', '$typo_award', '$year_award', '$source', '$region')";
        if (mysqli_query($conexion, $consulta)) {
            $success_message = "<div id=\"contact-success\">Tipografía registrada correctamente.</div>";
        } else {
            $error = "<div id=\"contact-warning\">Error al registrar la Tipografía</div>";
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
                        <!-- Mensajes de error o éxito -->
                        <?php if (!empty($error)) : ?>
                            <div id="contact-warning"><?php echo $error; ?></div>
                        <?php elseif (!empty($error)) : ?>
                            <div class="success-message"><?php echo $error; ?></div>
                        <?php endif; ?>

                        <h2>Registrar tipografía reconocida</h2><br><br>
                        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                        <fieldset>
                        <?php
                          if (isset($success_message)) {
                              echo $success_message;
                          }
                          
                        ?>
                            <label for="award">Premio:</label><br>
                            <input type="text" id="award" name="award" value="<?php echo $award; ?>"><br><br>

                            <label for="region">Región:</label>
                                <select id="region" name="region">
                                    <option value="nacional">Nacional</option>
                                    <option value="internacional">Internacional</option>
                                </select>

                            <label for="cat_award">Categoría del premio:</label><br>
                            <input type="text" id="cat_award" name="cat_award" value="<?php echo $cat_award; ?>"><br><br>

                            <label for="full_name">Nombre del diseñador:</label><br>
                            <input type="text" id="full_name" name="full_name" value="<?php echo $full_name; ?>"><br><br>

                            <label for="typo_award">Nombre de la tipografía:</label><br>
                            <input type="text" id="typo_award" name="typo_award" value="<?php echo $typo_award; ?>"><br><br>

                            <label for="year_award">Año del premio:</label><br>
                            <input type="number" id="year_award" name="year_award" value="<?php echo $year_award; ?>"><br><br>

                            <label for="source">Url de consulta:</label><br>
                            <input type="text" id="source" name="source" value="<?php echo $source; ?>"><br><br>

                            <input type="submit" class="button full" value="Registrar Tipografía">
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
