<?php

namespace Tests\Unit\Identity\Domain;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Przper\Tribe\Identity\Domain\Email;
use Przper\Tribe\Identity\Domain\InvalidEmailException;

class EmailTest extends TestCase
{
    #[Test]
    #[DataProvider('emailFormatProvider')]
    public function it_checks_email_format(string $email, bool $expectedValid): void
    {
        if (!$expectedValid) {
            $this->expectException(InvalidEmailException::class);
        }

        $emailObject = Email::fromString($email);

        if ($expectedValid) {
            $this->assertEquals($email, (string) $emailObject);
        }
    }

    public static function emailFormatProvider(): array
    {
        return [
            'valid email' => ['test@example.com', true],
            'valid email with subdomain' => ['test@sub.example.com', true],
            'valid email with plus' => ['test+label@example.com', true],
            'valid email with numbers' => ['test123@example.com', true],
            'invalid email without @' => ['testexample.com', false],
            'invalid email without domain' => ['test@', false],
            'invalid email with spaces' => ['test @example.com', false],
            'invalid email with multiple @' => ['test@@example.com', false],
            'empty email' => ['', false],
        ];
    }
}