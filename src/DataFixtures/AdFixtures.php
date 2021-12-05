<?php

namespace App\DataFixtures;

use App\Entity\Ad;
use App\Entity\Image;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AdFixtures extends Fixture {
  public function load(ObjectManager $manager): void {
    $faker = \Faker\Factory::create();
    $faker->addProvider(new \Bluemmb\Faker\PicsumPhotosProvider($faker));

    for ($i = 1; $i <= 10; $i++) {
      $content = '<p>' . join('<p></p>', $faker->paragraphs(5)) . '</p>';

      $ad = new Ad();
      $ad
        ->setTitle($faker->sentence(3))
        ->setPrice(mt_rand(300, 1400))
        ->setIntroduction($faker->sentence(7))
        ->setContent($content)
        ->setcoverImage($faker->imageUrl(1980, 700, true))
        ->setRooms(mt_rand(2, 5));

      $manager->persist($ad);

      for ($j = 0; $j < mt_rand(3, 5); $j++) {
        $image = new Image();
        $image
          ->setUrl($faker->imageUrl(1980, 700, true))
          ->setCaption($faker->sentence(5))
          ->setAd($ad);

        $manager->persist($image);
      }
    }

    $manager->flush();
  }
}
