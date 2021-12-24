<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;

class PanierController extends AbstractController
{
    #[Route('/panier', name: 'panier')]
    public function index(): Response
    {
        //TEST DU CACHE POUR LE PANIER---------------------------------------------
        $cachePoolProduit = new FilesystemAdapter('produit', 0, "cache");
        // var_dump($cachePoolProduit);

        //boucle dans $cachePoolProduit pour recup chaque produit
        // foreach ($cachePoolProduit as &$value) {
        //     var_dump($value);
        //     echo 'prod';
        // }
        
        // $fromCacheString = $cachePoolProduit->getItem('0');
        // $panier = $fromCacheString->get();
        // var_dump($panier);
        //-------------------------------------------------------------------------

        return $this->render('panier/index.html.twig', [
            'panierProduits' => $cachePoolProduit,
        ]);
    }
}
