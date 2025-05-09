<?php

namespace Tests\Unit\Identity\Domain;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\DataProvider;
use Przper\Tribe\Identity\Domain\Token;
use Przper\Tribe\Identity\Domain\InvalidTokenException;

class TokenTest extends TestCase
{
    #[Test]
    public function it_should_create_token_with_expected_format(): void
    {
        $token = Token::create();

        // Token value should be a 64-character hexadecimal string (32 bytes as hex)
        $this->assertMatchesRegularExpression('/^[0-9a-f]{64}$/', (string)$token);
    }

    #[Test]
    public function it_should_always_create_unique_tokens(): void
    {
        $tokens = [];
        for ($i = 0; $i < 100; $i++) {
            $tokens[] = (string)Token::create();
        }

        // All tokens should be unique
        $this->assertCount(100, array_unique($tokens), 'Tokens are not unique');
    }

    #[Test]
    public function it_should_restore_token_from_valid_string(): void
    {
        $originalValue = '0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef';
        $token = Token::restore($originalValue);

        $this->assertSame($originalValue, (string)$token);
    }

    #[Test]
    #[DataProvider('invalidTokenProvider')]
    public function it_should_reject_invalid_token_values(string $invalidValue): void
    {
        $this->expectException(InvalidTokenException::class);
        $this->expectExceptionMessage('Token must be 64 characters long');

        Token::restore($invalidValue);
    }

    #[Test]
    public function it_should_reject_token_with_invalid_characters(): void
    {
        $this->expectException(InvalidTokenException::class);

        Token::restore('0123456789abcdef0123456789abcdeg0123456789abcdef0123456789abcdef');
    }

    #[Test]
    public function it_should_reject_token_with_uppercase_characters(): void
    {
        $this->expectException(InvalidTokenException::class);

        Token::restore('0123456789ABCDEF0123456789ABCDEF0123456789ABCDEF0123456789ABCDEF');
    }

    #[Test]
    #[DataProvider('validTokenProvider')]
    public function it_should_accept_valid_token_values(string $validValue): void
    {
        $token = Token::restore($validValue);
        $this->assertSame($validValue, (string)$token);
    }

    #[Test]
    #[DataProvider('tokenValueProvider')]
    public function it_should_convert_to_string_correctly(string $value): void
    {
        $token = Token::restore($value);

        $this->assertSame($value, (string)$token);
        $this->assertSame($value, $token->__toString());
    }

    public static function tokenValueProvider(): array
    {
        return [
            'standard hex value' => ['0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef'],
            'all zeros' => ['0000000000000000000000000000000000000000000000000000000000000000'],
            'all fs' => ['ffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffff'],
            'mixed value' => ['a1b2c3d4e5f67890a1b2c3d4e5f67890a1b2c3d4e5f67890a1b2c3d4e5f67890'],
        ];
    }

    public static function invalidTokenProvider(): array
    {
        return [
            'empty string' => [''],
            'too short' => ['abc123'],
            'too long' => ['0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef00'],
        ];
    }

    public static function validTokenProvider(): array
    {
        return [
            'standard token' => ['0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef'],
            'all digits' => ['0123456789012345678901234567890123456789012345678901234567890123'],
            'all letters' => ['abcdefabcdefabcdefabcdefabcdefabcdefabcdefabcdefabcdefabcdefabcd'],
            'alternating' => ['a0a0a0a0a0a0a0a0a0a0a0a0a0a0a0a0a0a0a0a0a0a0a0a0a0a0a0a0a0a0a0a0'],
        ];
    }
}