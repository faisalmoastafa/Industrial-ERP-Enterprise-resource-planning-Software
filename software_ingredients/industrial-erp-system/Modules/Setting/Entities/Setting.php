<?php

namespace Modules\Setting\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Currency\Entities\Currency;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\File;

class Setting extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $fillable = [
        'company_address',
        'company_email',
        'company_name',
        'company_phone',
        'default_currency_id',
        'default_currency_position',
        'footer_text',
        'notification_email',
        'site_logo',
        'logo_solid',
        'logo_transparent',
        'app_title',
        'app_tagline',
    ];

    protected $with = ['currency', 'media'];

    public function currency() {
        return $this->belongsTo(Currency::class, 'default_currency_id', 'id');
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('logo_solid')
            ->singleFile()
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/svg+xml', 'image/webp']);

        $this->addMediaCollection('logo_transparent')
            ->singleFile()
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/svg+xml', 'image/webp']);
    }

    /**
     * Return the URL for the solid/login logo.
     * Falls back to the static images/logo.png if none uploaded.
     */
    public function getLogoSolidUrl(): string
    {
        $media = $this->getFirstMedia('logo_solid');

        if ($media) {
            return $media->getUrl();
        }

        return asset('images/logo.png');
    }

    /**
     * Return the URL for the transparent/sidebar logo.
     * Falls back to the static images/logo.png if none uploaded.
     */
    public function getLogoTransparentUrl(): string
    {
        $media = $this->getFirstMedia('logo_transparent');

        if ($media) {
            return $media->getUrl();
        }

        return asset('images/logo.png');
    }
}
