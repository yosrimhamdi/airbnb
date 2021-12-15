<?php

namespace App\DataFixtures;

use App\Entity\Ad;
use App\Entity\User;
use App\Entity\Image;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AppFixtures extends Fixture {
  public function load(ObjectManager $manager): void {
    $faker = \Faker\Factory::create();
    $faker->addProvider(new \Bluemmb\Faker\PicsumPhotosProvider($faker));

    for ($i = 0; $i < 5; $i++) {
      $firstName = $faker->firstNameMale();
      $lastName = $faker->lastName();
      $slug = strtolower($firstName . $lastName);
      $description = "<p>" . join("<p></p>", $faker->paragraphs()) . "</p>";

      $user = new User();
      $user
        ->setFirstName($firstName)
        ->setLastName($lastName)
        ->setEmail($faker->email())
        ->setIntroduction($faker->paragraph())
        ->setDescription($description)
        ->setSlug($slug)
        ->setHash("1234");

      $manager->persist($user);

      for ($j = 1; $j <= mt_rand(3, 15); $j++) {
        $content = "<p>" . join("<p></p>", $faker->paragraphs(5)) . "</p>";

        $ad = new Ad();
        $ad
          ->setTitle($faker->sentence(3))
          ->setPrice(mt_rand(300, 1400))
          ->setIntroduction($faker->sentence(7))
          ->setContent($content)
          ->setcoverImage($faker->imageUrl(1980, 700, true))
          ->setRooms(mt_rand(2, 5))
          ->setUser($user);

        $manager->persist($ad);

        for ($k = 0; $k < mt_rand(3, 5); $k++) {
          $image = new Image();
          $image
            ->setUrl($faker->imageUrl(1980, 700, true))
            ->setCaption($faker->sentence(5))
            ->setAd($ad);

          $manager->persist($image);
        }
      }
    }

    $manager->flush();
  }
}
