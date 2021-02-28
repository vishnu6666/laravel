<?php


namespace App\Helpers;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Str;
class ImageHelper
{

    /*
    |--------------------------------------------------------------------------
    |  Image Helper
    |--------------------------------------------------------------------------
    |
    | This helper is used for image related data.
    | This helper contain methods that upload and resize images.
    | 
    */

    /**
     * This method is used for save Image.
     *
     * @param file $image
     * @param URL $path
     * @param string $filePrefix
     *
     * @return void
    */
    public static function imageSave($image, $path, $filePrefix)
    {
        if(!empty($image)) {

            //Move Uploaded File
            $destinationPath = public_path($path);
            $fileName = $filePrefix.'_'.time().'_'.strtolower(Str::random(6) ).'.'.$image->getClientOriginalExtension();

            if($image->move($destinationPath, $fileName)) {
                chmod($destinationPath.'/'.$fileName,0777);
                return $fileName;
            }
        }
        return null;
    }

    /**
     * This method is used for Image Resize in different format.
     *
     * @param string $imageName
     * @param FilePath $path
     * @param FilePath $sourcePath
     * @param int $imageHeight
     * @param int $imageWidth
     *
     * @return string $imageName
    */
    public static function imageResize($imageName,$path,$sourcePath,$imageHeight,$imageWidth)
    {
        set_time_limit(500);
        if(!empty($imageName) ){

            $imgUrl = public_path($path.'/'.$imageName);

            $imgSize = getimagesize($imgUrl);
            
            if($imgSize[0] < 200 || $imgSize[1]  < 200 ){

                $imgFile = Image::make($imgUrl)->resizeCanvas($imageHeight, $imageWidth, 'center', false, '#ececec');
            }
            else
            {

                $imgFile = Image::make($imgUrl)->resize($imageHeight, $imageWidth, function ($constraint) {
                    $constraint->aspectRatio();} );

                // Fill up the blank spaces with transparent color
                $imgFile->resizeCanvas($imageHeight, $imageWidth, 'center', false, '#ececec');
            }
            $imgFile->save($sourcePath.'/'.$imageName,80);
            chmod($sourcePath.'/'.$imageName,0777);

            return $imageName;
        }

        return null;
    }
}