<?php



// Obtener la información del perfil del usuario (incluyendo la foto y el nombre) desde la base de datos
include 'conn.php';

// Verificar la conexión
if (!$conexion) {
    die("Error al conectar a la base de datos: " . mysqli_connect_error());
}


// Consulta para obtener el total de fundidoras tipográficas indexadas
$consulta_total_foundries = "SELECT COUNT(*) AS total_foundries FROM foundries";
$resultado_total_foundries = mysqli_query($conexion, $consulta_total_foundries);
$fila_total_foundries = mysqli_fetch_assoc($resultado_total_foundries);
$total_foundries = $fila_total_foundries['total_foundries'];

// Consulta para obtener el total de fundidoras tipográficas mexicanas
$consulta_foundries_mx = "SELECT COUNT(*) AS total_foundries_mx FROM foundries WHERE is_mx = 1";
$resultado_foundries_mx = mysqli_query($conexion, $consulta_foundries_mx);
$fila_foundries_mx = mysqli_fetch_assoc($resultado_foundries_mx);
$total_foundries_mx = $fila_foundries_mx['total_foundries_mx'];

// Consulta para obtener el total de fundidoras tipográficas no mexicanas
$total_foundries_no_mx = $total_foundries - $total_foundries_mx;

// Consulta para obtener el total de registros en fonts
$consulta_total_fonts = "SELECT COUNT(*) AS total_fonts FROM fonts WHERE draft <> 1";
$resultado_total_fonts = mysqli_query($conexion, $consulta_total_fonts);
$fila_total_fonts = mysqli_fetch_assoc($resultado_total_fonts);
$total_fonts = $fila_total_fonts['total_fonts'];

// Consulta para obtener el total de usuarios en cdmx
$consulta_total_usermx = "SELECT COUNT(*) AS total_usermx FROM usuarios WHERE role = 'autor' AND ciudad = 'Ciudad de México' AND country = 'México' ";
$resultado_total_usermx = mysqli_query($conexion, $consulta_total_usermx);
$fila_total_usermx = mysqli_fetch_assoc($resultado_total_usermx);
$total_usermx = $fila_total_usermx['total_usermx'];

// Consulta para obtener el total de usuarios en nocdmx
$consulta_total_usernomx = "SELECT COUNT(*) AS total_usernomx FROM usuarios WHERE role = 'autor' AND ciudad <> 'Ciudad de México' AND country = 'México' ";
$resultado_total_usernomx = mysqli_query($conexion, $consulta_total_usernomx);
$fila_total_usernomx = mysqli_fetch_assoc($resultado_total_usernomx);
$total_usernomx = $fila_total_usernomx['total_usernomx'];

// Consulta para obtener el total de diseñadores registrados
$consulta_total_designers = "SELECT COUNT(DISTINCT ID) AS total_designers FROM usuarios WHERE role = 'autor' AND country = 'México'";
$resultado_total_designers = mysqli_query($conexion, $consulta_total_designers);
$fila_total_designers = mysqli_fetch_assoc($resultado_total_designers);
$total_designers = $fila_total_designers['total_designers'];

// Consulta para obtener el total de diseñadores emergentes registrados
$consulta_total_emergente = "SELECT COUNT(DISTINCT ID) AS total_emergente FROM usuarios WHERE role = 'autor' AND clase = 'emergente' AND country = 'México'";
$resultado_total_emergente = mysqli_query($conexion, $consulta_total_emergente);
$fila_total_emergente = mysqli_fetch_assoc($resultado_total_emergente);
$total_emergente = $fila_total_emergente['total_emergente'];

// Consulta para obtener el total de diseñadores profesionales registrados
$consulta_total_profesional = "SELECT COUNT(DISTINCT ID) AS total_profesional FROM usuarios WHERE role = 'autor' AND clase IS NULL AND country = 'México'";
$resultado_total_profesional = mysqli_query($conexion, $consulta_total_profesional);
$fila_total_profesional = mysqli_fetch_assoc($resultado_total_profesional);
$total_profesional = $fila_total_profesional['total_profesional'];

// Consulta para obtener el total de tipografías premiadas
$consulta_total_awards = "SELECT COUNT(*) AS total_awards FROM fonts_award WHERE id_font IS NOT NULL;";
$resultado_total_awards = mysqli_query($conexion, $consulta_total_awards);
$fila_total_awards = mysqli_fetch_assoc($resultado_total_awards);
$total_awards = $fila_total_awards['total_awards'];

