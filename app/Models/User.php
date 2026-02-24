<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Filament\Panel;
use Spatie\Activitylog\LogOptions;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Support\Facades\Storage;
use Filament\Models\Contracts\HasAvatar;
use Illuminate\Notifications\Notifiable;
use Filament\Models\Contracts\FilamentUser;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements FilamentUser, HasAvatar
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles, LogsActivity;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'username',
        'NIK',
        'address',
        'gender',
        'idCompany',
        'idRegional',
        'idBusinessUnit',
        'idDepartment',
        'idSubDepartment',
        'idSection',
        'idPosition',
        'ext',
        'image',
        'resign',
        'tanggalResign',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
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
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function company()
    {
        return $this->belongsTo(Company::class, 'idCompany');
    }

    public function regional()
    {
        return $this->belongsTo(Regional::class, 'idRegional');
    }

    public function businessunit()
    {
        return $this->belongsTo(BusinessUnit::class, 'idBusinessUnit');
    }

    public function department()
    {
        return $this->belongsTo(Department::class, 'idDepartment');
    }

    public function subdepartment()
    {
        return $this->belongsTo(SubDepartment::class, 'idSubDepartment');
    }

    public function section()
    {
        return $this->belongsTo(Section::class, 'idSection');
    }

    public function position()
    {
        return $this->belongsTo(Position::class, 'idPosition');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['*']);
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return $this->exists;
    }

    // public function getFilamentAvatarUrl(): ?string
    // {
    //     return $this->image ? asset('storage/' . $this->image) : null;
    // }

    public function getFilamentAvatarUrl(): ?string
    {
        return $this->image
            ? asset($this->image)
            : null;
    }

    // set user registration default role
    protected static function boot()
    {
        parent::boot();

        static::created(function ($user) {
            // Set default role
            $defaultRole = Role::where('name', 'user')->first();
            if ($defaultRole) {
                $user->assignRole($defaultRole);
            }
        });

        static::updating(function ($user) {
            if ($user->isDirty('image')) {
                $oldImage = $user->getOriginal('image'); // Ambil path file lama
                if ($oldImage) {
                    Storage::disk('public')->delete($oldImage);
                }
            }
        });
    }
}
