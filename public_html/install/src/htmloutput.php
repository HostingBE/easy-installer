<?php

namespace Install;

use Symfony\Component\Yaml\Yaml;
use Symfony\Component\Yaml\Exception\ParseException;

class HtmlOutput {

    private $config;

public function __construct() {
    $this->config = $this->getConfig();
}


private function getConfig($yamlfile = __DIR__ . '/../config/config.yaml') {
    
    try {
        $cfg = Yaml::parseFile($yamlfile);
    } catch (ParseException $exception) {
        printf('Unable to parse the YAML string: %s', $exception->getMessage());
    }

    return (array) $cfg;
    }

public function getPHPversion() {
if (phpversion() < $this->config['phpversion']) {
    return array('version' => phpversion(),'check' => false,'message' => 'your php version ' . phpversion() . ' does not meet the standard ' . $this->config['phpversion']);
    }
if (phpversion() >= $this->config['phpversion']) {
    return array('version' => phpversion(),'check' => true,'message' => 'your php version ' . phpversion() . ' is good to go at least ' . $this->config['phpversion']);
    }    
return array();
}


public function getName() {
    return $this->config['name'];    
    }

 public function getDatabase() {
    return $this->config['database']['settings'];    
    }

public function getTitle() {
    return $this->config['title'];       
    }

public function getModules() {
    return $this->config['modules'];       
    }

public function getDirectories() {

    $dir = $this->config['directories']['settings'];
    foreach ($dir as $key => $val) {
        if ($val['type'] == 'file') {
        $dir[$key]['dircheck'] = $this->checkFile(__DIR__.'/../../../'.$val['name']);
        }
        if ($val['type'] == 'dir') {
        $dir[$key]['dircheck'] = $this->checkDirectory(__DIR__.'/../../../'.$val['name']);
        }
        $dir[$key]['permissions'] = $this->checkPermissions(__DIR__.'/../../../'.$val['name']); 
        $dir[$key]['permission_check'] = false;
        if ($dir[$key]['permissions'] == $val['chmod']) {
            $dir[$key]['permission_check'] = true;
            }
        }
    return $dir;       
    }

public function getConditions() {
    return $this->config['conditions'];       
    }

public function getSteps() {
    return $this->config['steps'];       
    }
//
/* public function getMenu() {
    $sort = array_column($this->config['menu'], 'sort');
    array_multisort($sort, SORT_ASC, $this->config['menu']);
    return $this->config['menu'];
    } */

private function checkPermissions($directory) {
      return substr(decoct(fileperms($directory)), -3);
      }

private function checkFile($file) {
    
        if (is_file($file)) {
        return true;
        } else {
        return false;
        }
      }

private function checkDirectory($directory) {
    
    if (is_dir($directory)) {
    return true;
    } else {
    return false;
    }
  }
}
?>