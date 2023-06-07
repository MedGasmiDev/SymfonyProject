<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Repository\ProduitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\HttpClientInterface as SymfonyHttpClientInterface;
use Symfony\Component\HttpFoundation\Request;

class ProduitController extends AbstractController
{
    private $client;

    public function __construct(SymfonyHttpClientInterface $client) // Updated type hint
    {
        $this->client = $client;
    }
    #[Route('/produit', name: 'List_Produit')]
    // public function index(ProduitRepository $prodR)
    // {
    //     // $test="hello world";
    //     $ListProd = $prodR->findAll();
    //     // dd($ListProd);
    //     return $this->render('produit/index.html.twig', ['listeProduits' => $ListProd]);
    // }
    public function index(ProduitRepository $produitRepository): Response
    {
        $res = $this->client->request(
            'GET',
            'https://tech.dev.ats-digital.com/api/products?size=50'
        );

        
        $data = json_decode($res->getContent(), true);
      //   dd($data);

        return $this->render('produit/index.html.twig', [
            'listeProduits' => $data
        ]);
    }


    #[Route('/produit/{id}', name: 'show_produit', methods: ['GET'])]
    public function showProduit(Produit $produit)
    {
        // dd($produit);
        $reviews = $produit->getReviews();

        return $this->render('produit/show.html.twig', [
            'produit' => $produit,
            'reviews' => $reviews,
        ]);
    }
}
