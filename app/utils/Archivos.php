<?php

class Archivos
{
    static public function darAltaImagen($objeto, $imagen, $nombre_imagen, $ruta_imagen){
        if (isset($imagen) && $imagen['error'] === UPLOAD_ERR_OK) {
            $extension_imagen = explode(".", strtolower($imagen['name']));

            $destino_imagen = $ruta_imagen . $nombre_imagen . "." . $extension_imagen[1];
            if (!move_uploaded_file($imagen["tmp_name"], $destino_imagen)) {
                return null;
            } else {
                $objeto->imagen = $destino_imagen;
                return $destino_imagen;
            }
        } 
    }
}
