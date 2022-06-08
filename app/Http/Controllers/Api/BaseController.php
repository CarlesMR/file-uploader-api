<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

class BaseController extends Controller
{
    private $okCode = 200;
    private $errorCode = 404;

    public function handleResponse($file)
    {
        return response()->json([
            'success' => true,
            'message' => 'Image Upload Successful',
            'filename' => $file
        ], $this->okCode);
    }

    public function handleError($errorMsg)
    {
        return response()->json([
            'success' => false,
            'message' => $errorMsg,
        ], $this->errorCode);
    }
}
