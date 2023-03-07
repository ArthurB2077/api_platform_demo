# UTILS COMMANDS

## maker-bundle

Allow to make entity in command line quickly with doctrine

### Install maker-bundle

```bash
docker-compose exec php composer require symfony/maker-bundle --dev
```

### Make an entity

```bash
docker-compose exec php php bin/console make:entity --api-resource
```

&nbsp;

## doctrine

Object relation mapping (ORM) that allows php to interact with the database

### Create database

```bash
docker-compose exec php php bin/console doctrine:database:create
```

### Make the database updated with our entity (without passing by migrations when there is no data persistentcy issues)

```bash
docker-compose exec php php bin/console doctrine:schema:update --force
```

&nbsp;

## orm-fixtures

Allow us to create fake data to test pour API

### Install orm-fixtures

```bash
docker-compose exec php composer require orm-fixtures --dev
```

### Load fixtures

```bash
docker-compose exec php php bin/console doctrine:fixture:load
```

&nbsp;

## fakerphp

Allow to generate content when creating fixtures with fake data

### Install fakerphp

```bash
docker-compose exec php composer require fakerphp/faker
```

### Make fake data

Adding 20 instances of the Book entity with rich content for the title and coverBook fields

```php
namespace App\DataFixtures;

use App\Entity\Book;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {

        $faker = Factory::create();

        for ($i = 0; $i < 20; $i++) {
            $book = new Book;
            $book->setTitle($faker->name());
            $book->setCoverText($faker->text());
            $manager->persist($book);
        }

        $manager->flush();
    }
}
```

For more see the documentation: <https://fakerphp.github.io/>

&nbsp;
