<?php

namespace App\Tests\Entity;

use App\Entity\User;
use DateTimeImmutable;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class UserTest extends KernelTestCase
{
    public function getEntity(): User
    {
        $user = (new User())
            ->setEmail('pierre.coelho@ac-nice.fr')
            ->setFirstname('Test')
            ->setLastname('Test')
            ->setUsername('Test')
            ->setPassword('Azerty7@')
            ->setRegisteredAt(new DateTimeImmutable())
            ->setIsVerified(true);

        return $user;
    }

    public function assertHasErrors(User $user, int $nbErrors = 0)
    {
        self::bootKernel();
        $error = self::getContainer()->get('validator')->validate($user);

        $this->assertCount($nbErrors, $error);
    }

    public function testCreateUser()
    {
        $user = $this->getEntity();

        $this->assertInstanceOf(User::class, $user);
        $this->assertSame('pierre.coelho@ac-nice.fr', $user->getEmail());
        $this->assertSame('Test', $user->getFirstname());
        $this->assertSame('Test', $user->getLastname());
        $this->assertSame('Test', $user->getUsername());
        $this->assertSame('Azerty7@', $user->getPassword());
        $this->assertInstanceOf(\DateTimeImmutable::class, $user->getRegisteredAt());
        $this->assertSame(true, $user->isVerified());
    }

    public function testUpdateDocument()
    {
        $user = $this->getEntity();

        $user->setEmail('pierre.coelho@ac-besancon.fr')
            ->setFirstname('Pierre')
            ->setLastname('Coelho')
            ->setUsername('pierre.coelho25')
            ->setPassword('Testify06*')
            ->setRegisteredAt(new DateTimeImmutable())
            ->setIsVerified(false);

        $this->assertSame('pierre.coelho@ac-besancon.fr', $user->getEmail());
        $this->assertSame('Pierre', $user->getFirstname());
        $this->assertSame('Coelho', $user->getLastname());
        $this->assertSame('pierre.coelho25', $user->getUsername());
        $this->assertSame('Testify06*', $user->getPassword());
        $this->assertInstanceOf(\DateTimeImmutable::class, $user->getRegisteredAt());
        $this->assertSame(false, $user->isVerified());
    }

    public function testValidUser()
    {
        $this->assertHasErrors($this->getEntity(), 0);
        $this->assertHasErrors($this->getEntity()->setFirstname('Gérard'), 0);
        $this->assertHasErrors($this->getEntity()->setLastname('Lenôtre'), 0);
    }

    public function testInvalidEmail()
    {
        $this->assertHasErrors($this->getEntity()->setEmail('test'), 1);
        $this->assertHasErrors($this->getEntity()->setEmail('test@test'), 1);
        $this->assertHasErrors($this->getEntity()->setEmail('test.fr'), 1);
        $this->assertHasErrors($this->getEntity()->setEmail('test@test.fr'), 1);
    }

    public function testInvalidBlankEmail()
    {
        $this->assertHasErrors($this->getEntity()->setEmail(''), 1);
    }

    public function testInvalidFirstname()
    {
        $this->assertHasErrors($this->getEntity()->setFirstname('123'), 1);
        $this->assertHasErrors($this->getEntity()->setFirstname('Test1'), 1);
    }

    public function testInvalidBlankFirstname()
    {
        $this->assertHasErrors($this->getEntity()->setFirstname(''), 1);
    }

    public function testInvalidLastName()
    {
        $this->assertHasErrors($this->getEntity()->setLastname('123'), 1);
        $this->assertHasErrors($this->getEntity()->setLastname('Test1'), 1);
    }

    public function testInvalidBlankLastName()
    {
        $this->assertHasErrors($this->getEntity()->setLastname(''), 1);
    }

    public function testInvalidPassword()
    {
        $this->assertHasErrors($this->getEntity()->setLastname('12345'), 1);
        $this->assertHasErrors($this->getEntity()->setLastname('azerty7*'), 1);
        $this->assertHasErrors($this->getEntity()->setLastname('AZERTY7*'), 1);
        $this->assertHasErrors($this->getEntity()->setLastname('Azertyt*'), 1);
        $this->assertHasErrors($this->getEntity()->setLastname('Azertyt7'), 1);
        $this->assertHasErrors($this->getEntity()->setLastname('Azer ty7*'), 1);
    }

    public function testInvalidBlankPassword()
    {
        $this->assertHasErrors($this->getEntity()->setPassword(''), 1);
    }
}
