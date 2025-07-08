<?php

namespace App\DataFixtures;

use App\Controller\WishController;
use App\Entity\Category;
use App\Entity\User;
use App\Entity\Wish;
use App\Repository\UserRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use function Symfony\Component\Clock\now;

class WishFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {

        $user = $this->getReference('user_'.mt_rand(1, 2), User::class);
        $wish = new Wish();
        $faker = \faker\Factory::create(locale: 'en_EN');
//
//        $wish->setTitle("Make more time for family and friends")
//            ->setDescription("Description")
//            ->setUser($user)
//            ->setIsPublished(true)
//            ->setImageFilename("image-686b9e6d94606.jpg")
//            ->setDateCreated(new \DateTimeImmutable("now"))
//            ->setCategory($this->getReference("category4", Category::class));
//        $manager->persist($wish);
//        $this->addReference('wish_1', $wish);
//
//        $wish = new Wish();
//        $wish->setTitle("Go running every day")
//            ->setDescription("Description")
//            ->setUser($user)
//            ->setIsPublished(true)
//            ->setImageFilename("image-686b9e6d94606.jpg")
//            ->setDateCreated(new \DateTimeImmutable("now"))
//            ->setCategory($this->getReference("category2", Category::class));
//        $manager->persist($wish);
//        $this->addReference('wish_2', $wish);
//
////
        for($i=1; $i<=20; $i++){
            $wish = new Wish();
            $wish->setTitle($faker->randomElement(['Go running every day', "Visit Ireland again", "Go and visit the family in the UK",
                "Live in a house", "Have a vegetable garden", "Take part in a baking competition", "Compete in a triathlon", "Buy a pony"]));
            $wish->setDescription($faker->text());
            $wish->setUser($user);
            $wish->setImageFilename("image-686b9e6d94606.jpg");
            $wish->setIsPublished(true);
            $wish->setDateCreated(new \DateTimeImmutable("now"));
            $wish->setCategory($this->getReference('category'.mt_rand(1,5), Category::class));
            $manager->persist($wish);
            $this->addReference('wish_'. $i, $wish);
        }
        $manager->flush();
    }


    public function getDependencies():array{
        return[
            UserFixtures::class,
            CategoryFixtures::class,
        ];
    }

}