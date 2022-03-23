<?php

declare(strict_types=1);

if (getenv('APP_ENV') !== 'production') {
    error_reporting(E_ALL);
}

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: ' . getenv('REACT_APP_URL'));

use Phalcon\Mvc\Application;
use Phalcon\Mvc\Router;
use Phalcon\DI\FactoryDefault;

require_once __DIR__ . '/../vendor/autoload.php';

$diContainer = new FactoryDefault();

$application = new Application($diContainer);
$application->useImplicitView(false);

// Database
$databaseConfig = include __DIR__ . '/../config/database.php';
$diContainer->set('db', function () use ($databaseConfig) {
    $adapter = 'Phalcon\Db\Adapter\Pdo\\' . $databaseConfig['adapter'];
    return new $adapter([
        'host' => $databaseConfig['host'],
        'username' => $databaseConfig['username'],
        'password' => $databaseConfig['password'],
        'dbname' => $databaseConfig['dbname'],
    ]);
});

// Router
$routes = include __DIR__ . '/../config/routes.php';
$diContainer->set('router', function () use ($routes) {
    $router = new Router();
    $router->setUriSource(Router::URI_SOURCE_SERVER_REQUEST_URI);
    $router->removeExtraSlashes(true);
    foreach ($routes as $name => $config) {
        $route = $router->add(
            $config['pattern'],
            $config['paths'] ?? null,
            $config['httpMethods'] ?? null,
            $config['position'] ?? Router::POSITION_LAST
        );
        $route->setName($name);
        if (isset($config['hostname'])) {
            $route->setHostname($config['hostname']);
        }
        if (isset($config['converts'])) {
            foreach ($config['converts'] as $id => $callback) {
                $route->convert($id, $callback);
            }
        }
    }
    return $router;
});

$diContainer->setShared('config', function () {
    return include __DIR__ . '/../config/config.php';
});

try {
    $application->handle()->getContent();
} catch (\App\SharedKernel\Exceptions\NotFoundException $exception) {
    $diContainer->getShared('response')
        ->setStatusCode(404)
        ->setJsonContent([
            'error' => $exception->getMessage(),
        ])
        ->send();
} catch (App\SharedKernel\Exceptions\AccessDeniedException $exception) {
    $diContainer->getShared('response')
        ->setStatusCode(403)
        ->setJsonContent([
            'error' => $exception->getMessage(),
        ])
        ->send();
} catch (\InvalidArgumentException $exception) {
    $diContainer->getShared('response')
        ->setStatusCode(400)
        ->setJsonContent([
            'error' => $exception->getMessage(),
        ])
        ->send();
} catch (\Phalcon\Mvc\Dispatcher\Exception $exception) {
    $diContainer->getShared('response')
        ->setStatusCode(404)
        ->setJsonContent([
            'error' => 'Resource not found',
        ])
        ->send();
} catch (\Throwable $exception) {
    $diContainer->getShared('response')
        ->setStatusCode(500)
        ->setJsonContent([
            'error' => getenv('APP_ENV') === 'production' ? 'Server error' : $exception->getMessage(),
        ])
        ->send();
}
