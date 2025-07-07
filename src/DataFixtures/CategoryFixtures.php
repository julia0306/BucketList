<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CategoryFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
         $travelCategory = new Category();
         $travelCategory->setName('Travel & Adventure');
         $manager->persist($travelCategory);
         $this->addReference('travel-category', $travelCategory);

        $sportCategory = new Category();
        $sportCategory->setName('Sport');
        $manager->persist($sportCategory);
        $this->addReference('sport-category', $sportCategory);

        $entertainmentCategory = new Category();
        $entertainmentCategory->setName('Entertainment');
        $manager->persist($entertainmentCategory);
        $this->addReference('entertainment-category', $entertainmentCategory);

        $humanRelationsCategory = new Category();
        $humanRelationsCategory->setName('Human Relations');
        $manager->persist($humanRelationsCategory);
        $this->addReference('human-relations-category', $humanRelationsCategory);

        $othersCategory = new Category();
        $othersCategory->setName('Others');
        $manager->persist($othersCategory);
        $this->addReference('others-category', $othersCategory);

        $manager->flush();
    }
}
