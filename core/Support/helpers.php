<?php

use Symfony\Component\VarDumper\VarDumper;

if (!function_exists('dd')) {
    function dd(...$vars)
    {
        foreach ($vars as $v) {
            VarDumper::dump($v);
        }
        exit;
    }
}

if (!function_exists('container')) {
    function container(): Psr\Container\ContainerInterface
    {
        return Core\Application::getInstance()->container();
    }
}

if (!function_exists('config')) {
    function config(): array
    {
        return container()->get(\Core\Config\Config::class);
    }
}

if (!function_exists('base_path')) {
    function base_path(string $path = ""): string
    {
        $path = trim($path, '\/');
        return realpath($path);
    }
}

if (!function_exists('app_path')) {
    function app_path(): string
    {
        return base_path('app');
    }
}

if (!function_exists('config_path')) {
    function config_path(): string
    {
        return base_path('config');
    }
}

if (!function_exists('public_path')) {
    function public_path(): string
    {
        return base_path('public');
    }
}

if (!function_exists('resources_path')) {
    function resources_path(): string
    {
        return base_path('resources');
    }
}

if (!function_exists('routes_path')) {
    function routes_path(): string
    {
        return base_path('routes');
    }
}

if (!function_exists('value')) {
    function value($value)
    {
        return $value instanceof Closure ? $value() : $value;
    }
}

if (!function_exists('env')) {
    function env($key, $default = null)
    {
        $value = getenv($key);

        if ($value === false) {
            return value($default);
        }

        switch (strtolower($value)) {
            case 'true':
            case '(true)':
                return true;
            case 'false':
            case '(false)':
                return false;
            case 'empty':
            case '(empty)':
                return '';
            case 'null':
            case '(null)':
                return null;
        }

        if (($valueLength = strlen($value)) > 1 && $value[0] === '"' && $value[$valueLength - 1] === '"') {
            return substr($value, 1, -1);
        }

        return $value;
    }
}

if (!function_exists('is_api')) {
    function is_api(\Psr\Http\Message\ServerRequestInterface $request): bool
    {
        return strpos($request->getUri()->getPath(), '/api') === 0;
    }
}

if (!function_exists('mix')) {
    function mix(string $file)
    {
        $manifestPath = public_path().'/resources/mix-manifest.json';
        $manifest = json_decode(file_get_contents($manifestPath), true);
        return asset($manifest[$file]);
    }
}

if (!function_exists('asset')) {
    function asset($file)
    {
        return "/resources/$file";
    }
}

if (!function_exists('view')) {
    function view(string $template, array $vars = []): string
    {
        $pathTemplate = resources_path()."/views/$template.php";

        if (file_exists($pathTemplate) && is_file($pathTemplate)) {
            extract($vars, EXTR_SKIP);
            ob_start();
            include "$pathTemplate";
            return ob_get_clean() ?: '';
        }

        return '';
    }
}
