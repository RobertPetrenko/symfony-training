<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Article;
use App\Entity\Category;
use App\Entity\Comment;

class ArticleFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = \Faker\Factory::create('fr_FR');

        //Creating 3 fake categories
        for ($i = 1; $i <= 3 ; $i++) { 
            $category = new Category;
            $category->setTitile($faker->sentence())
                     ->setDescription($faker->paragraph());

            $manager->persist($category);


            //Creating articles from 4 up to 6
            for ($j = 1; $j <= mt_rand(4, 6); $j++) { 
                $article = new Article();

                //Treating arrays of fake paragraphs for article content
                $content =  '<p>' . join($faker->paragraphs(5), '<p></p>') . '</p>';

                $article->setTitle($faker->sentence())
                        ->setContent($content)
                        ->setImage($faker->imageUrl())
                        ->setCreatedAt($faker->dateTimeBetween('-6 months'))
                        ->setCategory($category);

                $manager->persist($article);

                //Treating arrays of fake paragraphs for comments content
                $content =  '<p>' . join($faker->paragraphs(2), '<p></p>') . '</p>';

                $now = new \DateTime;
                $interval = $now->diff($article->getCreatedAt());
                $days = $interval->days;
                $minimum = '-' . $days . ' days';

                for ($k = 1; $k <= mt_rand(4, 10); $k++) { 
                    $comment = new Comment;
                    $comment->setAuthor($faker->name)
                            ->setContent($content)
                            ->setCreatedAt($faker->dateTimeBetween($minimum))
                            ->setArticle(($article));

                    $manager->persist($comment);        
                }
            }
        }

        $manager->flush();
    }
}
