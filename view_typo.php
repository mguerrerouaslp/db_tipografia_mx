<?php
// Verificar si se recibió el parámetro 'id' en la URL
if (!isset($_GET['id'])) {
    die("ID del registro no especificado.");
}

// Obtener el ID del registro de fonts desde la URL
$id_registro = $_GET['id'];

// Conexión a la base de datos (incluye el archivo de conexión o define las variables aquí)
include 'conn.php';

// Verificar la conexión
if (!$conexion) {
    die("Error al conectar a la base de datos: " . mysqli_connect_error());
}

// Consulta SQL para obtener los detalles del registro de fonts
$consulta = "SELECT f.*, GROUP_CONCAT(CONCAT(a.award, ' ', a.year_award) ORDER BY a.year_award SEPARATOR ', ') AS premios
             FROM fonts f
             LEFT JOIN fonts_award a ON f.ID = a.id_font
             WHERE f.ID = $id_registro
             GROUP BY f.ID";

// Ejecutar la consulta
$resultado = mysqli_query($conexion, $consulta);

// Verificar si se obtuvieron resultados
if (!$resultado) {
    die("Error al obtener detalles del registro: " . mysqli_error($conexion));
}

// Obtener los detalles del registro
$fila = mysqli_fetch_assoc($resultado);
?>

