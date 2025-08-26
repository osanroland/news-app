<?php

namespace App\Config;

class Dotenv
{
    private array $vars = [];

    public function __construct(string $path)
    {
        if (!file_exists($path)) {
            return;
        }

        foreach (file($path) as $line) {
            $line = trim($line);
            if ($line === '' || $line[0] === '#') {
                continue;
            }

            $parts = explode('=', $line, 2);
            if (count($parts) === 2) {
                $this->vars[$parts[0]] = $parts[1];
                putenv($parts[0] . '=' . $parts[1]);
            }
        }
    }

    public function get(string $key, string $default = ''): string
    {
        if (isset($this->vars[$key])) {
            return $this->vars[$key];
        }
        $val = getenv($key);
        return $val !== false ? $val : $default;
    }
}
