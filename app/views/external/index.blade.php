<?
error_reporting(E_ALL);
	$url = 'http://ilu.zhbluzern.ch/F/?/&func=find-b&find_code=SYS&request=000277165';
	$url = 'http://desarrolloat.ananas.travel';
 	$ch = curl_init();  
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/2.0 (compatible; MSIE 3.02; Update a; AK; Windows 95)");
  curl_setopt($ch, CURLOPT_HTTPGET, true);
  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 6000);
  $got_html = curl_exec($ch); 
  var_dump($got_html);
?>