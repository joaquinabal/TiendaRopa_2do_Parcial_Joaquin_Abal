<?php
require_once './models/Producto.php';

require_once './utils/Archivos.php';
define('IMAGENESROPA','./ImagenesDeRopa/2024/');

class ProductoController extends Producto
{
  public function CargarUno($request, $response, $args)
  {
    $parametros = $request->getParsedBody();

    $nombre = $parametros['nombre'];
    $tipo = $parametros['tipo'];
    $talla = $parametros['talla'];
    $color = $parametros['color'];
    $precio = $parametros['precio'];
    $stock = $parametros['stock'];

    $prod = new Producto();
    $prod->nombre = $nombre;
    $prod->tipo = $tipo;
    $prod->talla = $talla;
    $prod->precio = $precio;
    $prod->color = $color;
    $prod->stock = $stock;
    Archivos::darAltaImagen($prod, $_FILES['imagen'], self::generarNombreImagenPrenda($prod), IMAGENESROPA); 
    
    
    if(Producto::obtenerSegunNombreYTipo($prod->nombre, $prod->tipo)){
      $stock_nuevo = Producto::obtenerSegunNombreYTipo($prod->nombre, $prod->tipo)->stock + $prod->stock;
      Producto::obtenerSegunNombreYTipo($prod->nombre, $prod->tipo)->actualizarPrecioYStock($prod->precio, $stock_nuevo);
      $payload = json_encode(array("mensaje" => "Producto modificado con exito"));
    } else {
      $prod->crearProducto();
      $payload = json_encode(array("mensaje" => "Producto creado con exito"));
    }

    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }

  public function ConsultarPorNombreTipoYColor($request, $response, $args){
    $parametros = $request->getParsedBody();
    $nombre = $parametros['nombre'];
    $tipo = $parametros['tipo'];
    $color = $parametros['color'];

    $mensajes = array();

    if(Producto::obtenerSegunNombreTipoYColor($nombre, $tipo, $color)){
        $mensajes[] = "Producto Existe";
    } else {
        if(!Producto::obtenerSegunNombre($nombre)){
            $mensajes[] = "No hay productos con nombre $nombre.";
        }
        if(!Producto::obtenerSegunTipo($tipo)){
            $mensajes[] = "No hay productos con tipo $tipo.";
        }
        if(!Producto::obtenerSegunColor($color)){
            $mensajes[] = "No hay productos con color $color.";
        }
        
        if(empty($mensajes)) {
            $mensajes[] = "No hay coincidencias.";
        }
    }

    $payload = json_encode(array("mensaje" => $mensajes));
    

  $response->getBody()->write($payload);
  return $response
    ->withHeader('Content-Type', 'application/json');

  }

  static private function generarNombreImagenPrenda($producto){
    $nombre_imagen = trim(($producto->nombre)," ") . "_" . $producto->tipo;
    return $nombre_imagen;
  }

  public function ConsultaProductosEntrePrecios($request, $response, $args)
  {
      $params = $request->getQueryParams();
      $precio_min = $params["precio_min"];
      $precio_max = $params["precio_max"];

      $listaProductos = Producto::obtenerProductosEntreDosPrecios($precio_min, $precio_max);
      if($listaProductos){
        $payload = json_encode(array('productos' => $listaProductos));
      } else {
        $payload = json_encode(array('mensaje' => "no hay productos entre esos precios."));
      }
      $response->getBody()->write($payload);
      return $response->withHeader('Content-Type', 'application/json');
  }

  public function ConsultarProductoMasVendido($request, $response, $args)
  {
    $prod_mas_vendido = Producto::obtenerProductoMasVendido();
    $payload = json_encode(array('prod_mas_vendido' => $prod_mas_vendido));
    $response->getBody()->write($payload);
    return $response->withHeader('Content-Type', 'application/json');
  }

  }
