<?php

namespace App\Jobs;

use App\Models\Badge;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UpdateBadgeJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        private readonly User $user
    )
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $totalAchievements = $this->user->achievements()->count();

        $badge = $this->getBadge($totalAchievements);

        if($badge >= 0 && $badge )

        if($badge) {
            Badge::query()->updateOrCreate(
                ['user_id' => $this->user->id],
                [
                    'user_id' => $this->user->id,
                    'badge_name' => $badge
                ]
            );
        }
    }

    private function getBadge(int $count): string
    {
        return match (true) {
            $count >= 0 && $count < 4 => 'Beginner',
            $count >= 4 && $count < 8 => 'Intermediate',
            $count >= 8 && $count < 10 => 'Advanced',
            $count >= 10 => 'Master',
            default => '',
        };
    }
}
