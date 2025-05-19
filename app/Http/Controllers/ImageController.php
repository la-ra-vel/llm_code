<?php
// app/Http/Controllers/ImageController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Image;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;
use Illuminate\Support\Facades\Storage;

class ImageController extends Controller
{
    public function index()
    {
        return view('verifiy_images');
    }
    public function upload(Request $request)
    {

        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $imageName = time() . '.' . $request->image->extension();
        $request->image->move(public_path('images'), $imageName);

        $image = new Image();
        $image->path = 'images/' . $imageName;
        $image->user_id = Auth::id(); // Assuming the user is authenticated
        $image->save();

        return response()->json(['image_path' => $image->path]);
    }

    public function verify(Request $request)
{
//     $pythonPath = 'C:\\Users\\irfan\\newenv2\\Scripts\\python.exe';
//     $scriptPath = base_path('public/python/my_script.py');
//     $command = escapeshellcmd("{$pythonPath} {$scriptPath} 2>&1");

//     $output = shell_exec($command);
// echo "<pre>"; print_r($output); exit;
    // Validate request
    $request->validate([
        'image_path' => 'required|string',
        'known_images' => 'required|string',
    ]);

    $image_path = $request->input('image_path');
    $known_images_json = $request->input('known_images');
    $known_images = json_decode($known_images_json, true);

    if (preg_match('/^data:image\/(\w+);base64,/', $image_path, $type)) {
        $image_data = substr($image_path, strpos($image_path, ',') + 1);
        $image_type = $type[1];
        $image_data = base64_decode($image_data);

        if (!in_array($image_type, ['png', 'jpg', 'jpeg'])) {
            return response()->json(['errors' => ['image_path' => ['Unsupported image type.']]], 422);
        }

        $tempImagePath = storage_path('app/temp_image.' . $image_type);
        file_put_contents($tempImagePath, $image_data);

        $tempKnownImagesPath = storage_path('app/temp_known_images.txt');
        file_put_contents($tempKnownImagesPath, implode("\n", $known_images));

        $pythonScriptPath = base_path('public/python/facial_recognition.py');
        // $pythonInterpreter = env('PYTHON_INTERPRETER', 'C:\Users\irfan\newenv2\Scripts\python.exe');
        $pythonInterpreter = 'C:\Users\irfan\newenv2\Scripts\python.exe';
// dd($pythonInterpreter);
        // Ensure the paths are correct
        \Log::info('Python Script Path:', ['path' => $pythonScriptPath]);
        \Log::info('Temp Known Images Path:', ['path' => $tempKnownImagesPath]);
        \Log::info('Temp Image Path:', ['path' => $tempImagePath]);
        \Log::info('Python Interpreter:', ['interpreter' => $pythonInterpreter]);

        if (!file_exists($pythonScriptPath)) {
            \Log::error('Python script not found at path:', ['path' => $pythonScriptPath]);
            return response()->json(['error' => 'Python script not found'], 500);
        }

        // Command as an array
        $command = [
            $pythonInterpreter,
            $pythonScriptPath,
            $tempKnownImagesPath,
            $tempImagePath
        ];

        $process = new Process($command);
        $process->run(function ($type, $buffer) {
            \Log::info($type === Process::ERR ? "ERR > $buffer" : "OUT > $buffer");
        });

        if (!$process->isSuccessful()) {
            \Log::error('Process failed', ['error' => $process->getErrorOutput()]);
            return response()->json(['error' => $process->getErrorOutput()], 500);
        }

        unlink($tempImagePath);
        unlink($tempKnownImagesPath);

        $results = json_decode($process->getOutput(), true);
        return response()->json(['results' => $results]);
    } else {
        return response()->json(['errors' => ['image_path' => ['Invalid image data.']]], 422);
    }
}







    public function getUserImages()
    {
        $user_id = Auth::id();
        $images = Image::where('user_id', $user_id)->get();

        return response()->json($images);
    }
}
