<?php
// Insertar palabras clave en la tabla keywords
foreach ($keywords_array as $keyword) {
    $keyword = trim($keyword);
    if (!empty($keyword)) {
        $consulta_insertar_keyword = "INSERT INTO keywords (keyword, id_keywords) VALUES ('$keyword', '$font_id')";
        if (!mysqli_query($conexion, $consulta_insertar_keyword)) {
            echo "Error al insertar palabra clave: " . mysqli_error($conexion);
        }
    }
}

?>
