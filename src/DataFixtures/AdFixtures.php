<?php

namespace App\DataFixtures;

use App\Entity\Ad;
use Cocur\Slugify\Slugify;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AdFixtures extends Fixture {
  public function load(ObjectManager $manager): void {
    $faker = \Faker\Factory::create();
    $slugify = new Slugify();

    for ($i = 0; $i < 10; $i++) {
      $title = str_replace('.', '', $faker->sentence(3));

      $ad = new Ad();
      $ad
        ->setTitle($title)
        ->setSlug($slugify->slugify($title))
        ->setPrice(mt_rand(300, 1400))
        ->setIntroduction($faker->sentence(7))
        ->setContent($faker->paragraph(3))
        ->setcoverImage($faker->imageUrl(400, 400))
        ->setRooms(mt_rand(2, 5));

      $manager->persist($ad);
    }

    $manager->flush();
  }
}
