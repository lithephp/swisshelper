# Lithe SwissHelper

<p align="center">
  <a href="https://packagist.org/packages/lithephp/swisshelper"><img src="https://img.shields.io/packagist/dt/lithephp/swisshelper" alt="Total Downloads"></a>
  <a href="https://packagist.org/packages/lithephp/swisshelper"><img src="https://img.shields.io/packagist/v/lithephp/swisshelper" alt="Latest Stable Version"></a>
  <a href="https://packagist.org/packages/lithephp/swisshelper"><img src="https://img.shields.io/packagist/l/lithephp/swisshelper" alt="License"></a>
</p>

## Introduction

Lithe SwissHelper is a comprehensive PHP utility library designed to simplify common programming tasks. It provides a collection of helper functions for string manipulation, array handling, data validation, and more, all designed with a focus on developer experience and code readability.

## Installation

Install the package via Composer:

```bash
composer require lithephp/swisshelper
```

## Detailed Function Documentation

### DateTime Helper (now)

The `now()` function provides a simple interface for datetime manipulation.

```php
// Get current DateTime object
$datetime = now();

// Get formatted current date
$formatted = now('Y-m-d H:i:s');
$date = now('Y-m-d');
$time = now('H:i:s');
```

### String Helper (str)

The string helper provides various methods for string manipulation.

#### Creating Slugs
```php
$slug = str('Hello World!')->slug();
// Output: "hello-world"

$slug = str('Café com leite')->slug();
// Output: "cafe-com-leite"
```

#### Removing Accents
```php
$text = str('Café à la crème')->removeAccents();
// Output: "Cafe a la creme"
```

#### Extracting Numbers
```php
$numbers = str('Phone: (123) 456-7890')->onlyNumbers();
// Output: "1234567890"

$numbers = str('Order #123-456')->onlyNumbers();
// Output: "123456"
```

#### Applying Masks
```php
// CPF (Brazilian ID)
$masked = str('12345678901')->mask('###.###.###-##');
// Output: "123.456.789-01"

// Phone number
$masked = str('1234567890')->mask('(##) ####-####');
// Output: "(12) 3456-7890"

// Custom mask
$masked = str('ABC123')->mask('???-###');
// Output: "ABC-123"
```

### Array Helper (arr)

The array helper provides methods for array manipulation and access.

#### Accessing Nested Arrays
```php
$array = [
    'user' => [
        'profile' => [
            'name' => 'John Doe',
            'settings' => [
                'theme' => 'dark'
            ]
        ]
    ]
];

// Access with dot notation
$name = arr($array)->get('user.profile.name');
// Output: "John Doe"

// With default value
$color = arr($array)->get('user.profile.color', 'blue');
// Output: "blue"
```

#### Selecting Specific Keys
```php
$array = ['name' => 'John', 'age' => 30, 'email' => 'john@example.com'];

// Get only specific keys
$only = arr($array)->only(['name', 'email']);
// Output: ['name' => 'John', 'email' => 'john@example.com']

// Get everything except specified keys
$except = arr($array)->except(['age']);
// Output: ['name' => 'John', 'email' => 'john@example.com']
```

### Validation Helper (validate)

The validation helper provides methods for validating different types of data.

#### Email Validation
```php
validate('email@example.com')->email(); // true
validate('invalid-email')->email(); // false
```

#### URL Validation
```php
validate('https://example.com')->url(); // true
validate('invalid-url')->url(); // false
```

#### IP Address Validation
```php
validate('192.168.1.1')->ip(); // true
validate('256.256.256.256')->ip(); // false
```

#### Date Validation
```php
validate('2024-01-03')->date(); // true
validate('2024-13-45')->date(); // false

// Custom format
validate('03/01/2024')->date('d/m/Y'); // true
```

#### Name Validation
```php
validate('John Doe')->name(); // true
validate('John123')->name(); // false
```

#### Credit Card Validation
```php
validate('4532015112830366')->creditCard(); // true
validate('1234567890123456')->creditCard(); // false
```

#### Password Validation
```php
// Validate password strength
validate('P@ssw0rd')->password(); // true
validate('weak')->password(); // false

// Custom rules
validate('1234Abcd!')->password(8, false, true, true, true); // true
```

#### Age Validation
```php
// Check if age is within a range
validate('2000-01-01')->age(18, 60); // true
validate('2010-01-01')->age(18); // false
```

#### Value Length Validation
```php
// Check if string or numeric value is between limits
validate('Hello')->between(3, 10); // true
validate(100)->between(50, 150); // true
```

#### String Search Validation
```php
// Check if string contains a specific substring
validate('Hello World')->contains('World'); // true

// Check if string starts or ends with specific text
validate('example.com')->startsWith('example'); // true
validate('example.com')->endsWith('.com'); // true
```

### Random String Generator (random)

The `random()` function generates random strings of various types.

```php
// Generate a random alphanumeric string of 16 characters
$alnum = random(16);

// Generate a random alphabetic string of 10 characters
$alpha = random(10, 'alpha');

// Generate a random numeric string of 8 characters
$numeric = random(8, 'numeric');

// Generate a random non-zero numeric string of 6 characters
$nozero = random(6, 'nozero');
```

### URL Helper (url)

The URL helper provides methods for handling URLs.

```php
// Get the current URL
$current = url()->current();

// Get the base URL
$base = url()->base();

// Generate a URL to a specific path
$path = url()->to('path/to/resource');

// Get the previous URL (referer)
$previous = url()->previous();
```

### Session Helper (session)

The session helper provides methods for managing session data.

```php
// Set a session variable
session()->put('key', 'value');

// Get a session variable
$value = session()->get('key');

// Check if a session variable exists
$exists = session()->has('key');

// Get all session variables
$all = session()->all();

// Unset a session variable
session()->forget('key');

// Destroy the session
session()->destroy();
```

### CSRF Helpers

#### CSRF Token
```php
$token = csrf_token();
```

#### CSRF Hidden Field
```php
$field = csrf_field();
// Output: <input type="hidden" name="_token" value="your_csrf_token_here">
```

## Contributing

Contributions are welcome and will be fully credited. Please follow these steps:

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add some amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.

## Support

Create an issue in the GitHub repository.
