<?php
function getAutores($foundry_id, $conexion)
{
    $consulta_autores = "SELECT DISTINCT fu.id_usuario, f.full_name
                         FROM fonts_usuarios fu
                         INNER JOIN fonts f ON fu.id_usuario = f.userid
                         WHERE fu.id_foundrie = $foundry_id
                         ORDER BY f.full_name ASC";

    $resultado_autores = mysqli_query($conexion, $consulta_autores);

    if (!$resultado_autores) {
        return "Error al obtener los autores asociados al foundry_id: " . mysqli_error($conexion);
    }


    if (mysqli_num_rows($resultado_autores) > 0) {
      $html = "<b>Diseñadores asociados:</b><br><hr>";
        while ($fila_autores = mysqli_fetch_assoc($resultado_autores)) {
            $html .= "<a href='view_user.php?id={$fila_autores['id_usuario']}'> {$fila_autores['full_name']}</a><br>";
        }
    }

    return $html;
}
?>
