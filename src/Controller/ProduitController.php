<?php

namespace App\Controller;

use App\Entity\Produit;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
 
class ProduitController extends AbstractController
{
	#[Route('/produit', name: 'app_produit')]
    public function index(ManagerRegistry $doctrine): Response
    {
        $produits = $doctrine->getRepository(Produit::class);

        $produit = $produits->findAll();

        return $this->render('produit/index.html.twig', [
			'produit' => $produit,
        ]);
    }

    #[Route('/produit/{id}', name: 'app_produit_select')]
    public function list_produit(ManagerRegistry $doctrine, int $id): Response
    {
        $produit = $doctrine->getRepository(Produit::class)->find($id);


        //TEST DU CACHE POUR LE PANIER---------------------------------------------
        // $cachePoolProduit = new FilesystemAdapter('produit', 0, "cache");
        // $toCacheString = $cachePoolProduit->getItem('0');

        // $toCacheString->set($produit);
        // $cachePoolProduit->save($toCacheString);
        //-------------------------------------------------------------------------

        return $this->render('produit/produit.html.twig', [
			'produit' => $produit,
        ]);
    }
}