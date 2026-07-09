<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;

class AlreadyConfirmedException extends Exception
{
    public function render(): JsonResponse
    {
        return response()->json([
            'message' => 'Vous avez déjà confirmé cet incident.'
        ], 409);
    }
}