<?php

namespace App\Exceptions;

use Exception;
use Longman\TelegramBot\Request;

class TelegramAuthException extends Exception
{
    /**
     * Report the exception.
     *
     * @return void
     */
    public function report($message)
    {
        
    }

    /**
     * Render the exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
     */
    public function render($request)
    {
        return response('Hello World', 200)
                  ->header('Content-Type', 'text/plain');
    }
}
