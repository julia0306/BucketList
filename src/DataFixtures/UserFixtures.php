<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{

    public function __construct(private UserPasswordHasherInterface $PasswordHasher){

    }
    public function load(ObjectManager $manager): void
    {
        $faker = \Faker\Factory::create('en_EN');

        $user = new User();
        $user->setUsername('user')
            ->setRoles(['ROLE_USER'])
            ->setEmail("user@user.com")
            ->setPassword($this->PasswordHasher->hashPassword($user, 'user123'));
        $manager->persist($user);
        $this->addReference('user_1', $user);

        $user = new User();
        $user->setUsername('admin')
            ->setRoles(['ROLE_ADMIN'])
            ->setEmail("admin@admin.com")
            ->setPassword($this->PasswordHasher->hashPassword($user, 'admin123'));
        $manager->persist($user);
        $this->addReference('user_2', $user);

        for($i=3; $i<=10; $i++){
            $user = new User();
            $user->setUsername('user_'.$i)
                ->setEmail($faker->email)
                ->setRoles(['ROLE_USER'])
                ->setPassword($this->PasswordHasher->hashPassword($user, 'user123'));
            $manager->persist($user);
            $this->addReference('user_'.$i, $user);
        }
        $manager->flush();
    }
}
