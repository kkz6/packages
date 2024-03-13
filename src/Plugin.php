<?php

namespace RalphJSmit\Packages;

use const DIRECTORY_SEPARATOR;

use Closure;
use Composer\Composer;
use Composer\EventDispatcher\EventSubscriberInterface;
use Composer\IO\IOInterface;
use Composer\Plugin\PluginEvents;
use Composer\Plugin\PluginInterface;
use Composer\Plugin\PreFileDownloadEvent;

class Plugin implements EventSubscriberInterface, PluginInterface
{
    public const PLUGIN_VERSION = '1.1.0';

    protected Closure $directoryResolver;

    public function __construct(
        ?Closure $directoryResolver = null,
        protected string $directorySeparator = DIRECTORY_SEPARATOR,
    ) {
        $this->directoryResolver = $directoryResolver ?? function (): string {
            return __DIR__;
        };
    }

    public static function getSubscribedEvents(): array
    {
        return [
            PluginEvents::PRE_FILE_DOWNLOAD => [
                [
                    'onPreFileDownload',
                    9999,
                ],
            ],
        ];
    }

    /**
     * Note for future self: update the `PLUGIN_VERSION` constant before releasing.
     */
    public function onPreFileDownload(PreFileDownloadEvent $event): void
    {
        $processedUrl = $event->getProcessedUrl();

        if (! str_contains($processedUrl, 'ralphjsmit')) {
            return;
        }

        $directory = ($this->directoryResolver)();

        if (str_contains($directory, 'releases')) {
            $directorySanitized = $this->getEnvoyerDirectorySanitized($directory);
        } else {
            $directorySanitized = $directory;
        }

        if (str_contains($directory, 'releases')) {
            $directoryName = $this->getEnvoyerDirectoryName($directory);
        } else {
            $directoryName = $this->getDefaultDirectoryName($directory);
        }

        $identifier = gethostname() . '|' . sha1($directorySanitized) . '|' . $directoryName;

        // Modifying this code is against the product license. Just buy the dang thing and save yourself the effort.
        $event->setProcessedUrl($processedUrl . '?id=' . urlencode($identifier) . '&ralphjsmit-packages-version=' . static::PLUGIN_VERSION);
    }

    protected function getEnvoyerDirectorySanitized(string $directory): string
    {
        $directorySeparator = $this->directorySeparator;

        if ($directorySeparator === '\\') {
            $directorySeparator = '\\\\';
        }

        return preg_replace(
            '#' . $directorySeparator . 'releases' . $directorySeparator . '.*?' . $directorySeparator . 'vendor' . $directorySeparator . '#',
            "{$this->directorySeparator}releases{$this->directorySeparator}{release}{$this->directorySeparator}vendor{$this->directorySeparator}",
            $directory
        );
    }

    protected function getEnvoyerDirectoryName(string $directory): string
    {
        // Str::before() implementation...
        $directoryBeforeReleases = strstr($directory, 'releases', true);

        if ($directoryBeforeReleases === false) {
            $directoryBeforeReleases = $directory;
        }

        // Trim the trailing directory separator.
        $directoryBeforeReleases = rtrim($directoryBeforeReleases, $this->directorySeparator);

        // Str::afterLast() implementation...
        $positionLastDirectorySeparator = strrpos($directoryBeforeReleases, (string) $this->directorySeparator);

        if ($positionLastDirectorySeparator === false) {
            return $directoryBeforeReleases;
        }

        return substr($directoryBeforeReleases, $positionLastDirectorySeparator + strlen($this->directorySeparator));
    }

    protected function getDefaultDirectoryName(string $directory): string
    {
        $directorySeparator = $this->directorySeparator;

        // Windows uses backslashes as directory separators. If we use a backslash in the string for regex, we need to escape this one double.
        if ($directorySeparator === '\\') {
            $directorySeparator = '\\\\';
        }

        preg_match(
            '#' . $directorySeparator . '([^' . $directorySeparator . ']+)' . $directorySeparator . 'vendor' . $directorySeparator . '#',
            $directory,
            $matches
        );

        return $matches[1] ?? $directory;
    }

    public function activate(Composer $composer, IOInterface $io): void
    {
        //
    }

    public function deactivate(Composer $composer, IOInterface $io): void
    {
        //
    }

    public function uninstall(Composer $composer, IOInterface $io): void
    {
        //
    }
}
