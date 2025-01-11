<?php
namespace App\Controller;

use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    #[Route('/api/products', methods: ['GET'])]
    public function getProducts(EntityManagerInterface $em): JsonResponse
    {
        $products = $em->getRepository(Product::class)->findAll();

        $data = [];
        foreach ($products as $product) {
            $data[] = [
                'id' => $product->getId(),
                'name' => $product->getName(),
                'price' => $product->getPrice(),
                'image' => $product->getImage(),
                'category' => $product->getCategory()->getId(),
            ];
        }

        return $this->json($data);
    }

    #[IsGranted('ROLE_ADMIN')]  // Cette ligne assure que l'utilisateur doit être authentifié
    #[Route('api/products/list', name: 'product_list')]
    public function showProducts(EntityManagerInterface $em)
    {
        // Récupérer les produits depuis la base de données
        $products = $em->getRepository(Product::class)->findAll();

        // Passer les produits au template
        return $this->render('product/index.html.twig', [
            'products' => $products
        ]);
    }

    #[Route('/api/products', methods: ['POST'])]
    public function addProduct(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $product = new Product();
        $product->setName($data['name']);
        $product->setPrice($data['price']);
        $product->setImage($data['image']);

        $em->persist($product);
        $em->flush();

        return $this->json(['message' => 'Product created successfully'], 201);
    }
}
