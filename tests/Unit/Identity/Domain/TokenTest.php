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
        $this->assertMatchesRegularExpression('/^[0-9a-f]{64}$/', $token->getValue());
    }

    #[Test]
    public function it_should_always_create_unique_tokens(): void
    {
        $tokens = [];
        for ($i = 0; $i < 100; $i++) {
            $tokens[] = Token::create()->getValue();
        }

        // All tokens should be unique
        $this->assertCount(100, array_unique($tokens), 'Tokens are not unique');
    }

    #[Test]
    #[DataProvider('validTokenProvider')]
    public function it_should_restore_token_from_valid_string(string $value): void
    {
        $token = Token::restore($value);

        $this->assertSame($value, $token->getValue());
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

    #[Test]
    #[DataProvider('invalidTokenProvider')]
    public function it_should_reject_invalid_token_values(string $invalidValue): void
    {
        $this->expectException(InvalidTokenException::class);
        $this->expectExceptionMessage('Token must be 64 characters long');

        Token::restore($invalidValue);
    }

    public static function invalidTokenProvider(): array
    {
        return [
            'empty string' => [''],
            'too short' => ['abc123'],
            'too long' => ['0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef00'],
            'uppercase' => ['0123456789ABCDEF0123456789ABCDEF0123456789ABCDEF0123456789ABCDEF000000000000000']
        ];
    }
}
