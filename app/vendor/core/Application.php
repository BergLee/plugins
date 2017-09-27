<?php 
class Application{
	public static $config;
	public function Application($config){
		self::$config=json_decode(json_encode($config));
	}
	public function run(){
		$router=$this->parseRoute();
		$this->dispatcher($router);
	}

	public static function autoload($className){
		$alias=self::$config->alias;
		$path=$className;
		foreach($alias as $key=>$val){
			if(in_array($key,explode("\\",trim($className,"/")))){
				$path=str_replace($key,$val,$className);
				$path=str_replace("\\","/",$path);
				break;
			}
		}
		require_once("{$path}.php");
		return new $className;
	}

	public function parseRoute(){
		$pathArr=self::$config->route->default;
			$pathInfo=$_SERVER["PATH_INFO"];
			$pathParams=explode("/",trim($pathInfo,"/"));
			if(isset($pathParams[0])) $pathArr[0]=$pathParams[0];
			if(isset($pathParams[1])) $pathArr[1]=$pathParams[1];
		return $pathArr;
	}

	public function dispatcher($router){
		$ctrl="controllers\\".ucwords($router[0])."Controller";
		$act=$router[1];
        echo (new $ctrl)->$act();
	}

}
spl_autoload_register(["Application","autoload"]);
?>