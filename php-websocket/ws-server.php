<?php
header("Content-type:text/html;charset=utf8");
$addr="127.0.0.1";
$port=6644;
$socket=socket_create(AF_INET,SOCK_STREAM,SOL_TCP);
socket_bind($socket, $addr,$port);
socket_listen($socket,10);
$clients=array($socket);
while(true){
  $read=$clients;
  $write=NULL;
  $exception=NULL;
  if(socket_select($read,$write,$exception,0)<1){
    continue;
  }
  if(in_array($socket,$read)){
    $clients[]=$newsock=socket_accept($socket);
    $data=socket_read($newsock,1024);
    handshake($data,$newsock,$addr,$port);
    socket_getpeername($newsock, $ip,$port);
    $response = data_mask("123");
    sendmessage($response,$clients);
    $key=array_search($socket,$read);
    unset($read[$key]);
  }
  foreach($read as $read_socket){
      $data=socket_read($read_socket,1024);
      $data=data_unmask($data);
      // $data=json_decode($data);
      if($data){
        // $response=data_mask(json_encode(array("type"=>"user","name"=>$data->name,"message"=>$data->message)));
	$response=data_mask($data);
        sendmessage($response,$clients);
      }
  }
}
socket_close($newsock);
socket_close($socket);
function sendmessage($msg,$clients)
{
    foreach($clients as $changed_socket)
    {
        @socket_write($changed_socket,$msg,strlen($msg));
    }
    return true;
}
function data_unmask($text) {
    $length = ord($text[1]) & 127;
    if($length == 126) {
        $masks = substr($text, 4, 4);
        $data = substr($text, 8);
    }
    elseif($length == 127) {
        $masks = substr($text, 10, 4);
        $data = substr($text, 14);
    }
    else {
        $masks = substr($text, 2, 4);
        $data = substr($text, 6);
    }
    $text = "";
    for ($i = 0; $i < strlen($data); ++$i) {
        $text .= $data[$i] ^ $masks[$i%4];
    }
    return $text;
}
//编码数据
function data_mask($text)
{
    $b1 = 0x80 | (0x1 & 0x0f);
    $length = strlen($text);
    if($length <= 125)
        $header = pack('CC', $b1, $length);
    elseif($length > 125 && $length < 65536)
        $header = pack('CCn', $b1, 126, $length);
    elseif($length >= 65536)
        $header = pack('CCNN', $b1, 127, $length);
    return $header.$text;
}
function handshake($data,$socket,$host,$port){
  $headers = array();
  $lines = preg_split("/\r\n/", $data);
  foreach($lines as $line)
  {
      $line = chop($line);
      if(preg_match('/\A(\S+): (.*)\z/', $line, $matches))
      {
          $headers[$matches[1]] = $matches[2];
      }
  }
  $secKey = $headers['Sec-WebSocket-Key'];
  $secAccept = base64_encode(pack('H*', sha1($secKey . '258EAFA5-E914-47DA-95CA-C5AB0DC85B11')));
  $upgrade  = "HTTP/1.1 101 Web Socket Protocol Handshake\r\n" .
  "Upgrade: websocket\r\n" .
  "Connection: Upgrade\r\n" .
  "WebSocket-Origin: $host\r\n" .
  "WebSocket-Location: ws://$host:$port/demo/shout.php\r\n".
  "Sec-WebSocket-Accept:$secAccept\r\n\r\n";
  socket_write($socket,$upgrade,strlen($upgrade));
}
?>