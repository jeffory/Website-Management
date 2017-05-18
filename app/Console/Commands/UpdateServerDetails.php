<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\RemoteServer;

class UpdateServerDetails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'remoteservers:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Query web hosts for new details.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        RemoteServer::updateServerList();
    }
}
