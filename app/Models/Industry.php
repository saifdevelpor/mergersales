<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Industry extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug', 'meta_title', 'meta_description', 'og_image'];

    protected static function booted(): void
    {
        static::saving(function (self $industry): void {
            if (! $industry->slug || $industry->isDirty('name')) {
                $industry->slug = Str::slug((string) $industry->name);
            }
        });
    }

    public function subIndustries()
    {
        return $this->hasMany(SubIndustry::class);
    }
}
