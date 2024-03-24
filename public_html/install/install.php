<?php

/**
 * @author Constan van Suchtelen van de Haere <constan@hostingbe.com>
 * @copyright 2024 HostingBE
 */

require __DIR__ . '/assets/vendor/autoload.php';
require __DIR__ . '/src/htmloutput.php';

use Install\HtmlOutput;

$html = new HtmlOutput();

$steps = $html->getSteps();

if ($_SERVER['REQUEST_METHOD'] == "GET") {
    $method = $_SERVER['REQUEST_METHOD'];
    $step;
    if (isset($_GET['step'])) { 
        $step = $_GET['step'];
        } 
 
    }

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $method = $_SERVER['REQUEST_METHOD'];
    $body = json_decode(file_get_contents('php://input'));
    $step = $body->step; 
    }

    $stepkey = array_search($step, array_column($steps,'step'));


if ($method == 'POST') {

/*
* check if user aggrees on our terms and conditions
*/
if ($steps[$stepkey]['name'] == "terms and conditions") {
 
 
    if ($body->data->agree == 'true') {
    print json_encode(['status' => 'success','message' => 'thank you !']);  
    exit;
      }
      if ($body->data->agree != 'true') { 
    print json_encode(['status' => 'error','message' => 'You need to agree to the terms and conditions! ' ]);  
    exit;
    }
}

/*
* check the database settings and check connection with these settings
*/
if ($steps[$stepkey]['name'] == "database settings") { 
    foreach ($html->getDatabase() as $field) {
        if (($body->data->{$field['name']} == "") && ($field['required'] == 1)) {
            print json_encode(['status' => 'error','message' => "Database field {$field['name']} is required!"]);  
            exit;    
        }         
    }
    print json_encode(['status' => 'success','message' => "Database settings succesfully saved !"]); 
    exit;
}
/*
* check directory and file permissions
*/
if ($steps[$stepkey]['name'] == "file permissions") {
 
foreach ($html->getDirectories() as $dir) {

    if ($dir['dircheck'] === false) {
    print json_encode(['status' => 'error','message' => "File or directory {$dir['name']} does not exist!"]);  
    exit;
      }
      if ($dir['permission_check'] === false) {
        print json_encode(['status' => 'error','message' => "Permissions file or directory {$dir['name']} are not correct!"]);  
        exit;
          }      
            }

}
    
    print json_encode(['status' => 'error','message' => 'You have reached the end of the internet!' ]);
}


if ($method == 'GET') {

if ($step == "admin") {
    echo $twig->render('admin.twig', ['title' => $html->getTitle(),'menu' => $html->getMenu() ]);
    }

if ($step == "version") {
    echo $twig->render('version.twig', ['title' => $html->getTitle(),'menu' => $html->getMenu(),'php' => $html->getPHPversion(),'modules' => $html->getModules() ]);
    }

if ($steps[$stepkey]['name'] == "database settings") {
    print json_encode(['title' => $html->getTitle(),'name' => $html->getName(), 'content' => $html->getDatabase() ]);
    }
if ($steps[$stepkey]['name'] == "terms and conditions") {
    print json_encode(['title' => $html->getTitle(),'name' => $html->getName(), 'content' => $html->getConditions() ]);
    }

if ($steps[$stepkey]['name'] == "file permissions") {
    print json_encode(['title' => $html->getTitle(),'name' => $html->getName(), 'content' => $html->getDirectories() ]);
    }
       
if (!$step) {
    echo $twig->render('install.twig', ['title' => $html->getTitle(),'menu' => $html->getMenu() ]);
    }
}

?>