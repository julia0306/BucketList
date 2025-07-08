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
         $this->addReference('category1', $travelCategory);

        $sportCategory = new Category();
        $sportCategory->setName('Sport');
        $manager->persist($sportCategory);
        $this->addReference('category2', $sportCategory);

        $entertainmentCategory = new Category();
        $entertainmentCategory->setName('Entertainment');
        $manager->persist($entertainmentCategory);
        $this->addReference('category3', $entertainmentCategory);

        $humanRelationsCategory = new Category();
        $humanRelationsCategory->setName('Human Relations');
        $manager->persist($humanRelationsCategory);
        $this->addReference('category4', $humanRelationsCategory);

        $othersCategory = new Category();
        $othersCategory->setName('Others');
        $manager->persist($othersCategory);
        $this->addReference('category5', $othersCategory);



        $manager->flush();


    }
}
