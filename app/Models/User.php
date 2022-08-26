<?php

namespace App\Models;

use App\Traits\HandleImageTrait;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    use HandleImageTrait, HasRoles;

    const IMAGE_SAVE_PATH = 'public/upload/';
    const IMAGE_SHOW_PATH = 'storage/upload/';
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'address',
        'gender'
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
    ];

    public function images()
    {
        return $this->morphMany(Image::class, 'imageable');
    }

    /**
     * @return Attribute
     */
    public function imagePath(): Attribute
    {
        return Attribute::make(
            get: fn() => asset(self::IMAGE_SHOW_PATH. $this?->images?->first()?->url)
        );
    }

    /**
     * @param $imageUrl
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function syncImage($imageUrl)
    {
        $this->deleteImage();
        return $this->images()->create(['url' => $imageUrl]);
    }

    /**
     * @return int
     */
    public function deleteImage(): int
    {
        return $this->images()->delete();
    }

    /**
     * @param array|int $roles
     * @return array
     */
    public function assignRoles(array | int $roles): array
    {
        return $this->roles()->sync($roles);
    }
}
