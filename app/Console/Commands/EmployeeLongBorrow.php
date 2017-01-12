<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use DB;

class EmployeeLongBorrow extends Command {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'employee:LongBorrow';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle() {
        DB::table('test_timer')->delete();
        $this->info('The happy birthday messages were sent successfully!');
    }

}
