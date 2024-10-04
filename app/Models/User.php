<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Data\GroupData;
use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasAvatar;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Jetstream\HasTeams;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements FilamentUser, HasAvatar
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use HasTeams;
    use Notifiable;
    use TwoFactorAuthenticatable;
    use HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'current_group_id',
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

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class, 'user_id');
    }

    public function receivedMessages(): HasMany
    {
        return $this->hasMany(Message::class, 'receiver_id');
    }

    public function assignments(): HasMany
    {
        return $this->hasMany(Assignment::class);
    }

    public function messageReplies(): HasMany
    {
        return $this->hasMany(MessageReply::class);
    }

    public function teacher(): HasOne
    {
        return $this->hasOne(Teacher::class, 'user_id');
    }

    public function absences(): HasMany
    {
        return $this->hasMany(Absence::class);
    }

    public function articles(): HasMany
    {
        return $this->hasMany(Article::class);
    }

    public function homeworks()
    {
        return $this->currentTeam->lessons()->with('homeworks')->get()->pluck('homeworks')->flatten();
    }

    public function teams(): BelongsToMany
    {
        return $this->belongsToMany(Team::class, 'team_user')
            ->withPivot('role')
            ->withTimestamps();
    }

    public function groups(): BelongsToMany
    {
        return $this->belongsToMany(Group::class, 'group_members')
            ->withPivot('group_role_id')
            ->withTimestamps();
    }

    public function documents(): MorphMany
    {
        return $this->morphMany(Document::class, 'documentable');
    }

    public function grades(): HasMany
    {
        return $this->hasMany(AssignedGrade::class);
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return $this->hasAnyRole(['admin', 'school', 'teacher']);
    }

    public function getFilamentAvatarUrl(): ?string
    {
        return '/' . $this->profile_photo_path;
    }

    public function currentGroup($loadMembers = true): GroupData|null
    {
        if (!$this->current_group_id) {
            return null;
        }

        if ($loadMembers) {
            return GroupData::from(Group::with('users')->find($this->current_group_id));
        }

        return GroupData::from(Group::find($this->current_group_id));
    }

    public function currentGroupRole(): string|null
    {
        $roleId = $this->groups()->find($this->current_group_id)?->pivot->group_role_id;

        return Str::lower(GroupRole::find($roleId)?->name);
    }


    public function isCurrentGroupAdmin(): bool
    {
        return $this->currentGroupRole() === 'admin' || $this->currentGroup(loadMembers: false)?->userId === $this->id;
    }

    public function currentTeamRole(): string|null
    {
        return $this->currentTeam?->members()->where('user_id', $this->id)->first()->pivot->role;
    }
}