// Consulta para obtener el total de registros en fonts_award donde region es internacional y id_font no es nulo
$consulta_total_fonts_award = "SELECT COUNT(*) AS total_award_entries FROM fonts_award WHERE region = 'internacional' AND id_font IS NOT NULL";
$resultado_total_fonts_award = mysqli_query($conexion, $consulta_total_fonts_award);
$fila_total_fonts_award = mysqli_fetch_assoc($resultado_total_fonts_award);
$total_award_entries = $fila_total_fonts_award['total_award_entries'];

// Consulta para obtener el total de registros en fonts_award donde region es nacional y id_font no es nulo
$consulta_total_fonts_awardmx = "SELECT COUNT(*) AS total_award_entriesmx FROM fonts_award WHERE region = 'nacional' AND id_font IS NOT NULL";
$resultado_total_fonts_awardmx = mysqli_query($conexion, $consulta_total_fonts_awardmx);
$fila_total_fonts_awardmx = mysqli_fetch_assoc($resultado_total_fonts_awardmx);
$total_award_entriesmx = $fila_total_fonts_awardmx['total_award_entriesmx'];


// Consulta para obtener el total de tipografías por cada clasificación
$consulta_total_by_classification = "SELECT classification, COUNT(*) AS total FROM fonts WHERE draft <> 1 GROUP BY classification";
$resultado_total_by_classification = mysqli_query($conexion, $consulta_total_by_classification);
$total_by_classification = array();
while ($fila = mysqli_fetch_assoc($resultado_total_by_classification)) {
    $total_by_classification[$fila['classification']] = $fila['total'];
}




// Cerrar la conexión a la base de datos
mysqli_close($conexion);
?>

<?php include 'header_nologo.php' ?>


<!-- Portada
================================================== -->
<section id="archive">
    <div class="container">

        <div class="entry">

            <div class="desktop-12 tablet-12 nested columns">
                    <div class="box-content" style="margin-left: 20px;">
                            <table>
                                    <thead>
                                        <tr >
                                            <th class="first-column" style="width: 30%;"><h2><?php echo $total_foundries; ?></h2> FUNDIDORAS TIPOGRÁFICAS INDEXADAS </th>
                                            <td ><b><?php echo $total_foundries_mx; ?></b> Fundidoras tipográficas Mexicanas <br> <b><?php echo $total_foundries_no_mx; ?></b> Fundidoras tipográficas Internacionales </td>
                                        </tr>
                                        <tr >
                                            <th ><h2><?php echo $total_fonts; ?></h2> FICHAS TIPOGRÁFICAS REGISTRADAS</th>
                                            <td ><b><?php echo isset($total_by_classification['Texto']) ? $total_by_classification['Texto'] : 0; ?></b> Tipografías para texto <b> | <?php echo isset($total_by_classification['Títulos']) ? $total_by_classification['Títulos'] : 0; ?></b> Tipografías para títulos | <b><?php echo isset($total_by_classification['Script']) ? $total_by_classification['Script'] : 0; ?></b> Tipografías script<br>
                                            <b><?php echo isset($total_by_classification['Experimental']) ? $total_by_classification['Experimental'] : 0; ?></b> Tipografías experimentales | <b><?php echo isset($total_by_classification['Dingbats']) ? $total_by_classification['Dingbats'] : 0; ?></b> Tipografías dingbats</td>

                                        </tr>
                                        <tr >
                                            <th><h2><?php echo $total_designers; ?></h2> DISEÑADORES REGISTRADOSS</th>
                                            <td><b><?php echo $total_usermx; ?></b> Diseñadores de la Ciudad de México | <b><?php echo $total_usernomx; ?></b> Diseñadores del resto de México<br>
                                            <b><?php echo $total_profesional; ?></b> Diseñadores | <b><?php echo $total_emergente; ?></b> Diseñadores emergentes</td>

                                        </tr>
                                        <tr >
                                            <th ><h2><?php echo $total_awards; ?></h2> TIPOGRAFÍAS RECONOCIDAS</th>
                                            <td><b><?php echo $total_award_entries; ?></b> Reconocimientos internacionales <br><b><?php echo $total_award_entriesmx; ?></b> Reconocimientos nacionales</td>

                                        </tr>
                                    </thead>
                            </table>
                    </div><!-- // .box-content -->
            </div><!-- // .desktop-12 tablet-12 nested columns -->

            <div class="clear"></div>
        </div><!-- // .entry -->

    </div><!-- // .container -->
</section><!-- // section#archive -->





<?php
// Cerrar la conexión
mysqli_close($conexion);
?>
<?php include 'footer.php' ?>
