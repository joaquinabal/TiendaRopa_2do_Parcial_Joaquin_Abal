<?php

class Validador
{
    static public function validarNombre($nombre)
    {
        if(is_string($nombre)){
            return true;
        } else {
            return false;
        }
    }
    
    static public function validarPrecio($precio)
    {
        if(is_numeric($precio)){
            return true;
        } else {
            return false;
        }
    }
    
    static public function validarTipo($tipo)
    {
        $tipos_array = ['CAMISETA', 'PANTALON'];
        if(in_array(strtoupper($tipo), $tipos_array)){
            return true;
        } else {
            return false;
        }
    }
    
    static public function validarTalla($talla)
    {
        $tallas_array = ['S', 'M', 'L'];
        if(in_array(strtoupper($talla), $tallas_array)){
            return true;
        } else {
            return false;
        }
    }
    
    static public function validarColor($color)
    {
        if(is_string($color)){
            return true;
        } else {
            return false;
        }
    }
    
    static public function validarStock($stock)
    {
        if(is_numeric($stock)){
            return true;
        } else {
            return false;
        }
    }
    
    static public function validarImagen($imagen)
    {
        if($imagen['error'] === UPLOAD_ERR_OK){
            return true;
        } else {
            return false;
        }
    }
    
    static public function validarEmail($email){
        if(filter_var($email, FILTER_VALIDATE_EMAIL)){
            return true;
        } else {
            return false;
        }
    }
    
        static public function validarCantidad($cantidad)
    {
        if(is_numeric($cantidad)){
            return true;
        } else {
            return false;
        }
    }
    
    static public function validarNroPedido($pedido)
    {
        if(is_numeric($pedido) && $pedido < 10000){
            return true;
        } else {
            return false;
        }
    }

    static public function validarFecha($fecha, $formato = 'Y-m-d')
{
    $timestamp = strtotime($fecha);
    
    if (!$timestamp) {
        return false;
    }
    
   if(date($formato, $timestamp) === $fecha){
       return true;
   }
}
}