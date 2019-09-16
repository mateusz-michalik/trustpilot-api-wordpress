<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit9e1c4406c37b4d16ff498f3152e54d18
{
    public static $prefixLengthsPsr4 = array (
        'M' => 
        array (
            'Milchek\\TrustpilotApiWordPress\\' => 31,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Milchek\\TrustpilotApiWordPress\\' => 
        array (
            0 => __DIR__ . '/../..' . '/inc',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit9e1c4406c37b4d16ff498f3152e54d18::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit9e1c4406c37b4d16ff498f3152e54d18::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}