<?php

/**
 * Comprobamos la orientación de la imagen.
 *
 * @param  Intervention\Image\Image $image
 * 
 * @return boolean
 */
if (!function_exists('isLandscape')) {
    function isLandscape($image)
    {
        return $image->width() > $image->height();
    }
}
