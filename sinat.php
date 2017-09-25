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
 echo '<br/><br/>----------新浪短网址API----------<br/><br/>';
 for($i=0;$i<=9;$i++){
    echo 'Long to Short: '.$i.xlUrlAPI(1,'https://typecodes.com/'.$i).'<br/>';
 }
 echo 'Short to Long: '.xlUrlAPI(0,'http://t.cn/8FdW1rm').'<br/><br/>';
?>