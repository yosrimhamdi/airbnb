<?php
namespace App\DataFixtures;
use App\Entity\Ad;
use App\Entity\User;
use App\Entity\Image;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture {
  private $encoder;

  public function __construct(UserPasswordEncoderInterface $encoder) {
    $this->encoder = $encoder;
  }

  public function load(ObjectManager $manager): void {
    $faker = \Faker\Factory::create();
    $faker->addProvider(new \Bluemmb\Faker\PicsumPhotosProvider($faker));

    for ($i = 0; $i < 5; $i++) {
      $description = "<p>" . join("<p></p>", $faker->paragraphs()) . "</p>";

      $photo =
        "https://randomuser.me/api/portraits/men/" . mt_rand(1, 99) . ".jpg";
      $user = new User();
      $user
        ->setFirstName($faker->firstNameMale())
        ->setLastName($faker->lastName())
        ->setEmail($faker->email())
        ->setIntroduction($faker->paragraph())
        ->setDescription($description)
        ->setPhoto($photo)
        ->setPassword($this->encoder->encodePassword($user, "password"));

      $manager->persist($user);

      for ($j = 1; $j <= mt_rand(0, 15); $j++) {
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
