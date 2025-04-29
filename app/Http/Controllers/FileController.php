<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use function PHPUnit\Framework\isEmpty;

class FileController extends BaseAPIController
{
    public function uploadModel(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $request->validate([
                'file' => 'required|file|max:102400', // 100MB in kilobytes
            ]);

            $file = $request->file('file');
            $label = $request->file('label');

            if (!$file) {
                return response()->json([
                    'success' => false,
                    'message' => 'Model file is missing.',
                ], 400);
            }

            if ($file->getClientOriginalExtension() !== 'tflite') {
                return response()->json([
                    'success' => false,
                    'message' => 'Only .tflite files are allowed.',
                ], 400);
            }

            if ($label && $label->getClientOriginalExtension() !== 'txt') {
                return response()->json([
                    'success' => false,
                    'message' => 'The label file must be .txt format.',
                ], 400);
            }

            // Store the files
            $filename = 'face_detection.tflite';
            $labelName = 'labels.txt';

            $path = $file->storeAs('models', $filename, 'public');
            $labelPath = $label?->storeAs('labels', $labelName, 'public');

//            return response()->json([
//                'success' => true,
//                'url' => asset('storage/' . $path),
//                'label' => $labelPath ? asset('storage/' . $labelPath) : null,
//            ]);
//            return $this->sendSuccess(msg: "success",data:["tlife"=>asset('storage/' . $path),"label"=>$labelPath ? asset('storage/' . $labelPath) : null,] );
            return $this->sendSuccess(msg: "success",data:["tlife"=>asset('storage') . '/'. $path,"label"=>$labelPath ? asset('storage') . '/'.$labelPath : null,] );
        } catch (\Exception $ex) {
            return $this->sendError($ex->getMessage(), 400);
        }
    }




}
