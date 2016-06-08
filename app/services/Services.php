<?php 
namespace Gabs\Services;
use Gabs\Services\Exceptions as Exceptions;


abstract class Services
{
    public static function getService($name)
    {
    	$className = "\\Gabs\\Services\\{$name}Service";
		
        if ( ! class_exists($className)) {
            throw new Exceptions\InvalidServiceException("Class {$className} no existe.");
        }
        
        return new $className();
    }
}
