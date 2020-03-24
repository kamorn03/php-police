<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\http\Request;

class LineMessaging extends BaseController
{
    public function index(){
        return "hello LINE Messaging";
    }

    public function hook(Request $request)
    {
        // URL API LINE
        $API_URL = 'https://api.line.me/v2/bot/message';
        // ใส่ Channel access token (long-lived)
        $ACCESS_TOKEN = 'OcdbPg6PPYGyQYZJzCKwmy+tBeTgQaoCmg9myl3ZUyTSenlEdIoR5Y+9HIoUveQOWSnUac3YnzDo4yqGLkF9D8KwKu5HXDH2OdYYD2ME0U3OiEUn4xYHrp8r3O/Tj0Vlrz/Ga/v3FysbqWlVzY+w5QdB04t89/1O/w1cDnyilFU=';
        // ใส่ Channel Secret
        $CHANNEL_SECRET = '415ad75651a6ce083553122bfda8a215';

        // Set HEADER
        $POST_HEADER = array('Content-Type: application/json', 'Authorization: Bearer ' . $ACCESS_TOKEN);
        // Get request content
        // $request = file_get_contents('php://input');
        // Decode JSON to Array
        // $request_array = json_decode($request, true);

        $events = $request->get('events', []);
        // print_r($Request);

        if(count($events) > 0){
            foreach($events as $event){
                $replyToken = $event['replyToken'];
                $data = [
                    'replyToken' => $replyToken,
                    'messages' => [
                        ['type' => 'text', 
                         'text' => 'HELLO']
                     ]
                ];
        
                $post_body = json_encode($data, JSON_UNESCAPED_UNICODE);
                $send_result = $this->send_reply_message($API_URL.'/reply', $POST_HEADER, $post_body);
                echo "Result: ".$send_result."\r\n";
            }
        }
        echo "OK";
    }

    public function send_reply_message($url, $post_header, $post_body)
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

    
}
