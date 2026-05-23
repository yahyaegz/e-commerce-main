<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class StoreController extends AbstractController
{
    #[Route('/', name: 'app_home', methods: ['GET'])]
    public function home(ProductRepository $productRepository, CategoryRepository $categoryRepository): Response
    {
        return $this->render('store/home.html.twig', [
            'products' => $productRepository->findRecent(6),
            'categories' => $categoryRepository->findAllOrdered(),
        ]);
    }

    #[Route('/categories', name: 'app_categories', methods: ['GET'])]
    public function categories(CategoryRepository $categoryRepository): Response
    {
        return $this->render('store/categories.html.twig', [
            'categories' => $categoryRepository->findAllOrdered(),
        ]);
    }

    #[Route('/category/{slug}', name: 'app_category_show', methods: ['GET'])]
    public function productsByCategory(string $slug, CategoryRepository $categoryRepository, ProductRepository $productRepository): Response
    {
        $category = $categoryRepository->findOneBy(['slug' => $slug]);

        if ($category === null) {
            throw $this->createNotFoundException('Category not found.');
        }

        return $this->render('store/category.html.twig', [
            'category' => $category,
            'products' => $productRepository->findByCategory($category),
        ]);
    }

    #[Route('/product/{id<\d+>}', name: 'app_product_show', methods: ['GET'])]
    public function productDetails(int $id, ProductRepository $productRepository): Response
    {
        $product = $productRepository->find($id);

        if ($product === null) {
            throw $this->createNotFoundException('Product not found.');
        }

        return $this->render('store/product_details.html.twig', [
            'product' => $product,
        ]);
    }
}