<?php include'header_nologo.php'?>
<section id="archive">
    <div class="container">

        <div class="entry">
            <div class="desktop-2 tablet-12 columns">
                <div class="page-desc">
                  <h4></h4>
                    <span class="date">UASLP - CAVD</span>&#160;<i class="bi bi-arrow-right-circle"></i>
                </div><!-- // .box-meta -->
            </div><!-- // .desktop-3 -->

            <div class="desktop-7 tablet-12 nested columns">
                <div class="desktop-7 tablet-12 columns">
                        <div class="box-content">
                            Detalles
                            <div class="designer-fullname"><?php echo $fila['typo']; ?></div>
                                      <img src="<?php echo $fila['sample']; ?>">
                                      <h5><a href="<?php echo $fila['sample_cred']; ?>" target="_blank"><i class="bi bi-arrow-up-circle"></i> fuente img.</a></h5>
                                  <table>
                                  <tr>
                                      <th class="first-column">Año</th>
                                      <td><?php echo $fila['year_reg']; ?><?php if (!empty($fila['year_update'])): ?> - Actualizada: (&ensp;<?php echo $fila['year_update']; ?>&ensp;)<?php endif; ?></td>
                                  </tr>

                                  <?php if (!empty($fila['full_name2'])): ?>
                                    <tr>
                                      <th>Créditos</th>
                                        <td>
                                          <?php echo $fila['full_name2']; ?>
                                        </td>
                                      </tr><?php endif; ?>

                                  <tr>
                                    <th>Descripción</th>
                                    <td>
                                      <p style="margin: 0px;"><?php echo $fila['description']; ?></p>
                                    </td>
                                  </tr>
                                  <tr>
                                    <th>Distribución</th>
                                    <td>
                                      <?php echo $fila['distribution']; ?>
                                    </td>
                                  </tr>
                                  <tr>
                                    <th>Clasificación</th>
                                    <td>
                                      <?php if (!empty($fila['classification'])): ?>
                                          <?php echo $fila['classification']; ?><?php endif; ?>
                                    </td>
                                  </tr>
                                  <tr>
                                    <th>Familia(s)</th>
                                    <td>
                                      <?php echo $fila['vars_name']; ?>
                                    </td>
                                  </tr>
                                  <tr>
                                    <th>Formato</th>
                                    <td>
                                      <?php echo $fila['format']; ?>
                                    </td>
                                  </tr>
                                  <tr>
                                    <th>Estilos</th>
                                    <td>
                                      <?php echo $fila['vars_num']; ?>
                                    </td>
                                  </tr>
                                  <?php if (!empty($fila['axes_num'])): ?>
                                    <tr>
                                      <th>Variable Axis</th>
                                        <td>
                                          <?php echo $fila['axes_num']; ?>
                                        </td>
                                      </tr><?php endif; ?>
                                  <tr>
                                    <th>Lenguajes                                       </th>
                                    <td>
                                      <?php
                                      $words = str_word_count($fila['lang'], 1); // Obtener un array de palabras
                                      $limit = 40; // Límite de palabras a mostrar

                                      if (count($words) > $limit) {
                                          $shortLang = implode(' ', array_slice($words, 0, $limit)); // Unir las primeras palabras hasta el límite
                                          echo $shortLang . '...';
                                          $words = str_word_count($fila['lang']); // Contar las palabras en $fila['lang']
                                          echo " (" . $words . ")"; // Mostrar el número de palabras
                                           // Mostrar las palabras limitadas con puntos suspensivos
                                      } else {
                                          echo $fila['lang']; // Si hay menos palabras que el límite, mostrar todo el contenido
                                      }
                                      ?>

                                    </td>
                                  </tr>
                                  <tr>
                                    <th>Glifos</th>
                                    <td>
                                      <?php echo $fila['num_characters']; ?>
                                    </td>
                                  </tr>
                                  <tr>
                                  <th>Set</th>
                                    <td>
                                      <?php echo $fila['type']; ?>
                                    </td>
                                  </tr>

                                  <?php if (!empty($fila['studio'])): ?>
                                  <tr>
                                  <th>Estudio</th>
                                      <td>
                                          <?php echo $fila['studio']; ?>
                                      </td>
                                  </tr>
                                  <?php endif; ?>

                                  <?php if (!empty($fila['publisher'])): ?>
                                  <tr>
                                  <th>Fundidora</th>
                                      <td><?php
                                          if (!empty($fila['foundry_id'])) {
                                              echo "<a href='view_foundry.php?id={$fila['foundry_id']}'>{$fila['publisher']}</a>";
                                          } else {
                                              echo $fila['publisher'];
                                          }?>
                                      </td>
                                  </tr>
                                  <?php endif; ?>

                                  <?php if (!empty($fila['client'])): ?>
                                  <tr>
                                  <th>Cliente</th>
                                      <td>
                                          <?php echo $fila['client']; ?>
                                      </td>
                                  </tr>
                                  <?php endif; ?>

                                  <?php if (!empty($fila['status'])): ?>
                                  <tr>
                                  <th>Estatus</th>
                                      <td>
                                          <?php echo $fila['status']; ?>
                                      </td>
                                  </tr>
                                  <?php endif; ?>

                                  <?php if (!empty($fila['url'])): ?>
                                  <tr>
                                  <th>Enlace</th>
                                      <td>
                                          <a href="<?php echo $fila['url']; ?>" target="_blank"><?php echo $fila['url']; ?></a>
                                      </td>
                                  </tr>
                                  <?php endif; ?>

                                  <?php if (!empty($fila['premios'])): ?>
                                    <tr>
                                    <th>Reconocimientos</th>
                                        <td>
                                       <?php echo $fila['premios']; ?>
                                    </td>
                                </tr>
                                  <?php endif; ?>

                                  <?php if (!empty($fila['keywords'])): ?>
                                    <tr>
                                    <th>Palabras clave</th>
                                        <td>
                                       <?php echo $fila['keywords']; ?>
                                    </td>
                                </tr>
                                  <?php endif; ?>

                              </table>
                          </div><!-- // .box-content -->

                        </div><!-- // .desktop-7 -->

                        <div class="clear"></div>
                        </div><!-- // .box-tags -->

                        </div><!-- // .desktop-9 .nested -->

                        <div class="clear"></div>
                        </div><!-- // .entry -->

                        </div><!-- // .container -->
                        </section><!-- // section#archive -->

<?php
// Cerrar la conexión
mysqli_close($conexion);
?>

<?php include 'footer.php' ?>
