
<?php
 /**
 * @author: vfhky 20130304 20:10
 * @description: PHP调用新浪短网址API接口
 * @reference: http://t.cn/8FgeBI2
 * @param string $type: 非零整数代表长网址转短网址,0表示短网址转长网址
 */
 function xlUrlAPI($type,$url){
   /* 这是我申请的APPKEY，大家可以测试使用 */
   $key = '724492140';
   if($type)
      $baseurl = 'http://api.t.sina.com.cn/short_url/shorten.json?source='.$key.'&url_long='.$url;
   else
      $baseurl = 'http://api.t.sina.com.cn/short_url/expand.json?source='.$key.'&url_short='.$url;
   $ch=curl_init();
   curl_setopt($ch, CURLOPT_URL,$baseurl);
   curl_setopt($ch, CURLOPT_HEADER, 0);
   curl_setopt($ch, CURLOPT_TIMEOUT, 15);
   curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
   $strRes=curl_exec($ch);
   curl_close($ch);
   $arrResponse=json_decode($strRes,true);
   if (isset($arrResponse->error) || !isset($arrResponse[0]['url_long']) || $arrResponse[0]['url_long'] == '')
      return 0;
   if($type)
      return $arrResponse[0]['url_short'];
   else
      return $arrResponse[0]['url_long'];
 }
 function getShortUrls(){
     $shorturls = "";
     $longurls = getLongUrls();
     if(!$longurls || !isset($_POST["longurls"])){
         return "";
     }else{
         $longurlArray = explode(PHP_EOL,$_POST['longurls']);
         for($c=0; $c<sizeof($longurlArray); $c++ ){
            if (filter_var( $longurlArray[$c], FILTER_VALIDATE_URL)) {
                $shorturls = $shorturls . xlUrlAPI(1,$longurlArray[$c]) . PHP_EOL;
               // echo "valid" . $c ."<br/>";
            } else {
                $shorturls = $shorturls . " " .$longurlArray[$c]. PHP_EOL;
               // echo "invalid" . $c ."<br/>";
            }
             
         }
     }
     return $shorturls;
 }

 function getLongUrls(){
    if(!isset($_POST["longurls"])){
        return "";
    }else{
        $longurls = $_POST["longurls"];
        return $longurls;
    }
}
//刷新缓存代码

function getPurgeURLdata(){
     if(!isset($_POST["purgeurls"])){
         return '';
     }else{
	 $inputURL = $_POST['purgeurls'];
		if (filter_var( $inputURL, FILTER_VALIDATE_URL)) {
				if($inputURL[strlen($inputURL)-1]!='/'){
					$inputURL = $inputURL.'/';
				}
				$js = '{"files":["'.
				$inputURL .'","'.
				$inputURL . '?from=groupmessage","'.
				$inputURL . '?from=groupmessage&isappinstalled=0","'.
				$inputURL . '?from=timeline","'.
				$inputURL . '?from=timeline&isappinstalled=0","'.
				$inputURL . '?from=singlemessage"]}';
			
		 }else{
			 return '';
		 }
		// $inputURLParameterlist = substr($inputURLParameterlist,0,-1);
     }
	 $purgeURL = 'https://api.cloudflare.com/client/v4/zones/1ea3465bf3f0c691de549ce55a7360a6/purge_cache';
	$ch=curl_init();
   curl_setopt($ch, CURLOPT_URL,$purgeURL);
   curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'X-Auth-Email:admin@chinesenzherald.co.nz',
    'X-Auth-Key: 83ce0a7bd06c547d17ddc44bd80b1bd3e8cb8',
	'Content-Type: application/json'
    ));
   curl_setopt($ch, CURLOPT_TIMEOUT, 15);
   curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
   curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
   curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
   curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
   echo $js;
   curl_setopt($ch, CURLOPT_POSTFIELDS,$js);
   $strRes=curl_exec($ch);
   if(!$strRes = curl_exec($ch)) 
    { 
        trigger_error(curl_error($ch)); 
    }
   echo '----strRes:'.$strRes;
   curl_close($ch);
   $arrResponse=json_decode($strRes,true);
   return $arrResponse->success;
 }

