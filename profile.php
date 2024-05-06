<?php
session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION["usuario"])) {
    // Redirigir a la página de inicio de sesión si no ha iniciado sesión
    header("Location: login");
    exit();
}

// Obtener el usuario de la sesión
$usuario = $_SESSION["usuario"];

// Conexión a la base de datos (incluye el archivo de conexión o define las variables aquí)
include 'conn.php';

// Verificar la conexión
if (!$conexion) {
    die("Error al conectar a la base de datos: " . mysqli_connect_error());
}

// Obtener el usuario de la sesión
$usuario = $_SESSION["usuario"];

// Consulta para obtener la información del perfil del usuario
$consulta_perfil = "SELECT name, lastname, email, ciudad, estado, cv_corto, foto FROM usuarios WHERE email = '$usuario'";
$resultado_perfil = mysqli_query($conexion, $consulta_perfil);

// Verificar si se obtuvieron resultados
if (!$resultado_perfil) {
    die("Error al obtener información del perfil del usuario: " . mysqli_error($conexion));
}

// Obtener los datos del perfil
$fila_perfil = mysqli_fetch_assoc($resultado_perfil);

// Consulta para obtener los registros de la tabla fonts asociados al usuario
$consulta_registros = "SELECT ID, typo, year_reg 
                       FROM fonts 
                       WHERE userid = (SELECT id FROM usuarios WHERE email = '$usuario') 
                       AND draft <> 1 
                       ORDER BY year_reg";
$resultado_registros = mysqli_query($conexion, $consulta_registros);



// Verificar si se obtuvieron resultados
if (!$resultado_registros) {
    die("Error al obtener registros del usuario: " . mysqli_error($conexion));
}
?>

<?php include'header_nologo.php'?>

<!-- Portada
================================================== -->
<section id="archive">
    <div class="container">

        <div class="entry">

            <div class="desktop-3 tablet-12 columns">
                <div class="page-desc" style="padding-top: 50px; ">
                  <img src="<?php echo $fila_perfil['foto']; ?>" alt="Foto de perfil">
                    <span class="date">UASLP - CAVD &#160;<i class="bi bi-arrow-right-circle"></i></span><br><hr>
                    Revisa tu perfil<hr>
                    <a href="update_profile">Actualizar perfil</a><hr>
                    <a href="capture_font">Registra una tipografía</a><hr>
                    <a href="logout">Cerrar sesión</a><hr>
                    <p>

                      <?php if (mysqli_num_rows($resultado_registros) > 0) : ?>
                          <b>Producción tipográfica:</b><br><hr>
                            <?php while ($fila_registro = mysqli_fetch_assoc($resultado_registros)) : ?>

                                <a href="view_typo.php?id=<?php echo $fila_registro['ID']; ?>"><?php echo $fila_registro['year_reg']; ?> - <?php echo $fila_registro['typo']; ?></a><br>
                            <?php endwhile; ?>

                      <?php else : ?>

                      <?php endif; ?>
                    </p>
                </div><!-- // .box-meta -->
            </div><!-- // .desktop-3 -->

            <div class="desktop-7 tablet-12 nested columns">
                <div class="desktop-7 tablet-12 columns">
                        <div class="box-content">
                            <h2><?php echo $fila_perfil['name'] . ' ' . $fila_perfil['lastname']; ?></h2>
                            <p><?php echo $fila_perfil['email']; ?><br>
                            <?php echo $fila_perfil['ciudad']; ?><br></p>
                            <p><br><?php echo $fila_perfil['cv_corto']; ?></p>
                          </div><!-- // .desktop-9 -->

                          <div class="clear"></div>
                      </div><!-- // .desktop-9 .nested -->

                      <div class="clear"></div>
                  </div><!-- // .entry -->

              </div><!-- // .container -->
          </section><!-- // section#archive -->

                        <?php
                        // Cerrar la conexión a la base de datos
                        mysqli_close($conexion);
                        ?>
    <?php include 'footer.php' ?>
