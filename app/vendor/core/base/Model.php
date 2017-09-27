<?php 
namespace core\base;
class Model{
	public $pdo;
	public $dsn;
	public $user;
	public $pass;
	public function __Construct(){
		return $this->pdo=new \Pdo("mysql:host=127.0.0.1;dbname=mysql","root","");
	}

	public function tableName(){

		$class=get_class($this);
		$arr=array_reverse(explode("\\",$class));
		preg_match_all("/([A-Z][a-z].*)?/",$arr[0],$res);
		print_r($res);
	}
}
?>
