<?
header("content-type:text/html");
function GithubConnect($url, $oauthKey)
{
	$c = curl_init();
	curl_setopt($c, CURLOPT_VERBOSE, true);
	curl_setopt($c, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
	curl_setopt($c, CURLOPT_USERPWD, $oauthKey.":x-oauth-basic");
	curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($c, CURLOPT_USERAGENT, "49dbe5d6f4fda95e526a");
	curl_setopt($c, CURLOPT_TIMEOUT, 240);
	curl_setopt($c, CURLOPT_FOLLOWLOCATION, true);
	curl_setopt($c, CURLOPT_HTTPGET, true);
	curl_setopt($c, CURLOPT_URL, $url);
	curl_setopt($c, CURLOPT_SSL_VERIFYHOST, 0);
	curl_setopt($c, CURLOPT_SSL_VERIFYPEER, 0);
	$data = curl_exec($c);
	curl_close($c);
	return $data;
}

function GithubTags($user, $repo)
{
  $cachetime = 3600;
	$oauth = "YOUR_KEY";
	$cachepath = "./cache/" . $user . "_" . $repo . "_releases_tag.cache";
	$createnew = false;
	if(!file_exists($cachepath)) $createnew = true;
	else if(filemtime($cachepath) + $cachetime < microtime()) $createnew = true;

	if($createnew) // cache for api limitation
	{
		$data = GithubConnect("https://api.github.com/repos/".$user."/".$repo."/tags", $oauth);
		$data = json_decode($data);
		
		$fw = fopen($cachepath, 'w+');
		fwrite($fw, str_replace("\\/", "/", json_encode($data)));
		fclose($fw);
	}
	else
	{
		$fo = fopen($cachepath, "r");
		$data = json_decode(fread($fo, filesize($cachepath)));
		fclose($fo);
	}
	return $data;
}

function GithubRepo($user, $repo)
{
  $cachetime = 300;
	$oauth = "YOUR_KEY";
	$cachepath = "./cache/" . $user . "_" . $repo . "_releases.cache";
	$createnew = false;
	if(!file_exists($cachepath)) $createnew = true;
	else if(filemtime($cachepath) + $cachetime < microtime()) $createnew = true;

	if($createnew) // cache for api limitation
	{
		$data = GithubConnect("https://api.github.com/repos/".$user."/".$repo."/releases", $oauth);
		$data = json_decode($data);
		
		$fw = fopen($cachepath, 'w+');
		fwrite($fw, str_replace("\\/", "/", json_encode($data)));
		fclose($fw);
	}
	else
	{
		$fo = fopen($cachepath, "r");
		$data = json_decode(fread($fo, filesize($cachepath)));
		fclose($fo);
	}
	return $data;
}
?>
