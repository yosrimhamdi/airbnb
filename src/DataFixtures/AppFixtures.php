<?php
namespace App\DataFixtures;
use App\Entity\Ad;
use App\Entity\Booking;
use App\Entity\User;
use App\Entity\Image;
use App\Entity\Role;
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

        $adminRole = new Role();
        $adminRole->setTitle('ROLE_ADMIN');

        $manager->persist($adminRole);

        $adminUser = new User();
        $adminUser
            ->setFirstName('Yosri')
            ->setLastName('Mhamdi')
            ->setEmail('yosri@mhamdi.co')
            ->setPassword(
                $this->encoder->encodePassword($adminUser, 'password')
            )
            ->setIntroduction($faker->paragraph())
            ->setDescription(
                '<p>' . join('<p></p>', $faker->paragraphs()) . '</p>'
            )
            ->addUserRole($adminRole)
            ->setPhoto(
                'https://www.mhamdi.co/assets/images/pro-pic-of-me.jfif'
            );

        $manager->persist($adminUser);

        $users = [$adminUser];

        for ($i = 0; $i < 10; $i++) {
            $description =
                '<p>' . join('<p></p>', $faker->paragraphs()) . '</p>';

            $photo =
                'https://randomuser.me/api/portraits/men/' .
                mt_rand(1, 99) .
                '.jpg';
            $user = new User();
            $user
                ->setFirstName($faker->firstNameMale())
                ->setLastName($faker->lastName())
                ->setEmail($faker->email())
                ->setIntroduction($faker->paragraph())
                ->setDescription($description)
                ->setPhoto($photo)
                ->setPassword(
                    $this->encoder->encodePassword($user, 'password')
                );

            $users[] = $user;

            $manager->persist($user);
        }

        for ($j = 1; $j <= 30; $j++) {
            $content = '<p>' . join('<p></p>', $faker->paragraphs(5)) . '</p>';

            $ad = new Ad();
            $ad->setTitle($faker->sentence(3))
                ->setPrice(mt_rand(300, 1400))
                ->setIntroduction($faker->sentence(7))
                ->setContent($content)
                ->setCoverImage($faker->imageUrl(1980, 700, true))
                ->setRooms(mt_rand(2, 5))
                ->setUser($users[mt_rand(0, count($users) - 1)]);

            $manager->persist($ad);

            for ($k = 0; $k < mt_rand(3, 5); $k++) {
                $image = new Image();
                $image
                    ->setUrl($faker->imageUrl(1980, 700, true))
                    ->setCaption($faker->sentence(5))
                    ->setAd($ad);

                $manager->persist($image);
            }

            for ($p = 0; $p < mt_rand(5, 10); $p++) {
                $createdAt = $faker->dateTimeBetween('-3 months');
                $startDate = $faker->dateTimeBetween($createdAt);
                $nbrDays = mt_rand(2, 7);
                $endDate = (clone $startDate)->modify("+$nbrDays days");

                $booking = new Booking();
                $booking
                    ->setAd($ad)
                    ->setBooker($users[mt_rand(0, count($users) - 1)])
                    ->setStartDate($startDate)
                    ->setEndDate($endDate)
                    ->setCreatedAt($createdAt)
                    ->setAmount($ad->getPrice() * mt_rand(1, 7))
                    ->setComment($faker->paragraph());

                $manager->persist($booking);
            }
        }

        $manager->flush();
    }
}
