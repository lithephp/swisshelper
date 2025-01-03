<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class SwissHelperTest extends TestCase
{
    public function testNowFunction()
    {
        // Test default behavior
        $this->assertInstanceOf(\DateTime::class, now());
        
        // Test formatted date
        $format = 'Y-m-d';
        $this->assertEquals(date($format), now($format));
    }

    public function testStrFunction()
    {
        // Test slug
        $this->assertEquals('hello-world', str('Hello World')->slug());
        $this->assertEquals('ola-mundo', str('Olá Mundo')->slug());
        
        // Test removeAccents
        $this->assertEquals('arroz', str('arrôz')->removeAccents());
        
        // Test onlyNumbers
        $this->assertEquals('123456', str('abc123def456')->onlyNumbers());
        $this->assertEquals('11987654321', str('(11) 98765-4321')->onlyNumbers());
        
        // Test mask
        $this->assertEquals('123.456.789-10', str('12345678910')->mask('###.###.###-##'));
        $this->assertEquals('(11) 98765-4321', str('11987654321')->mask('(##) #####-####'));
    }

    public function testArrFunction()
    {
        $array = [
            'user' => [
                'name' => 'John',
                'email' => 'john@example.com',
                'profile' => [
                    'age' => 30,
                    'city' => 'New York'
                ]
            ],
            'settings' => [
                'theme' => 'dark'
            ]
        ];

        // Test get with dot notation
        $this->assertEquals('John', arr($array)->get('user.name'));
        $this->assertEquals(30, arr($array)->get('user.profile.age'));
        $this->assertEquals('dark', arr($array)->get('settings.theme'));
        
        // Test get with default value
        $this->assertEquals('default', arr($array)->get('invalid.key', 'default'));
        
        // Test only
        $only = arr(['name' => 'John', 'age' => 30, 'city' => 'NY'])->only(['name', 'age']);
        $this->assertEquals(['name' => 'John', 'age' => 30], $only);
        
        // Test except
        $except = arr(['name' => 'John', 'age' => 30, 'city' => 'NY'])->except(['city']);
        $this->assertEquals(['name' => 'John', 'age' => 30], $except);
    }

    public function testMoneyFunction()
    {
        // Test USD formatting
        $this->assertEquals('$1,234.56', money(1234.56, 'USD'));
        $this->assertEquals('$1,234.00', money(1234, 'USD'));
        
        // Test BRL formatting
        $this->assertEquals('R$ 1.234,56', money(1234.56, 'BRL'));
        
        // Test EUR formatting
        $this->assertEquals('1.234,56€', money(1234.56, 'EUR'));
        
        // Test custom options
        $this->assertEquals('1,234.56', money(1234.56, 'USD', ['symbol' => false]));
        $this->assertEquals('$1,235', money(1234.56, 'USD', ['decimals' => 0]));
    }

    public function testValidateFunction()
    {
        // Test email validation
        $this->assertTrue(validate('test@example.com')->email());
        $this->assertFalse(validate('invalid-email')->email());
        
        // Test URL validation
        $this->assertTrue(validate('https://example.com')->url());
        $this->assertFalse(validate('invalid-url')->url());
        
        // Test IP validation
        $this->assertTrue(validate('192.168.1.1')->ip());
        $this->assertFalse(validate('256.256.256.256')->ip());
        
        // Test date validation
        $this->assertTrue(validate('2024-01-03')->date());
        $this->assertFalse(validate('2024-13-45')->date());
        
        // Test name validation
        $this->assertTrue(validate('John Doe')->name());
        $this->assertFalse(validate('John123')->name());
        
        // Test credit card validation
        $this->assertTrue(validate('4532015112830366')->creditCard()); // Valid test number
        $this->assertFalse(validate('1234567890123456')->creditCard());
        
        // Test password validation
        $this->assertTrue(validate('Abc123!@#')->password());
        $this->assertFalse(validate('weak')->password());
        
        // Test age validation
        $this->assertTrue(validate('1990-01-01')->age(18, 65));
        $this->assertFalse(validate('2020-01-01')->age(18));
    }

    public function testRandomFunction()
    {
        // Test length
        $this->assertEquals(16, strlen(random()));
        $this->assertEquals(8, strlen(random(8)));
        
        // Test alnum type
        $random = random(10, 'alnum');
        $this->assertTrue((bool) preg_match('/^[0-9a-zA-Z]+$/', $random));
        
        // Test alpha type
        $random = random(10, 'alpha');
        $this->assertTrue((bool) preg_match('/^[a-zA-Z]+$/', $random));
        
        // Test numeric type
        $random = random(10, 'numeric');
        $this->assertTrue((bool) preg_match('/^[0-9]+$/', $random));
        
        // Test nozero type
        $random = random(10, 'nozero');
        $this->assertTrue((bool) preg_match('/^[1-9]+$/', $random));
    }

    public function testUrlFunction()
    {
        // Simulate server variables
        $_SERVER['HTTPS'] = 'on';
        $_SERVER['HTTP_HOST'] = 'example.com';
        $_SERVER['REQUEST_URI'] = '/test/page';
        $_SERVER['HTTP_REFERER'] = 'https://example.com/previous';
        
        // Test current URL
        $this->assertEquals('https://example.com/test/page', url()->current());
        
        // Test base URL
        $this->assertEquals('https://example.com', url()->base());
        
        // Test URL generation
        $this->assertEquals('https://example.com/products', url()->to('products'));
        
        // Test previous URL
        $this->assertEquals('https://example.com/previous', url()->previous());
    }
}