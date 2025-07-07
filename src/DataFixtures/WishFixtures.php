<?php

namespace App\DataFixtures;

use App\Controller\WishController;
use App\Entity\Category;
use App\Entity\Wish;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use function Symfony\Component\Clock\now;

class WishFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $wish = new Wish();
        $wish->setTitle("Make more time for family and friends")
            ->setDescription("Description")
            ->setAuthor("Julia")
            ->setIsPublished(true)
            ->setDateCreated(new \DateTimeImmutable("now"))
            ->setCategory($this->getReference("human-relations-category", Category::class));
        $manager->persist($wish);

        $wish = new Wish();
        $wish->setTitle("Go running every day")
            ->setDescription("Description")
            ->setAuthor("Julia")
            ->setIsPublished(true)
            ->setDateCreated(new \DateTimeImmutable("now"))
            ->setCategory($this->getReference("sport-category", Category::class));
        $manager->persist($wish);

        $manager->flush();
    }

    public function getDependencies():array{
        return[
            CategoryFixtures::class,
        ];
    }

}