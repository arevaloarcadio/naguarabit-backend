<?php

namespace App\Traits;

trait SendNotificationFcm
{
	//sendnotification("Device_ID","Test Notification","Test Message","https://www.google.co.in/images/branding/googleg/1x/googleg_standard_color_128dp.png",array("ID"=>1));
    public function sendNotification($to, $title, $message, $img = "https://realliga.com/pictures/ball-render.png", $datapayload = ""){

     	ini_set("allow_url_fopen", "On");
        $data = 
        [
            "to" => $to,
            "notification" => [
                "body" => $message,
                "title" => $title,
            ],
            "data" => $datapayload
        ];

        $options = array(
            'http' => array(
                'method'  => 'POST',
                'content' => json_encode( $data ),
                'header'=>  "Content-Type: application/json\r\n" .
                            "Accept: application/json\r\n" . 
                            "Authorization: key=AAAAexgOpqM:APA91bE8VLfD_E6-bBe1xQhsmkRWinDeKLIjtSDN8YkR_J6QvuwjpSLR9tfu_HHIh_d_7bwVI0vYXKux3RfTZQ3grPoGEM-ZxgRgfymZ8eFXz_YwC7670qHJr5YXAWT0wglRfab0BlfW"
            )
        );

        $context  = stream_context_create( $options );
        $result = file_get_contents( "https://fcm.googleapis.com/fcm/send", false, $context );
        
        return json_decode( $result );
	}
}		
