<?php

/**
 * Created by PhpStorm.
 * User: F1678688
 * Date: 2017/9/5
 * Time: 下午 04:14
 */
namespace app\widgets\ueditor;
class Ftp {
    public $config;
    public $con;
    public function __construct(){
        $this->config=\Yii::$app->params["FtpConfig"];
        $this->con=ftp_connect($this->config['ftpIP']) or die("ftp connect faild");
        ftp_login($this->con,$this->config['ftpUser'],$this->config['ftpPwd']) or die("login faild");
        ftp_pasv($this->con, true);
        return $this;

    }


    public function put($dest,$file){
        $dest=trim($dest,"/");
        $file=trim($file,"/");
        if(@ftp_put($this->con,$dest,$file,FTP_BINARY)){
            return true;
        }
    }


    public function matchFiles($path, $allowFiles=[], &$files = array()){
        static $data=[];
        $items=ftp_nlist($this->con,trim($path,"/"));
        foreach ($items as $item){
            if($this->ftp_dir_exists($item)){
                $this->matchFiles($item,$allowFiles);
            }else{
                if(count($allowFiles)>0){
                    if(in_array(substr(strrchr($item,"."),1),$allowFiles)){
                        $data[]=[
                            "url"=>$this->config["httpIP"].$item
                        ];
                    }
                }else{
                    $data[]=[
                        "url"=>$this->config["httpIP"].$item
                    ];
                }
            }
        }
        return $data;
    }

    public function mkdirs($name){
        $params=explode("/",trim($name,"/"));
        $level=0;
        $flag=true;
        while(count($params)>0){
            $dir=array_shift($params);
            if(@ftp_chdir($this->con,$dir)){
                $level++;
            }elseif(@ftp_mkdir($this->con,$dir)){
                @ftp_chdir($this->con,$dir);
                $level++;
            }else{
                $flag=false;
                break;
            }
        }

        while($level>0){
            @ftp_cdup($this->con);
            $level--;
        }
        return $flag;
    }


    public function rmdirs($name){
        if($this->ftp_dir_exists($name)){
            $items=ftp_nlist($this->con,"./".$name);
            foreach ($items as $item) {
                if($this->ftp_dir_exists($item)){
                    $this->rmdirs($item);
                }else{
                    @ftp_delete($this->con,$item);
                }
            }
            return @ftp_rmdir($this->con,$name);
        }

    }


    public function ftp_dir_exists($name){
        $params=explode("/",trim($name,"/"));
        $level=0;
        $flag=true;
        while(count($params)>0){
            if(@ftp_chdir($this->con,array_shift($params))){
                $level++;
            }else{
                $flag=false;
                break;
            }
        }

        while($level>0){
            @ftp_cdup($this->con);
            $level--;
        }

        return $flag;
    }


    public function __destruct(){
        @ftp_close($this->con);
    }
}