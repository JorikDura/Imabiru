<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Facades\Storage;

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

    public function delete(): ?bool
    {
        $this->deleteImages();

        return parent::delete();
    }

    public function deleteImages(): void
    {
        Storage::delete("public/$this->image_name");

        if (!is_null($this->image_name_scaled)) {
            Storage::delete("public/$this->image_name_scaled");
        }
    }
}
