<?php

namespace App\Http\Controllers;

class BaseAPIController extends Controller {
    public function sendSuccess($msg = null, $data=[]) {
        return response()->json([
            'success' => true,
            'data'      => $data,
            'message'   => $msg ?? 'Success',
        ], 200);
    }

    public function sendError($msg, $code = 400) {
        return response()->json([
            'success'   => false,
            'data'      => null,
            'message'   => $msg,
        ], $code);
    }
}
