<?php

class Producto {
    public $id;
    public $nombre;
    public $tipo;
    public $talla;
    public $color;
    public $precio;
    public $stock;
    public $imagen;
    public $fecha_baja;
     public function crearProducto()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO productos (nombre, tipo, talla, color, precio, stock, imagen) VALUES (:nombre, :tipo, :talla, :color, :precio, :stock, :imagen)");
        $consulta->bindValue(':nombre', $this->nombre, PDO::PARAM_STR);
        $consulta->bindValue(':tipo', $this->tipo, PDO::PARAM_STR);
        $consulta->bindValue(':talla', $this->talla, PDO::PARAM_STR);
        $consulta->bindValue(':color', $this->color, PDO::PARAM_STR);
        $consulta->bindValue(':precio', $this->precio, PDO::PARAM_STR);
        $consulta->bindValue(':stock', $this->stock, PDO::PARAM_INT);
        $consulta->bindValue(':imagen', $this->imagen, PDO::PARAM_STR);
        $consulta->execute();

        return $objAccesoDatos->obtenerUltimoId();
    }

   public static function obtenerTodos()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT nombre, tipo, talla, color, precio, stock, imagen FROM productos");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function obtenerSegunNombreYTipo($nombre, $tipo){
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id, nombre, tipo, talla, color, precio, stock, imagen FROM productos WHERE nombre = :nombre AND tipo = :tipo");
        $consulta->bindValue(':nombre', $nombre, PDO::PARAM_STR);
        $consulta->bindValue(':tipo', $tipo, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchObject('Producto');
    }

    public static function obtenerSegunNombreTipoYTalla($nombre, $tipo, $talla){
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id, nombre, tipo, talla, color, precio, stock, imagen FROM productos WHERE nombre = :nombre AND tipo = :tipo AND talla = :talla");
        $consulta->bindValue(':nombre', $nombre, PDO::PARAM_STR);
        $consulta->bindValue(':tipo', $tipo, PDO::PARAM_STR);
        $consulta->bindValue(':talla', $talla, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchObject('Producto');
    }

    public  function actualizarPrecioYStock($precio, $stock){
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("UPDATE productos SET precio = :precio, stock = :stock WHERE id = :id");
        $consulta->bindValue(':id', $this->id, PDO::PARAM_STR);
        $consulta->bindValue(':precio', $precio, PDO::PARAM_STR);
        $consulta->bindValue(':stock', $stock, PDO::PARAM_STR);
        $consulta->execute();
    }

    public static function obtenerSegunNombre($nombre){
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id, nombre, tipo, talla, color, precio, stock, imagen FROM productos WHERE nombre = :nombre");
        $consulta->bindValue(':nombre', $nombre, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchObject('Producto');
    }

    public static function obtenerSegunTipo($tipo){
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id, nombre, tipo, talla, color, precio, stock, imagen FROM productos WHERE tipo = :tipo");
        $consulta->bindValue(':tipo', $tipo, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchObject('Producto');
    }

    public static function obtenerSegunNombreTipoYColor($nombre, $tipo, $color){
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id, nombre, tipo, talla, color, precio, stock, imagen FROM productos WHERE nombre = :nombre AND tipo = :tipo AND color = :color");
        $consulta->bindValue(':nombre', $nombre, PDO::PARAM_STR);
        $consulta->bindValue(':tipo', $tipo, PDO::PARAM_STR);
        $consulta->bindValue(':color', $color, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchObject('Producto');
    }

    public static function obtenerSegunColor($color){
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id, nombre, tipo, talla, color, precio, stock, imagen FROM productos WHERE color = :color");
        $consulta->bindValue(':color', $color, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchObject('Producto');
    }

    public static function obtenerSegunID($id){
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id, nombre, tipo, talla, color, precio, stock, imagen FROM productos WHERE id = :id");
        $consulta->bindValue(':id', $id, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchObject('Producto');
    }

    public function actualizarStock($stock){
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("UPDATE productos SET stock = :stock WHERE id = :id");
        $consulta->bindValue(':id', $this->id, PDO::PARAM_STR);
        $consulta->bindValue(':stock', $stock, PDO::PARAM_STR);
        $consulta->execute();
    }

    static public  function obtenerProductosEntreDosPrecios($precio_min, $precio_max){
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id, nombre, tipo, talla, color, precio, stock FROM productos WHERE precio BETWEEN :precio_min AND :precio_max");
        $consulta->bindValue(':precio_min', $precio_min, PDO::PARAM_STR);
        $consulta->bindValue(':precio_max', $precio_max, PDO::PARAM_STR);
        $consulta->execute();
        return $consulta->fetchAll(PDO::FETCH_ASSOC);
    }

    static public  function obtenerProductoMasVendido(){
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT p.id as id, p.nombre as nombre, p.talla as talla, p.color as color, SUM(v.cantidad) as total_vendidos FROM productos p JOIN ventas v on p.id = v.id_producto ORDER BY total_vendidos DESC LIMIT 1");
        $consulta->execute();
        return $consulta->fetchAll(PDO::FETCH_ASSOC);
    }

}