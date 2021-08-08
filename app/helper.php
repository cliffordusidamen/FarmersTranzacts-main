<?php

use App\Models\Designation;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Str;

if (!defined('DATABASE_STRING_MAXLENGTH')) {
    define('DATABASE_STRING_MAXLENGTH', 191);
}

if (!defined('INPUT_TEXTAREA_MAXLENGTH')) {
    define('INPUT_TEXTAREA_MAXLENGTH', 1000);
}

if (!defined('MODEL_PERMISSION_CREATE')) {
    /**
     * Permission to create model entity
     * 
     * @return string
     */
    define('MODEL_PERMISSION_CREATE', 'create');
}

if (!defined('MODEL_PERMISSION_UPDATE')) {
    /**
     * Permission to update model entity
     * 
     * @return string
     */
    define('MODEL_PERMISSION_UPDATE', 'update');
}

if (!defined('MODEL_PERMISSION_DELETE')) {
    /**
     * Permission to delete model entity
     * 
     * @return string
     */
    define('MODEL_PERMISSION_DELETE', 'delete');
}

if (!defined('MODEL_PERMISSION_FORCE_DELETE')) {
    /**
     * Permission to delete model entity, if trashed
     * 
     * @return string
     */
    define('MODEL_PERMISSION_FORCE_DELETE', 'forceDelete');
}

if (!defined('MODEL_PERMISSION_RESTORE')) {
    /**
     * Permission to restore model entity, from trash
     * 
     * @return string
     */
    define('MODEL_PERMISSION_RESTORE', 'restore');
}

if (!defined('MODEL_PERMISSION_VIEW')) {
    /**
     * Permission to view a single model entity
     * 
     * @return string
     */
    define('MODEL_PERMISSION_VIEW', 'view');
}

if (!defined('MODEL_PERMISSION_VIEW_ANY')) {
    /**
     * Permission to update model entity
     * 
     * @return string
     */
    define('MODEL_PERMISSION_VIEW_ANY', 'viewAny');
}

if (!function_exists('carbon')) {
    /**
     * Create \Carbon\Carbon dateTime object
     * 
     * @param string $dateTime
     * @param bool $returnNullIfInvalid
     * @return \Carbon\Carbon|bool
     */
    function carbon($dateTime = null, $returnFalseIfInvalid = false, $africaLagosTimeZone = false)
    {
        try {
            $timezone = $africaLagosTimeZone ? (new DateTimeZone('Africa/Lagos')) : null;
            return new \Carbon\Carbon($dateTime, $timezone);
        } catch (\Carbon\Exceptions\InvalidFormatException $e) {
            return $returnFalseIfInvalid ? false : new \Carbon\Carbon();
        }
    }
}

if (!function_exists('carbon_ng')) {
    /**
     * Create \Carbon\Carbon dateTime object in Africa/Lagos timezone
     * 
     * @param string $dateTime
     * @return \Carbon\Carbon|bool
     */
    function carbon_ng($dateTime = null)
    {
        return carbon($dateTime, false, true);
    }
}

if (!function_exists('flashMessage')) {
    /**
     * Set flash message
     * 
     * @param  string  $type
     * @param  string  $message
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    function flashMessage($type, $message, ?Request $request = null)
    {
        $request = $request ?? request();

        $request->session()->flash('flash.banner', $message);
        $request->session()->flash('flash.bannerStyle', $type);

    }
}

if (!function_exists('userIsSuperAdmin')) {

    /**
     * Check if authenticated user is admin
     * 
     * @return bool
     */
    function userIsSuperAdmin(): bool
    {
        return auth()->check() && auth()->user()->is_super_admin;
    }
}

if (!function_exists('userIsAdminDesignation')) {

    /**
     * Check if authenticated user is admin
     * 
     * @return bool
     */
    function userIsAdminDesignation(): bool
    {
        return auth()->check() && auth()->user()->designation_id && auth()->user()->designation_id == Designation::STAFF_ID;
    }
}

if (!function_exists('authUserIsShopper')) {

    /**
     * Check if authenticated user is shopper
     * 
     * @return bool
     */
    function authUserIsShopper(): bool
    {
        return auth()->check() && auth()->user()->designation_id && auth()->user()->designation_id == Designation::SHOPPER_ID;
    }
}

