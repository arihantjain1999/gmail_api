<?php
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
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

    $historyid = DB::table('mails')
        ->select('*')
        ->where('user_email', Auth::user()->email)
        ->max('history_id');
    // dd($historyid);
    $urlParameters = [
        'labelIds' => $labelId,
        'maxResults' => 10,
    ];

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
        $allMailArray = [];
        $mailDatas = ['Date', 'Message-ID', 'Subject', 'From', 'To'];
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
            
            // dd($response);
            $labelincsv = $response['labelIds'];
            $arr = [];
            $arr = implode(',', $labelincsv);
            // dd($arr);

            if (!empty($payloadBodys)) 
            {
                foreach ($payloadBodys as $payloadBody) 
                {
                    if ($payloadBody['mimeType'] == 'text/html') {
                        $payloadBodydata = $payloadBody['body'];
                        $mailDatabase['body'] = $payloadBodydata['data'];
                    }
                    elseif ($payloadBody['mimeType'] == 'text/plain') {
                        $payloadBodydata = $payloadBody['body'];
                        $mailDatabase['body'] = $payloadBodydata['data'];
                    }

                }
            } 
            elseif (!empty($payload['body'])) 
            {
                $data = $payload['body'];
                $mailDatabase['body'] = $data['data'];

            } else {
                $mailDatabase['body'] = "null";
            }
            // adding body to maildata array

            foreach ($payloadheaders as $payloadheader) {
                foreach ($mailDatas as $mailData) {
                    if ($payloadheader['name'] == $mailData) {
                        // dd($payloadheader['value']);
                        $str = Str::lower($mailData);
                        $str = str_replace('-', '_', $str);
                        $mailDatabase[$str] = $payloadheader['value'];
                    }
                }
            }

            // array_push($mailDatabase , $response['id'] , $response['threadId'] , $response['labelIds']);
            $mailDatabase['mail_id'] = $response['id'];
            $mailDatabase['thread_id'] = $response['threadId'];
            $mailDatabase['label_ids'] = $arr;
            $mailDatabase['history_id'] = $response['historyId'];
            $mailDatabase['user_email'] = Auth::user()->email;
            // dump($mailDatabase);

            // dd($mailDatabase);
            array_push($allMailArray, $mailDatabase);
            // dd($allMailArray['label_ids']);

        }
        // dd($allMailArray);
        // $inputData = [];
        $inputData = [];
        foreach ($allMailArray as $key => $allMail) {
            // dump($allMail);
            $test = DB::table('mails')->where('mail_id', $allMail['mail_id'])->first();
            if (!$test) {
                $inputData[$key] = array(
                    "body" => $allMail['body'],
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
                );
            }
        }
        DB::table('mails')->insert($inputData);
        // dd('hello');
//
        // dd($allMailArray);
        return $response;
    } else {
        $err = '<div class="alert alert-warning alert-dismissible fade show m-2" role="alert">
    The <strong>' . $labelId . '</strong> has no New Emails.
  </div>';
        return $err;
    }

}

function sendGmailMessage($loginDetails, $messageDetails)
{
    $request = $messageDetails;
    $messageDetails = $messageDetails->all();

    if ($request->hasFile('image')) {
        $fileName = $request->file('image')->getClientOriginalName();
        $filePath = $request->file('image')->storeAs('public/attachment', $fileName);
        $filePath = storage_path() . '\app\public\attachment\\' . $fileName;

        $boundary = uniqid(rand(), true);
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $filePath);
    }

//Message format that has to be sent
    if ($request->hasFile('image')) {
        $from = 'To: ' . $messageDetails['To'] . '
From: ' . Auth::user()->name . '   <' . $messageDetails['From'] . '>
Cc: ' . $messageDetails['Cc'] . '
Bcc: ' . $messageDetails['Bcc'] . '
Subject: =?uth-8?B?' . base64_encode($messageDetails['Subject']) . '?=
Content-type: multipart/mixed; boundary="' . $boundary . '"' . "\r\n" . '
--' . $boundary . '
Content-type: multipart/alternative; boundary="' . $boundary . '"' . "\r\n" . '
--' . $boundary . '
Content-Type: text/plain; charset=utf-8' . "\r\n" . '
' . $messageDetails['Body'] . "\r\n" . '
--' . $boundary . '
Content-Type: ' . $mimeType . '; name="' . $fileName . '";' . '
Content-Disposition: attachment; filename="' . $fileName . '"; size=' . filesize($filePath) . ';' . '
Content-Transfer-Encoding: base64' . '
Content-ID: <' . $messageDetails['From'] . '>'. '
' . chunk_split(base64_encode(file_get_contents($filePath)), 76) . "\r\n" . "\r\n" . '
--' . $boundary;
    } else {
        $from = 'To: ' . $messageDetails['To'] . '
From: ' . Auth::user()->name . '   <' . $messageDetails['From'] . '>
Cc: ' . $messageDetails['Cc'] . '
Bcc: ' . $messageDetails['Bcc'] . '
Subject: =?uth-8?B?' . base64_encode($messageDetails['Subject']) . '?=

' . $messageDetails['Body'];
    }

    $thetoken = $loginDetails['token'];
    $email = $loginDetails['email'];
    // dd($from);
    $curl = curl_init();

    //encoding data to be sent
    $encoded = base64_encode($from);
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
    return $response;

}

function dateFormat($date)
{
    // dd($date);
    $dateFormated = Carbon::parse($date)->format('d/m/Y H:i');
    return $dateFormated;
}
