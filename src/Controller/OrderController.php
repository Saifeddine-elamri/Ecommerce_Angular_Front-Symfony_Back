<?php 
namespace App\Controller;

use App\Entity\Order;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response; // Importation correcte de Response
use Symfony\Component\Security\Http\Attribute\IsGranted;

class OrderController extends AbstractController
{
    #[Route('/api/checkout', name: 'checkout', methods: ['POST'])]
    public function checkout(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
      try {
            // Décodage des données JSON envoyées par le frontend
            $data = json_decode($request->getContent(), true);

            // Validation des champs obligatoires
            if (empty($data['name']) || empty($data['email']) || empty($data['address']) || empty($data['cartItems'])) {
                return new JsonResponse(['message' => 'Tous les champs sont requis.'], 400);
            }

            // Exemple : Enregistrement de la commande dans la base de données
            // Vous pouvez personnaliser cette section pour correspondre à vos entités
            $order = new Order();
            $order->setName($data['name']);
            $order->setEmail($data['email']);
            $order->setAdress($data['address']);
            // Sérialiser les éléments du panier en JSON
            $order->setCartItems(json_encode($data['cartItems']));
            // Définir la date de la commande
            $order->setDate(new \DateTime());

            // Sauvegarder la commande dans la base de données
            $entityManager->persist($order);

            // Finaliser et sauvegarder la commande
             $entityManager->flush();

            // Retourner une réponse en cas de succès
            return new JsonResponse(['message' => 'Commande enregistrée avec succès !'], 200);

        } catch (\Exception $e) {
            // Gérer les exceptions et retourner une réponse JSON avec le message d'erreur
            return new JsonResponse(['message' => 'Erreur côté serveur. Veuillez réessayer plus tard.', 'error' => $e->getMessage()], 500);
        }
    
    }

    #[IsGranted('ROLE_ADMIN')]  // Cette ligne assure que l'utilisateur doit être authentifié
    #[Route('/api/orders', name: 'get_orders', methods: ['GET'])]
    public function getOrders(EntityManagerInterface $entityManager): Response
    {
        $orders = $entityManager->getRepository(Order::class)->findAll();
        // Décoder les items du panier pour chaque commande
   
        return $this->render('order/orders.html.twig', [
            'orders' => $orders
        ]);
    }
}
