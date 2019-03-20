<?php
class Autoloader
{
	public static $classMap = array (
		'Securibox\CloudAgents\Document'=> __DIR__ . '/Documents',
		'Securibox\CloudAgents\Document\Entities'=> __DIR__ . '/Documents/Entities',
		'Securibox\CloudAgents\Http'=> __DIR__ . '/Http',
		'Securibox\CloudAgents\Http\JWT'=> __DIR__ . '/Http/JWT',
		'Securibox\CloudAgents\Http\JWT\Parsing'=> __DIR__ . '/Http/JWT/Parsing',
		'Securibox\CloudAgents\Http\JWT\Signer'=> __DIR__ . '/Http/JWT/Signer');
		
    public static function load($classFullName)
    {
		$namespaceParts = explode('\\', $classFullName);
		$class = $namespaceParts[sizeof($namespaceParts) - 1];	
		foreach(Autoloader::$classMap as $namespace => $path){
			$file = $path . '/' . $class . '.php';
			if(file_exists($file)){
				require_once ($file);
				
			}
		}
    }
	
	public function __construct(){
		spl_autoload_register(array($this, 'load'));
	}	
}

new Autoloader();