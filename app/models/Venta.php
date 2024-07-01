<?php

class Venta {
    public $id;
    public $email;
    public $id_producto;
    public $cantidad;
    public $monto_total;
    public $nro_pedido;
    public $imagen;
    public $fecha;
    public $fecha_baja;
     public function crearVenta()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO ventas (email, id_producto, cantidad, monto_total, nro_pedido, imagen, fecha) VALUES (:email, :id_producto, :cantidad, :monto_total, :nro_pedido, :imagen, :fecha)");
        $consulta->bindValue(':email', $this->email, PDO::PARAM_STR);
        $consulta->bindValue(':id_producto', $this->id_producto, PDO::PARAM_STR);
        $consulta->bindValue(':cantidad', $this->cantidad, PDO::PARAM_STR);
        $consulta->bindValue(':monto_total', $this->monto_total, PDO::PARAM_STR);
        $consulta->bindValue(':nro_pedido', self::generarNroPedido(), PDO::PARAM_STR);
        $consulta->bindValue(':imagen', $this->imagen, PDO::PARAM_STR);
        $consulta->bindValue(':fecha', date('Y-m-d H:i:s'), PDO::PARAM_STR);
        $consulta->execute();

        return $objAccesoDatos->obtenerUltimoId();
    }

    static public  function actualizarVenta($email, $nro_pedido, $id_prod, $cantidad, $monto_total){
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("UPDATE ventas SET email = :email, id_producto = :id_producto, cantidad = :cantidad, monto_total = :monto_total WHERE nro_pedido = :nro_pedido");
        $consulta->bindValue(':nro_pedido', $nro_pedido, PDO::PARAM_STR);
        $consulta->bindValue(':email', $email, PDO::PARAM_STR);
        $consulta->bindValue(':id_producto', $id_prod, PDO::PARAM_STR);
        $consulta->bindValue(':cantidad', $cantidad, PDO::PARAM_STR);
        $consulta->bindValue(':monto_total', $monto_total, PDO::PARAM_STR);
        $consulta->execute();
    }

    static public  function obtenerVentaSegunNroPedido($nro_pedido){
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT email, id_producto, cantidad, monto_total, nro_pedido, imagen, fecha FROM ventas WHERE nro_pedido = :nro_pedido");
        $consulta->bindValue(':nro_pedido', $nro_pedido, PDO::PARAM_STR);
        $consulta->execute();
        return $consulta->fetchObject('Venta');
    }

    static public  function obtenerProductosVendidosPorFecha($fecha){
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT SUM(cantidad) as total FROM ventas WHERE fecha = :fecha");
        $consulta->bindValue(':fecha', $fecha, PDO::PARAM_STR);
        $consulta->execute();
        $resultado = $consulta->fetch(PDO::FETCH_ASSOC);
        return $resultado['total'] ?? 0;
    }

    static public  function obtenerProductosVendidosDeAyer(){
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT SUM(cantidad) as total FROM ventas WHERE fecha = :fecha");
        $fecha_ayer = date('Y-m-d', strtotime('-1 day'));
        $consulta->bindValue(':fecha', $fecha_ayer, PDO::PARAM_STR);
        $consulta->execute();
        $resultado = $consulta->fetch(PDO::FETCH_ASSOC);
        return $resultado['total'] ?? 0;
    }

    static public  function obtenerVentasSegunUsuario($email){
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT v.id as id_venta, p.nombre as nombre_producto, p.tipo as tipo_producto, p.talla as talla_producto, p.color as color_producto, cantidad, monto_total, nro_pedido, fecha FROM ventas v JOIN productos p on p.id = v.id_producto WHERE email = :email");
        $consulta->bindValue(':email', $email, PDO::PARAM_STR);
        $consulta->execute();
        return $consulta->fetchAll(PDO::FETCH_ASSOC);
    }

    static public  function obtenerVentasSegunTipoProducto($tipo){
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT v.id as id, v.email as usuario, p.nombre as nombre_producto, p.talla as talla_producto, p.color as color_producto, cantidad, monto_total, nro_pedido, fecha FROM ventas v JOIN productos p on p.id = v.id_producto WHERE p.tipo = :tipo");
        $consulta->bindValue(':tipo', $tipo, PDO::PARAM_STR);
        $consulta->execute();
        return $consulta->fetchAll(PDO::FETCH_ASSOC);
    }

    static public  function obtenerIngresosSegunFecha($fecha){
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT nro_pedido, monto_total, fecha FROM ventas WHERE fecha = :fecha");
        $consulta->bindValue(':fecha', $fecha, PDO::PARAM_STR);
        $consulta->execute();
        return $consulta->fetchAll(PDO::FETCH_ASSOC);
    }

    static public  function obtenerTodosLosIngresos(){
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT nro_pedido, monto_total, fecha FROM ventas");
        $consulta->execute();
        return $consulta->fetchAll(PDO::FETCH_ASSOC);
    }


    private static function generarNroPedido(){
        return rand(1,10000);
    }

}