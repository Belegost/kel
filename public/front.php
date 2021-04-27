<?php

require_once '../vendor/autoload.php';

$loader = new \Twig\Loader\FilesystemLoader('../assets/templates');
$twig = new \Twig\Environment($loader);

$page = htmlspecialchars($_GET["page"]);

if (file_exists('../assets/templates/page/'.$page.'.html.twig')) {
    echo $twig->render('page/'.$page.'.html.twig');
} else {
    echo $twig->render('404.html.twig');
}
