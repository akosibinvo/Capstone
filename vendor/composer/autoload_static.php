<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInita17852e4597999b78ed9b79704dc83d7
{
    public static $files = array (
        'ad155f8f1cf0d418fe49e248db8c661b' => __DIR__ . '/..' . '/react/promise/src/functions_include.php',
        'c4e03ecd470d2a87804979c0a8152284' => __DIR__ . '/..' . '/react/async/src/functions_include.php',
        '7b11c4dc42b3b3023073cb14e519683c' => __DIR__ . '/..' . '/ralouphie/getallheaders/src/getallheaders.php',
        '6e3fae29631ef280660b3cdad06f25a8' => __DIR__ . '/..' . '/symfony/deprecation-contracts/function.php',
        '0e6d7bf4a5811bfa5cf40c5ccd6fae6a' => __DIR__ . '/..' . '/symfony/polyfill-mbstring/bootstrap.php',
        '37a3dc5111fe8f707ab4c132ef1dbc62' => __DIR__ . '/..' . '/guzzlehttp/guzzle/src/functions_include.php',
        'decc78cc4436b1292c6c0d151b19445c' => __DIR__ . '/..' . '/phpseclib/phpseclib/phpseclib/bootstrap.php',
        'ebf8799635f67b5d7248946fe2154f4a' => __DIR__ . '/..' . '/ringcentral/psr7/src/functions_include.php',
    );

    public static $prefixLengthsPsr4 = array (
        'p' => 
        array (
            'phpseclib\\' => 10,
        ),
        'k' => 
        array (
            'kornrunner\\' => 11,
        ),
        'W' => 
        array (
            'Web3\\' => 5,
        ),
        'S' => 
        array (
            'Symfony\\Polyfill\\Mbstring\\' => 26,
            'Symfony\\Component\\Dotenv\\' => 25,
            'Sop\\CryptoTypes\\' => 16,
            'Sop\\CryptoEncoding\\' => 19,
        ),
        'R' => 
        array (
            'RingCentral\\Psr7\\' => 17,
            'React\\Stream\\' => 13,
            'React\\Socket\\' => 13,
            'React\\Promise\\' => 14,
            'React\\Http\\' => 11,
            'React\\EventLoop\\' => 16,
            'React\\Dns\\' => 10,
            'React\\Cache\\' => 12,
        ),
        'P' => 
        array (
            'Psr\\Http\\Message\\' => 17,
            'Psr\\Http\\Client\\' => 16,
        ),
        'G' => 
        array (
            'GuzzleHttp\\Psr7\\' => 16,
            'GuzzleHttp\\Promise\\' => 19,
            'GuzzleHttp\\' => 11,
        ),
        'F' => 
        array (
            'Fig\\Http\\Message\\' => 17,
        ),
        'E' => 
        array (
            'Evenement\\' => 10,
        ),
        'A' => 
        array (
            'ASN1\\' => 5,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'phpseclib\\' => 
        array (
            0 => __DIR__ . '/..' . '/phpseclib/phpseclib/phpseclib',
        ),
        'kornrunner\\' => 
        array (
            0 => __DIR__ . '/..' . '/kornrunner/keccak/src',
        ),
        'Web3\\' => 
        array (
            0 => __DIR__ . '/..' . '/sc0vu/web3.php/src',
            1 => __DIR__ . '/..' . '/web3p/web3.php/src',
        ),
        'Symfony\\Polyfill\\Mbstring\\' => 
        array (
            0 => __DIR__ . '/..' . '/symfony/polyfill-mbstring',
        ),
        'Symfony\\Component\\Dotenv\\' => 
        array (
            0 => __DIR__ . '/..' . '/symfony/dotenv',
        ),
        'Sop\\CryptoTypes\\' => 
        array (
            0 => __DIR__ . '/..' . '/sop/crypto-types/lib/CryptoTypes',
        ),
        'Sop\\CryptoEncoding\\' => 
        array (
            0 => __DIR__ . '/..' . '/sop/crypto-encoding/lib/CryptoEncoding',
        ),
        'RingCentral\\Psr7\\' => 
        array (
            0 => __DIR__ . '/..' . '/ringcentral/psr7/src',
        ),
        'React\\Stream\\' => 
        array (
            0 => __DIR__ . '/..' . '/react/stream/src',
        ),
        'React\\Socket\\' => 
        array (
            0 => __DIR__ . '/..' . '/react/socket/src',
        ),
        'React\\Promise\\' => 
        array (
            0 => __DIR__ . '/..' . '/react/promise/src',
        ),
        'React\\Http\\' => 
        array (
            0 => __DIR__ . '/..' . '/react/http/src',
        ),
        'React\\EventLoop\\' => 
        array (
            0 => __DIR__ . '/..' . '/react/event-loop/src',
        ),
        'React\\Dns\\' => 
        array (
            0 => __DIR__ . '/..' . '/react/dns/src',
        ),
        'React\\Cache\\' => 
        array (
            0 => __DIR__ . '/..' . '/react/cache/src',
        ),
        'Psr\\Http\\Message\\' => 
        array (
            0 => __DIR__ . '/..' . '/psr/http-factory/src',
            1 => __DIR__ . '/..' . '/psr/http-message/src',
        ),
        'Psr\\Http\\Client\\' => 
        array (
            0 => __DIR__ . '/..' . '/psr/http-client/src',
        ),
        'GuzzleHttp\\Psr7\\' => 
        array (
            0 => __DIR__ . '/..' . '/guzzlehttp/psr7/src',
        ),
        'GuzzleHttp\\Promise\\' => 
        array (
            0 => __DIR__ . '/..' . '/guzzlehttp/promises/src',
        ),
        'GuzzleHttp\\' => 
        array (
            0 => __DIR__ . '/..' . '/guzzlehttp/guzzle/src',
        ),
        'Fig\\Http\\Message\\' => 
        array (
            0 => __DIR__ . '/..' . '/fig/http-message-util/src',
        ),
        'Evenement\\' => 
        array (
            0 => __DIR__ . '/..' . '/evenement/evenement/src',
        ),
        'ASN1\\' => 
        array (
            0 => __DIR__ . '/..' . '/sop/asn1/lib/ASN1',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInita17852e4597999b78ed9b79704dc83d7::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInita17852e4597999b78ed9b79704dc83d7::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}