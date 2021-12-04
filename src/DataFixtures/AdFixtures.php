<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Ad;

class AdFixtures extends Fixture {
  public function load(ObjectManager $manager): void {
    $faker = \Faker\Factory::create();

    for ($i = 0; $i < 10; $i++) {
      $title = str_replace('.', '', $faker->sentence(3));
      $slug = strtolower(str_replace(' ', '-', $title));

      $ad = new Ad();
      $ad
        ->setTitle($title)
        ->setSlug($slug)
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
