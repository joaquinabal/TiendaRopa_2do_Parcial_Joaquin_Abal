<?php
require_once './models/Venta.php';
require_once './models/Producto.php';

require_once './utils/Archivos.php';
define('IMAGENESVENTA', './ImagenesDeVenta/2024/');

class VentaController extends Venta
{
    public function CargarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();

        $email = $parametros['email'];
        $nombre = $parametros['nombre'];
        $tipo = $parametros['tipo'];
        $talla = $parametros['talla'];
        $cantidad = $parametros['cantidad'];

        $prod = Producto::obtenerSegunNombreTipoYTalla($nombre, $tipo, $talla);
        $stock = $prod->stock;
        $id_prod = $prod->id;
        if($prod){
            if ($stock >= $cantidad) {
    
                $venta = new Venta();
                $venta->email = $email;
                $venta->cantidad = $cantidad;
                $venta->id_producto = $id_prod;
    
                $monto_total = $prod->precio * $cantidad;
    
                $venta->monto_total = $monto_total;
    
                $venta->imagen = Archivos::darAltaImagen($venta, $_FILES['imagen'], self::generarNombreImagenVenta($venta, $prod), IMAGENESVENTA);
    
                $stock_nuevo = $stock - $cantidad;
    
                $venta->crearVenta();
                $prod->actualizarStock($stock_nuevo);
    
                $payload = json_encode(array("mensaje" => "Venta generada con exito"));
            } else {
                $payload = json_encode(array("mensaje" => "No hay stock disponible"));
            }
        } else {
            $payload = json_encode(array("mensaje" => "No existe tal producto"));
        }

        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json');
    }

    public function ModificarUno($request, $response, $args)
    {   
        $inputData = file_get_contents('php://input');
        $parametros = json_decode($inputData, true);

        $nro_pedido = $parametros['nro_pedido'];
        $email = $parametros['email'];
        $nombre = $parametros['nombre'];
        $tipo = $parametros['tipo'];
        $talla = $parametros['talla'];
        $cantidad = $parametros['cantidad'];

        $prod_nuevo = Producto::obtenerSegunNombreTipoYTalla($nombre, $tipo, $talla);
        $stock_nuevo = $prod_nuevo->stock;
        $id_prod_nuevo = $prod_nuevo->id;

        $venta = Venta::obtenerVentaSegunNroPedido($nro_pedido);

        if ($venta && $prod_nuevo) {

            $prod_anterior = Producto::obtenerSegunID($venta->id_producto);

            if ($prod_anterior) {
                $stock_nuevo_prod_anterior = $prod_anterior->stock + $venta->cantidad;
                $prod_anterior->actualizarStock($stock_nuevo_prod_anterior);

                if ($venta->id_producto == $id_prod_nuevo) {

                    if ($stock_nuevo >= $cantidad) {

                        $monto_total = $prod_anterior->precio * $cantidad;

                        $venta->monto_total = $monto_total;

                        Venta::actualizarVenta($email, $nro_pedido, $id_prod_nuevo, $cantidad, $monto_total);

                        $stock_ajustado = $stock_nuevo_prod_anterior - $cantidad;
                        $prod_anterior->actualizarStock($stock_ajustado);
                    } else {
                        $payload = json_encode(array("mensaje" => "Stock insuficiente para el producto actual"));
                        $response->getBody()->write($payload);
                        return $response->withHeader('Content-Type', 'application/json');
                    }
                } else {
                    if ($stock_nuevo >= $cantidad) {

                        $monto_total = Producto::obtenerSegunID($id_prod_nuevo)->precio * $cantidad;

                        $venta->monto_total = $monto_total;
                        Venta::actualizarVenta($email, $nro_pedido, $id_prod_nuevo, $cantidad, $monto_total);

                        $stock_nuevo_final = $stock_nuevo - $cantidad;
                        $prod_nuevo->actualizarStock($stock_nuevo_final);
                    } else {
                        $payload = json_encode(array("mensaje" => "Stock insuficiente para el nuevo producto"));
                        $response->getBody()->write($payload);
                        return $response->withHeader('Content-Type', 'application/json');
                    }
                }
                $payload = json_encode(array("mensaje" => "Venta actualizada con éxito"));
            } else {
                $payload = json_encode(array("mensaje" => "Producto anterior no encontrado"));
            }
        } else {
            $payload = json_encode(array("mensaje" => "Venta o producto nuevo no encontrado"));
        }
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function ConsultarTotalPorFecha($request, $response, $args)
    {
        $params = $request->getQueryParams();
        if($params != NULL){
            $fecha = $params["fecha"];
        }
        

        if(isset($fecha)){
            $cantidad = Venta::obtenerProductosVendidosPorFecha($fecha);
        } else {
            $cantidad = Venta::obtenerProductosVendidosDeAyer();
            $fecha = date('Y-m-d', strtotime('-1 day'));
        }

        if($cantidad == 0){
            $payload = json_encode(array("mensaje" => "No hay ventas en esa fecha."));
        } else {
            $payload = json_encode(array("mensaje" => "La cantidad vendida en la fecha $fecha es $cantidad."));
        }
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function ConsultarVentasPorUsuario($request, $response, $args)
    {
        $params = $request->getQueryParams();
        $email = $params["email"];

        $listaVentas = Venta::obtenerVentasSegunUsuario($email);
        if($listaVentas){
            $payload = json_encode(array($email => $listaVentas));
        } else {
            $payload = json_encode(array('error' => 'No hay usuario con el email ingresado.'));
        }
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function ConsultarVentasPorTipoProducto($request, $response, $args)
    {
        $params = $request->getQueryParams();
        $tipo = $params["tipo"];

        $listaVentas = Venta::obtenerVentasSegunTipoProducto($tipo);
        $payload = json_encode(array($tipo => $listaVentas));
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function ConsultarIngresosPorFecha($request, $response, $args)
    {
        $params = $request->getQueryParams();
        if(isset($params["fecha"])){
            $fecha = $params["fecha"];
            $lista_ingresos = Venta::obtenerIngresosSegunFecha($fecha);
        } else {
            $lista_ingresos = Venta::obtenerTodosLosIngresos();
        }
        $payload = json_encode(array("Ingresos" => $lista_ingresos));

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }
    

    static private function generarNombreImagenVenta($venta, $prod)
    {
        $nombre_imagen = $prod->nombre . " - " . $prod->tipo . "-" . $prod->talla . "-" . explode("@", $venta->email)[0];
        return $nombre_imagen;
    }
}
