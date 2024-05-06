<?php
session_start();

include 'conn.php';

if (!$conexion) {
    die("Error al conectar a la base de datos: " . mysqli_connect_error());
}

$error_message = '';
$success_message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener otros datos del formulario
    $name = $_POST['name'];
    $lastname = $_POST['lastname'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $country = $_POST['country'];
    $ciudad = $_POST['ciudad'];
    $estado = $_POST['estado'];
    $cv_corto = $_POST['cv_corto'];

    // Procesar la carga de archivo
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["file"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Verificar si el archivo es una imagen real
    if (isset($_POST["submit"])) {
        $check = getimagesize($_FILES["file"]["tmp_name"]);
        if ($check !== false) {
            $uploadOk = 1;
        } else {
            $error_message = "El archivo no es una imagen.";
            $uploadOk = 0;
        }
    }

    // Verificar si el archivo ya existe
    if (file_exists($target_file)) {
        $error_message = "Lo siento, el archivo ya existe.";
        $uploadOk = 0;
    }

    // Verificar tamaño máximo de archivo (opcional)
    if ($_FILES["file"]["size"] > 5000000) {
        $error_message = "Lo siento, tu archivo es demasiado grande.";
        $uploadOk = 0;
    }

    // Permitir solo ciertos formatos de archivo
    if ($imageFileType != "jpg" && $imageFileType != "png") {
        $error_message = "Lo siento, solo se permiten archivos JPG, JPEG, PNG.";
        $uploadOk = 0;
    }

    // Si $uploadOk es 0, ocurrió un error
    if ($uploadOk == 0) {
        $error_message = "Lo siento, tu archivo no fue subido.";
    // Si todo está bien, intenta subir el archivo
    } else {
        if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
            // El archivo se subió correctamente, guardar el enlace en la base de datos
            $foto = $target_file;

            // Insertar usuario en la base de datos con el enlace de la foto
            $insertar = "INSERT INTO usuarios (name, lastname, email, password, country, ciudad, estado, cv_corto, foto) VALUES ('$name', '$lastname', '$email', '$password', '$country', '$ciudad', '$estado', '$cv_corto', '$foto')";

            if (mysqli_query($conexion, $insertar)) {
                $success_message = "Datos enviados satisfactoriamente.";
                header("Location: login");
                exit();
            } else {
                $error_message = "Error al insertar datos: " . mysqli_error($conexion);
            }
        } else {
            $error_message = "Lo siento, hubo un error al subir tu archivo.";
        }
    }
}
?>
<?php include'header.php'?>

<!-- Portada
================================================== -->
<section id="contact">
    <div class="container">

        <div class="entry">

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
                        <?php if (!empty($error_message)) : ?>
                            <div id="contact-warning"><?php echo $error_message; ?></div>
                        <?php elseif (!empty($success_message)) : ?>
                            <div class="success-message"><?php echo $success_message; ?></div>
                        <?php endif; ?>

                        <form action="register" method="post" enctype="multipart/form-data">
                            <fieldset>
                                <label for="name">Nombre: <span class="required">*</span></label>
                                <input type="text" name="name" placeholder="name" required><br>

                                <label for="lastname">Apellido: <span class="required">*</span></label>
                                <input type="text" name="lastname" placeholder="lastname" required><br>

                                <label for="email">email: <span class="required">*</span></label>
                                <input type="email" name="email" placeholder="email" required><br>

                                <label for="password">Contraseña: <span class="required">*</span></label>
                                <input type="password" name="password" placeholder="password" required><br>

                                <label for="country">País: <span class="required">*</span></label>
                                <input type="text" name="country" placeholder="country" required><br>

                                <label for="ciudad">Ciudad: </label>
                                <input type="text" name="ciudad" placeholder="ciudad" <br>

                                <label for="estado">Estado:</label>
                                <input type="text" name="estado" placeholder="estado"><br>

                                <label for="cv_corto">CV:</span></label>
                                <textarea rows="10" name="cv_corto" >CV corto...</textarea><br>

                                <label for="file">Foto:</label>
                                <input type="file" name="file" id="file"><br><br>

                                <input type="submit" class="button full" value="Registrarse" name="submit">
                            </fieldset>
                        </form>
                      </div><!-- // .box-content -->
                      <hr>
          </div><!-- // .desktop-9 -->
          <div class="clear"></div>
          </div><!-- // .desktop-9 .nested -->
          <div class="clear"></div>
      </div><!-- // .entry -->
  </div><!-- // .container -->
</section><!-- // section#contact -->
<?php include 'footer.php' ?>
