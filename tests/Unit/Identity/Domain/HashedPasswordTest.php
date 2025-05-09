<?php

namespace Tests\Unit\Identity\Domain;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Przper\Tribe\Identity\Domain\HashedPassword;
use Przper\Tribe\Identity\Domain\InvalidHashedPassword;

class HashedPasswordTest extends TestCase
{
    #[Test]
    #[DataProvider('validHashedPasswordProvider')]
    public function it_should_accept_valid_hash_formats(string $validHash): void
    {
        $this->assertInstanceOf(HashedPassword::class, HashedPassword::fromString($validHash));
    }

    /**
     * @return array<string, array{string}>
     */
    public static function validHashedPasswordProvider(): array
    {
        return [
            'bcrypt_60_chars' => [
                '$2y$10$abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ01',
            ],
            'argon2id_hash' => [
                '$argon2id$v=19$m=65536,t=4,p=1$abcdefghijklmnopqrstuvwxyz$ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefgh',
            ],
        ];
    }

    #[Test]
    #[DataProvider('invalidHashedPasswordProvider')]
    public function it_should_throw_exception_for_invalid_hashed_password(string $invalidHash, string $expectedExceptionMessage): void
    {
        $this->expectException(InvalidHashedPassword::class);
        $this->expectExceptionMessage($expectedExceptionMessage);

        HashedPassword::fromString($invalidHash);
    }

    /**
     * @return array<string, array{string, string}>
     */
    public static function invalidHashedPasswordProvider(): array
    {
        return [
            'empty_string' => [
                '',
                'Hashed password is too short',
            ],
            'short_hash' => [
                '$2y$10$tooShort',
                'Hashed password is too short',
            ],
            'almost_valid_hash' => [
                '$2y$10$abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWX', // 59 chars
                'Hashed password is too short',
            ],
        ];
    }

    #[Test]
    public function is_equal_to_another_HashedPassword(): void
    {
        $sut = HashedPassword::fromString('$2y$10$abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ01');

        $this->assertTrue($sut->is(HashedPassword::fromString('$2y$10$abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ01')));
        $this->assertFalse($sut->is(HashedPassword::fromString('$2y$10$abcdefghijklmnopqrstuvwxyzABCDE1111111111111111111111')));

    }
}
