<?php 
namespace controllers;
use core\base\Controller;
class SiteController extends Controller{
	public function index(){
		$this->assign("name","xxx");
        $this->assign("gendar","male");
        $this->assign("email","12345@qq.com");
		return $this->display("index");
//		$user=new \models\UserList();
//		echo $user->tableName();
		// throw new \Exception("Unauthorized",403);
	}

	public function create(){
		exit(file_get_contents("php://input"));
		exit(json_encode(["create user ok"]));
	}

	public function update(){
		exit(json_encode(["update user ok"]));
	}

	public function delete(){
		exit(json_encode(["delete user ok"]));
	}
}
 ?>
