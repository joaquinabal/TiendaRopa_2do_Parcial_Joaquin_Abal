<?php
// Error Handling
error_reporting(-1);
ini_set('display_errors', 1);

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Factory\AppFactory;
use Slim\Routing\RouteCollectorProxy;
use Slim\Routing\RouteContext;

require __DIR__ . '/../vendor/autoload.php';

require_once './db/AccesoDatos.php';

require_once './middlewares/ProductosMW.php';
require_once './middlewares/VentasMW.php';
require_once './middlewares/ConsultasMW.php';

require_once './controllers/ProductoController.php';
require_once './controllers/VentaController.php';


// Load ENV
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();

// Instantiate App
$app = AppFactory::create();

$app->setBasePath('/2024C1/2DOPARCIAL/app');

// Add error middleware
$app->addErrorMiddleware(true, true, true);

// Add parse body
$app->addBodyParsingMiddleware();

// Routes
$app->group('/tienda', function (RouteCollectorProxy $group) {
  $group->post('/alta', \ProductoController::class . ':CargarUno')->add([new ProductosMiddleware(), 'ValoresProductoCarga'])->add([new ProductosMiddleware(), 'ParamsProductoCarga']);
  $group->post('/consultar', \ProductoController::class . ':ConsultarPorNombreTipoYColor')->add([new ProductosMiddleware(), 'ValoresProductoConsulta'])->add([new ProductosMiddleware(), 'ParamsProductoConsulta']);
  });

  $app->group('/ventas', function (RouteCollectorProxy $group) {
    $group->post('/alta', \VentaController::class . ':CargarUno');
    $group->put('/modificar', \VentaController::class . ':ModificarUno');
    $group->group('/consultar', function (RouteCollectorProxy $consultasGroup) {
      $consultasGroup->get('/productos/vendidos', \VentaController::class . ':ConsultarTotalPorFecha')->add([new ConsultasMiddleware(), 'ValoresFechaEspecifica'])->add([new ConsultasMiddleware(), 'ParamsFechaEspecifica']);
      $consultasGroup->get('/ventas/porUsuario', \VentaController::class . ':ConsultarVentasPorUsuario')->add([new ConsultasMiddleware(), 'ValoresEmail'])->add([new ConsultasMiddleware(), 'ParamsEmail']);
      $consultasGroup->get('/ventas/porProducto', \VentaController::class . ':ConsultarVentasPorTipoProducto')->add([new ConsultasMiddleware(), 'ValoresTipo'])->add([new ConsultasMiddleware(), 'ParamsTipo']);
      $consultasGroup->get('/productos/entreValores', \ProductoController::class . ':ConsultaProductosEntrePrecios')->add([new ConsultasMiddleware(), 'ValoresPrecioMinMax'])->add([new ConsultasMiddleware(), 'ParamsPrecioMinMax']);;
      $consultasGroup->get('/ventas/ingresos', \VentaController::class . ':ConsultarIngresosPorFecha')->add([new ConsultasMiddleware(), 'ValoresFechaEspecifica'])->add([new ConsultasMiddleware(), 'ParamsFechaEspecifica']);
      $consultasGroup->get('/productos/masVendido', \ProductoController::class . ':ConsultarProductoMasVendido')->add([new ConsultasMiddleware(), 'ConsultaSinParametros']);
      });
    });
  
$app->run();
