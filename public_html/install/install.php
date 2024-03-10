<?php


require __DIR__ . '/assets/vendor/autoload.php';
require __DIR__ . '/src/htmloutput.php';

use Install\HtmlOutput;


//$loader = new \Twig\Loader\FilesystemLoader(__DIR__ . '/assets/templates');
//$twig = new \Twig\Environment($loader, [
//    'debug' => true,
//    'cache' => __DIR__ . '/assets/templates_c',
// ]);

$html = new HtmlOutput();

$steps = $html->getSteps();

if ($_SERVER['REQUEST_METHOD'] == "GET") {
    $method = "get";
    $step;
    if (isset($_GET['step'])) { 
        $step = $_GET['step'];
        } 
 
    }
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $method = "post";
    }

    $stepkey = array_search($step, array_column($steps,'step'));

    if ($step == "admin") {
    echo $twig->render('admin.twig', ['title' => $html->getTitle(),'menu' => $html->getMenu() ]);
    }

if ($step == "version") {
    echo $twig->render('version.twig', ['title' => $html->getTitle(),'menu' => $html->getMenu(),'php' => $html->getPHPversion(),'modules' => $html->getModules() ]);
    }

if ($step == "database") {
    echo $twig->render('database.twig', ['title' => $html->getTitle(),'menu' => $html->getMenu() ]);
    }
if ($steps[$stepkey]['name'] == "terms and conditions") {
    print json_encode(['title' => $html->getTitle(),'name' => $html->getName(), 'conditions' => $html->getConditions() ]);
    }
if ($step == "permissions") {
    echo $twig->render('permissions.twig', ['title' => $html->getTitle(),'menu' => $html->getMenu(),'name' => $html->getName(),'directories' => $html->getDirectories()]);
    }

        
if (!$step) {
    echo $twig->render('install.twig', ['title' => $html->getTitle(),'menu' => $html->getMenu() ]);
    }
?>