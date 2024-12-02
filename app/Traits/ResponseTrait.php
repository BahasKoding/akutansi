<?php

namespace App\Traits;

trait ResponseTrait
{
    protected function successResponse($message, $redirect = null)
    {
        return redirect($redirect ?? back())
            ->with('success', $message)
            ->with('type', 'success');
    }

    protected function errorResponse($message, $redirect = null)
    {
        return redirect($redirect ?? back())
            ->with('error', $message)
            ->with('type', 'error');
    }
}
