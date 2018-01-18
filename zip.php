<?php
header("Content-type:text/html;charset=utf8");
function S($dir){
	static $data;
	$dirs=scandir($dir);
		foreach ($dirs as $key => $val) {
			if($val!="." & $val!=".."){
				$file=$dir."/".$val;
				if(is_file($file)){
					$data[]=$file;
				}
				if(is_dir($file)){
					$data[]=$file;
					S($file);
				}
			}
		}
		return $data;
}

function compress($dir){
	static $zip;
	static $data;
	if(!isset($data)){
	echo <<<HTML
	<html>
		<body>
			<script>
				document.body.innerText="";
			</script>
			<p>.....正在扫描.....</p>
		</body>
	</html>
HTML;
		ob_flush();
		flush();
		$data=S($dir);
	}
	if(!isset($zip)){
		$zip=new ZipArchive();
		if(!$zip->open("project.zip",ZipArchive::CREATE)){
			die("open faild");
		}
	}

	foreach ($data as $key => $value) {
		if(is_file($value)){
			$zip->addFile($value);
		}

		if(is_dir($value)){
			$zip->addEmptyDir($value);
		}

		usleep(1);
		$s=round($key/count($data)*100,1);
	echo <<<HTML
	<html>
		<body>
			<script>
				document.body.innerText="";
			</script>
			<p>当前进度：{$s}%</p>
		</body>
	</html>
HTML;
		ob_flush();
		flush();
	}



	echo <<<HTML
	<html>
		<body>
			<script>
				document.body.innerText="";
			</script>
			<p>.....后台处理中.....</p>
		</body>
	</html>
HTML;
		ob_flush();
		flush();


	$zip->close();

	echo <<<HTML
	<html>
		<body>
			<script>
				document.body.innerText="";
			</script>
			<p>.....压缩完成.....</p>
		</body>
	</html>
HTML;
		ob_flush();
		flush();
}
set_time_limit(0);
compress("demo");


// $x=0;
// while($x<1000000){
// 	$x++;
// 	$m=memory_get_peak_usage();
// 	echo <<<HTML
// 	<html>
// 		<body>
// 			<script>
// 				document.body.innerText="";
// 			</script>
// 			<p>{$x}</p>
// 			<p>{$m}</p>
// 		</body>
// 	</html>
// HTML;
// 	ob_flush();
// 	flush();
// 	usleep(100000);
// }
