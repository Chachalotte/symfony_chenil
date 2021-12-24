<?php

namespace App\DataFixtures;

use App\Entity\Animal;
use App\Entity\Produit;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {

     // create 20 animals! Bam!
     for ($i = 0; $i < 20; $i++) {
            $date = new \DateTime('@'.strtotime('now'));
            $rand = ['Maine Coon', 'Persan', 'Bengal'][rand(0,2)];
            $nom = ['Chat stylé', 'Chien stylé', 'Hamster stylé'][rand(0,2)];

            $animal = new Animal();
            $animal->setAge(mt_rand(1, 20));
            $animal->setNom($nom);
            $animal->setRace($rand);
            $animal->setTaille(mt_rand(10, 30));
            $animal->setDescription('Lorem Ipsum');
            $animal->setImg($rand);
            $animal->setDate($date);
            $animal->setGenre(['Male', 'Femelle'][rand(0,1)]);

            $manager->persist($animal);

            $rand = ['Ownat', 'Schleich', 'MAXI ZOO'][rand(0,2)];
            $categorie = ['Croquette', 'Jouet', 'Vêtements'][rand(0,2)];

            $produit = new Produit();
            $produit->setPrix(mt_rand(1, 20));
            $produit->setStock(mt_rand(1, 20));
            $produit->setDescription('Lorem Ipsum', strval($rand));
            $produit->setImg(mt_rand(10, 30));
            $produit->setTitre($rand);
            $produit->setImg($rand);
            $produit->setCategorie($categorie);

            $manager->persist($produit);
        }

    $manager->flush();
    }

    // public function produit(ObjectManager $manager): void
    // {

    //  // create 20 products! Bam!
    //  for ($i = 0; $i < 20; $i++) {
    //         $date = new \DateTime('@'.strtotime('now'));
    //         $rand = ['Croquette', 'Jouet', 'Vêtements'][rand(0,2)];
    //         $categorie = ['Croquette', 'Jouet', 'Vêtements'][rand(0,2)];

    //         $produit = new Produit();
    //         $produit->setPrix(mt_rand(1, 20));
    //         $produit->setStock(mt_rand(1, 20));
    //         $produit->setDescription('Lorem Ipsum');
    //         $produit->setImg(mt_rand(10, 30));
    //         $produit->setTitre('');
    //         $produit->setImg($rand);
    //         $produit->setCategorie($categorie);

    //         $manager->persist($produit);
    //     }

    // $manager->flush();
    // }
}