if (!function_exists('userVendorIsApproved')) {
    /**
     * Check if user is a vendor and has approved registration
     * 
     * @return bool
     */
    function userVendorIsApproved(): bool
    {
        if (!authUserIsVendor()) {
            return false;
        }

        return auth()->user()->vendors()->first()->approval_status == \App\Models\Vendor::STATUS_APPROVED;
    }
}

if (!function_exists('authUserIsVendor')) {

    /**
     * Check if authenticated user is vendor
     * 
     * @return bool
     */
    function authUserIsVendor(): bool
    {
        return auth()->check() && auth()->user()->designation_id && auth()->user()->designation_id == Designation::VENDOR_ID;
    }
}

if (!function_exists('getClassNames')) {
    /**
     * Get a list of all class names of php files in the given directory
     * 
     * @return array
     */
    function getClassNames($path) {
        $out = [];
        $results = scandir($path);
        foreach ($results as $result) {
            if ($result === '.' || $result === '..') {
                continue;
            }

            $isPhpFile = preg_match('/(\.php)$/', $result);

            if (!$isPhpFile) {
                continue;
            }

            $filename = $result;
            if (is_dir($filename)) {
                $out = array_merge($out, getClassNames($filename));
            } else {
                $out[] = substr($filename,0,-4);
            }
        }
        return $out;
    }
}

if (!function_exists('getModelNames')) {
    /**
     * Get list of names of all models in projects
     * 
     * @return array
     */
    function getModelNames()
    {
        return getClassNames(app_path() . '/Models');
    }
}

if (!function_exists('initModelPermissions')) {
    /**
     * Initialize all model related permissions recognizable in the application
     * 
     * @return void
     */
    function initModelPermissions() {
        $modelNames = getModelNames();

        foreach($modelNames as $modelName) {
            $modelClassName = "\\App\\Models\\{$modelName}";
            $definesPermissions = method_exists($modelClassName, 'getPermissions');
            if (!$definesPermissions) {
                continue;
            }
            $permissions = collect($modelClassName::getPermissions());

            $permissions->map(fn($permissionName, $key) => (
                Permission::firstOrCreate([
                    'name' => $permissionName . ' ' . Str::snake($modelName),
                ])
            ));
        }
    }
}

if (!function_exists('getModelPermissions')) {
    /**
     * Get all model related permissions recognizable in the application
     * 
     * @param  bool  $readableModelName
     * @return array
     */
    function getModelPermissions(bool $readableModelName = false): array
    {
        $modelPermissions = [];

        $modelNames = getModelNames();

        foreach($modelNames as $modelName) {
            $modelClassName = "\\App\\Models\\{$modelName}";
            $definesPermissions = method_exists($modelClassName, 'getPermissions');
            if (!$definesPermissions) {
                continue;
            }
            $modelNameSnakeCase = Str::snake($modelName);
            $modelKey = $readableModelName
                ? ucwords(strtolower(str_replace('_', ' ', $modelNameSnakeCase)))
                : $modelNameSnakeCase;

            $permissions = collect($modelClassName::getPermissions())->mapWithKeys(function($permName, $perKey) use ($modelNameSnakeCase) {
                return ["{$permName} {$modelNameSnakeCase}" => $permName];
            });

            $modelPermissions[$modelKey] = $permissions->toArray();
        }

        return $modelPermissions;
    }
}

if (!function_exists('defaultModelPermissions')) {
    /**
     * Get list of default model permissions
     * 
     * @return array
     */
    function defaultModelPermissions(): array
    {
        return [
            MODEL_PERMISSION_VIEW_ANY,
            MODEL_PERMISSION_VIEW,
            MODEL_PERMISSION_CREATE,
            MODEL_PERMISSION_UPDATE,
            MODEL_PERMISSION_DELETE,
        ];
    }
}

if (!function_exists('lastWordInText')) {
    /**
     * Get last word in text
     * 
     * @param  string  $text
     * @return string
     */
    function lastWordInText($text): string
    {   
        $pos = strrpos($text, ' ');

        if(!$pos) {
            return $text;
        }

        $lastWord = substr($text,$pos + 1);

        return $lastWord;
    }

}

if (!function_exists('spaceCamelCase')) {
    /**
     * convert camel/pascal case string to normal spaced
     * 
     * @param  string  $text
     * @param  bool  $lowerCase
     * @return string
     */
    function spaceCamelCase(string $text, bool $lowerCase = false): string
    {
        $spaced = trim(str_replace('_', ' ', Str::snake($text)));

        return $lowerCase ? strtolower($spaced) : $spaced;
    }
}