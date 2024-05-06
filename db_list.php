<?php



// Obtener la información del perfil del usuario (incluyendo la foto y el nombre) desde la base de datos
include 'conn.php';

// Verificar la conexión
if (!$conexion) {
    die("Error al conectar a la base de datos: " . mysqli_connect_error());
}

// Consulta SQL para obtener los datos de la tabla fonts, ordenados por la columna 'typo'
$consulta = "SELECT * FROM fonts WHERE draft <> 1 ORDER BY year_reg";

$consulta_perfil = "SELECT role FROM usuarios WHERE email = '$usuario'";
$resultado_perfil = mysqli_query($conexion, $consulta_perfil);

// Ejecutar la consulta
$resultado = mysqli_query($conexion, $consulta);

// Verificar si se obtuvieron resultados
if (!$resultado) {
    die("Error al obtener datos de la tabla fonts: " . mysqli_error($conexion));
}

if (!$resultado_perfil) {
    die("Error al obtener información del perfil del usuario: " . mysqli_error($conexion));
}
// Obtener el número total de registros
$total_registros = mysqli_num_rows($resultado);



?>

<?php include'header_nologo.php'?>

<!-- Portada
================================================== -->
<section id="archive">
    <div class="container">

        <div class="entry">

            <div class="desktop-12 tablet-12 nested columns">
                <div class="listado">
                        <div class="box-content" style="margin-left: 20px;">
                        
<h4>Listado de registros</h4>

<table>
    <thead>
        <tr style="background-color: #f5f5f5;">
            <th style="width: 200px;" class="first-column">Tipografía</th>
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
    <tbody>
        <?php while ($fila = mysqli_fetch_assoc($resultado)) : ?>
            <tr>
                <th><a href="view_typo.php?id=<?php echo $fila['id']; ?>"><?php echo $fila['typo']; ?></a></td>
                <td><?php echo $fila['year_reg']; ?><?php if (!empty($fila['year_update'])): ?> - <?php echo $fila['year_update']; ?><?php endif; ?></td>
                <td><a href="view_user.php?id=<?php echo $fila['userid']; ?>"><?php echo $fila['full_name']; ?></a></td>
                <td><?php echo $fila['vars_num']; ?></td>
                <td><?php echo $fila['classification']; ?></td>
                <td><?php echo $fila['distribution']; ?></td>
                <td><?php
                    if (!empty($fila['foundry_id'])) {
                        echo "<a href='view_foundry.php?id={$fila['foundry_id']}'>{$fila['publisher']}</a>";
                    } else {
                        echo $fila['publisher'];
                    }?>
                </td>
                <td><a href="<?php echo $fila['url']; ?>" target="_blank">Enlace</a></td>
                <td><?php echo $fila['verif'] == 1 ? "<i class='bi bi-check-lg'></i>" : "-"; ?></td>
            </tr>
        <?php endwhile; ?>
    </tbody>

</table>
<?php echo "Mostrando $total_registros fuentes tipográficas indexadas";?>
</div><!-- // .box-content -->
</div><!-- // .desktop-7 -->

<div class="clear"></div>



</div><!-- // .desktop-10 .nested -->

<div class="clear"></div>
</div><!-- // .entry -->

</div><!-- // .container -->
</section><!-- // section#archive -->





<?php
// Cerrar la conexión
mysqli_close($conexion);
?>

<?php include 'footer.php' ?>
