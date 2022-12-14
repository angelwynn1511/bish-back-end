<?php

namespace App\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Annotations as OA;
use App\Repository\ProduitInCommandeRepository;
use Symfony\Component\HttpFoundation\Request;

#[Route('api/produitInCommande')]
class ProduitInCommandeController extends AbstractController{
        /**
     * @param ProduitInCommandeRepository $ProduitInCommandeRepository
     * @return JsonResponse
     * @OA\Tag (name="ProduitInCommande")
     * @OA\Response(
     *     response="200",
     *     description = "OK"
     * )
     */
    #[Route('/produitInCommande/{idCommande}', name: 'produit_in_commande', methods:"POST")]
    public function ProduitInCommande(ProduitInCommandeRepository $produitInCommandeRepository, Request $request): JsonResponse
    {
        $produitInCommandes = $produitInCommandeRepository->findOneOrderbyIdCommandes($request->attributes->get('idCommande'));
        
        $produitInCommandeArray = [];
        foreach($produitInCommandes as $produitInCommande){
            $produitInCommandeArray[] = [
                'id' => $produitInCommande->getId(),
                'quantite' => $produitInCommande->getQuantite(),
                'prixUnitaire' => $produitInCommande->getPrice(),
                'nomProduit' => $produitInCommande->getProduit()->getName(),
                'remise' => $produitInCommande->getPrice() * $produitInCommande->getRemise()/100,
                'remise en %' => $produitInCommande->getRemise(),
                'total' => $produitInCommande->getQuantite() * $produitInCommande->getPrice(),
                'Taille' => $produitInCommande->getTaille(),
            ];
            if(end($produitInCommandes)=== $produitInCommande){
            $infosCommandes[] = [
                'dateFacture' => $produitInCommande->getCommande()->getDateFacture()->format("d-m-Y"),
                'numeroCommande' => $produitInCommande->getCommande()->getId(),
                'Etat' => $produitInCommande->getCommande()->getEtatCommande(),
                'Adresse' => [
                    'ville' => $produitInCommande->getVille(),
                    'rue' => $produitInCommande->getRue(),
                    'Code Postal' => $produitInCommande->getCodePostal()
                ]
            ];
            array_push($produitInCommandeArray, $infosCommandes);
        }
    }
        return new JsonResponse($produitInCommandeArray);
    }
}