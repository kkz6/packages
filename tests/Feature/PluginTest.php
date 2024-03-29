<?php

use Composer\Config;
use Composer\IO\NullIO;
use Composer\Plugin\PluginEvents;
use Composer\Plugin\PreFileDownloadEvent;
use Composer\Util\HttpDownloader;
use RalphJSmit\Packages\Plugin;

beforeEach(function () {
    $this->originalDir = __DIR__;
});

afterEach(function () {
    define(__DIR__, $this->originalDir);
});

it('can generate an identifier and construct the URL', function () {
    $dir = '/Users/ralphjsmit/Code/Sites/test-project/vendor/ralphjsmit/packages/src';

    $plugin = new Plugin(fn () => $dir);

    $preFileDownloadEvent = new PreFileDownloadEvent(
        name: PluginEvents::PRE_FILE_DOWNLOAD,
        httpDownloader: new HttpDownloader(new NullIO(), new Config()),
        processedUrl: 'https://satis.ralphjsmit.com/dist/ralphjsmit/laravel-filament-media-library/ralphjsmit-laravel-filament-media-library-5aa15ac21255b3b617c3d14d116200e469b8e7af-zip-6bd925.zip',
        type: 'package'
    );

    expect($preFileDownloadEvent)
        ->getProcessedUrl()->toBe('https://satis.ralphjsmit.com/dist/ralphjsmit/laravel-filament-media-library/ralphjsmit-laravel-filament-media-library-5aa15ac21255b3b617c3d14d116200e469b8e7af-zip-6bd925.zip');

    $plugin->onPreFileDownload($preFileDownloadEvent);

    $expectedIdentifier = urlencode(gethostname() . '|' . sha1($dir) . '|' . ('test-project'));

    expect($preFileDownloadEvent)
        ->getProcessedUrl()->toBe(
            'https://satis.ralphjsmit.com/dist/ralphjsmit/laravel-filament-media-library/ralphjsmit-laravel-filament-media-library-5aa15ac21255b3b617c3d14d116200e469b8e7af-zip-6bd925.zip?id=' . $expectedIdentifier . '&ralphjsmit-packages-version=' . Plugin::PLUGIN_VERSION
        );
});

it('can generate an identifier and construct the URL for Windows', function () {
    $dir = '\Users\ralphjsmit\Code\Sites\test-project\vendor\ralphjsmit\packages\src';

    $plugin = new Plugin(fn () => $dir, '\\');

    $preFileDownloadEvent = new PreFileDownloadEvent(
        name: PluginEvents::PRE_FILE_DOWNLOAD,
        httpDownloader: new HttpDownloader(new NullIO(), new Config()),
        processedUrl: 'https://satis.ralphjsmit.com/dist/ralphjsmit/laravel-filament-media-library/ralphjsmit-laravel-filament-media-library-5aa15ac21255b3b617c3d14d116200e469b8e7af-zip-6bd925.zip',
        type: 'package'
    );

    expect($preFileDownloadEvent)
        ->getProcessedUrl()->toBe('https://satis.ralphjsmit.com/dist/ralphjsmit/laravel-filament-media-library/ralphjsmit-laravel-filament-media-library-5aa15ac21255b3b617c3d14d116200e469b8e7af-zip-6bd925.zip');

    $plugin->onPreFileDownload($preFileDownloadEvent);

    $expectedIdentifier = urlencode(gethostname() . '|' . sha1($dir) . '|' . ('test-project'));

    expect($preFileDownloadEvent)
        ->getProcessedUrl()->toBe(
            'https://satis.ralphjsmit.com/dist/ralphjsmit/laravel-filament-media-library/ralphjsmit-laravel-filament-media-library-5aa15ac21255b3b617c3d14d116200e469b8e7af-zip-6bd925.zip?id=' . $expectedIdentifier . '&ralphjsmit-packages-version=' . Plugin::PLUGIN_VERSION
        );
});

it('can generate an identifier and construct the URL for Laravel Envoyer', function () {
    $dir = '/Users/ralphjsmit/Code/Sites/test-project/releases/20240101140000/vendor/ralphjsmit/packages/src';

    $plugin = new Plugin(fn () => $dir);

    $preFileDownloadEvent = new PreFileDownloadEvent(
        name: PluginEvents::PRE_FILE_DOWNLOAD,
        httpDownloader: new HttpDownloader(new NullIO(), new Config()),
        processedUrl: 'https://satis.ralphjsmit.com/dist/ralphjsmit/laravel-filament-media-library/ralphjsmit-laravel-filament-media-library-5aa15ac21255b3b617c3d14d116200e469b8e7af-zip-6bd925.zip',
        type: 'package'
    );

    expect($preFileDownloadEvent)
        ->getProcessedUrl()->toBe('https://satis.ralphjsmit.com/dist/ralphjsmit/laravel-filament-media-library/ralphjsmit-laravel-filament-media-library-5aa15ac21255b3b617c3d14d116200e469b8e7af-zip-6bd925.zip');

    $plugin->onPreFileDownload($preFileDownloadEvent);

    $expectedIdentifier = urlencode(gethostname() . '|' . sha1('/Users/ralphjsmit/Code/Sites/test-project/releases/{release}/vendor/ralphjsmit/packages/src') . '|' . ('test-project'));

    expect($preFileDownloadEvent)
        ->getProcessedUrl()->toBe(
            'https://satis.ralphjsmit.com/dist/ralphjsmit/laravel-filament-media-library/ralphjsmit-laravel-filament-media-library-5aa15ac21255b3b617c3d14d116200e469b8e7af-zip-6bd925.zip?id=' . $expectedIdentifier . '&ralphjsmit-packages-version=' . Plugin::PLUGIN_VERSION
        );
});

it('can generate an identifier and construct the URL for Laravel Envoyer on Windows based server', function () {
    $dir = '\Users\ralphjsmit\Code\Sites\test-project\releases\20240101140000\vendor\ralphjsmit\packages\src';

    $plugin = new Plugin(fn () => $dir, '\\');

    $preFileDownloadEvent = new PreFileDownloadEvent(
        name: PluginEvents::PRE_FILE_DOWNLOAD,
        httpDownloader: new HttpDownloader(new NullIO(), new Config()),
        processedUrl: 'https://satis.ralphjsmit.com/dist/ralphjsmit/laravel-filament-media-library/ralphjsmit-laravel-filament-media-library-5aa15ac21255b3b617c3d14d116200e469b8e7af-zip-6bd925.zip',
        type: 'package'
    );

    expect($preFileDownloadEvent)
        ->getProcessedUrl()->toBe('https://satis.ralphjsmit.com/dist/ralphjsmit/laravel-filament-media-library/ralphjsmit-laravel-filament-media-library-5aa15ac21255b3b617c3d14d116200e469b8e7af-zip-6bd925.zip');

    $plugin->onPreFileDownload($preFileDownloadEvent);

    $expectedIdentifier = urlencode(gethostname() . '|' . sha1('\Users\ralphjsmit\Code\Sites\test-project\releases\{release}\vendor\ralphjsmit\packages\src') . '|' . ('test-project'));

    expect($preFileDownloadEvent)
        ->getProcessedUrl()->toBe(
            'https://satis.ralphjsmit.com/dist/ralphjsmit/laravel-filament-media-library/ralphjsmit-laravel-filament-media-library-5aa15ac21255b3b617c3d14d116200e469b8e7af-zip-6bd925.zip?id=' . $expectedIdentifier . '&ralphjsmit-packages-version=' . Plugin::PLUGIN_VERSION
        );
});
