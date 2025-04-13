<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use function PHPUnit\Framework\isEmpty;

class FileController extends BaseAPIController
{
    public function uploadModel(Request $request): \Illuminate\Http\JsonResponse
    {
        try {

            $file = $request->file('file');
            $label = $request->file('label');
            if ($file->getClientOriginalExtension() !== 'tflite' && !isEmpty($file)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Only .tflite files are allowed.',
                ], 400);
            }
            if($label->getClientOriginalExtension() !== 'txt' && !isEmpty($label)) {
                return response()->json([
                    'success' => false,
                    'message' => 'The label Only .txt files are allowed.',
                ], 400);
            }


            // Store the file
            $filename = 'fruites.tflite';
            $labelName = 'labels.txt';
            $path = $file->storeAs('models', $filename, 'public');
            $pathLabel = $file->storeAs('labels', $labelName, 'public');

            // Generate the full URL
            $url = asset('storage/' . $path);
            $pathLabelUrl = asset('storage/' . $pathLabel);

            return response()->json([
                'success' => true,
                'url' => $url,
                'label' =>  $pathLabelUrl,
            ]);
        } catch (\Exception $ex) {
            return $this->sendError($ex->getMessage(), 400);
        }
    }



}
