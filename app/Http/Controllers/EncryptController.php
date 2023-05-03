<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use ParagonIE\ConstantTime\Hex;
use Spatie\Crypto\Rsa\KeyPair;
use Spatie\Crypto\Rsa\PrivateKey;
use Spatie\Crypto\Rsa\PublicKey;


class EncryptController extends BaseController
{

    public function UseEncrypt2(Request $request)
    {
        try {
            $data = json_encode($request->data);

            //$data = $request->data;

            //proobando otro metodo
            //$iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('AES-256-CBC'));
            $iv = "";

            if(strlen($iv) < 16)
                $iv .= $this->random_strings(openssl_cipher_iv_length('AES-256-CBC') - strlen($iv));

            $encrypted = openssl_encrypt($data, "AES-256-CBC", "GgRGRVjrosUSZ3PBIxMxYBWw/2CvAQ==", 0, $iv);

            $encripte = base64_encode($encrypted."::".$iv);
            //************************* */

            //return $this->sendResponse($encripte, 'Encripter');
            return $encripte;

        } catch (\TypeError | \ErrorException | \RuntimeException $ex) {
        return $this->sendError('Encripter', 'Error Interno ' . $ex->getMessage(), 200);
        } catch (\Exception | \ParseError $ex){
        return $this->sendError('Encripter', 'Error Interno ' . $ex->getMessage(), 200);
        }
    }
    function random_strings($length_of_string)
    {
        $str_result = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz=/';

        return substr(str_shuffle($str_result),
                        0, $length_of_string);
    }

    public function UseDecrypt(Request $request)
    {

        try {
            $data = $request->data;
            $web = null;
            $key = "GgRGRVjrosUSZ3PBIxMxYBWw/2CvAQ==";

            //********* */
            try {
                list($encrypted_data, $iv, $web) = explode('::', base64_decode($data), 3);
            } catch(Exception $ex) {
                list($encrypted_data, $iv) = explode('::', base64_decode($data), 2);
            }

            if(!is_null($web)) {
                $key = "cd81by4VjrosWSVTBiubLO680Vwnd2==";
                $decrip = openssl_decrypt($encrypted_data, 'AES-256-CBC', $key, 0, $iv);
            } else {
                $decrip = openssl_decrypt($encrypted_data, 'AES-256-CBC', "GgRGRVjrosUSZ3PBIxMxYBWw/2CvAQ==", 0, $iv);
            }

            $StrDecrip = json_decode(str_replace('\\"','"', $decrip));
            $StrDecrip = json_decode($decrip);
            //**************** */

            return $StrDecrip;

        } catch (\TypeError | \ErrorException | \RuntimeException $ex) {
        return $this->sendError('Decripter', 'Error Interno ' . $ex->getMessage(), 200);
        } catch (\Exception | \ParseError $ex){
        return $this->sendError('Decripter', 'Error Interno' . $ex->getMessage(), 200);
        }
    }
}