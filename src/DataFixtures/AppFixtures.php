<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    /**
     * @var UserPasswordHasherInterface
     */
    private $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {

        $user = new User();

        $hash = $this->passwordHasher->hashPassword($user, "Helix2014");

        $user->setEmail('xribant@gmail.com')
            ->setUsername('xribant@gmail.com')
            ->setFirstName('Xavier')
            ->setLastName('Ribant')
            ->setPassword($hash)
            ->setUid(uniqid())
            ->setRoles(['ROLE_ADMIN'])
        ;

        $manager->persist($user);

        $manager->flush();
    }
}