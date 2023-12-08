<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * The comments that belong to the user.
     */
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * The lessons that a user has access to.
     */
    public function lessons()
    {
        return $this->belongsToMany(Lesson::class);
    }

    /**
     * The lessons that a user has watched.
     */
    public function watched()
    {
        return $this->belongsToMany(Lesson::class)->wherePivot('watched', true);
    }

    /**
     * @return HasMany
     */
    public function achievements(): HasMany
    {
        return $this->hasMany(Achievement::class);
    }

    /**
     * @param string $achievement
     * @return void
     */
    public function unlockAchievement(string $achievement): void
    {
        // If the achievement exists and the user has not unlocked it yet, attach it to the user
        if ($achievement && !$this->achievements()->where('achievement_name', $achievement)->exists()) {
            $this->achievements()->create(['achievement_name' => $achievement]);
        }
    }

    /**
     * @return HasOne
     */
    public function badge(): HasOne
    {
        return $this->hasOne(Badge::class);
    }

    /**
     * @return void
     */
    public function updateBadge(): void
    {
        // Get the number of achievements unlocked by the user
        $count = $this->achievements()->count();

        $userBadge = $this->badge;

        // Determine the badge name based on the count
        $badge = match (true) {
            $count >= 0 && $count < 4 => 'Beginner',
            $count >= 4 && $count < 8 => 'Intermediate',
            $count >= 8 && $count < 10 => 'Advanced',
            $count >= 10 => 'Master',
            default => $userBadge->badge ?? null,
        };

        // Update or create user badge
        if(!$userBadge) {
            $this->badge()->create(['badge_name' => $badge]);
        } else if ($badge !== $userBadge->badge) {
            $userBadge->update(['badge_name' => $badge]);
        }
    }
}

