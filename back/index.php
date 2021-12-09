<?php
    require_once __DIR__.'/vendor/autoload.php';
    require_once "./App/Roots/UsuariosRoots.php";
    require_once "./App/Roots/TokenRoots.php";
    require_once "./App/Roots/DireccionesRoots.php";

    use EasyProjects\SimpleRouter\Router as Router;

    $app = new Router;

    new UsuariosRoots($app);
    new TokenRoots($app);
    new DireccionesRoots($app);

    $app->start();
    