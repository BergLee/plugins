<?php 
namespace core\base;
class Controller{
	public $view;
	public $vars=[];
	public function assign($name,$val){
		$this->vars[$name]=$val;
	}

	public function display($tmpl){
		$file=\Application::$config->viewPath."/".$tmpl.".php";
		extract($this->vars);
		include $file;
	}
};
 ?>
