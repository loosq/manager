<?php


namespace App\Tests\Unit\Model\User\Entity\User\SignUp\ResetTest;

use App\Model\User\Entity\User\Email;
use App\Model\User\Entity\User\User;
use Monolog\Test\TestCase;
use App\Model\User\Entity\User\Id;
use App\Model\User\Entity\User\ResetToken;

class ResetTest extends TestCase
{
    public function testSuccess(): void
    {
        $now = new \DateTimeImmutable();
        $token = new ResetToken('token', $now->modify('+1 day'));
        $user = $this->buildSignedUpUserByEmail();

        $user->requestPasswordReset($token, $now);

        self::assertNotNull($user->getResetToken());

        $user->passwordReset($now, $hash = 'hash');

        self::assertNotNull($user->getResetToken());
        self::assertEquals($hash, $user->getPasswordHash());
    }

    public function testExpiredToken(): void
    {
        $now = new \DateTimeImmutable();
        $token = new ResetToken('token', $now);

        $user = $this->buildSignedUpUserByEmail();

        $user->requestPasswordReset($token, $now);

        $this->expectExceptionMessage('Reset token is expired.');
        $user->passwordReset($now->modify('+1 day'), "hash");
    }


    public function testNotRequested(): void
    {
        $user = $this->buildSignedUpUserByEmail();
        $now = new \DateTimeImmutable();
        $this->expectExceptionMessage('Resetting is not requested.');
        $user->passwordReset($now, "hash");
    }

    public function buildSignedUpUserByEmail(): User
    {
        $user = new User(
            Id::next(),
            new \DateTimeImmutable()
        );

        $user->signUpByEmail(
            new Email('email@test.test'),
            'hash',
            'token'
        );

        return $user;
    }

    public function buildSignedUpUserByNetwork(): User
    {
        $user = new User(
            Id::next(),
            new \DateTimeImmutable()
        );

        $user->signUpByNetwork(
            'vk',
            '000001'
        );

        return $user;
    }
}
