# PHP_github_RESTv3
Github RESTv3 API Helper

## Preview:

https://ffxiv.io/github/r/laiglinne-ff/PHP_github_RESTv3

## Example:
```PHP
require("func.php");

$user = "laiglinne-ff";
$repo = "Samhain.ACTInstaller.Client";
$data = ReleasesParse($_GET["user"], $_GET["repo"]);


function ReleasesParse($user, $repo)
{
	$data = GithubRepo($user, $repo);
	$tags = GithubTags($user, $repo);
	$mdata = [];
	foreach($data as $val)
	{
		$rdata = new stdClass();
		$rdata->author = new stdClass();
		$rdata->author->name = $val->author->login;
		$rdata->author->avatar = $val->author->avatar_url;
		$rdata->author->url = "https://github.com/" . $user;
		$rdata->files = [];
		foreach($val->assets as $files)
		{
			$fdata = new stdClass();
			$fdata->name = $files->name;
			$fdata->size = $files->size;
			$fdata->downloaded = $files->download_count;
			$fdata->header = $files->content_type;
			$fdata->url = $files->browser_download_url;
			$rdata->files[] = $fdata;
		}
		$rdata->name = $val->name;
		$rdata->tag = $val->tag_name;
		$rdata->commit = "forked";
		foreach($tags as $tag)
		{
			if($val->tag_name == $tag->name)
			{
				$rdata->commit = $tag->commit->sha;
			}
		}
		$rdata->pre = $val->prerelease;
		$rdata->body = $val->body;

		$mdata[] = $rdata;
	}
	return $mdata;
}
```
