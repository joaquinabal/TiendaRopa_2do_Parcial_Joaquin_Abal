<?php


use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;

require_once "./utils/validador.php";

class VentasMiddleware
{
    public function ParamsVentaCarga(Request $request, RequestHandler $handler): Response
    {   
        $params = $request->getParsedBody();
        
        $email = $params["email"];
        $nombre = $params["nombre"];
        $tipo  =  $params["tipo"];
        $talla = $params["talla"];
        $cantidad = $params["cantidad"];
        $imagen = $_FILES["imagen"];
        
        if(isset($email, $nombre, $tipo, $talla, $cantidad, $imagen)){
           $response = $handler->handle($request); 
        } else {
            $response = new Response();
            $payload = json_encode(array('mensaje' => 'Parametros erroneos.'));
            $response->getBody()->write($payload);
        }
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function ValoresVentaCarga(Request $request, RequestHandler $handler): Response
    {   
        $params = $request->getParsedBody();
        
        $email = $params["email"];
        $nombre = $params["nombre"];
        $tipo  =  $params["tipo"];
        $talla = $params["talla"];
        $cantidad = $params["cantidad"];
        $imagen = $_FILES["imagen"];
        
        $mensaje = [];
        
        $error = false;
        
        if(!Validador::validarEmail($email)){
            $mensaje[] = ['error' => 'Email invalido.'];
            $error = true;
        }
        
        if(!Validador::validarNombre($nombre)){
            $mensaje[] = ['error' => 'Nombre invalido.'];
            $error = true;
        }
        
        
        if(!Validador::validarTipo($tipo)){
            $mensaje[] = ['error' => 'Tipo invalido. (Camiseta o Pantalon)'];
            $error = true;
        }
        
        if(!Validador::validarTalla($talla)){
            $mensaje[] = ['error' => 'Talla invalida. (S, M o L)'];
            $error = true;
        }
    
        
        if(!Validador::validarCantidad($cantidad)){
            $mensaje[] = ['error' => 'Cantidad invalido.'];
            $error = true;
        }
        
        if(!Validador::validarImagen($imagen)){
            $mensaje[] = ['error' => 'Imagen invalida.'];
            $error = true;
        }
        
        if($error){
            $response = new Response();
            $payload = json_encode($mensaje);
            $response->getBody()->write($payload);
        } else {
            $response = $handler->handle($request); 
        }
        return $response->withHeader('Content-Type', 'application/json');
    }
    
    public function ParamsVentaModificar(Request $request, RequestHandler $handler): Response
    {   
        $params = $request->getParsedBody();
        
        $nro_pedido = $params["nro_pedido"];
        $email = $params["email"];
        $nombre = $params["nombre"];
        $tipo  =  $params["tipo"];
        $talla = $params["talla"];
        $cantidad = $params["cantidad"];
        
        if(isset($email, $nombre, $tipo, $talla, $cantidad, $nro_pedido)){
           $response = $handler->handle($request); 
        } else {
            $response = new Response();
            $payload = json_encode(array('mensaje' => 'Parametros erroneos.'));
            $response->getBody()->write($payload);
        }
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function ValoresVentaModificar(Request $request, RequestHandler $handler): Response
    {   
        $params = $request->getParsedBody();
        
        $nro_pedido = $params["nro_pedido"];
        $email = $params["email"];
        $nombre = $params["nombre"];
        $tipo  =  $params["tipo"];
        $talla = $params["talla"];
        $cantidad = $params["cantidad"];
        
        $mensaje = [];
        
        $error = false;
        
        if(!Validador::validarEmail($email)){
            $mensaje[] = ['error' => 'Email invalido.'];
            $error = true;
        }
        
        if(!Validador::validarNombre($nombre)){
            $mensaje[] = ['error' => 'Nombre invalido.'];
            $error = true;
        }
        
        
        if(!Validador::validarTipo($tipo)){
            $mensaje[] = ['error' => 'Tipo invalido. (Camiseta o Pantalon)'];
            $error = true;
        }
        
        if(!Validador::validarTalla($talla)){
            $mensaje[] = ['error' => 'Talla invalida. (S, M o L)'];
            $error = true;
        }        
        
        
        if(!Validador::validarCantidad($cantidad)){
            $mensaje[] = ['error' => 'Cantidad invalido.'];
            $error = true;
        } 
        
        if(!Validador::validarNroPedido($nro_pedido)){
            $mensaje[] = ['error' => 'Nro Pedido invalido. (del 1 al 9999)'];
            $error = true;
        } 
        
        if($error){
            $response = new Response();
            $payload = json_encode($mensaje);
            $response->getBody()->write($payload);
        } else {
            $response = $handler->handle($request); 
        }
        return $response->withHeader('Content-Type', 'application/json');
    }
}