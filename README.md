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

### Money Helper (money)

The money helper formats monetary values according to different currency standards.

```php
// Basic formatting
echo money(1234.56); // "$1,234.56"
echo money(1234.56, 'EUR'); // "1.234,56€"
echo money(1234.56, 'BRL'); // "R$ 1.234,56"

// Custom formatting options
echo money(1234.56, 'USD', [
    'decimals' => 0,
    'symbol' => false
]); // "1,235"

// Supported currencies
// USD - United States Dollar
// EUR - Euro
// GBP - British Pound
// JPY - Japanese Yen
// CNY - Chinese Yuan
// BRL - Brazilian Real
// INR - Indian Rupee
// RUB - Russian Ruble
// AUD - Australian Dollar
// CAD - Canadian Dollar
// AOA - Angolan Kwanza
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
validate('Abc123!@#')->password(
    minLength: 8,
    requireSpecial: true,
    requireNumber: true,
    requireUpper: true,
    requireLower: true
); // true

// Customize requirements
validate('simple123')->password(
    minLength: 6,
    requireSpecial: false,
    requireUpper: false
); // true
```

#### Age Validation
```php
validate('1990-01-01')->age(min: 18, max: 65); // true
validate('2020-01-01')->age(min: 18); // false
```

#### Other Validations
```php
// Between validation
validate('test')->between(2, 5); // true (string length)
validate(10)->between(1, 100); // true (numeric value)

// Contains validation
validate('Hello World')->contains('World'); // true

// Starts/Ends With validation
validate('Hello World')->startsWith('Hello'); // true
validate('Hello World')->endsWith('World'); // true

// Integer validation
validate('123')->int(); // true
validate('12.3')->int(); // false
```

### Random String Generator (random)

The random helper generates random strings with different characteristics.

```php
// Default (16 characters, alphanumeric)
echo random(); // "a1B2c3D4e5F6g7H8"

// Specific length
echo random(8); // "Xa4Kp9Yz"

// Only letters
echo random(8, 'alpha'); // "AbCdEfGh"

// Only numbers
echo random(6, 'numeric'); // "123456"

// Numbers without zero
echo random(4, 'nozero'); // "1234"
```

### URL Helper (url)

The URL helper provides methods for URL manipulation and generation.

```php
// Get current URL
echo url()->current();
// Output: "https://example.com/current/path"

// Get base URL
echo url()->base();
// Output: "https://example.com"

// Generate URL
echo url()->to('products/1');
// Output: "https://example.com/products/1"

// Get previous URL
echo url()->previous();
// Output: Returns the previous URL or base URL if not available
```

## Testing

The package includes a comprehensive test suite. To run the tests:

```bash
composer test
```

## Contributing

Contributions are welcome and will be fully credited. Please follow these steps:

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add some amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

### Coding Standards

This package follows PSR-12 coding standards. Ensure your code adheres to these standards:

```bash
composer check-style
composer fix-style
```

## Security

If you discover any security-related issues, please email security@yourdomain.com instead of using the issue tracker.

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.

## Support

Create an issue in the GitHub repository.