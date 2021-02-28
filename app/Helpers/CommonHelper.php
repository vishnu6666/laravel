<?php

namespace App\Helpers;

use App\Model\Team;

class CommonHelper 
{

    /*
    |--------------------------------------------------------------------------
    |  Common Helper
    |--------------------------------------------------------------------------
    |
    | This helper is used for common methods written in controllers.
    | This helper contain common methods that used at various controllers.
    | 
    */

    /**
     * This method is used for encrypt string.
     *
     * @param string $string
     *
     * @return string encrypted string
    */
    public static function encrypt($string)
    {
        $output = false;
        $encrypt_method = "AES-256-CBC";
        $secret_key = 'Vttips';
        $secret_iv = 'Vttips';

        // hash
        $key = hash('sha256', $secret_key);

        // iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
        $iv = substr(hash('sha256', $secret_iv), 0, 16);

        $output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
        $output = base64_encode($output);

        return $output;
    }

    /**
     * This method is used for decrypt string.
     *
     * @param string $string
     *
     * @return string decrypted string
    */
    public static function decrypt($string)
    {
        $output = false;
        $encrypt_method = "AES-256-CBC";
        $secret_key = 'Vttips';
        $secret_iv = 'Vttips';

        // hash
        $key = hash('sha256', $secret_key);

        // iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
        $iv = substr(hash('sha256', $secret_iv), 0, 16);

        $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);

        return $output;
    }
    
     /**
     *
     * check edit page is admin data 
     *
     * @param $object
     * @param $restaurantId
     * @return page
     *
     **/

    public static function checkIsAdminData($object,$restaurantId)
    {

        if($object->restaurantId  != $restaurantId) 
        {
             return abort(404);
        }    

        return null;
    }
}