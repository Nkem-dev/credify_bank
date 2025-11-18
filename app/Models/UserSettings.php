<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserSettings extends Model
{
    protected $fillable = [
        'user_id',
        'two_factor_enabled',
        'biometric_enabled',
        'security_questions',
        'session_timeout',
        'email_notifications',
        'sms_notifications',
        'push_notifications',
        'notification_preferences',
        'hide_balance',
        'language',
        'theme',
        'currency_format',
        'account_closed',
        'closed_at',
        'closure_reason',
    ];

    protected $casts = [
        'two_factor_enabled' => 'boolean',
        'biometric_enabled' => 'boolean',
        'security_questions' => 'array',
        'session_timeout' => 'integer',
        'email_notifications' => 'boolean',
        'sms_notifications' => 'boolean',
        'push_notifications' => 'boolean',
        'notification_preferences' => 'array',
        'hide_balance' => 'boolean',
        'account_closed' => 'boolean',
        'closed_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // relationship with user
     public function user()
    {
        return $this->belongsTo(User::class);
    }

    // helper to check if a user has enabled or disabled a specific type of notification
     public function getNotificationPreference(string $type): bool
    {
        $preferences = $this->notification_preferences ?? [];
        return $preferences[$type] ?? true;
    }

    // helper to update or create a user’s notification preference and then save it to the database
     public function setNotificationPreference(string $type, bool $enabled): void
    {
        $preferences = $this->notification_preferences ?? [];
        $preferences[$type] = $enabled;
        $this->notification_preferences = $preferences;
        $this->save();
    }

    // get available languages
     public static function getAvailableLanguages(): array
    {
        return [
            'en' => 'English',
            'yo' => 'Yoruba',
            'ig' => 'Igbo',
            'ha' => 'Hausa',
        ];
    }

    // get available themes
    public static function getAvailableThemes(): array
    {
        return [
            'light' => 'Light',
            'dark' => 'Dark',
            'system' => 'System',
        ];
    }

}
