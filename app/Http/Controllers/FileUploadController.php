<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\FileUpload;

use Auth;
use Illuminate\Support\Facades\Storage;

use Pion\Laravel\ChunkUpload\Exceptions\UploadMissingFileException;
use Pion\Laravel\ChunkUpload\Handler\AbstractHandler;
use Pion\Laravel\ChunkUpload\Handler\HandlerFactory;
use Pion\Laravel\ChunkUpload\Receiver\FileReceiver;

class FileUploadController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Retrieve an upload.
     *
     * Uses pion/laravel-chunk-upload to parse chunk uploads.
     *
     * @return \Illuminate\Http\Response
     */
    private function processFileUpload(Request $request, $file_variable)
    {
        $file_upload = new FileReceiver($file_variable, $request, HandlerFactory::classFromRequest($request));

        // If the chunk upload was successful
        if ($file_upload->isUploaded()) {
            // Handle the chunk
            return $file_upload->receive();
        } else {
            throw new UploadMissingFileException();
        }
    }

    /**
     * Store a new file.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $file = $this->processFileUpload($request, "upload_file");

        // If the file hasn't finished uploading yet...
        if (! $file->isFinished()) {
            return;
        }

        $directory = 'uploads/'. date('Y-m-j'). '/'. str_random(8);

        // Like lighting a smoke in a petrol station...
        while (Storage::exists($directory)) {
            $directory = 'uploads/'. date('Y-m-j'). '/'. str_random(8);
        }

        $filename = $file->getClientOriginalName();
        $filepath = $file->storeAs($directory, $filename);
        
        return [
            'status' => 'Upload complete.'
        ];

        // $fileUpload = new FileUpload();
        // $fileUpload->filename = $filename;
        // $fileUpload->filepath = $filepath;
        // $fileUpload->user_id = Auth::user()->id;
        // $fileUpload->save();

        // if ($request->wantsJson()) {
        //     return compact($fileUpload);
        // }

    }
}
