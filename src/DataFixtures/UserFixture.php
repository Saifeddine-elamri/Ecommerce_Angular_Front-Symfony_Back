<?php

namespace App\DataFixtures;
use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UserFixture extends Fixture
{

    
    private $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }
    public function load(ObjectManager $manager): void
    {
        $user1 = new User();
        $user1->setEmail('user3@example.com');
        $user1->setRoles(['ROLE_USER']);
        $hashedPassword1 = $this->passwordHasher->hashPassword($user1, 'password345');
        $user1->setPassword($hashedPassword1);

        $user2 = new User();
        $user2->setEmail('user4@example.com');
        $user2->setRoles(['ROLE_ADMIN']);
        $hashedPassword2 = $this->passwordHasher->hashPassword($user2, 'password456');
        $user2->setPassword($hashedPassword2);
        $manager->persist($user1);
        $manager->persist($user2);
        $manager->flush();
    }    


}
