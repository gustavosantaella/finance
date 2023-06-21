<?php

namespace App\Services\Email;

use App\Helpers\Log;
use App\Mail\DefaultMail;
use App\Mail\ForgotPasswordMail;
use App\Services\Service;
use Exception;
use Illuminate\Contracts\Mail\Mailable;
use Illuminate\Support\Facades\Mail;

class EmailService extends Service
{
    public function __construct()
    {
    }

    private function send($users = [],  Mailable $mailable = null)
    {
        try {
            if (!$mailable) {
                throw new Exception("Mailbale is required");
            }
            Mail::to($users)->send($mailable);
            return true;
        } catch (Exception $e) {
            throw $e;
        }
    }


    public function forgotPassword($user, $data)
    {
        try {
            $mailable = new ForgotPasswordMail([...$data, "subject" => "Restablecer clave"]);
            $this->send($user, $mailable);
        } catch (Exception $e) {
            throw $e;
        }
    }
}
