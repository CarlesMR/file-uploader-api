<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Api\BaseController;
use App\Http\Requests\FileRequest;
use Illuminate\Support\Facades\Storage;

class FileController extends BaseController
{

    private $public_path = 'public';
    private $public_storage_path = 'storage';

    /**
     * Obtener la URL de un fichero.
     * 
     ** Es necesario pasar por Request las variables: LOCATION (Carpeta en la que se encuentra la imagen) y FILENAME (Nombre con el que se ha guardado el archivo).
     *
     * @param  \Illuminate\Http\Request  $request
     * 
     * @return string
     */
    public function getUrl(Request $request)
    {
        return asset($this->public_storage_path . DIRECTORY_SEPARATOR . $request->location . DIRECTORY_SEPARATOR . $request->filename);
    }

    /**
     * Descargar un archivo.
     * 
     ** Es necesario pasar por Request las variables: LOCATION (Carpeta en la que se encuentra la imagen) y FILENAME (Nombre con el que se ha guardado el archivo).
     *
     * @param  \Illuminate\Http\Request  $request
     * 
     * @return Illuminate\Support\Facades\Storage
     */
    public function download(Request $request)
    {
        return Storage::download($this->public_path . DIRECTORY_SEPARATOR . $request->location . DIRECTORY_SEPARATOR . $request->filename);
    }

    /**
     * Almacenar un archivo
     * 
     ** Es necesario pasar por Request las variables: LOCATION (Carpeta en la que se encuentra la imagen) y FILENAME (Nombre con el que se ha guardado el archivo).
     *
     * @param  \Illuminate\Http\FileRequest  $request
     * 
     * @return \Illuminate\Http\Response
     */
    public function upload(FileRequest $request)
    {
        //Guardamos el archivo que recibimos por Request y se guarda en una localización concreta.
        $file = $request->file('file')->store('public' . DIRECTORY_SEPARATOR . $request->location);

        try {
            $image = \Image::make(Storage::get($file));
            //Comprobamos la orientación de la imagen, esta función se encuentra en la clase Helpers.php.
            $isLandscape = isLandscape($image);

            //Dependiendo de la orientación se modifica el tamaño del archivo.
            if ($isLandscape) {
                $image->resize(1240, 877);
            } else {
                $image->resize(877, 1240);
            }

            //Sobreescribimos la imagen por la imagen con el tamaño modificado.
            $image->save(storage_path('app' . DIRECTORY_SEPARATOR . $file));
        } catch (Exception $e) {
            $this->handleError($e->getMessage());
        }

        return $this->handleResponse($file);
    }
}
