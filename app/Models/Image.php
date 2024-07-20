<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Image extends Model
{
    protected $fillable = [
        'imageable_id',
        'imageable_type',
        'image_name',
        'image_name_scaled'
    ];

    public $timestamps = false;

    public function imageable(): MorphTo
    {
        return $this->morphTo();
    }
}
