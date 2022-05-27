<?php
/**
 * Task fixtures.
 */

namespace App\DataFixtures;

use App\Entity\Movie;
use Doctrine\Persistence\ObjectManager;
use DateTimeImmutable;

/**
 * Class MovieFixtures.
 */
class MovieFixtures extends AbstractBaseFixtures
{
    /**
     * Load data.
     *
     * @param ObjectManager $manager Persistence object manager
     */
    public function loadData(): void
    {
        for ($i = 0; $i < 10; ++$i) {
            $movie = new Movie();
            $movie->setTitle($this->faker->sentence);
            $movie->setCreatedAt(
                DateTimeImmutable::createFromMutable($this->faker->dateTimeBetween('-100 days', '-1 days'))
            );
            $movie->setUpdatedAt(
                DateTimeImmutable::createFromMutable($this->faker->dateTimeBetween('-100 days', '-1 days'))
            );
            $movie->setYear($this->faker->numberBetween(1900, 2022));
            $movie->setDescription($this->faker->paragraph);
            $movie->setDirector($this->faker->name);
            $movie->setDuration($this->faker->numberBetween(50, 180));
            $this->manager->persist($movie);
        }
        $this->manager->flush();
    }
}