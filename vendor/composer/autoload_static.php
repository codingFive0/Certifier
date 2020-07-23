<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitfb4a5aaeaff5c4e86f8148f3c9005495
{
    public static $prefixLengthsPsr4 = array (
        'c' => 
        array (
            'codingFive0\\Certifier\\' => 22,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'codingFive0\\Certifier\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitfb4a5aaeaff5c4e86f8148f3c9005495::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitfb4a5aaeaff5c4e86f8148f3c9005495::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInitfb4a5aaeaff5c4e86f8148f3c9005495::$classMap;

        }, null, ClassLoader::class);
    }
}
