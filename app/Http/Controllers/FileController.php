<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use function PHPUnit\Framework\isEmpty;

class FileController extends BaseAPIController
{

    public function uploadModel(Request $request): \Illuminate\Http\JsonResponse
    {
        try {


            // Validate the file and label
            $request->validate([
                'file' => 'required|file|max:102400', // 100MB in kilobytes
            ]);

            // Get the files from the request
            $file = $request->file('file');
            $label = $request->file('label');

            // Check if the model file exists
            if (!$file) {
                return response()->json([
                    'success' => false,
                    'message' => 'Model file is missing.',
                ], 400);
            }

            // Check the extension of the model file
            if ($file->getClientOriginalExtension() !== 'tflite') {
                return response()->json([
                    'success' => false,
                    'message' => 'Only .tflite files are allowed.',
                ], 400);
            }

            // Check if the label exists and if it's a valid .txt file
            if ($label && $label->getClientOriginalExtension() !== 'txt') {
                return response()->json([
                    'success' => false,
                    'message' => 'The label file must be .txt format.',
                ], 400);
            }


            // Save the files
            $path = saveImage($file, 'models'); // Save the model file
            $labelPath = saveImage( $label, 'labels'); // Save the model file
//            $labelPath = $label ? $label->storeAs('labels', 'labels.txt', 'public') : null; // Save the label file if it exists

            // Return the success response with paths
            return $this->sendSuccess(msg: "success", data: [
                "model" =>asset('storage') . '/' . $path,
                "label" => $labelPath ? asset('storage'). '/' . $labelPath  : null,
            ]);
        } catch (\Exception $ex) {
            // Return error in case of an exception
            return $this->sendError($ex->getMessage(), 400);
        }
    }

//    public function uploadModel(Request $request): \Illuminate\Http\JsonResponse
//    {
//        try {
//            $request->validate([
//                'file' => 'required|file|max:102400', // 100MB in kilobytes
//            ]);
//
//            $file = $request->file('file');
//            $label = $request->file('label');
//
//            if (!$file) {
//                return response()->json([
//                    'success' => false,
//                    'message' => 'Model file is missing.',
//                ], 400);
//            }
//
//            if ($file->getClientOriginalExtension() !== 'tflite') {
//                return response()->json([
//                    'success' => false,
//                    'message' => 'Only .tflite files are allowed.',
//                ], 400);
//            }
//
//            if ($label && $label->getClientOriginalExtension() !== 'txt') {
//                return response()->json([
//                    'success' => false,
//                    'message' => 'The label file must be .txt format.',
//                ], 400);
//            }
//
//            // Store the files
//            $filename = 'face_detection.tflite' as $uploaded_file;
//            $labelName = 'labels.txt';
//
//            $path = saveImage( $filename,'models', $filename, 'public');
//            $labelPath = $label?->storeAs('labels', $labelName, 'public');
//
//         return $this->sendSuccess(msg: "success",data:["tlife"=>asset('storage') . '/'. $path,"label"=>$labelPath ? asset('storage') . '/'.$labelPath : null,] );
//        } catch (\Exception $ex) {
//            return $this->sendError($ex->getMessage(), 400);
//        }
//    }




}
