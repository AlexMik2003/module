<?php

define('ROOT_PATH',realpath(__DIR__ . "/.."));

require_once ROOT_PATH . '/vendor/autoload.php';

use \App\Helpers\Session;

Session::init();

$config = require_once ROOT_PATH."/bootstrap/config.php";

$app = new \Slim\App($config);

$container = $app->getContainer();

$container["view"] = function ($container)
{
    $view = new \Slim\Views\Twig(ROOT_PATH."/resources/views",[
        "cache" => false,
    ]);

    $view->addExtension(new \Slim\Views\TwigExtension(
        $container->router,
        $container->request->getUri()
    ));

    $view->addExtension(new \Twig_Extension_Debug());
    $view->getEnvironment()->addGlobal("slider" ,$container->get("page")->slider());
    $view->getEnvironment()->addGlobal("auth" ,$container->get("auth")->check());
    $view->getEnvironment()->addGlobal("status" ,$container->get("auth")->status());

    return $view;

};

$capsule = new \Illuminate\Database\Capsule\Manager;
$capsule->addConnection($container["db"]);
$capsule->setAsGlobal();
$capsule->bootEloquent();

$container["db"] = function ($container) use ($capsule)
{
    return $capsule;
};

$container["page"] = function ($container)
{
    return new \App\Controllers\PageController($container);
};

$container["category"] = function ($container)
{
    return new \App\Controllers\CategoryController($container);
};

$container["search"] = function ($container)
{
    return new \App\Controllers\SearchController($container);
};

$container["news"] = function ($container)
{
    return new \App\Controllers\NewsController($container);
};

$container["tags"] = function ($container)
{
    return new \App\Controllers\TagController($container);
};

$container["auth"] = function ($container)
{
    return new App\Auth\Auth;
};

$container["authorized"] = function ($container)
{
    return new \App\Controllers\AuthController($container);
};

$container["analitics"] = function ($container)
{
    return new \App\Controllers\Analitics($container);
};

$container["comment"] = function ($container)
{
    return new \App\Controllers\CommentController($container);
};

$container["user"] = function ($container)
{
    return new \App\Controllers\UserController($container);
};

$container["admin"] = function ($container)
{
    return new \App\Controllers\AdminController($container);
};

$container["validator"] = function ($container)
{
    return new \App\Validation\Validator;
};

$container["csrf"] = function ($container)
{
    return new \Slim\Csrf\Guard;
};

$container["flash"] = function ($container){
    return new \Slim\Flash\Messages;
};

$app->add(new \App\Middleware\ValidationErrorsMiddleware($container));
$app->add(new \App\Middleware\CsrfViewMiddleware($container));
$app->add(new \App\Middleware\OldInputMiddleware($container));


$app->add($container->csrf);

require_once ROOT_PATH."/app/routes.php";
