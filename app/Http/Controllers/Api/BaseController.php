<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

class BaseController extends Controller
{
    private $okCode = 200;
    private $errorCode = 404;

    public function handleResponse($message)
    {
        return response()->json([
            'success' => true,
            'message' => $message,
        ], $this->okCode);
    }

    public function handleResponseFile($file)
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
