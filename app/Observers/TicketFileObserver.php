<?php
namespace App\Observers;

use App\TicketFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class TicketFileObserver
{
    /**
     * Listen to the TicketFile created event.
     *
     * @param  TicketFile  $ticket_file
     * @return void
     */
    public function created(TicketFile $ticket_file)
    {
        //
    }

    /**
     * On TicketFile deletion clean up orphaned files.
     *
     * @param  TicketFile  $ticket_file
     * @return void
     */
    public function deleting(TicketFile $ticket_file)
    {
        if (Storage::exists($ticket_file->path)) {
            Storage::delete($ticket_file->path);
        }

        $directory = dirname($ticket_file->path);

        // If the upload directory is empty, delete it.
        if (count(Storage::allFiles($directory)) +
            count(Storage::allDirectories($directory)) == 0) {
            Storage::deleteDirectory($directory);
        }
    }
}
