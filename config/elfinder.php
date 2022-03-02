<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Upload dir
    |--------------------------------------------------------------------------
    |
    | The dir where to store the images (relative from public).
    |
    */
    'dir' => ['storage/uploads', 'videos'],

    /*
    |--------------------------------------------------------------------------
    | Filesystem disks (Flysytem)
    |--------------------------------------------------------------------------
    |
    | Define an array of Filesystem disks, which use Flysystem.
    | You can set extra options, example:
    |
    | 'my-disk' => [
    |        'URL' => url('to/disk'),
    |        'alias' => 'Local storage',
    |    ]
    */
    'disks' => [
        // 'uploads',
    ],

    /*
    |--------------------------------------------------------------------------
    | Routes group config
    |--------------------------------------------------------------------------
    |
    | The default group settings for the elFinder routes.
    |
    */

    'route' => [
        'prefix'     => config('backpack.base.route_prefix', 'admin').'/elfinder',
        'middleware' => ['web', config('backpack.base.middleware_key', 'admin')], //Set to null to disable middleware filter
    ],

    /*
    |--------------------------------------------------------------------------
    | Access filter
    |--------------------------------------------------------------------------
    |
    | Filter callback to check the files
    |
    */

    'access' => 'Barryvdh\Elfinder\Elfinder::checkAccess',

    /*
    |--------------------------------------------------------------------------
    | Roots
    |--------------------------------------------------------------------------
    |
    | By default, the roots file is LocalFileSystem, with the above public dir.
    | If you want custom options, you can set your own roots below.
    |
    */

    'roots' => null,

    /*
    |--------------------------------------------------------------------------
    | Options
    |--------------------------------------------------------------------------
    |
    | These options are merged, together with 'roots' and passed to the Connector.
    | See https://github.com/Studio-42/elFinder/wiki/Connector-configuration-options-2.1
    |
    */

    'options' => [
        'bind' => [
            'upload.pre mkdir.pre mkfile.pre rename.pre archive.pre ls.pre' => [
                'Plugin.Normalizer.cmdPreprocess',
                'Plugin.Sanitizer.cmdPreprocess'
            ],
            'ls' => [
                'Plugin.Normalizer.cmdPostprocess',
                'Plugin.Sanitizer.cmdPostprocess'
            ],
            'upload.presave' => [
                'Plugin.AutoResize.onUpLoadPreSave',
                'Plugin.Normalizer.onUpLoadPreSave',
                'Plugin.Sanitizer.onUpLoadPreSave'
            ],
        ],
        'plugin' => [
            'Normalizer' => [
                'enable' => true,
                'targets'  => ['\\','/',':','*','?','"','<','>','|',' '], // target chars
                'replace'  => '_', // replace to this
                'convmap' => [
                    ',' => '_',
                    '^' => '_',
                    "а" => "a",
                    "б" => "b",
                    "в" => "v",
                    "г" => "g",
                    "д" => "d",
                    "е" => "e",
                    "ё" => "e",
                    "ж" => "zh",
                    "з" => "z",
                    "и" => "i",
                    "й" => "j",
                    "к" => "k",
                    "л" => "l",
                    "м" => "m",
                    "н" => "n",
                    "о" => "o",
                    "п" => "p",
                    "р" => "r",
                    "с" => "s",
                    "т" => "t",
                    "у" => "u",
                    "ф" => "f",
                    "х" => "h",
                    "ц" => "ts",
                    "ч" => "ch",
                    "ш" => "sh",
                    "щ" => "shch",
                    "ы" => "y",
                    "э" => "e",
                    "ю" => "yu",
                    "я" => "ya",
                    "А" => "a",
                    "Б" => "b",
                    "В" => "v",
                    "Г" => "g",
                    "Д" => "d",
                    "Е" => "e",
                    "Ё" => "e",
                    "Ж" => "zh",
                    "З" => "z",
                    "И" => "i",
                    "Й" => "j",
                    "К" => "k",
                    "Л" => "l",
                    "М" => "m",
                    "Н" => "n",
                    "О" => "o",
                    "П" => "p",
                    "Р" => "r",
                    "С" => "s",
                    "Т" => "t",
                    "У" => "u",
                    "Ф" => "f",
                    "Х" => "h",
                    "Ц" => "ts",
                    "Ч" => "ch",
                    "Ш" => "sh",
                    "Щ" => "shch",
                    "Ы" => "y",
                    "Э" => "e",
                    "Ю" => "yu",
                    "Я" => "ya",
                    " " => "_"
                ]
            ]
        ]
    ]
];
