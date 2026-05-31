<?php

namespace App\Http\Controllers\Traits;

use Illuminate\Http\Request;
use Intervention\Image\Laravel\Facades\Image;

trait FileUploadTrait
{
    /**
     * File upload trait used in controllers to upload files
     */
    public function saveFiles(Request $request)
    {
        $uploadPath = public_path(config('quickadmin.upload_path'));
        $thumbPath = public_path(config('quickadmin.upload_path').'/thumb');
        if (! file_exists($uploadPath)) {
            mkdir($uploadPath, 0775);
            mkdir($thumbPath, 0775);
        }

        $finalRequest = $request;

        foreach ($request->all() as $key => $value) {
            if ($request->hasFile($key)) {
                if ($request->has($key.'_max_width') && $request->has($key.'_max_height')) {
                    // Check file width
                    $filename = time().'-'.$request->file($key)->getClientOriginalName();
                    $file = $request->file($key);
                    $image = Image::read($file);
                    if (! file_exists($thumbPath)) {
                        mkdir($thumbPath, 0775, true);
                    }
                    Image::read($file)->resize(50, 50)->save($thumbPath.'/'.$filename);
                    $width = $image->width();
                    $height = $image->height();
                    if ($width > $request->{$key.'_max_width'} && $height > $request->{$key.'_max_height'}) {
                        $image->scaleDown(width: $request->{$key.'_max_width'}, height: $request->{$key.'_max_height'});
                    } elseif ($width > $request->{$key.'_max_width'}) {
                        $image->scale(width: $request->{$key.'_max_width'});
                    } elseif ($height > $request->{$key.'_max_height'}) {
                        $image->scale(height: $request->{$key.'_max_height'});
                    }
                    $image->save($uploadPath.'/'.$filename);
                    $finalRequest = new Request(array_merge($finalRequest->all(), [$key => $filename]));
                } else {
                    $filename = time().'-'.$request->file($key)->getClientOriginalName();
                    $request->file($key)->move($uploadPath, $filename);
                    $finalRequest = new Request(array_merge($finalRequest->all(), [$key => $filename]));
                }
            }
        }

        return $finalRequest;
    }
}
