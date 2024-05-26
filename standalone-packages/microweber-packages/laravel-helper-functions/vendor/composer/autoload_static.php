<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitc1206dc7a5523ee338b66fbb9c5eea74
{
    public static $prefixLengthsPsr4 = array (
        'M' => 
        array (
            'MicroweberPackages\\LaravelHelperFunctions\\' => 42,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'MicroweberPackages\\LaravelHelperFunctions\\' => 
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
            $loader->prefixLengthsPsr4 = ComposerStaticInitc1206dc7a5523ee338b66fbb9c5eea74::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitc1206dc7a5523ee338b66fbb9c5eea74::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInitc1206dc7a5523ee338b66fbb9c5eea74::$classMap;

        }, null, ClassLoader::class);
    }
}
