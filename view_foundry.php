<?php
// Verificar si se recibió el parámetro 'id' en la URL
if (!isset($_GET['id'])) {
    die("ID fundidora no especificada.");
}
// Obtener el ID de la fundidora desde la URL
$id_foundrie = $_GET['id'];

// Conexión a la base de datos (incluye el archivo de conexión o define las variables aquí)
include 'conn.php';
require_once 'get_autores.php'; // Incluir el módulo de autores

// Verificar la conexión
if (!$conexion) {
    die("Error al conectar a la base de datos: " . mysqli_connect_error());
}

// Consulta SQL para obtener los detalles de la fundidora por su ID
$consulta = "SELECT * FROM foundries WHERE id = $id_foundrie";

// Ejecutar la consulta
$resultado = mysqli_query($conexion, $consulta);

// Verificar si se obtuvieron resultados
if (!$resultado) {
    die("Error al obtener detalles de la fundidora: " . mysqli_error($conexion));
}

// Obtener la fila de resultados
$fila = mysqli_fetch_assoc($resultado);
$resultado_autores = mysqli_query($id_foundrie, $conexion);
$html_autores = getAutores($id_foundrie, $conexion); // Obtener autores asociados
// Cerrar la conexión
mysqli_close($conexion);

?>



<?php include 'header_nologo.php' ?>
<section id="archive">
    <div class="container">

        <div class="entry">
            <div class="desktop-3 tablet-12 columns">
                <div class="page-desc" style="padding-top:20px;">
                    <span class="date">
                        <?php include 'get_tipografias.php'; ?><br>
                        <?php echo $html_autores; ?>
                </div><!-- // .box-meta -->
            </div><!-- // .desktop-3 -->

            <div class="desktop-6 tablet-12 nested columns">
                <div class="desktop-6 tablet-12 columns">

                          <div class="designer-fullname"><?php echo $fila['name'];?></div>
                          <p><?php echo $fila['description'];?></p>
                          <p><b>Ciudad </b><?php echo $fila['ciudad'];?><br>
                          <b>Website </b><a href="<?php echo $fila['sitio_web'];?>" target="_blank"><?php echo $fila['sitio_web'];?></a></p>

</div><!-- // .desktop-7 -->

<div class="clear"></div>
</div><!-- // .box-tags -->

</div><!-- // .desktop-9 .nested -->

<div class="clear"></div>
</div><!-- // .entry -->

</div><!-- // .container -->
</section><!-- // section#archive -->


<?php include 'footer.php' ?>
