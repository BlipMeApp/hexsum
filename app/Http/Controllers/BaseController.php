<?php

namespace App\Http\Controllers;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use App\Http\Controllers\EncryptController;
class BaseController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

public function sendResponse($result, $message)
    {
        $success = true;
        $messa = $message;
    	$response = [
            'success' => $success,
            'data'    => $result,
            'message' => $messa,

        ];


        return response()->json($response, 200);
    }

    public function sendEncryptResponse($result, $message)
    {
        $encryp = new EncryptController();
        $success = true;
        $messa = $message;
    	$response = [
            'success' => $success,
            'data'    => $result,
            'message' => $messa,

        ];

        return response()->json(["r" => $encryp->UseEncrypt(json_encode($response))], 200);
    }

    public function sendDecriptResponse($result, $message)
    {
    	$response = [
            'success' => true,
            'data'    => $result,
            'message' => $message,

        ];

        return response()->json($response, 200);
    }

    public function sendError($error, $errorMessages = [], $code = 200)
    {
    	$response = [
            'success' => false,
            'message' => $error,
        ];

        if(!empty($errorMessages)){
            $response['data'] = $errorMessages;
        }

        return response()->json($response, $code);
    }

    public function sendEncryptError($error, $errorMessages = [], $code = 200)
    {
        $encryp = new EncryptController();
    	$response = [
            'success' => false,
            'message' => $error,
        ];

        if(!empty($errorMessages)){
            $response['data'] = $errorMessages;
        }

        return response()->json(["r" => $encryp->UseEncrypt(json_encode($response))], 200);
    }
}