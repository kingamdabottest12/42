<?php

$store_locally = true; /* change to false if you don't want to host videos locally */ 

function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

function downloadVideo($video_url, $geturl = false)
{
    $ch = curl_init();
    $headers = array(
        'Range: bytes=0-',
    );
    $options = array(
        CURLOPT_URL            => $video_url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HEADER         => false,
        CURLOPT_HTTPHEADER     => $headers,
        CURLOPT_FOLLOWLOCATION => true,
        CURLINFO_HEADER_OUT    => true,
        CURLOPT_USERAGENT => 'okhttp',
        CURLOPT_ENCODING       => "utf-8",
        CURLOPT_AUTOREFERER    => true,
        CURLOPT_COOKIEJAR      => 'cookie.txt',
	CURLOPT_COOKIEFILE     => 'cookie.txt',
        CURLOPT_REFERER        => 'https://www.tiktok.com/',
        CURLOPT_CONNECTTIMEOUT => 30,
        CURLOPT_SSL_VERIFYHOST => false,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_TIMEOUT        => 30,
        CURLOPT_MAXREDIRS      => 10,
    );
    curl_setopt_array( $ch, $options );
    if (defined('CURLOPT_IPRESOLVE') && defined('CURL_IPRESOLVE_V4')) {
      curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
    }
    $data = curl_exec($ch);
    $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    if ($geturl === true)
    {
        return curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
    }
    curl_close($ch);
    $filename = "user_videos/" . generateRandomString() . ".mp4";
    $d = fopen($filename, "w");
    fwrite($d, $data);
    fclose($d);
    return $filename;
}

if (isset($_GET['url']) && !empty($_GET['url'])) {
    if ($_SERVER['HTTP_REFERER'] != "") {
        $url = $_GET['url'];
        $name = downloadVideo($url);
        echo $name;
        exit();
    }
    else
    {
        echo "";
        exit();
    }
}

function getContent($url, $geturl = false)
  {
    $ch = curl_init();
    $options = array(
        CURLOPT_URL            => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HEADER         => false,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_USERAGENT => 'Mozilla/5.0 (Linux; Android 5.0; SM-G900P Build/LRX21T) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/86.0.4240.111 Mobile Safari/537.36',
        CURLOPT_ENCODING       => "utf-8",
        CURLOPT_AUTOREFERER    => false,
        CURLOPT_COOKIEJAR      => 'cookie.txt',
	CURLOPT_COOKIEFILE     => 'cookie.txt',
        CURLOPT_REFERER        => 'https://www.tiktok.com/',
        CURLOPT_CONNECTTIMEOUT => 30,
        CURLOPT_SSL_VERIFYHOST => false,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_TIMEOUT        => 30,
        CURLOPT_MAXREDIRS      => 10,
    );
    curl_setopt_array( $ch, $options );
    if (defined('CURLOPT_IPRESOLVE') && defined('CURL_IPRESOLVE_V4')) {
      curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
    }
    $data = curl_exec($ch);
    $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    if ($geturl === true)
    {
        return curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
    }
    curl_close($ch);
    return strval($data);
  }

  function getKey($playable)
  {
  	$ch = curl_init();
  	$headers = [
    'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
    'Accept-Encoding: gzip, deflate, br',
    'Accept-Language: en-US,en;q=0.9',
    'Range: bytes=0-200000'
	];

    $options = array(
        CURLOPT_URL            => $playable,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HEADER         => false,
        CURLOPT_HTTPHEADER     => $headers,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_USERAGENT => 'Mozilla/5.0 (X11; Ubuntu; Linux i686; rv:28.0) Gecko/20100101 Firefox/28.0',
        CURLOPT_ENCODING       => "utf-8",
        CURLOPT_AUTOREFERER    => false,
        CURLOPT_COOKIEJAR      => 'cookie.txt',
	CURLOPT_COOKIEFILE     => 'cookie.txt',
        CURLOPT_REFERER        => 'https://www.tiktok.com/',
        CURLOPT_CONNECTTIMEOUT => 30,
        CURLOPT_SSL_VERIFYHOST => false,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_TIMEOUT        => 30,
        CURLOPT_MAXREDIRS      => 10,
    );
    curl_setopt_array( $ch, $options );
    if (defined('CURLOPT_IPRESOLVE') && defined('CURL_IPRESOLVE_V4')) {
      curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
    }
    $data = curl_exec($ch);
    $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    $tmp = explode("vid:", $data);
    if(count($tmp) > 1){
    	$key = trim(explode("%", $tmp[1])[0]);
    }
    else
    {
    	$key = "";
    }
    return $key;
  }
?>

<!DOCTYPE html>
<html>
<head>
	<title>TikTok Video Downloader</title>
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
	<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
<link href="https://fonts.googleapis.com/css2?family=Gotu&display=swap" rel="stylesheet">
<style type="text/css">
	html, body
	{
		font-family: "Gotu"
	}
	input
	{
		padding: 5px;
		border-radius: 10px;
		border-style: solid;
		border-color: blue;
		transition-duration: 0.5s;
		width: 80%;
	}
	input:focus
	{
		border-color: skyblue;
		transition-duration: 0.5s;
	}
    body {
      background-image: url("https://g.top4top.io/p_21168we641.jpg"), url("paper.gif");
      background-color: #cccccc;
    }
    h1 {
  font-size: 6em;
  color: #fff;
  font-family: sans-serif;
}
p {
  font-size: 1.5em;
  color: #fff;
  font-family: sans-serif;
}
p1 {
  font-size: 1.5em;
  color: #fff;
  font-family: sans-serif;
}
li {
  font-size: 1.5em;
  color: #fff;
  font-family: sans-serif;
}
li1 {
  font-size: 1em;
  color: #fff;
  font-family: sans-serif;
  font-weight: bold;
}
   
