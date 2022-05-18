<?php

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

function getGmailList($loginDetails)
{
    $thetoken = $loginDetails['user'];
    $email = $loginDetails['email'];
    $labelid = $loginDetails['label'];
    // dd($labelid);
    $ch = curl_init();
    $headers = array("Authorization: Bearer " . $thetoken . "");
    $thedomain = str_replace('@', '%40', $email);
    if ($labelid == "null") {
        curl_setopt($ch, CURLOPT_URL, 'https://www.googleapis.com/gmail/v1/users/' . $thedomain . '/labels/');
    } else {
        curl_setopt($ch, CURLOPT_URL, 'https://www.googleapis.com/gmail/v1/users/' . $thedomain . '/labels/' . $labelid . '');
    }
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    $response = curl_exec($ch);
    curl_close($ch);
    $response = json_decode($response, true);

    // return redirect()->route('label.index' , $response);
    return $response;
}

function getGmailMessage($loginDetails)
{
    // dd($loginDetails);
    $thetoken = $loginDetails['user'];
    $email = $loginDetails['email'];
    $emailId = $loginDetails['emailId'];
    $labelId = $loginDetails['labelIds'];
    $labelName = $loginDetails['labelname'];
    // dd($labelId);
    $ch = curl_init();
    $headers = array("Authorization: Bearer " . $thetoken . "");

    $urlParameters = [
        'labelIds' => $labelId,
        'maxResults' => 10,
    ];
    // dd($urlParameters);

    if ($emailId == "null") {
        curl_setopt($ch, CURLOPT_URL, 'https://gmail.googleapis.com/gmail/v1/users/' . $email . '/messages?' . http_build_query($urlParameters));
    } else {
        curl_setopt($ch, CURLOPT_URL, 'https://gmail.googleapis.com/gmail/v1/users/' . $email . '/messages/' . $emailId . '');
    }
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    $response = curl_exec($ch);
    curl_close($ch);
    $response = json_decode($response, true);
    // dd($response);

    if (isset($response['messages'])) {
        $allMesseges = $response['messages'];
// dd($allMesseges);
        $allMailArray = [];
        $mailDatas = ['Date', 'Message-Id', 'Subject', 'From', 'To'];
        $mailDatabase = [];
        $mailData = [];
        $mailPayloadHeaderData = [];
        foreach ($allMesseges as $message) {
            curl_setopt($ch, CURLOPT_URL, 'https://gmail.googleapis.com/gmail/v1/users/' . $email . '/messages/' . $message['id'] . '');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            $response = curl_exec($ch);
            curl_close($ch);

            $response = json_decode($response, true);
            $payload = $response['payload'];
            $payloadheaders = $payload['headers'];
            $payloadBodys = $payload['parts'] ?? [];
            //   dump($response);
            // dd($response);
            $labelincsv = $response['labelIds'];
            $arr = [];
            $arr = implode(',', $labelincsv);
            $arr2 = [];
            $arr3 = [];
            if (!empty($payloadBodys)) 
            {
                foreach ($payloadBodys as $payloadBody) {
                    // dump($payloadBody);
                    // dd($payloadBody);

                    if ($payloadBody['mimeType'] == 'text/html') {
                        $payloadBodydata = $payloadBody['body'];
                        // dd($payloadBodydata);
                        $mailDatabase['body'] = $payloadBodydata['data'];
                        // dump($mailDatabase);
                    }
                    

                    if ($payloadBody['mimeType'] == 'image/jpeg' ||
                        $payloadBody['mimeType'] == 'application/pdf' || 
                        $payloadBody['mimeType'] == 'image/png' ||
                        $payloadBody['mimeType'] == 'application/vnd.openxmlformats-officedocument.presentationml.presentation' ||
                        $payloadBody['mimeType'] == 'audio/amr-wb' ||
                        $payloadBody['mimeType'] == 'video/mp4' ||
                        $payloadBody['mimeType'] == 'video/octet-stream' ||
                        $payloadBody['mimeType'] == 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' ||
                        $payloadBody['mimeType'] == 'text/csv' ||
                        $payloadBody['mimeType'] == 'application/vnd.openxmlformats-officedocument.wordprocessingml.document') {
                        $payloadBodydata = $payloadBody['body'];
                        // $mailDatabase['filename'] = $payloadBody['filename'];
                        array_push($arr2, $payloadBody['filename']);
                        $payloadBodyFilename = implode(',', $arr2);
                        $mailDatabase['filename'] = $payloadBodyFilename;
                        array_push($arr3, $payloadBodydata['attachmentId']);
                        $payloadBodyattachmentId = implode(',', $arr3);
                        $mailDatabase['attachmentId'] = $payloadBodyattachmentId;   
                    } 
                    elseif ($payloadBody['mimeType'] == 'text/plain') 
                    {
                        $payloadBodydata = $payloadBody['body'];
                        // dd($payloadBodydata);
                        $mailDatabase['body'] = $payloadBodydata['data'];
                        // dump($mailDatabase);
                    }
                }
            } 
            elseif (!empty($payload['body'])) {
                $data = $payload['body'];
                $mailDatabase['body'] = $data['data'];

            } else {
                $mailDatabase['body'] = "null";
            }
            // $payloadBodyFilename =  explode(',', $payloadBodyFilename);
            // dd(implode(',', $arr2) , implode(',', $arr3),$mailDatabase , explode(',', $payloadBodyFilename ));

            // dd($mailDatabase);

            // adding body to maildata array

            foreach ($payloadheaders as $payloadheader) {
                foreach ($mailDatas as $mailData) {
                    if ($payloadheader['name'] == $mailData) {
                        // dd($payloadheader['value']);
                        // dd($mailData);
                        $str = Str::lower($mailData);
                        $str = str_replace('-', '_', $str);
                        $mailDatabase[$str] = $payloadheader['value'];
                        // dd($mailDatabase);
                    }
                }
            }
            // dd($mailDatabase);
            // array_push($mailDatabase , $response['id'] , $response['threadId'] , $response['labelIds']);
            $mailDatabase['mail_id'] = $response['id'];
            $mailDatabase['thread_id'] = $response['threadId'];
            $mailDatabase['label_ids'] = $arr;
            $mailDatabase['history_id'] = $response['historyId'];
            $mailDatabase['user_email'] = Auth::user()->email;

            array_push($allMailArray, $mailDatabase);
            // dd($allMailArray['label_ids']);

        }
        // dd($allMailArray);
        $inputData = [];
        foreach ($allMailArray as $key => $allMail) {
            // dump($allMail);
            $test = DB::table('mails')->where('mail_id', $allMail['mail_id'])->first();
            if (!$test) {
                $inputData[$key] = array(
                    "body" => $allMail['body']??'',
                    "from" => $allMail['from'],
                    "date" => $allMail['date'],
                    "message_id" => $allMail['message_id'] ?? '',
                    "subject" => $allMail['subject'],
                    "to" => $allMail['to'],
                    "mail_id" => $allMail['mail_id'],
                    "thread_id" => $allMail['thread_id'],
                    "label_ids" => $allMail['label_ids'],
                    "history_id" => $allMail['history_id'],
                    "user_email" => $allMail['user_email'],
                    "attachmentId" => $allMail['attachmentId'] ?? '',
                    "file_name" => $allMail['filename'] ?? '',
                );
                if (!empty($allMail['attachmentId'])) {
                    $payloadBodyattachmentId1 =  explode(',', $allMail['attachmentId']);
                    $payloadBodyFilename =  explode(',', $allMail['filename']);
                    for ($i=0; $i < count($payloadBodyFilename) ; $i++) { 
                            $attachment = getAttachment($payloadBodyattachmentId1[$i], $allMail['message_id']??'', $allMail['user_email'], $thetoken);
                            $attachmentData = $attachment['data'];
                            $attachmentData = str_replace(' ', '+', $attachmentData);
                            $attachmentData = str_replace('_', '/', str_replace('-', '+', $attachmentData));
                            $imageName = $payloadBodyFilename[$i];
                            $attachmentPath = public_path().'\storage\public\attachment\\'.$imageName;
                            // $attachmentdecode = $attachmentPath.base64_decode($attachmentData);
                            File::put($attachmentPath, base64_decode($attachmentData));
                        }
                }
            }
        }
        DB::table('mails')->insert($inputData);

        return $response;
    } else {
        $error = '<div class="alert alert-warning alert-dismissible fade show m-2" role="alert">
    The <strong>' . $labelName . '</strong> has no New Emails.
  </div>';

        return $error;
    }
}

