<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DesktopSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'value',
    ];

    protected $casts = [
        'value' => 'json',
    ];

    /**
     * Get a setting value by key
     */
    public static function get(string $key, $default = null)
    {
        $setting = static::where('key', $key)->first();
        return $setting ? $setting->value : $default;
    }

    /**
     * Set a setting value
     */
    public static function set(string $key, $value): void
    {
        static::updateOrCreate(
            ['key' => $key],
            ['value' => $value]
        );
    }

    /**
     * Check if this is the first run
     */
    public static function isFirstRun(): bool
    {
        return static::get('first_run', 'true') === 'true';
    }

    /**
     * Mark first run as complete
     */
    public static function completeFirstRun(): void
    {
        static::set('first_run', 'false');
    }

    /**
     * Get app version
     */
    public static function getAppVersion(): string
    {
        return static::get('app_version', '1.0.0');
    }

    /**
     * Update app version
     */
    public static function updateAppVersion(string $version): void
    {
        static::set('app_version', $version);
    }

    /**
     * Get backup settings
     */
    public static function getBackupSettings(): array
    {
        return [
            'enabled' => static::get('backup_enabled', 'true') === 'true',
            'interval' => static::get('backup_interval', 'daily'),
            'last_backup' => static::get('last_backup'),
        ];
    }

    /**
     * Update backup settings
     */
    public static function updateBackupSettings(array $settings): void
    {
        if (isset($settings['enabled'])) {
            static::set('backup_enabled', $settings['enabled'] ? 'true' : 'false');
        }
        if (isset($settings['interval'])) {
            static::set('backup_interval', $settings['interval']);
        }
    }

    /**
     * Update last backup time
     */
    public static function updateLastBackup(): void
    {
        static::set('last_backup', now()->toISOString());
    }
}