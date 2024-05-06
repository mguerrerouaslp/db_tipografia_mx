<?php
// Incluir archivo de conexión a la base de datos
include 'conn.php';

// Consulta SQL para obtener los datos de las tablas fonts_award
$consulta = "SELECT typo_award, cat_award, year_award, full_name, award, user_id, id_font, source
             FROM fonts_award
             ORDER BY award ASC, year_award ASC";



// Ejecutar la consulta
$resultado = mysqli_query($conexion, $consulta);

// Verificar si se obtuvieron resultados
if (!$resultado) {
    die("Error al obtener datos de la tabla fonts_award: " . mysqli_error($conexion));
}

$total_registros = mysqli_num_rows($resultado);
?>

<?php include 'header_nologo.php' ?>


<section id="archive">
    <div class="container">
        <div class="entry">
            <div class="desktop-12 tablet-12 nested columns">
                <div class="listado">
                    <div class="box-content" style="margin-left: 20px;">
                      <h4><i class="bi bi-award-fill"></i> Listado de tipografías premiadas</h4>
  <?php
  // Variable para almacenar el nombre del premio actual
  $current_award = '';

  // Recorremos los resultados de la consulta
  while ($fila = mysqli_fetch_assoc($resultado)) {
      // Si es un nuevo premio, mostrar el título
      if ($fila['award'] != $current_award) {
          echo "<table>
            <tr>
                    <th><br> <b>{$fila['award']}</b></th></table>";
          $current_award = $fila['award'];
      }
  ?>
  <!-- Mostrar los datos de cada tabla -->
  <table>
      <tr>
          <td style="width: 200px;"><?php if (!empty($fila['id_font'])): ?>
    <a href="view_typo.php?id=<?php echo $fila['id_font']; ?>" target="_blank"><b><?php echo $fila['typo_award']; ?></b></a>
<?php else: ?>
    <b><?php echo $fila['typo_award']; ?></b>
<?php endif; ?></td>
          </td>
          <td style="width: 80px;"><?php echo $fila['year_award']; ?></td>
          <td style="width: 200px;"><?php echo $fila['cat_award']; ?></td>
          <td style="width: 250px;"><?php if (!empty($fila['user_id'])): ?>
    <a href="view_user.php?id=<?php echo $fila['user_id']; ?>" target="_blank"><?php echo $fila['full_name']; ?></a>
<?php else: ?>
    <?php echo $fila['full_name']; ?>
<?php endif; ?></td>
<td><?php if (!empty($fila['source'])): ?>
<a href="<?php echo $fila['source']; ?>" target="_blank">Referencia</a>
<?php else: ?>
<?php endif; ?></td>
      </tr>
  </table>
  <?php } ?>
  <?php echo "Mostrando $total_registros datos indexados";?>

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
