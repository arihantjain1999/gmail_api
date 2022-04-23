<?php
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
    function getGmailList($loginDetails)
    {   
        $thetoken =  $loginDetails['user'];
        $email =  $loginDetails['email'];
        $labelid = $loginDetails['label'];
        // dd($labelid);
        $ch = curl_init();
        $headers = array("Authorization: Bearer ".$thetoken."");
        $thedomain =  str_replace('@', '%40', $email);
        if($labelid == "null")
        {
            curl_setopt($ch, CURLOPT_URL, 'https://www.googleapis.com/gmail/v1/users/'.$thedomain.'/labels/');
        }
        else{
            curl_setopt($ch, CURLOPT_URL, 'https://www.googleapis.com/gmail/v1/users/'.$thedomain.'/labels/'.$labelid.'');
        }
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $response = curl_exec($ch);
        curl_close($ch);
        $response = json_decode($response,true);
  
        // return redirect()->route('label.index' , $response);
        return $response;
    }






    function getGmailMessage($loginDetails)
    {   
        $thetoken =  $loginDetails['user'];
        $email =  $loginDetails['email'];
        $emailId = $loginDetails['emailId'];
        $labelId = $loginDetails['labelIds'];
        // dd($labelId);
        $ch = curl_init();
        $headers = array("Authorization: Bearer ".$thetoken."");
       
        $urlParameters = [
            'labelIds'=> $labelId,
            'maxResults' => 10
        ];
        // dd($urlParameters);

        if($emailId == "null")
        {
            curl_setopt($ch, CURLOPT_URL, 'https://gmail.googleapis.com/gmail/v1/users/'.$email.'/messages?'.http_build_query($urlParameters));
        }
        else{
            curl_setopt($ch, CURLOPT_URL, 'https://gmail.googleapis.com/gmail/v1/users/'.$email.'/messages/'.$emailId.'');
        }
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $response = curl_exec($ch);
        curl_close($ch);
        $response   = json_decode($response,true);
        // dd($response);
        $allMesseges = $response['messages'];
        // dd($allMesseges);
        if (isset($response['messages']))
        {

        } 
        else 
        {
    //             "error" => array:4 [▼
    //     "code" => 400
    //     "message" => "Invalid id value"
    //     "errors" => array:1 [▶]
    //     "status" => "INVALID_ARGUMENT"
    //   ]
        }
        
       
        $allMailArray = []; 
        $mailDatas = ['Date' ,'Message-ID' , 'Subject' ,'From' , 'To'];
        $mailDatabase = [];
        $mailData = [];
        $mailPayloadHeaderData = [];
        foreach ($allMesseges as $message) {
                curl_setopt($ch, CURLOPT_URL, 'https://gmail.googleapis.com/gmail/v1/users/'.$email.'/messages/'.$message['id'].'');
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                $response = curl_exec($ch);
                curl_close($ch);
                $response   = json_decode($response,true);
                          $payload = $response['payload'];
                          $payloadheaders = $payload['headers'];
                          $payloadBodys = $payload['parts']  ?? [];
                        //   dump($response);
                        //   dd($payloadBodys);
                         $labelincsv =  $response['labelIds'];
                                       $arr = [];
                                        $arr = implode(',' , $labelincsv);
                                // dd($arr);


                        if(!empty($payloadBodys))
                        {
                            foreach ($payloadBodys as $payloadBody) {
                                // dd($payloadBody);
                                // dd("hel");
                                if($payloadBody['mimeType'] == 'text/html'){
                                    $payloadBodydata = $payloadBody['body'];
                                        // dd($payloadBodydata);
                                    $mailDatabase['body']=$payloadBodydata['data'];
                                    // dump($mailDatabase);
                                }
                                // dd('he;;');              
                                                  // dd($mailDatabase);
                                // dd('hel');
                            }
                        }
                        // adding body to maildata array
                       
                        foreach ($payloadheaders as $payloadheader) {
                            foreach ($mailDatas as $mailData) {
                                if($payloadheader['name'] == $mailData){
                                    // dd($payloadheader['value']);
                                     $str=Str::lower($mailData);
                                     $str =  str_replace('-', '_', $str);
                                    $mailDatabase[$str] = $payloadheader['value'];
                                   
                                }
                            }
                        }
 
                       


                          
                        // array_push($mailDatabase , $response['id'] , $response['threadId'] , $response['labelIds']);
                            $mailDatabase['mail_id'] = $response['id'];
                            $mailDatabase['thread_id'] = $response['threadId'];
                            $mailDatabase['label_ids'] = $arr;

                            foreach ($mailPayloadHeaderData as $mailPayloadHeaderData) {
                                // array_push($mailDatabase , $mailPayloadHeaderData); 
                            }
                            // dd($mailDatabase);
                array_push($allMailArray , $mailDatabase);
                            // dd($allMailArray['label_ids']);
               
            }
            // dd($allMailArray);
            $inputData = [];
            foreach ($allMailArray as $key =>$allMail) {
                // dump($allMail);
                $test = DB::table('mails')->where( 'mail_id', $allMail['mail_id'])->first();
                if(!$test){
                    $inputData[$key] = array( 
                        "body" => $allMail['body'],
                        "from" => $allMail['from'],
                        "date" => $allMail['date'],
                        "message_id" => $allMail['message_id'],
                        "subject" => $allMail['subject'],
                        "to" => $allMail['to'],
                        "mail_id" => $allMail['mail_id'],
                        "thread_id" => $allMail['thread_id'],
                        "label_ids" => $allMail['label_ids']);
                }

            }
            DB::table('mails')->insert($inputData);
                // dd('hello');
// 
                // dd($allMailArray);
        // // return redirect()->route('label.index' , $response);
        return $response;
    }
?>