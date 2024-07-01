<?php


use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;

require_once "./utils/validador.php";

class ConsultasMiddleware
{
    public function ConsultaSinParametros(Request $request, RequestHandler $handler): Response
    {
        $params = $request->getQueryParams();
        if (!$params) {
            $response = $handler->handle($request);
        } else {
            $response = new Response();
            $payload = json_encode(array('mensaje' => 'La consulta no requiere parametros.'));
            $response->getBody()->write($payload);
        }
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function ParamsFechaEspecifica(Request $request, RequestHandler $handler): Response
    {
        $params = $request->getQueryParams();


        if (isset($params["fecha"]) || $params == NULL) {
            $response = $handler->handle($request);
        } else {
            $response = new Response();
            $payload = json_encode(array('mensaje' => 'Parametros erroneos.'));
            $response->getBody()->write($payload);
        }
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function ValoresFechaEspecifica(Request $request, RequestHandler $handler): Response
    {
        $params = $request->getQueryParams();

        if ($params != NULL) {
            $fecha = $params["fecha"];
        }

        if ($params == NULL) {
            $response = $handler->handle($request);
        } else {
            if (!Validador::validarFecha($fecha)) {
                $response = new Response();
                $payload = json_encode(array('mensaje' => 'Fecha mal ingresada. (Y-m-d)'));
                $response->getBody()->write($payload);
            } else {
                $response = $handler->handle($request);
            }
        }
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function ParamsEmail(Request $request, RequestHandler $handler): Response
    {
        $params = $request->getQueryParams();

        if (isset($params["email"])) {
            $response = $handler->handle($request);
        } else {
            $response = new Response();
            $payload = json_encode(array('mensaje' => 'Parametros erroneos.'));
            $response->getBody()->write($payload);
        }
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function ValoresEmail(Request $request, RequestHandler $handler): Response
    {
        $params = $request->getQueryParams();

        $email = $params["email"];

        if (!Validador::validarEmail($email)) {
            $response = new Response();
            $payload = json_encode(array('mensaje' => 'Formato de email erroneo.'));
            $response->getBody()->write($payload);
        } else {
            $response = $handler->handle($request);
        }
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function ParamsTipo(Request $request, RequestHandler $handler): Response
    {
        $params = $request->getQueryParams();

        if (isset($params["tipo"])) {
            $response = $handler->handle($request);
        } else {
            $response = new Response();
            $payload = json_encode(array('mensaje' => 'Parametros erroneos.'));
            $response->getBody()->write($payload);
        }
        return $response->withHeader('Content-Type', 'application/json');
    }


    public function ValoresTipo(Request $request, RequestHandler $handler): Response
    {
        $params = $request->getQueryParams();

        $tipo = $params["tipo"];

        if (!Validador::validarTipo($tipo)) {
            $response = new Response();
            $payload = json_encode(array('mensaje' => 'Tipo invalido. (Camiseta o Pantalon)'));
            $response->getBody()->write($payload);
        } else {
            $response = $handler->handle($request);
        }
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function ParamsPrecioMinMax(Request $request, RequestHandler $handler): Response
    {
        $params = $request->getQueryParams();

        if (isset($params["precio_min"], $params["precio_max"])) {
            $response = $handler->handle($request);
        } else {
            $response = new Response();
            $payload = json_encode(array('mensaje' => 'Parametros erroneos.'));
            $response->getBody()->write($payload);
        }
        return $response->withHeader('Content-Type', 'application/json');
    }


    public function ValoresPrecioMinMax(Request $request, RequestHandler $handler): Response
    {
        $params = $request->getQueryParams();

        $precio_min = $params["precio_min"];
        $precio_max = $params["precio_max"];


        if (!Validador::validarPrecio($precio_min) || !Validador::validarPrecio($precio_max)) {
            $response = new Response();

            $payload = json_encode(array('mensaje' => 'Precios invalidos.'));
            $response->getBody()->write($payload);
        } else if ($precio_max < $precio_min) {
            $response = new Response();
            $payload = json_encode(array('mensaje' => 'Precio maximo menor a precio minimo.'));
            $response->getBody()->write($payload);
        } else {
            $response = $handler->handle($request);
        }
        return $response->withHeader('Content-Type', 'application/json');
    }
}