function sendGmailMessage($loginDetails, $messageDetails)
{
    $request = $messageDetails;
    $messageDetails = $messageDetails->all();
    $boundary = uniqid(rand(), true);
    $messageDetails['To'] = str_replace(' ', ',', $messageDetails['To']);
    // dd($request->allFiles('image'));
    if ($request->hasFile('image')) {

        foreach ($messageDetails['image'] as $files) {
            // dd($files);
            $fileName = $files->getClientOriginalName();
            // dd($fileName);
            $filePath = $files->storeAs('public/attachment', $fileName);
            $filePath = storage_path() . '\app\public\attachment\\' . $fileName;

            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mimeType = finfo_file($finfo, $filePath);
            $path = '
--' . $boundary . '
Content-Type: ' . $mimeType . '; name="' . $fileName . '";' . '
Content-Disposition: attachment; filename="' . $fileName . '"; size='
            . filesize($filePath) . ';' . '
Content-Transfer-Encoding: base64' . '
Content-ID: <' . 'lavleen.agr7@gmail.com' . '>' . '
' . chunk_split(base64_encode(file_get_contents($filePath)), 76);
// dd($path);
            $fileAttach[] = $path;
        }
        // dd($path);
        $path = implode("\r\n", $fileAttach);
    }
    $objDateTime = new DateTime('NOW');
    $objDateTime->modify("+330 minutes");
    $isoDate = $objDateTime->format(DateTime::ISO8601);
    // dd($isoDate);
//Message format that has to be sent
    if ($request->hasFile('image')) {
        $from = 'To: ' . $messageDetails['To'] . '
From:   ' . Auth::user()->name . '   <' . $messageDetails['From'] . '>
Cc: ' . $messageDetails['Cc'] . '
Bcc: ' . $messageDetails['Bcc'] . '
Subject: =?uth-8?B?' . base64_encode($messageDetails['Subject']) . '?=
Date: ' . $isoDate . '
Content-type: multipart/mixed; boundary="' . $boundary . '"' . "\r\n" . '
--' . $boundary . '
Content-type: multipart/alternative; boundary="' . $boundary . '"' . "\r\n" . '
--' . $boundary . '
Content-Type: text/plain; charset=utf-8' . "\r\n" . '
' . $messageDetails['Body'] . "\r\n" . $path . "\r\n" . '
--' . $boundary;
    } else {
        $from = 'To: ' . $messageDetails['To'] . '
From:   ' . Auth::user()->name . '   <' . $messageDetails['From'] . '>
Cc: ' . $messageDetails['Cc'] . '
Bcc: ' . $messageDetails['Bcc'] . '
Subject: =?uth-8?B?' . base64_encode($messageDetails['Subject']) . '?=
Date: ' . $isoDate . '

' . $messageDetails['Body'];
    }

    $thetoken = $loginDetails['token'];
    $email = $loginDetails['email'];
    $curl = curl_init();

    $encoded = base64_encode($from);

    // dd($from);
    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://gmail.googleapis.com/gmail/v1/users/' . $email . '/messages/send',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => 'CURL_HTTP_VERSION_1_1',
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => json_encode(["raw" => $encoded]),
        CURLOPT_HTTPHEADER => array(
            'Authorization: Bearer ' . $thetoken . '',
            'Content-Type: application/json',
        ),
    ));

    $response = curl_exec($curl);
    curl_close($curl);
    $response = json_decode($response, true);
    // dd($response);
    return $response;

}

function dateFormat($date)
{
    // dd($date);
    $dateFormated = Carbon::parse($date)->format('d/m/Y H:i');
    // date('c',strtotime('+5 hour +30 minutes',strtotime(date('M d, Y h:i:s A'))));
    // dd($dateFormated);
    return $dateFormated;
}

function getAttachment($attachmentId, $messageId, $userId, $thetoken)
{
    // dd($attachmentId, $messageId, $userId);
    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://gmail.googleapis.com/gmail/v1/users/' . $userId . '/messages/' . $messageId . '/attachments/' . $attachmentId . '',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_HTTPHEADER => array(
            'Authorization: Bearer ' . $thetoken . '',
            CURLOPT_TIMEOUT_MS => 5000,
        ),
    ));

    $response = curl_exec($curl);

    curl_close($curl);
    $response = json_decode($response, true);
    return $response;

}
