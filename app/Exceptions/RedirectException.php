<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\RedirectResponse;

class RedirectException extends Exception
{
    public RedirectResponse $response;

    public function __construct(RedirectResponse $response)
    {
        $this->response = $response;
        parent::__construct('Redirect Exception');
    }

    public function render()
    {
        return $this->response;
    }
}
