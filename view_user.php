<?php
// Verificar si se recibió el parámetro 'id' en la URL
if (!isset($_GET['id'])) {
    die("ID de usuario no especificado.");
}

// Obtener el ID del usuario desde la URL
$id_usuario = $_GET['id'];

// Conexión a la base de datos (incluye el archivo de conexión o define las variables aquí)
include 'conn.php';

// Verificar la conexión
if (!$conexion) {
    die("Error al conectar a la base de datos: " . mysqli_connect_error());
}

// Consulta SQL para obtener los detalles del usuario por su ID
$consulta = "SELECT * FROM usuarios WHERE ID = $id_usuario";

// Ejecutar la consulta
$resultado = mysqli_query($conexion, $consulta);

// Verificar si se obtuvieron resultados
if (!$resultado) {
    die("Error al obtener detalles del usuario: " . mysqli_error($conexion));
}

// Obtener los detalles del usuario
$fila = mysqli_fetch_assoc($resultado);

// Identificar el rol del usuario
$rol_usuario = $fila['role']; // Suponiendo que el campo de rol se llama 'role' en la tabla de usuarios

// Cerrar la conexión
mysqli_close($conexion);
?>


<?php include 'header_nologo.php' ?>
<section id="archive">
    <div class="container">

        <div class="entry">
            <div class="desktop-3 tablet-12 columns">
                <div class="page-desc" style="margin-top: 40px;">
                    <span class="date"><img src="<?php echo $fila['foto']; ?>"></span>

                    <?php if ($rol_usuario !== 'editor') : ?>
                        <!-- Mostrar el contenido del módulo product.php -->
                        <?php include 'product.php'; ?>
                    <?php endif; ?>


                </div><!-- // .box-meta -->
            </div><!-- // .desktop-3 -->

            <div class="desktop-6 tablet-12 nested columns">
                <div class="desktop-6 tablet-12 columns">

                  <?php if ($rol_usuario === 'editor') { ?>
                  <div class="role">Editor</div>
              <?php } elseif ($rol_usuario === 'autor') { ?>
                  <div class="role">Diseñador</div>
              <?php } ?>

                          <div class="designer-fullname"><?php echo $fila['name'];?> <?php echo $fila['lastname']; ?></div>
                          <div class="cv"><?php echo $fila['cv_corto'];?></div>
</div><!-- // .desktop-7 -->

<div class="clear"></div>
</div><!-- // .box-tags -->

</div><!-- // .desktop-9 .nested -->

<div class="clear"></div>
</div><!-- // .entry -->

</div><!-- // .container -->
</section><!-- // section#archive -->


<?php include 'footer.php' ?>
