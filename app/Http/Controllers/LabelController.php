<?php

namespace App\Http\Controllers;

use App\Models\Label;
use Auth;
use Illuminate\Http\Request;

class LabelController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('gmail.gmailmesseges');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $userDetails = Auth::user();
        $fields = $request->all();
        $label = last($fields);
        if (empty($fields)) {
            $emaildetais = ['emailId' => 'null', 'email' => $userDetails->email, 'user' => $userDetails->token, 'labelIds' => $label, 'labelname' => ''];
        } else {
            $labelName = last(array_keys($fields));

            $emaildetais = ['emailId' => 'null', 'email' => $userDetails->email, 'user' => $userDetails->token, 'labelIds' => $label, 'labelname' => $labelName];
            $response = getGmailMessage($emaildetais);

            if (is_string($response)) {
                return view('gmail.gmailmesseges', ['err' => $response]);

            } else {
                return view('gmail.gmailmesseges', ['labelid' => $label]);
            }
        }

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Label  $label
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        // dd($id);
        return view('gmail.singleemail', ['mail_id' => $id]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Label  $label
     * @return \Illuminate\Http\Response
     */
    public function edit(Label $label)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Label  $label
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Label $label)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Label  $label
     * @return \Illuminate\Http\Response
     */
    public function destroy(Label $label)
    {
        //
    }

    public function getMyLabels(Request $request)
    {
        $label = $request->all();
        $label = last($label);
        return view('gmail.gmailmesseges', ['labelid' => $label]);
    }
}
