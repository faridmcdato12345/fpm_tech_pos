<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class RunAIServer extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ai:serve';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Start AI Prediction Service';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $scriptPath = base_path('app/ai_scripts/python/server.py'); // Adjust path as needed
        $command = "python $scriptPath > storage/logs/ai.log 2>&1 &";

        exec($command);

        $this->info('AI Prediction Service Started.');
    }
}
