<?php

namespace App\DataFixtures;

use App\Entity\Flat;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class FlatFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $flatsNumber = rand(10, 20);
        $availableDiscounts = [null, 5, 10, 15];
        $alphabeth = implode('', range('A', 'Z'));

        while ($flatsNumber > 0) {
            $flat = new Flat(
                substr(str_shuffle(str_repeat($alphabeth, 10)),1, 10),
                rand(4, 8),
                rand(10000, 10000)/100,
                $availableDiscounts[mt_rand(0, 3)]
            );
            $manager->persist($flat);
            $flatsNumber--;
        }

        $manager->flush();
    }
}
