<?php
// Obtener el ID del usuario del URL
$id_usuario = isset($_GET["id"]) ? $_GET["id"] : die("ID de usuario no proporcionado en el URL.");

// Conexión a la base de datos (incluye el archivo de conexión o define las variables aquí)
include 'conn.php';

// Verificar la conexión y realizar consultas
// Verificar la conexión y realizar consultas
if (!$conexion) {
    die("Error al conectar a la base de datos: " . mysqli_connect_error());
}

// Consulta para obtener los registros de la tabla fonts_usuarios asociados al usuario
$consulta_registros = "SELECT fonts.ID AS id_font, fonts.typo, fonts.year_reg
                      FROM fonts_usuarios
                      INNER JOIN fonts ON fonts_usuarios.id_font = fonts.ID
                      WHERE fonts_usuarios.id_usuario = $id_usuario
                      AND fonts.draft != 1
                      ORDER BY fonts.year_reg ASC";

$resultado_registros = mysqli_query($conexion, $consulta_registros);

// Verificar si se obtuvieron resultados
if (!$resultado_registros) {
    die("Error al obtener registros del usuario: " . mysqli_error($conexion));
}
?>

<div style="margin-top: 40px;">
    <?php if (mysqli_num_rows($resultado_registros) > 0) : ?>
      <b>Producción tipográfica registrada:</b><br><hr>
        <?php while ($fila_registro = mysqli_fetch_assoc($resultado_registros)) : ?>
            <a href="view_typo.php?id=<?php echo $fila_registro['id_font']; ?>">
                <?php echo $fila_registro['year_reg']; ?> - <?php echo $fila_registro['typo']; ?>
            </a><br>
        <?php endwhile; ?>
    <?php else : ?>

    <?php endif; ?>
</div>

<?php
// Cerrar la conexión
mysqli_close($conexion);
?>
