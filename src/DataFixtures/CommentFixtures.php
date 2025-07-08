<?php

namespace App\DataFixtures;

use App\Entity\Comment;
use App\Entity\User;
use App\Entity\Wish;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class CommentFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = \Faker\Factory::create('en_EN');
        $comment = new Comment();
        $user = $this->getReference('user_'.mt_rand(1, 10), User::class);
        $wish = $this->getReference('wish_'.mt_rand(1, 10), Wish::class);
        $comment->setUser($user);
        $comment->setWish($wish);
        $comment->setContent($faker->realText(200));

        $manager->persist($comment);
//
//        for ($i = 1; $i <= 20; $i++) {
//            $comment = new Comment();
//            $user = $this->getReference('user_'.mt_rand(1, 10), User::class);
//            $wish = $this->getReference('wish_'.mt_rand(1, 10), Wish::class);
//
//            $comment->setUser($user);
//            $comment->setWish($wish);
//            $comment->setContent($faker->realText(200));
//
//            $manager->persist($comment);
//        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            CategoryFixtures::class,
            UserFixtures::class,
            WishFixtures::class,
        ];
    }

}