</style>
</head>
<body>
	<div class="text-center p-5">
<h1 class="mt-5"><b>Tiktok Video Downloader</b></h1>
	</div>
	<div class="text-center">
		<P>Paste a video url below and press "Download". Now scroll down to "Download Video" button or "Download Watermark Free!" button and press to initiate the download process.</p><br><br>
		<p1>🔥🔥Past Link here🔥🔥</p1>
        <form method="POST" class="mt-2">
			<input type="text" placeholder="🔥🔥Past Tiktok Video Link here🔥🔥" name="tiktok-url"><br><br>
			<button class="btn btn-success" type="submit">Download</button>
		</form>
	</div>
	<?php
		if (isset($_POST['tiktok-url']) && !empty($_POST['tiktok-url'])) {
			$url = trim($_POST['tiktok-url']);
			$resp = getContent($url);
			//echo "$resp";
			$check = explode('"downloadAddr":"', $resp);
			if (count($check) > 1){
				$contentURL = explode("\"",$check[1])[0];
                $contentURL = str_replace("\\u0026", "&", $contentURL);
				$thumb = explode("\"",explode('og:image" content="', $resp)[1])[0];
				$username = explode('/',explode('"$pageUrl":"/@', $resp)[1])[0];
				$create_time = explode(',', explode('"createTime":', $resp)[1])[0];
				$dt = new DateTime("@$create_time");
				$create_time = $dt->format("d M Y H:i:s A");
				$videoKey = getKey($contentURL);
				$cleanVideo = "https://api2-16-h2.musical.ly/aweme/v1/play/?video_id=$videoKey&vr_type=0&is_play_url=1&source=PackSourceEnum_PUBLISH&media_type=4";
				$cleanVideo = getContent($cleanVideo, true);
				if (!file_exists("user_videos") && $store_locally){
					mkdir("user_videos");
				}
				if ($store_locally){
					?>
                    <script type="text/javascript">
                        $(document).ready(function(){
                            $('#wmarked_link').text("Please wait ...");
                            $.get('./<?php echo basename($_SERVER['PHP_SELF']); ?>?url=<?php echo urlencode($contentURL); ?>').done(function(data)
                                {
                                    $('#wmarked_link').removeAttr('disabled');
                                    $('#wmarked_link').attr('onclick', 'window.location.href="' + data + '"');
                                    $('#wmarked_link').text("Download Video");
                                });
                        });
                    </script>
                    <?php
				}
		?>
		<script>
		    $(document).ready(function(){
		        $('html, body').animate({
					    scrollTop: ($('#result').offset().top)
					},1000);
		    });
		</script>
	<div class="border m-3 mb-5" id="result">
	   
		<div class="row m-0 p-2">
			<div class="col-sm-5 col-md-5 col-lg-5 text-center"><img width="250px" height="250px" src="<?php echo $thumb; ?>"></div>
			<div class="col-sm-6 col-md-6 col-lg-6 text-center mt-5"><ul style="list-style: none;padding: 0px">
				<li>A Video By ➔ <b>@<?php echo $username; ?></b></li>
				<li>Uploaded On ➔ <b><?php echo $create_time; ?></b></li>
				<li><button id="wmarked_link" disabled="disabled" class="btn btn-primary mt-3" onclick="window.location.href='<?php if ($store_locally){ echo $filename;} else { echo $contentURL; } ?>'">Download Video</button> <button class="btn btn-info mt-3" onclick="window.location.href='<?php echo $cleanVideo; ?>'">Download Watermark Free!</button></li>
				<li1><div class="alert alert-primary mb-0 mt-3">වීඩියෝව Directly විවෘත වුවහොත් CTRL+S එබීමෙන් Download කරගත හැකි අතර දුරකථනයෙන් එය Download කරන්න ඔනෙ නම් පහළ වම් කෙලවරේ තියන තිත් තුන දිලා Download දෙන්න.</div></li1>
			</ul></div>
		</div>
	</div>
	<?php
			}
			else
			{
				?>
				<script>
        		    $(document).ready(function(){
        		        $('html, body').animate({
        					    scrollTop: ($('#result').offset().top)
        					},1000);
        		    });
        		</script>
				<div class="mx-5 px-5 my-3" id="result">
				    <div class="text-center"><br>Bot/Scraper Development Services: <a target="_blank" href="https://www.we-can-solve.com">We-Can-Solve.com</a></div>
					<div class="alert alert-danger mb-0"><b>Please double check your url and try again.</b></div>
				</div>

				<?php
			}
		}
	?>
	<div class="m-5">
		&nbsp;
	</div>
	<B><div class="bg-dark text-white" style="position: fixed; bottom: 0;width: 100%;padding:15px">Developed by ★彡[PASINDU SAMARA$INGHA]彡★<span style="float: right;">42 Set Eka Team Work   Copyright &copy; <?php echo date("Y"); ?></span></div>
    </B><script type="text/javascript">
        window.setInterval(function(){
            if ($("input[name='tiktok-url']").attr("placeholder") == "https://www.tiktok.com/@username/video/1234567890123456789") {
                $("input[name='tiktok-url']").attr("placeholder", "https://vm.tiktok.com/a1b2c3/");
            }
            else
            {
                $("input[name='tiktok-url']").attr("placeholder", "https://www.tiktok.com/@username/video/1234567890123456789");
            }
        }, 3000);
    </script>
</body>
</html>
