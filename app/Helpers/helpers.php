<?php
    function getGmailList($loginDetails)
    {
        $thetoken =  $loginDetails['user'];
        $email =  $loginDetails['email'];
        $ch = curl_init();
        $headers = array("Authorization: Bearer ".$thetoken."");
        $thedomain =  str_replace('@', '%40', $email);
        curl_setopt($ch, CURLOPT_URL, 'https://www.googleapis.com/gmail/v1/users/'.$thedomain.'/labels');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $response = curl_exec($ch);
        curl_close($ch);
        $response = json_decode($response,true);
        return $response ;
    }
?>