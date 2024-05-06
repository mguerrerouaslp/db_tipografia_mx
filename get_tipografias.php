<?php
// Obtener el ID de la fundidora del URL
$id_foundry = isset($_GET["id"]) ? $_GET["id"] : die("ID de fundidora no proporcionado en el URL.");

// Conexión a la base de datos (incluye el archivo de conexión o define las variables aquí)
include 'conn.php';

// Verificar la conexión y realizar consultas
if (!$conexion) {
    die("Error al conectar a la base de datos: " . mysqli_connect_error());
}

// Consulta para obtener las tipografías asociadas a la fundidora desde la tabla fonts_usuarios
$consulta_tipografias = "SELECT DISTINCT fonts_usuarios.id_font, fonts.year_reg, fonts.typo
                         FROM fonts_usuarios
                         INNER JOIN fonts ON fonts_usuarios.id_font = fonts.ID
                         WHERE fonts_usuarios.id_foundrie = $id_foundry
                         AND fonts.draft != 1
                         ORDER BY fonts.year_reg ASC";

$resultado_tipografias = mysqli_query($conexion, $consulta_tipografias);

// Verificar si se obtuvieron resultados
if (!$resultado_tipografias) {
    die("Error al obtener las tipografías asociadas a la fundidora: " . mysqli_error($conexion));
}
?>

<div style="margin-top: 40px;">
    <?php if (mysqli_num_rows($resultado_tipografias) > 0) { ?>
        <b>Producción tipográfica registrada:</b><br><hr>
        <?php while ($fila_tipografia = mysqli_fetch_assoc($resultado_tipografias)) { ?>
            <a href="view_typo.php?id=<?php echo $fila_tipografia['id_font']; ?>" target="_blank">
                <?php echo $fila_tipografia['year_reg']; ?> - <?php echo $fila_tipografia['typo']; ?>
            </a><br>
        <?php } ?>
    <?php } else { ?>
        No se han encontrado tipografías asociadas a esta fundidora.
    <?php } ?>
</div>

<?php
// Cerrar la conexión
mysqli_close($conexion);
?>
