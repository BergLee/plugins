<?php 
namespace core\auth;
class Jwt{
	public $header;
	public $payload;
	public $secretKey="xyz";
	public function Jwt(){
		$this->header=[
			"alg"=>"hs256",
			"type"=>"JWT"
		];
		$this->payload=[
			"name"=>"xx",
			"expire"=>666
		];
	}
	public function getToken(){
		return base64_encode(json_encode($this->header)).".".base64_encode(json_encode($this->payload)).".".$this->sign();
	}

	public function verify($token){
		$token=base64_encode(json_encode($this->header));
		$header=json_decode(base64_decode($token));
		print_r($header);
	}

	private function sign(){
		return hash_hmac("sha256",base64_encode(json_encode($this->header)).".".base64_encode(json_encode($this->payload)),$this->secretKey);
	}
}
 ?>
