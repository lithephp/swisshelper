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


if (!function_exists('session')) {
    function session()
    {

        /**
         * Component responsible for managing session.
         */
        return new class
        {
            /**
             * Set a session variable.
             *
             * @param string $name Name of the session variable.
             * @param mixed $value Value to be assigned to the session variable.
             */
            public static function put($name, $value)
            {
                self::checkSessionActive();
                $_SESSION[$name] = $value;
            }

            /**
             * Get the value of a session variable.
             *
             * @param string $name Name of the session variable.
             * @param mixed $default Default value to return if the session variable is not set.
             * @return mixed Value of the session variable or the default value if not set.
             */
            public static function get($name, $default = null)
            {
                self::checkSessionActive();
                return $_SESSION[$name] ?? $default;
            }

            /**
             * Unset a specific session variable or multiple session variables.
             *
             * @param mixed $name Name(s) of the session variable(s) to unset. Can be a string or an array of strings.
             */
            public static function forget($name)
            {
                self::checkSessionActive();

                if (is_array($name)) {
                    foreach ($name as $item) {
                        unset($_SESSION[$item]);
                    }
                } elseif (is_string($name)) {
                    unset($_SESSION[$name]);
                } else {
                    throw new \InvalidArgumentException('The parameter should be a string or an array of strings.');
                }
            }

            /**
             * Destroy session variables if a session is active.
             */
            public static function destroy()
            {
                self::checkSessionActive();
                session_unset();
                session_destroy();
            }

            /**
             * Check if the session is active.
             *
             * @return bool
             */
            public static function isActive()
            {
                return session_status() === PHP_SESSION_ACTIVE;
            }

            /**
             * Get all session variables.
             *
             * @return array Associative array of all session variables.
             */
            public static function all()
            {
                self::checkSessionActive();
                return $_SESSION;
            }

            /**
             * Check if session variables exist.
             *
             * @param string|array $names The name or names of the session variables.
             * @return bool True if all session variables exist, false otherwise.
             */
            public static function has(string|array $names)
            {
                self::checkSessionActive();

                if (is_array($names)) {
                    foreach ($names as $name) {
                        if (!isset($_SESSION[$name])) {
                            return false;
                        }
                    }
                    return true;
                }

                return isset($_SESSION[$names]);
            }

            /**
             * Check if the session is active.
             *
             * @throws RuntimeException If the session is not active.
             */
            private static function checkSessionActive()
            {
                if (!self::isActive()) {
                    throw new RuntimeException('Session is not active.');
                }
            }
        };
    }
}

// CSRF token helper
if (!function_exists('csrf_token')) {
    function csrf_token(string $name = '_token')
    {
        $token = session()->get($name);

        return $token;
    }
}

// CSRF field helper
if (!function_exists('csrf_field')) {
    function csrf_field(string $name)
    {
        $token = htmlspecialchars(csrf_token($name), ENT_QUOTES, 'UTF-8');
        return '<input type="hidden" name="_token" value="' . $token . '">';
    }
}
