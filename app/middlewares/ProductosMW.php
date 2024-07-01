<?php


use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;

require_once "./utils/validador.php";

class ProductosMiddleware
{
    
    public function ParamsProductoCarga(Request $request, RequestHandler $handler): Response
    {   
        $params = $request->getParsedBody();
        
        $nombre = $params["nombre"];
        $precio =  $params["precio"];
        $tipo  =  $params["tipo"];
        $talla = $params["talla"];
        $color =  $params["color"];
        $stock = $params["stock"];
        $imagen = $_FILES["imagen"];
        
        if(isset($nombre, $precio, $tipo, $talla, $color, $stock, $imagen)){
           $response = $handler->handle($request); 
        } else {
            $response = new Response();
            $payload = json_encode(array('mensaje' => 'Parametros erroneos.'));
            $response->getBody()->write($payload);
        }
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function ValoresProductoCarga(Request $request, RequestHandler $handler): Response
    {   
        $params = $request->getParsedBody();
        
        $nombre = $params["nombre"];
        $precio =  $params["precio"];
        $tipo  =  $params["tipo"];
        $talla = $params["talla"];
        $color =  $params["color"];
        $stock = $params["stock"];
        $imagen = $_FILES["imagen"];
        
        $mensaje = [];
        
        $error = false;
        
        if(!Validador::validarNombre($nombre)){
            $mensaje[] = ['error' => 'Nombre invalido.'];
            $error = true;
        }
        
        if(!Validador::validarPrecio($precio)){
            $mensaje[] = ['error' => 'Precio invalido.'];
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
        
        if(!Validador::validarColor($color)){
            $mensaje[] = ['error' => 'Color invalido.'];
            $error = true;
        }        
        
        if(!Validador::validarStock($stock)){
            $mensaje[] = ['error' => 'Stock invalido.'];
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
    
    
    
    public function ParamsProductoConsulta(Request $request, RequestHandler $handler): Response
    {   
        $params = $request->getParsedBody();
        
        $nombre = $params["nombre"];
        $tipo  =  $params["tipo"];
        $color =  $params["color"];
        
        if(isset($nombre, $tipo, $color)){
           $response = $handler->handle($request); 
        } else {
            $response = new Response();
            $payload = json_encode(array('mensaje' => 'Parametros erroneos.'));
            $response->getBody()->write($payload);
        }
        return $response->withHeader('Content-Type', 'application/json');
    }
    
    public function ValoresProductoConsulta(Request $request, RequestHandler $handler): Response
    {   
        $params = $request->getParsedBody();
        
        $nombre = $params["nombre"];
        $tipo  =  $params["tipo"];
        $color =  $params["color"];
        
        $mensaje = [];
        
        $error = false;
        
        if(!Validador::validarNombre($nombre)){
            $mensaje[] = ['error' => 'Nombre invalido.'];
            $error = true;
        }

        if(!Validador::validarTipo($tipo)){
            $mensaje[] = ['error' => 'Tipo invalido. (Camiseta o Pantalon)'];
            $error = true;
        }
        
        if(!Validador::validarColor($color)){
            $mensaje[] = ['error' => 'Color invalido.'];
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
