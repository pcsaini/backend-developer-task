<?php

namespace App\Console\Commands;

use App\Events\CommentWritten;
use App\Models\Comment;
use Illuminate\Console\Command;

class AddComment extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:add-comment';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
//        $comment = Comment::factory(1)->create()->first();

        $comment = Comment::query()->first();
        CommentWritten::dispatch($comment);
    }
}
