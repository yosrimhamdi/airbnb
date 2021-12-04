<?php

namespace App\DataFixtures;

use App\Entity\Ad;
use App\Entity\Image;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AdFixtures extends Fixture {
  public function load(ObjectManager $manager): void {
    $faker = \Faker\Factory::create();

    for ($i = 0; $i < 10; $i++) {
      $title = $faker->sentence(3);

      $ad = new Ad();
      $ad
        ->setTitle($title)
        ->setPrice(mt_rand(300, 1400))
        ->setIntroduction($faker->sentence(7))
        ->setContent($faker->paragraph(3))
        ->setcoverImage($faker->imageUrl(400, 400))
        ->setRooms(mt_rand(2, 5));

      $manager->persist($ad);

      for ($j = 0; $j < mt_rand(3, 5); $j++) {
        $image = new Image();
        $image
          ->setUrl($faker->imageUrl(1000, 500))
          ->setCaption($faker->sentence(5))
          ->setAd($ad);

        $manager->persist($image);
      }
    }

    $manager->flush();
  }
}
