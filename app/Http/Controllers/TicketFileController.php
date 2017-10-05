<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\TicketFile;

use Auth;
use Illuminate\Support\Facades\Storage;

use Pion\Laravel\ChunkUpload\Exceptions\UploadMissingFileException;
use Pion\Laravel\ChunkUpload\Handler\AbstractHandler;
use Pion\Laravel\ChunkUpload\Handler\HandlerFactory;
use Pion\Laravel\ChunkUpload\Receiver\FileReceiver;

class TicketFileController extends Controller
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
     * @param Request $request
     * @return TicketFile|\Illuminate\Http\RedirectResponse|void
     */
    public function upload(Request $request)
    {
        $file = $this->processFileUpload($request, "upload_file");

        // If the file hasn't finished uploading yet - this function will be called again.
        if (! $file->isFinished()) {
            return;
        }

        $directory = 'uploads/'. date('Y-m-d'). '/'. str_random(8);
        
        while (Storage::exists($directory)) {
            $directory = 'uploads/'. date('Y-m-d'). '/'. str_random(8);
        }

        Storage::makeDirectory($directory, 'public');

        $filename = $file->getClientOriginalName();
        $filepath = $file->storeAs($directory, $filename, 'public');

        Storage::disk('public')->setVisibility($filepath, 'public');

        $TicketFile = new TicketFile();
        $TicketFile->name = $filename;
        $TicketFile->path = $filepath;
        $TicketFile->url = Storage::disk('public')->url($filepath);
        $TicketFile->file_size = Storage::disk('public')->size($filepath);

        $TicketFile->user_id = Auth::user()->id;
        $TicketFile->token = str_random(60);
        $TicketFile->save();

        if ($request->wantsJson()) {
            return $TicketFile;
        }

        return redirect()->back();
    }

    /**
     * Delete a file via token or ID.
     *
     * @return \Illuminate\Http\Response|array
     */
    public function destroy(Request $request, $query)
    {
        // TODO: Needs authorisation.

        if (is_int($query) && strlen($query) < 20) {
            $TicketFile = TicketFile::where('id', $query)->first();
        } else {
            $TicketFile = TicketFile::where('token', $query)->first();
        }

        if ($request->wantsJson()) {
            return ['status' => $TicketFile->delete()];
        }

        return redirect()->back();
    }
}
