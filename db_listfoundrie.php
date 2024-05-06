<?php
// Obtener la información del perfil del usuario (incluyendo la foto y el nombre) desde la base de datos
include 'conn.php';

// Verificar la conexión
if (!$conexion) {
    die("Error al conectar a la base de datos: " . mysqli_connect_error());
}

$consulta = "SELECT fonts.*, foundries.name AS foundry_name
             FROM fonts
             LEFT JOIN foundries ON fonts.foundry_id = foundries.id
             WHERE fonts.foundry_id IS NOT NULL
             ORDER BY foundries.name, fonts.year_reg";


// Ejecutar la consulta
$resultado = mysqli_query($conexion, $consulta);

// Verificar si se obtuvieron resultados
if (!$resultado) {
    die("Error al obtener datos de la tabla fonts: " . mysqli_error($conexion));
}

// Obtener el número total de registros
$total_registros = mysqli_num_rows($resultado);
?>

<?php include 'header_nologo.php' ?>

<!-- Portada
================================================== -->
<section id="archive">
    <div class="container">
        <div class="entry">
            <div class="desktop-12 tablet-12 nested columns">
                <div class="listado">
                    <div class="box-content" style="margin-left: 20px;">
                        <h4>Listado total de fundidoras</h4>
                        <table>
                            <thead>
                                <tr>
                                    <th class="first-column">Tipografía</th>
                                    <td>Año</td>
                                    <td>Diseñador</td>
                                    <td>Variantes</td>
                                    <td>Clasificación</td>
                                    <td>Distribución</td>
                                    <td>Fundidora</td>
                                    <td>URL</td>
                                    <td>Verificado</td>
                                </tr>
                            </thead>
                            <?php
                            $current_foundry = '';
                            while ($fila = mysqli_fetch_assoc($resultado)) :
                                // Si es una nueva fundidora, mostrar el nombre como encabezado
                                if ($fila['foundry_name'] != $current_foundry) {
                                  $foundry_id = $fila['foundry_id'];
                                    echo "<thead><tr><th colspan='9' ><br><br><i class='bi bi-arrow-down-square-fill'></i>&emsp;<a href='view_foundry.php?id=$foundry_id'>{$fila['foundry_name']}</a></th></tr></thead>";
                                    $current_foundry = $fila['foundry_name'];
                                }
                            ?>
                            <tbody>
                                <tr>
                                    <th style="width: 200px;"><a href="view_typo.php?id=<?php echo $fila['id']; ?>"><?php echo $fila['typo']; ?></a></th>
                                    <td><?php echo $fila['year_reg']; ?><?php if (!empty($fila['year_update'])): ?> - <?php echo $fila['year_update']; ?><?php endif; ?></td>
                                    <td><a href="view_user.php?id=<?php echo $fila['userid']; ?>"><?php echo $fila['full_name']; ?></a></td>
                                    <td><?php echo $fila['vars_num']; ?></td>
                                    <td><?php echo $fila['classification']; ?></td>
                                    <td><?php echo $fila['distribution']; ?></td>
                                    <td><?php echo $fila['foundry_name']; ?></td>
                                    <td><a href="<?php echo $fila['url']; ?>" target="_blank">Enlace</a></td>
                                    <td><?php echo $fila['verif'] == 1 ? "<i class='bi bi-check-lg'></i>" : "-"; ?></td>
                                </tr>
                            </tbody>
                            <?php endwhile; ?>
                        </table>
                        <?php echo "Mostrando $total_registros fuentes tipográficas asociadas a diversas fundidoras"; ?>
                    </div><!-- // .box-content -->
                </div><!-- // .listado -->
            </div><!-- // .desktop-12 .tablet-12 .nested .columns -->
        </div><!-- // .entry -->
    </div><!-- // .container -->
</section><!-- // section#archive -->

<?php
// Cerrar la conexión
mysqli_close($conexion);
?>

<?php include 'footer.php' ?>
