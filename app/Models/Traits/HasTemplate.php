<?php

namespace App\Models\Traits;

use InvalidArgumentException;
use Illuminate\View\FileViewFinder;

trait HasTemplate
{
    public function getTemplateList()
    {
        $list = [];
        $view = 'show';
        $namespace = '';
        $viewPath = '';

        if (property_exists(static::class, 'template_view')) {
            $view = static::$template_view;
        }

        $finder = app()['view']->getFinder();
        $hints = $finder->getHints();

        if ($this->hasHintInformation($view)) {
            list($namespace, $view) = $this->parseNamespaceSegments($view);
        }

        if ($namespace) {
            $paths = $hints[$namespace] ?? [];
        } else {
            if (mb_strstr($view, '.') !== false) {
                $parts = explode('.', $view);
                $viewPath = implode('/', array_slice($parts, 0, count($parts) - 1));
                $view = $parts[count($parts) - 1];
                $paths[] = resource_path('views/' . $viewPath);
            }
        }

        if ($paths) {
            foreach ($paths as $path) {
                if (\File::exists($path)) {
                    foreach (\File::files($path) as $file) {
                        if (preg_match('/^(' . $view . '-(.*)).blade.php/', $file->getFilename(), $mch)) {
                            if ($namespace) {
                                $list[$namespace . '::' . $mch[1]] = $mch[2];
                            } else {
                                $list[$viewPath . '.' . $mch[1]] = $mch[2];
                            }
                        }
                    }
                }
            }
        }

        ksort($list);

        return $list;
    }

    /**
     * Returns whether or not the view name has any hint information.
     *
     * @param  string  $name
     * @return bool
     */
    public function hasHintInformation($name)
    {
        return strpos($name, FileViewFinder::HINT_PATH_DELIMITER) > 0;
    }

    /**
     * Get the segments of a template with a named path.
     *
     * @param  string  $name
     * @return array
     *
     * @throws \InvalidArgumentException
     */
    protected static function parseNamespaceSegments($name)
    {
        $segments = explode(FileViewFinder::HINT_PATH_DELIMITER, $name);

        if (count($segments) !== 2) {
            throw new InvalidArgumentException("View [{$name}] has an invalid name.");
        }

        return $segments;
    }
}