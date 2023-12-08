<?php

namespace App\Listeners;

use App\Events\CommentWritten;
use App\Jobs\UpdateBadgeJob;
use App\Models\Achievement;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use JetBrains\PhpStorm\NoReturn;

class UnlockCommentWrittenAchievement
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(CommentWritten $event): void
    {
        $user = $event->comment->user;

        // Get the comment counts
        $count = $user->comments()->count();

        switch ($count) {
            case 1:
                // Unlock the first lesson watched achievement for the user
                $user->unlockAchievement('First Lesson Watched');
                break;
            case 5:
                // Unlock the 5 lessons watched achievement for the user
                $user->unlockAchievement('5 Lessons Watched');
                break;
            case 10:
                // Unlock the 10 lessons watched achievement for the user
                $user->unlockAchievement('10 Lessons Watched');
                break;
            case 25:
                // Unlock the 25 lessons watched achievement for the user
                $user->unlockAchievement('25 Lessons Watched');
                break;
            case 50:
                // Unlock the 50 lessons watched achievement for the user
                $user->unlockAchievement('50 Lessons Watched');
                break;
        }

        // Update user badge
        $user->updateBadge();
    }


}
