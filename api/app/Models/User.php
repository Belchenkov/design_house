<?php

namespace App\Models;

use App\Notifications\ResetPassword;
use App\Notifications\VerifyEmail;
use Grimzy\LaravelMysqlSpatial\Eloquent\SpatialTrait;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;
use Tymon\JWTAuth\Contracts\JWTSubject;

/**
 * Class User
 * @package App\Models
 * @property int id
 */
class User extends Authenticatable implements JWTSubject, MustVerifyEmail
{
    use Notifiable, SpatialTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'tagline',
        'about',
        'username',
        'location',
        'formatted_address',
        'available_to_hire',
    ];

    protected $spatialFields = [
        'location',
        'area'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function sendEmailVerificationNotification()
    {
        $this->notify(new VerifyEmail);
    }

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims(): array
    {
        return [];
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPassword($token));
    }

    public function designs(): HasMany
    {
        return $this->hasMany(Design::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function teams(): BelongsToMany
    {
        return $this->belongsToMany(Team::class)->withTimestamps();
    }

    public function chats(): BelongsToMany
    {
        return $this->belongsToMany(Chat::class, 'participants');
    }

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class);
    }

    public function getChatWithUser(int $user_id)
    {
        return $this->chats()
            ->whereHas('participants', function($query) use ($user_id) {
                $query->where('user_id', $user_id);
            })
            ->first();
    }

    public function ownerTeams(): BelongsToMany
    {
        return $this->teams()
            ->where('owner_id', $this->id);
    }

    public function invitations(): HasMany
    {
        return $this->hasMany(Invitation::class, 'recipient_email', 'email');
    }

    public function isOwnerOfTeam(Team $team): bool
    {
        return (bool)$this->teams()
            ->where('id', $team->id)
            ->where('owner_id', $this->id)
            ->count();
    }

}
