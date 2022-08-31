<?php

namespace App\Console\Commands;

use App\TextParser\Fb2Reader;
use Illuminate\Console\Command;

class CreateFromFb2 extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lb:create_from_fb2 {filename?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create book from fb2 file';

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
     * @return int
     */
    public function handle()
    {
        $filename = $this->argument("filename");
        if(!$filename) $filename = "slovami_buddy.fb2";
        $content = file_get_contents(storage_path()."/source_books/".$filename);

        $fb2Reader = new Fb2Reader($content);


    }
}
