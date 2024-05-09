<?php
// Incluir el archivo de conexión a la base de datos
include 'conn.php';

// Verificar la conexión
if (!$conexion) {
    die("Error al conectar a la base de datos: " . mysqli_connect_error());
}

// Consulta SQL para obtener los datos de la tabla fonts_usuarios junto con el nombre y apellido del usuario y otros campos relacionados, ordenados por nombre completo
$consulta = "SELECT fu.*, CONCAT(u.name, ' ', u.lastname) AS designer_name,
             f.id, f.typo, f.year_reg, f.vars_num, f.classification, f.distribution, foundries.name AS foundry_name, f.url, f.verif
             FROM fonts_usuarios fu
             LEFT JOIN usuarios u ON fu.id_usuario = u.id
             LEFT JOIN fonts f ON fu.id_font = f.id
             LEFT JOIN foundries ON fu.id_foundrie = foundries.id
             WHERE f.draft != 1 AND u.country ='México'
             ORDER BY designer_name, f.year_reg";

// Ejecutar la consulta
$resultado = mysqli_query($conexion, $consulta);

// Verificar si se obtuvieron resultados
if (!$resultado) {
    die("Error al obtener datos de la tabla fonts_usuarios: " . mysqli_error($conexion));
}
// Obtener el número total de registros
$total_registros = mysqli_num_rows($resultado);
?>

<?php include 'header_nologo.php' ?>

<section id="archive">
    <div class="container">
        <div class="entry">
            <div class="desktop-12 tablet-12 nested columns">
                <div class="listado">
                    <div class="box-content" style="margin-left: 20px;">
                        <h4>Listado de fuentes tipográficas</h4>
                        <table>
                            <thead>
                                <tr>
                                    <td style="width: 200px;" class="first-column">Tipografía</td>
                                    <td>Año</td>
                                    <td>Variantes</td>
                                    <td>Clasificación</td>
                                    <td>Distribución</td>
                                    <td>Fundidora</td>
                                    <td>URL</td>
                                    <td>Verificado</td>
                                </tr>
                            </thead>

                            <?php
                            $current_designer = '';
                            while ($fila = mysqli_fetch_assoc($resultado)) :
                                if ($fila['designer_name'] != $current_designer) {
                                    $current_designer = $fila['designer_name'];
                                    // Agregar un enlace al nombre del autor
                                    echo "<thead><tr><th colspan='9'><br><br><i class='bi bi-arrow-down-square-fill'></i>&emsp;<a href='view_user.php?id={$fila['id_usuario']}'>{$fila['designer_name']}</a></th></tr></thead>";
                                }
                            ?>

    <tr>
        <th><a href="view_typo.php?id=<?php echo $fila['id']; ?>"><?php echo $fila['typo']; ?></a></th>
        <td><?php echo $fila['year_reg']; ?><?php if (!empty($fila['year_update'])): ?> - <?php echo $fila['year_update']; ?><?php endif; ?></td>
        <td><?php echo $fila['vars_num']; ?></td>
        <td><?php echo $fila['classification']; ?></td>
        <td><?php echo $fila['distribution']; ?></td>
        <td><?php echo $fila['foundry_name']; ?></td>
        <td><a href="<?php echo $fila['url']; ?>" target="_blank">Enlace</a></td>
        <td><?php echo $fila['verif'] == 1 ? "<i class='bi bi-check-lg'></i>" : "-"; ?></td>
    </tr>
    <?php endwhile; ?>
    </tbody>
    </table>
    <?php echo "Mostrando $total_registros fuentes tipográficas asociadas a diversos diseñadores"; ?>
</div><!-- // .box-content -->
</div><!-- // .listado -->
</div><!-- // .desktop-12 .tablet-12 .nested .columns -->
</div><!-- // .entry -->
</div><!-- // .container -->
</section><!-- // section#archive -->

<?php
mysqli_close($conexion);
?>

<?php include 'footer.php' ?>
