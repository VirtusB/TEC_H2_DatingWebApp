<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitf096d35fc98848f3b7c68cc345c3f82c
{
    public static $prefixLengthsPsr4 = array (
        'P' => 
        array (
            'PHPMailer\\PHPMailer\\' => 20,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'PHPMailer\\PHPMailer\\' => 
        array (
            0 => __DIR__ . '/..' . '/phpmailer/phpmailer/src',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitf096d35fc98848f3b7c68cc345c3f82c::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitf096d35fc98848f3b7c68cc345c3f82c::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}
