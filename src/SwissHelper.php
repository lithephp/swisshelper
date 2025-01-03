<?php

if (!function_exists('now')) {
    function now($format = null)
    {
        $date = new DateTime();
        return $format ? $date->format($format) : $date;
    }
}

if (!function_exists('str')) {
    function str($string)
    {
        return new class($string) {
            private $string;

            public function __construct($string)
            {
                $this->string = $string;
            }

            public function slug()
            {
                return strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $this->removeAccents())));
            }

            public function removeAccents()
            {
                return preg_replace(
                    array("/(á|à|ã|â|ä)/", "/(Á|À|Ã|Â|Ä)/", "/(é|è|ê|ë)/", "/(É|È|Ê|Ë)/", "/(í|ì|î|ï)/", "/(Í|Ì|Î|Ï)/", "/(ó|ò|õ|ô|ö)/", "/(Ó|Ò|Õ|Ô|Ö)/", "/(ú|ù|û|ü)/", "/(Ú|Ù|Û|Ü)/", "/(ñ)/", "/(Ñ)/"),
                    array("a", "A", "e", "E", "i", "I", "o", "O", "u", "U", "n", "N"),
                    $this->string
                );
            }

            public function onlyNumbers()
            {
                return preg_replace("/[^0-9]/", "", $this->string);
            }

            public function mask($mask)
            {
                $value = str($this->string)->onlyNumbers();
                $masked = '';
                $k = 0;
                for ($i = 0; $i <= strlen($mask) - 1; $i++) {
                    if ($mask[$i] == '#') {
                        if (isset($value[$k])) $masked .= $value[$k++];
                    } else {
                        if (isset($mask[$i])) $masked .= $mask[$i];
                    }
                }
                return $masked;
            }
        };
    }
}

if (!function_exists('arr')) {
    function arr($array)
    {
        return new class($array) {
            private $array;

            public function __construct($array)
            {
                $this->array = $array;
            }

            public function get($key, $default = null)
            {
                $array = $this->array;

                if (is_null($key)) {
                    return $array;
                }

                foreach (explode('.', $key) as $segment) {
                    if (!is_array($array) || !array_key_exists($segment, $array)) {
                        return $default;
                    }
                    $array = $array[$segment];
                }

                return $array;
            }

            public function only($keys)
            {
                return array_intersect_key($this->array, array_flip((array) $keys));
            }

            public function except($keys)
            {
                return array_diff_key($this->array, array_flip((array) $keys));
            }
        };
    }
}

if (!function_exists('money')) {
    /**
     * Format monetary values
     * 
     * @param float|int $value Amount to format
     * @param string $currency Currency code (ISO 4217)
     * @param array $options Formatting options
     * @return string
     */
    function money($value, $currency = 'USD', array $options = [])
    {
        // Currency configurations
        $currencies = [
            'USD' => [
                'symbol' => '$',
                'position' => 'before',
                'decimal' => '.',
                'thousands' => ',',
                'decimals' => 2,
                'template' => '{symbol}{value}'
            ],
            'EUR' => [
                'symbol' => '€',
                'position' => 'after',
                'decimal' => ',',
                'thousands' => '.',
                'decimals' => 2,
                'template' => '{value}{symbol}'
            ],
            'GBP' => [
                'symbol' => '£',
                'position' => 'before',
                'decimal' => '.',
                'thousands' => ',',
                'decimals' => 2,
                'template' => '{symbol}{value}'
            ],
            'JPY' => [
                'symbol' => '¥',
                'position' => 'before',
                'decimal' => '.',
                'thousands' => ',',
                'decimals' => 0,
                'template' => '{symbol}{value}'
            ],
            'CNY' => [
                'symbol' => '¥',
                'position' => 'before',
                'decimal' => '.',
                'thousands' => ',',
                'decimals' => 2,
                'template' => '{symbol}{value}'
            ],
            'BRL' => [
                'symbol' => 'R$',
                'position' => 'before',
                'decimal' => ',',
                'thousands' => '.',
                'decimals' => 2,
                'template' => '{symbol} {value}'
            ],
            'INR' => [
                'symbol' => '₹',
                'position' => 'before',
                'decimal' => '.',
                'thousands' => ',',
                'decimals' => 2,
                'template' => '{symbol}{value}'
            ],
            'RUB' => [
                'symbol' => '₽',
                'position' => 'after',
                'decimal' => ',',
                'thousands' => ' ',
                'decimals' => 2,
                'template' => '{value}{symbol}'
            ],
            'AUD' => [
                'symbol' => 'A$',
                'position' => 'before',
                'decimal' => '.',
                'thousands' => ',',
                'decimals' => 2,
                'template' => '{symbol}{value}'
            ],
            'CAD' => [
                'symbol' => 'CA$',
                'position' => 'before',
                'decimal' => '.',
                'thousands' => ',',
                'decimals' => 2,
                'template' => '{symbol}{value}'
            ],
            'AOA' => [
                'symbol' => 'Kz',
                'position' => 'before',
                'decimal' => ',',
                'thousands' => '.',
                'decimals' => 2,
                'template' => '{symbol} {value}'
            ]
        ];

        // Get currency configuration or use USD as default
        $config = $currencies[$currency] ?? $currencies['USD'];

        // Merge with custom options
        $config = array_merge($config, $options);

        // Format the number according to currency configuration
        $formatted = number_format(
            $value,
            $config['decimals'],
            $config['decimal'],
            $config['thousands']
        );

        // Return only the number if no symbol is needed
        if (isset($options['symbol']) && $options['symbol'] === false) {
            return $formatted;
        }

        // Apply template
        return str_replace(
            ['{symbol}', '{value}'],
            [$config['symbol'], $formatted],
            $config['template']
        );
    }
}

