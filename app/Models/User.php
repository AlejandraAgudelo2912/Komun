<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Builder;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasRoles;

    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;

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
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function scopeVerified(Builder $query): Builder
    {
        return $query->whereNotNull('email_verified_at');
    }

    public function scopeByRole(Builder $query, string $role): Builder
    {
        return $query->role($role);
    }

    public function scopeSearch(Builder $query, string $search): Builder
    {
        return $query->where(function ($query) use ($search) {
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
        });
    }

    public function scopeWithActiveRequests(Builder $query): Builder
    {
        return $query->whereHas('requests', function ($query) {
            $query->where('status', 'pending')
                  ->where(function ($query) {
                      $query->whereNull('deadline')
                            ->orWhere('deadline', '>', now());
                  });
        });
    }

    public function scopeFilter($query, $request, $role = null)
    {
        return $query
            ->with(['roles', 'assistant', 'assistant.verification'])
            ->when($role === 'god', function ($query) {
                $query->with('permissions');
            })
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->when($request->filled('role'), function ($query) use ($request) {
                $query->role($request->role);
            })
            ->when($request->filled('status'), function ($query) use ($request) {
                if ($request->status === 'verified') {
                    $query->whereHas('assistant', function ($q) {
                        $q->where('is_verified', true);
                    });
                } elseif ($request->status === 'unverified') {
                    $query->whereHas('assistant', function ($q) {
                        $q->where('is_verified', false);
                    });
                }
            });
    }

    public function requests()
    {
        return $this->hasMany(RequestModel::class);
    }

    public function appliedRequests()
    {
        return $this->belongsToMany(RequestModel::class, 'request_model_application')
                    ->withPivot('status', 'message')
                    ->withTimestamps();
    }

    public function sentMessages()
    {
        return $this->hasMany(Message::class, 'user_id');
    }

    public function receivedMessages()
    {
        return $this->hasMany(Message::class, 'receiver_id');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class, 'assistant_id');
    }

    public function updateRating()
    {
        $this->update([
            'total_reviews' => $this->reviews()->count(),
            'rating' => $this->reviews()->avg('rating') ?? 0
        ]);
    }

    public function assistant()
    {
        return $this->hasOne(Assistant::class);
    }

    public function followedCategories()
    {
        return $this->belongsToMany(Category::class)
                    ->withPivot('notifications_enabled')
                    ->withTimestamps();
    }
}
