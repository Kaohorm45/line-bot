<?php

$channelAccessToken = 'nHTuduD8rzW0kO4obYblXQDI9vgJOgx+ZG9ngQfkxxgiBF4snP/cC9SpbppxqyIibv7wjXogPxyku81RIuZHM5+ZWVlz5oLX9fm1dMmEHb6J0T9mHFojX538Ygxwq0OKNWAZhnW+qdHLXozDqdls0QdB04t89/1O/w1cDnyilFU='; // Access Token ค่าที่เราสร้างขึ้น

$request = file_get_contents('php://input');   // Get request content

$request_json = json_decode($request, true);   // Decode JSON request

foreach ($request_json['events'] as $event)
{
	if ($event['type'] == 'message') 
	{
		if($event['message']['type'] == 'text')
		{
			$text = $event['message']['text'];
			
			$reply_message = 'ฉันได้รับข้อความ "'. $text.'" ของคุณแล้ว!'; 
			
			if("ขอชื่อผู้พัฒนาระบบ"==$text){
				$reply_message = "นางสาวปุญญพัฒน์ สัญญากิจ" ;
			}
			if(("เส้นทางไปมหาวิทยาลัยพระจอมเกล้าธนบุรี"==$text) || ("ไปมหาลัยบางมด"==$text) || ("เส้นทางไปมหาวิทยาลับยางมด"==$text) || ("ไปมจธ."==$text) ){
				$reply_message = "เส้นทางไปมหาวิทยาลัยพระจอมเกล้าธนบุรี" ;
				
				$result = ('https://www.google.com/maps/place/King+Mongkut%E2%80%99s+University+of+Technology+Thonburi/@13.6512734,100.4943349,17z/data=!3m1!4b1!4m5!3m4!1s0x30e2a251bb6b0cf1:0xf656e94ff13324ad!8m2!3d13.6512734!4d100.4965236?hl=en');   // Get request content	
			
			 $result_json = json_decode($result, true);   // Decode JSON request
				
			if(("ประวัติมหาวิทยาลัยเทคโนโลยีพระจอมเกล้าธนบุรี"==$text) || ("ประวัติของมหาวิทยาลัยเทคโนโลยีพระจอมเกล้าธนบุรี"==$text) || ("ประวัติมหาวิทยาลับยางมด"==$text) || ("ประวัติมจธ."==$text) ){
				$reply_message = "ประวัติของมหาวิทยาลัยพระจอมเกล้าธนบุรี" ;
				
				$result = ('https://www.kmutt.ac.th/about-kmutt/history/')
			 $result_json = json_decode($result, true);   // Decode JSON request
				
				
			}
		
		} else {
			$reply_message = 'ฉันได้รับ "'.$event['message']['type'].'" ของคุณแล้ว!';
		}
		
	} else {
		$reply_message = 'ฉันได้รับ Event "'.$event['type'].'" ของคุณแล้ว!';
	}
	
	// reply message
	$post_header = array('Content-Type: application/json', 'Authorization: Bearer ' . $channelAccessToken);
	
	$data = ['replyToken' => $event['replyToken'], 'messages' => [['type' => 'text', 'text' => $reply_message]]];
	
	$post_body = json_encode($data);
	
	// reply method type-1 vs type-2
	$send_result = reply_message_1('https://api.line.me/v2/bot/message/reply', $post_header, $post_body); 
	//$send_result = reply_message_2('https://api.line.me/v2/bot/message/reply', $post_header, $post_body);
}

function reply_message_1($url, $post_header, $post_body)
{
        $context = stream_context_create([
            'http' => [
                'method' => 'POST',
                'header' => $post_header,
                'content' => $post_body,
            ],
        ]);
	
	$result = file_get_contents($url, false, $context);

	return $result;
}

function reply_message_2($url, $post_header, $post_body)
{
	$ch = curl_init($url);	
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $post_header);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $post_body);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	
	$result = curl_exec($ch);
	
	curl_close($ch);
	
	return $result;
}

?>