if (!function_exists('mask')) {
    function mask($value, $mask)
    {
        return str($value)->mask($mask);
    }
}

if (!function_exists('validate')) {
    function validate($value = null)
    {
        return new class($value) {
            private $value;

            public function __construct($value)
            {
                $this->value = $value;
            }

            public function email()
            {
                return filter_var($this->value, FILTER_VALIDATE_EMAIL) !== false;
            }

            public function url()
            {
                return filter_var($this->value, FILTER_VALIDATE_URL) !== false;
            }

            public function ip()
            {
                return filter_var($this->value, FILTER_VALIDATE_IP) !== false;
            }

            public function date($format = 'Y-m-d')
            {
                $date = DateTime::createFromFormat($format, $this->value);
                return $date && $date->format($format) === $this->value;
            }

            // Validate if the field value contains only letters and spaces
            public function name(): bool
            {
                return preg_match('/^[\p{L}\s]+$/u', $this->value) === 1;
            }

            public function creditCard()
            {
                $number = str($this->value)->onlyNumbers();
                $sum = 0;
                $length = strlen($number);
                $parity = $length % 2;

                for ($i = $length - 1; $i >= 0; $i--) {
                    $digit = (int)$number[$i];
                    if ($i % 2 == $parity) {
                        $digit *= 2;
                        if ($digit > 9) {
                            $digit -= 9;
                        }
                    }
                    $sum += $digit;
                }

                return ($sum % 10 == 0);
            }

            public function password($minLength = 8, $requireSpecial = true, $requireNumber = true, $requireUpper = true, $requireLower = true)
            {
                if (strlen($this->value) < $minLength) {
                    return false;
                }

                if ($requireSpecial && !preg_match('/[!@#$%^&*()\-_=+{};:,<.>]/', $this->value)) {
                    return false;
                }

                if ($requireNumber && !preg_match('/[0-9]/', $this->value)) {
                    return false;
                }

                if ($requireUpper && !preg_match('/[A-Z]/', $this->value)) {
                    return false;
                }

                if ($requireLower && !preg_match('/[a-z]/', $this->value)) {
                    return false;
                }

                return true;
            }

            public function age($min = null, $max = null)
            {
                try {
                    $birthday = new DateTime($this->value);
                    $today = new DateTime();
                    $age = $today->diff($birthday)->y;

                    if ($min !== null && $age < $min) {
                        return false;
                    }

                    if ($max !== null && $age > $max) {
                        return false;
                    }

                    return true;
                } catch (Exception $e) {
                    return false;
                }
            }

            public function between($min, $max)
            {
                $value = is_numeric($this->value) ? $this->value : strlen($this->value);
                return $value >= $min && $value <= $max;
            }

            public function contains($needle)
            {
                return str_contains($this->value, $needle);
            }

            public function endsWith($needle)
            {
                return str_ends_with($this->value, $needle);
            }

            public function startsWith($needle)
            {
                return str_starts_with($this->value, $needle);
            }

            public function int(): bool
            {
                return filter_var($this->value, FILTER_VALIDATE_INT) !== false;
            }
        };
    }
}

if (!function_exists('random')) {
    function random($length = 16, $type = 'alnum')
    {
        $types = [
            'alnum' => '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ',
            'alpha' => 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ',
            'numeric' => '0123456789',
            'nozero' => '123456789'
        ];

        $charset = $types[$type] ?? $types['alnum'];
        $str = '';

        for ($i = 0; $i < $length; $i++) {
            $str .= $charset[random_int(0, strlen($charset) - 1)];
        }

        return $str;
    }
}

if (!function_exists('url')) {
    function url($path = null)
    {
        return new class($path) {
            private $path;

            public function __construct($path)
            {
                $this->path = $path;
            }

            public function current()
            {
                $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' ? 'https' : 'http';
                return "$protocol://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
            }

            public function base()
            {
                $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' ? 'https' : 'http';
                return "$protocol://$_SERVER[HTTP_HOST]";
            }

            public function to($path = '')
            {
                return $this->base() . '/' . ltrim($path, '/');
            }

            public function previous()
            {
                return $_SERVER['HTTP_REFERER'] ?? $this->base();
            }
        };
    }
}