?>
<!doctype html>
<html lang="zh">

<head>
    <meta name="description" content="短网址批量生成 " />
    <meta http-equiv="Content-type" content="text/html; charset=utf-8" />

    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/css/bootstrap.min.css" integrity="sha384-/Y6pD6FV/Vv2HJnA6t+vslU6fwYXjCFtcEpHbNJ0lyAFsXTsjBbfaDjzALeQsN6M"
        crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN"
        crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js" integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4"
        crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/js/bootstrap.min.js" integrity="sha384-h0AbiXch4ZDo7tp9hKZ4TsHbi047NrKGLO3SEJAg45jXxnGIfYzk4Si90RDIqNm1"
        crossorigin="anonymous"></script>
    <script>
        (function (i, s, o, g, r, a, m) {
            i['GoogleAnalyticsObject'] = r; i[r] = i[r] || function () {
                (i[r].q = i[r].q || []).push(arguments)
            }, i[r].l = 1 * new Date(); a = s.createElement(o),
                m = s.getElementsByTagName(o)[0]; a.async = 1; a.src = g; m.parentNode.insertBefore(a, m)
        })(window, document, 'script', 'https://www.google-analytics.com/analytics.js', 'ga');

        ga('create', 'UA-83860431-1', 'auto');
        ga('send', 'pageview');
    </script>
    <script type="text/javascript">
        $(document).ready(function () {
            $("#toshort").click(function () {
                $("#urls").submit();
            });
        });
		$(document).ready(function () {
            $("#refreshCache").click(function () {
                $("#purgeurl").submit();
            });
        });
    </script>
</head>

<body>

    <div class="container">
	  <h3 style="text-align:center">缓存刷新</h3>
	  <form action="" method="post" id="purgeurl">
        <div class="row">
                刷新链接列表<br/>
                <textarea name="purgeurls" id="purgeurls" class="form-control" rows="2"></textarea><button type="button" class="btn btn-primary" name="refreshCache" id="refreshCache">刷新</button>
		</div>
		<div class="row">
		<?=getPurgeURLdata()?>
		</div>
    </form>
        <!-- Content here -->
        <br/>
        <h3 style="text-align:center">新浪短链接批量生成工具</h3>
        1. 可以只贴长链接列表，每一行一个链接。点击“变短”之后，短链接会出现在右侧窗口<br/>
        2. 建议同时贴新闻标题和长链接， 以免出现无法对应的情况。每一个标题的下一行是它的链接。譬如：<br/>
        <div style="font-size:0.8em;color:blue">
        韩前总统朴槿惠首次出庭受审 全盘否认18项罪名<br/>	http://www.chinesenzherald.co.nz/news/international/park-geun-hye-first-trial<br/>
        政府或将出台租房新规：房屋损坏租客需赔偿！<br/>	http://www.chinesenzherald.co.nz/news/property/law-change-may-see-tenants-liable-for-a-landlords-insurance-excess/<br/>
        5月24日天气和早间新闻：房东房客们注意，房屋损坏租客将需赔偿！英国恐袭凶手年仅22岁<br/>	http://www.chinesenzherald.co.nz/news/new-zealand/morning-20170524/<br/>
        “不能居住”的70万纽币物业，你会买吗？<br/>	http://www.chinesenzherald.co.nz/news/property/historic-boatshed-for-sale/<br/>
        英国再遭恐袭，欧洲可能永无宁日<br/>	http://www.chinesenzherald.co.nz/news/international/explosions-in-manchester/<br/><br/>
        </div>
        <form action="" method="post" id="urls">
        <div class="row">
            <div class="col-5">
                长链接列表<br/>
                <textarea name="longurls" id="longurls" class="form-control" rows="20"><?=getLongUrls()?></textarea>
            </div>
            <div class="col-2" style="text-align:center">
                <br/><br/>
                <button type="button" class="btn btn-primary" name="toshort" id="toshort">变短</button>
            </div>
            <div class="col-5">
                短链接列表<br/>
                <textarea name="shorturls" id="shorturls" class="form-control" rows="20"><?=getShortUrls()?></textarea>
            </div>
        </div>
    </form>
    </div>



</body>

</html>