<?php
$clients=array();
$addr='127.0.0.1';
$port=6644;
$socket=socket_create(AF_INET,SOCK_STREAM,SOL_TCP);
socket_set_nonblock($socket);
socket_bind($socket,$addr,$port);
socket_listen($socket,5);
$clients=array($socket);
while(true){
  $read=$clients;
  $write  = NULL;
  $except = NULL;
  if(socket_select($read, $write, $except, 0) < 1){
    continue;
  }
  if (in_array($socket, $read)) {
    $clients[] = $newsock = socket_accept($socket);
    socket_getpeername($newsock, $ip);
    $msg="connected:$ip";
    echo $msg."\n\r";
    //客户端列表不包括自己
    $key = array_search($socket, $read);
    unset($read[$key]);
  }
  foreach($read as $read_sock){
    $data = @socket_read($read_sock, 1024);
    echo $data."\r\n";
    if ($data === false) {
      $key = array_search($read_sock, $clients);
      unset($clients[$key]);
      continue;
    }
    $data = trim($data);
    foreach ($clients as $send_sock) {
      if ($send_sock == $socket || $send_sock == $read_sock){
        continue;
      }
      socket_write($send_sock, $data."\n");
    }
  }
}
socket_close($socket);
?>