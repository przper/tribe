<?php

namespace Tests\Integration\Identity\Intrastructure\Symfony;

use PHPUnit\Framework\Attributes\Test;
use Przper\Tribe\Identity\Domain\HashedPassword;
use Przper\Tribe\Identity\Domain\Password;
use Przper\Tribe\Identity\Infrastructure\Symfony\PasswordHashingService;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class PasswordHashingServiceTest extends KernelTestCase
{
    private PasswordHashingService $sut;

    protected function setUp(): void
    {
        self::bootKernel();
        $this->sut = self::getContainer()->get(PasswordHashingService::class);
    }

    #[Test]
    public function it_should_hash_a_password(): void
    {
        $password = Password::fromString('StrongPassword123!');

        $hashedPassword = $this->sut->hash($password);

        $this->assertInstanceOf(HashedPassword::class, $hashedPassword);
        $this->assertNotEquals($password->getValue(), $hashedPassword->getValue());
        $this->assertGreaterThanOrEqual(60, strlen($hashedPassword->getValue()));
    }

    #[Test]
    public function it_should_verify_a_matching_password(): void
    {
        $password = Password::fromString('StrongPassword123!');
        $hashedPassword = $this->sut->hash($password);

        $result = $this->sut->isMatching($password, $hashedPassword);

        $this->assertTrue($result);
    }

    #[Test]
    public function it_should_not_verify_a_non_matching_password(): void
    {
        $password = Password::fromString('StrongPassword123!');
        $hashedPassword = $this->sut->hash($password);
        $wrongPassword = Password::fromString('WrongPassword456!');

        $result = $this->sut->isMatching($wrongPassword, $hashedPassword);

        $this->assertFalse($result);
    }
}