<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use App\Models\CoreMessages;
use GuzzleHttp\Exception\BadResponseException;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    

    public function postWhatsappMessages($msg, $status, $receiver){
    }

    public function getMessage($messages_id){
        $messages = CoreMessages::where('messages_id', $messages_id)
        ->first();

        return $messages['messages_text'];
    }

    public function getMessageStatus($messages_id){
        $messages = CoreMessages::where('messages_id', $messages_id)
        ->first();

        return $messages['messages_status'];
    }
}
