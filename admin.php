<?php
session_start();

// Verificar si el usuario ha iniciado sesión, de lo contrario redirigir al login
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}

// Obtener el usuario de la sesión
$usuario = $_SESSION['usuario'];

// Obtener la información del perfil del usuario (incluyendo la foto y el nombre) desde la base de datos
include 'conn.php';

if (!$conexion) {
    die("Error al conectar a la base de datos: " . mysqli_connect_error());
}

$consulta_perfil = "SELECT name, lastname, foto, role FROM usuarios WHERE email = '$usuario'";
$resultado_perfil = mysqli_query($conexion, $consulta_perfil);

if (!$resultado_perfil) {
    die("Error al obtener información del perfil del usuario: " . mysqli_error($conexion));
}

$fila_perfil = mysqli_fetch_assoc($resultado_perfil);
$nombre_usuario = $fila_perfil['name'] . ' ' . $fila_perfil['lastname'];
$imagen_perfil = $fila_perfil['foto'];
$rol_usuario = $fila_perfil['role']; // Obtener el rol del usuario

// Determinar el texto específico según el rol del usuario
if ($rol_usuario === 'editor') {
    $texto_rol = "Como editor, podrás actualizar en cualquier momento los datos que aparecerán en el perfil del diseñador, también podrás realizar el registro y actualización de las fuentes tipográficas que aparecerán asociadas a tu perfil. Si tienes alguna duda sobre el proceso de registro, puedes consultar la sección <a href='https://vanguardiasdiseno.org/site/repositorio/proceso' target='_blank'>indexación</a>.";
} elseif ($rol_usuario === 'autor') {
    $texto_rol = "Como Diseñador, podrás registrar y actualizar tu producción tipográfica, además de revisar tu perfil y actualizar la información asociada a tus datos personales. Si tienes alguna duda sobre el proceso de registro, puedes consultar la sección <a href='https://vanguardiasdiseno.org/site/repositorio/proceso' target='_blank'>indexación</a>.";
}
?>

<?php include 'header_nologo.php' ?>

<!-- Portada
================================================== -->
<section id="archive">
    <div class="container">

        <div class="entry">

            <div class="desktop-3 tablet-12 columns">
                <div class="page-desc" style="padding-top: 60px; ">
                    <img src="<?php echo $imagen_perfil; ?>" alt="Foto de perfil">
                    <span class="date">UASLP - CAVD</span>&#160;<i class="bi bi-arrow-right-circle"></i><br><hr>
                    <?php if ($rol_usuario === 'editor') { ?>
                        <a href="profile">Revisa tu perfil</a><hr>
                        <a href="update_profile">Actualizar perfil</a><hr>
                        <a href="db_listed">Listado de tipografías</a><hr>
                        <a href="capture_foundrie">Agregar Fundidora</a><hr>
                        <a href="capture_award">Registrar tipografía reconocida</a><hr>
                    <?php } elseif ($rol_usuario === 'autor') { ?>
                        <a href="profile">Revisa tu perfil</a><hr>
                        <a href="update_profile">Actualizar perfil</a><hr>
                        <a href="capture_font">Registrar tipografía</a><hr>
                    <?php } ?>
                    <a href="logout">Cerrar sesión</a><hr>
                </div><!-- // .box-meta -->
            </div><!-- // .desktop-3 -->

            <div class="desktop-7 tablet-12 nested columns">
                <div class="desktop-7 tablet-12 columns">
                    <div class="box-content">
                        <h2>Hola, <?php echo $nombre_usuario; ?></h2>
                        <p>
                            El proyecto Mapeo de la Producción Tipográfica Mexicana, tiene como finalidad la construcción de una base de datos exhaustiva y detallada
                            que funcione como un compendio completo de la rica y diversa producción tipográfica
                            generada en México durante el periodo de 2000 a 2025. La recolección de datos
                            se enfocará en aspectos específicos, incluyendo autores, estudios, obras, estilos,
                            función, familia tipográfica, atributos formales, y otros elementos pertinentes
                            que contribuyan a un análisis profundo y significativo.
                        </p>

                        <p><?php echo $texto_rol; ?></p>
                    </div><!-- // .box-content -->

                </div><!-- // .desktop-9 -->

                <div class="clear"></div>
            </div><!-- // .desktop-9 .nested -->

            <div class="clear"></div>
        </div><!-- // .entry -->

    </div><!-- // .container -->
</section><!-- // section#archive -->

<?php include 'footer.php' ?>
