
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
     if(!isset($_POST["longurls"])){
         return "";
     }else{
         $longurls = $_POST["longurls"];

         return $longurls;
     }
 }

 function getLongUrls(){
    if(!isset($_POST["longurls"])){
        return "";
    }else{
        $longurls = $_POST["longurls"];
        return $longurls;
    }
}
 /*
 echo '<br/><br/>----------新浪短网址API----------<br/><br/>';
 for($i=0;$i<=9;$i++){
    echo 'Long to Short: '.$i.xlUrlAPI(1,'https://typecodes.com/'.$i).'<br/>';
 }
 echo 'Short to Long: '.xlUrlAPI(0,'http://t.cn/8FdW1rm').'<br/><br/>';
 */
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

        function cleanArray(actual) {
            var newArray = new Array();
            for (var i = 0; i < actual.length; i++) {
                if ($.trim(actual[i])) {
                    newArray.push($.trim(actual[i]));
                }
            }
            return newArray;
        }

        function generateShortURLs(longlist) {
            //$("#shorturls").val(longlist);
            $("#shorturls").text("");
           var shorturl = "";
            for (i = 0; i < longlist.length; i++) {
                shorturl = longlist[i] + '\n';
                $("#shorturls").append(shorturl);
            }

        }

        function shortenURL(longurl) {
            $.get('', function (data) {
                //data is the JSON string
                alert(data);
            });
        }
    </script>
</head>

<body>

    <div class="container">
        <!-- Content here -->
        <br/>
        <h3 style="text-align:center">新浪短链接批量生成工具</h3>
        <form action="" method="post" id="urls">
        <div class="row">
            <div class="col-5">
                请贴入长链接列表，每一行一个链接。<br/>
                <textarea name="longurls" id="longurls" class="form-control" rows="20"></textarea>
            </div>
            <div class="col-2" style="text-align:center">
                <br/><br/>
                <button type="button" class="btn btn-primary" name="toshort" id="toshort">变短</button>
            </div>
            <div class="col-5">
                短链接列表<br/>
                <textarea name="shorturls" id="shorturls" class="form-control" rows="20"><?php if(isset($_POST["longurls"]))?><?=getShortUrls()?></textarea>
            </div>
        </div>
    </form>
    </div>



</body>

</html>