<?php

namespace App\DataFixtures;

use App\Entity\Device;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class TestDataFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        // for testing purposes creating new users and attaching new devices
        for ($i = 0; $i < 15; $i++) {
            $user = new User();
            $user->setStatus($faker->randomElement(['active', 'inactive']))
                ->setEmail($faker->unique()->safeEmail())
                ->setCountryCode($faker->randomElement(['US', 'CA', 'ES', 'LT']))
                ->setLastActiveAt($faker->dateTimeBetween('-15 days'))
                ->setIsPremium($faker->boolean());
            for ($a = 0; $a < 3; $a++) {
                $device = (new Device())
                    ->setLabel($faker->word())
                    ->setPlatform($faker->randomElement(['android', 'ios', 'windows']))
                    ->setUser($user);
                $user->addDevice($device);
                $manager->persist($device);
            }
            $manager->persist($user);
        }

        $manager->flush();
    }
}
