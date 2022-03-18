<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit13720cd6ad513d678d5a1538c0651d9c
{
    public static $prefixLengthsPsr4 = array (
        'Y' => 
        array (
            'YcTech\\Composer\\' => 16,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'YcTech\\Composer\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
        'FPDF' => __DIR__ . '/..' . '/setasign/fpdf/fpdf.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit13720cd6ad513d678d5a1538c0651d9c::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit13720cd6ad513d678d5a1538c0651d9c::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit13720cd6ad513d678d5a1538c0651d9c::$classMap;

        }, null, ClassLoader::class);
    }
}