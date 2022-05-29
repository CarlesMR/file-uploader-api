<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\FileRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

//TODO: Hay que comentar correctamente las funciones.
class FileController extends Controller
{

    private $public_path = 'public';
    private $public_storage_path = 'storage';

    /**
     * Return the URL of the file. 
     */
    public function getUrl(Request $request)
    {
        return asset($this->public_storage_path . DIRECTORY_SEPARATOR . $request->location . DIRECTORY_SEPARATOR . $request->filename);
    }

    /**
     * Download the file. 
     */
    public function download(Request $request)
    {
        return Storage::download($this->public_path . DIRECTORY_SEPARATOR . $request->location . DIRECTORY_SEPARATOR . $request->filename);
    }

    /**
     * Upload the file. 
     */
    public function upload(FileRequest $request)
    {
        $file = $request->file('file')->store('public' . DIRECTORY_SEPARATOR . $request->location);

        try {
            $image = \Image::make(Storage::get($file));
            $isLandscape = $this->isLandscape($image);

            if ($isLandscape) {
                $image->resize(1240, 877);
            } else {
                $image->resize(877, 1240);
            }

            $image->save(storage_path('app' . DIRECTORY_SEPARATOR . $file));
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 200);
        }

        return response()->json([
            'success' => true,
            'message' => 'Image Upload Successful',
            'filename' => $file
        ], 200);
    }

    private function isLandscape($image)
    {
        $width = $image->width();
        $height = $image->height();
        return $width > $height;
    }
}
