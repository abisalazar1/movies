<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Request;

class AuthorisationException extends Exception
{
    /**
     * Message
     *
     * @var string
     */
    protected $message; 

    /**
     * Constructor
     *
     * @param string $message
     */
    public function __construct(string $message)
    {
        $this->message = $message;   
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Throwable
     */
    public function render(Request $request)
    {
        return response()->json(['error' => $this->message], 403);
    }
}
