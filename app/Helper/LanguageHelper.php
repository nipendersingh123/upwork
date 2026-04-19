<?php

namespace App\Helper;

use App\Models\Language;

class LanguageHelper
{
    private static $language = null;
    private static $default = null;
    private static $user_lang_slug = null;
    private static $default_slug = null;
    private static $user_lang = null;
    private static $all_language = null;

    public function __construct()
    {
        self::lang_instance();
    }

    private static function lang_instance()
    {
        if (self::$language === null) {
            self::$language = new Language();
        }
        return self::$language;
    }

    /**
     * Prefer row marked default; otherwise first published; otherwise any first row.
     */
    private static function resolveDefaultLanguage(): ?Language
    {
        $model = self::lang_instance();

        return $model->where('default', 1)->first()
            ?? $model->newQuery()->where('status', 'publish')->orderBy('id')->first()
            ?? $model->newQuery()->orderBy('id')->first();
    }

    public static function user_lang()
    {
        if (self::$user_lang === null) {
            $session_lang = session()->get('lang');
            if (! empty($session_lang) && $session_lang !== self::default_slug()) {
                self::$user_lang = self::lang_instance()->where('slug', $session_lang)->first();
            }
            self::$user_lang = self::$user_lang ?? self::default();
        }
        return self::$user_lang;
    }

    public static function default()
    {
        if (self::$default === null) {
            self::$default = self::resolveDefaultLanguage();
        }
        return self::$default;
    }

    public static function default_slug()
    {
        if (self::$default_slug === null) {
            self::$default_slug = self::default()?->slug ?? 'en';
        }
        return self::$default_slug;
    }

    public static function default_dir()
    {
        return self::default()?->direction ?? 'ltr';
    }

    public static function user_lang_slug()
    {
        if (self::$user_lang_slug === null) {
            self::$user_lang_slug = session()->get('lang') ?: self::default_slug();
        }
        return self::$user_lang_slug;
    }

    public static function user_lang_dir()
    {
        return self::user_lang()?->direction ?? 'ltr';
    }

    public static function all_languages(string $type = 'publish')
    {
        if (self::$all_language === null) {
            self::$all_language = self::lang_instance()->where(['status' => 'publish'])->get();
        }
        return self::$all_language;
    }
}
