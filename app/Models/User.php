<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    public const USER_TYPE = 'super_admin';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_type',
        'group_id',
        'username',
        'fname',
        'lname',
        'email',
        'password',
        'code',
        'mobile',
        'address',
        'logo',
        'firm_name',
        'status',
        'createdBy',
        'theme_mode',
        'pass_reset_code'
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
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */



    public function role(): BelongsTo
    {
        return $this->belongsTo(Group::class, 'group_id', 'id');
    }

    // Define an accessor for the full name
    public function getFullNameAttribute(): string
    {
        return "{$this->fname} {$this->lname}";
    }
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    public function activities(): HasMany
    {
        return $this->hasMany(LogActivity::class, 'user_id');
    }
    public function clients(): HasMany
    {
        return $this->hasMany(Client::class, 'createdBy');
    }

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($user) {
            if (empty($user->username)) {
                $user->username = static::generateUniqueUsername($user->fname, $user->lname);
            }
        });

        // static::saving(function ($user) {
        //     // Only generate a unique username if 'username' is not set
        //     if (empty($user->username) && !static::where('fname', $user->fname)->where('lname', $user->lname)->exists()) {
        //         $user->username = static::generateUniqueUsername($user->fname, $user->lname);
        //     }
        // });

        // Cache forget on create, update, delete
        static::created(function () {
            Cache::forget('users');
        });

        static::updated(function ($user) {
            if ($user->user_type == User::USER_TYPE && $user->isDirty('status') && $user->status == 'inactive') {
                abort(422, "Super Admin status cannot be changed to inactive.");
            }
            Cache::forget('users');
        });

        static::deleted(function () {
            Cache::forget('users');
        });

        static::deleting(function ($user) {
            if ($user->user_type == User::USER_TYPE) {
                abort(422, "Super Admin cannot be deleted.");
            }

            if ($user->clients()->count() > 0) {
                if (request()->ajax()) {
                    abort(422, "User cannot be deleted because they have associated clients.");
                } else {
                    throw new \Exception("User cannot be deleted because they have associated clients.");
                }
            }
        });
    }

    protected static function generateUniqueUsername($fname, $lname)
    {
        $baseUsername = Str::slug((string)$fname . '.' . $lname);
        $username = $baseUsername;
        $counter = 1;

        while (static::where('username', $username)->exists()) {
            $username = $baseUsername . $counter;
            $counter++;
        }

        return $username;

    }
}
