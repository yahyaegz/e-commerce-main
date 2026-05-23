<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Product;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public function __construct(private readonly UserPasswordHasherInterface $passwordHasher)
    {
    }

    public function load(ObjectManager $manager): void
    {
        $categories = [];

        foreach ($this->getCategoryData() as $categoryData) {
            $category = (new Category())
                ->setName($categoryData['name'])
                ->setSlug($categoryData['slug'])
                ->setDescription($categoryData['description'])
                ->setImage($categoryData['image']);

            $categories[$categoryData['slug']] = $category;
            $manager->persist($category);
        }

        foreach ($this->getProductData() as $index => $productData) {
            $product = (new Product())
                ->setName($productData['name'])
                ->setSlug($productData['slug'])
                ->setDescription($productData['description'])
                ->setPrice($productData['price'])
                ->setImage($productData['image'])
                ->setStock($productData['stock'])
                ->setCategory($categories[$productData['category']])
                ->setCreatedAt(new \DateTimeImmutable(sprintf('-%d days', $index)));

            $manager->persist($product);
        }

        $user = (new User())
            ->setEmail('student@example.com')
            ->setFirstname('Symfony')
            ->setLastname('Student');
        $user->setPassword($this->passwordHasher->hashPassword($user, 'password123'));
        $manager->persist($user);

        $manager->flush();
    }

    /**
     * @return list<array{name: string, slug: string, description: string, image: string}>
     */
    private function getCategoryData(): array
    {
        return [
            [
                'name' => 'Electronics',
                'slug' => 'electronics',
                'description' => 'Headphones, speakers, accessories and useful everyday gadgets.',
                'image' => 'images/mouse.png',
            ],
            [
                'name' => 'Fashion',
                'slug' => 'fashion',
                'description' => 'Clothing, accessories and wardrobe essentials for every season.',
                'image' => 'images/item.png',
            ],
            [
                'name' => 'Home & Garden',
                'slug' => 'home-garden',
                'description' => 'Smart home goods, decor pieces and garden helpers.',
                'image' => 'images/thumbnail.png',
            ],
            [
                'name' => 'Sports & Fitness',
                'slug' => 'sports-fitness',
                'description' => 'Workout gear, yoga essentials and recovery accessories.',
                'image' => 'images/airbod.png',
            ],
            [
                'name' => 'Books',
                'slug' => 'books',
                'description' => 'Learning guides, practical references and inspiring reads.',
                'image' => 'images/item.png',
            ],
        ];
    }

    /**
     * @return list<array{name: string, slug: string, description: string, price: float, image: string, stock: int, category: string}>
     */
    private function getProductData(): array
    {
        return [
            [
                'name' => 'Wireless Headphones',
                'slug' => 'wireless-headphones',
                'description' => 'Premium wireless headphones with active noise cancellation, comfortable cushions and up to 30 hours of battery life.',
                'price' => 79.99,
                'image' => 'images/airbod.png',
                'stock' => 15,
                'category' => 'electronics',
            ],
            [
                'name' => 'Bluetooth Speaker',
                'slug' => 'bluetooth-speaker',
                'description' => 'Portable speaker with clear sound, water resistance and enough battery for a full day outside.',
                'price' => 59.99,
                'image' => 'images/mouse.png',
                'stock' => 20,
                'category' => 'electronics',
            ],
            [
                'name' => 'Wireless Mouse',
                'slug' => 'wireless-mouse',
                'description' => 'Compact ergonomic mouse with silent clicks and reliable wireless connectivity.',
                'price' => 29.99,
                'image' => 'images/mouse.png',
                'stock' => 34,
                'category' => 'electronics',
            ],
            [
                'name' => 'Mechanical Keyboard',
                'slug' => 'mechanical-keyboard',
                'description' => 'A tactile keyboard with durable switches, compact layout and adjustable backlighting.',
                'price' => 89.99,
                'image' => 'images/item.png',
                'stock' => 12,
                'category' => 'electronics',
            ],
            [
                'name' => 'Classic Leather Jacket',
                'slug' => 'classic-leather-jacket',
                'description' => 'A timeless jacket with a clean cut, soft lining and durable zipper details.',
                'price' => 149.99,
                'image' => 'images/item.png',
                'stock' => 8,
                'category' => 'fashion',
            ],
            [
                'name' => 'Canvas Backpack',
                'slug' => 'canvas-backpack',
                'description' => 'Everyday backpack with padded straps, laptop pocket and water-resistant canvas.',
                'price' => 44.99,
                'image' => 'images/thumbnail.png',
                'stock' => 18,
                'category' => 'fashion',
            ],
            [
                'name' => 'Smart Plant Sensor',
                'slug' => 'smart-plant-sensor',
                'description' => 'Track soil moisture, light and temperature so your plants get the care they need.',
                'price' => 34.99,
                'image' => 'images/thumbnail.png',
                'stock' => 26,
                'category' => 'home-garden',
            ],
            [
                'name' => 'Ceramic Desk Lamp',
                'slug' => 'ceramic-desk-lamp',
                'description' => 'Warm desk lighting with a ceramic base and adjustable shade for focused work.',
                'price' => 52.50,
                'image' => 'images/item.png',
                'stock' => 11,
                'category' => 'home-garden',
            ],
            [
                'name' => 'Yoga Mat Premium',
                'slug' => 'yoga-mat-premium',
                'description' => 'Cushioned non-slip mat with a durable surface for stretching, pilates and yoga sessions.',
                'price' => 29.99,
                'image' => 'images/thumbnail.png',
                'stock' => 40,
                'category' => 'sports-fitness',
            ],
            [
                'name' => 'Adjustable Dumbbell Set',
                'slug' => 'adjustable-dumbbell-set',
                'description' => 'Space-saving dumbbells with quick weight changes for home strength training.',
                'price' => 119.99,
                'image' => 'images/airbod.png',
                'stock' => 6,
                'category' => 'sports-fitness',
            ],
            [
                'name' => 'Web Development Guide',
                'slug' => 'web-development-guide',
                'description' => 'A practical guide covering modern HTML, CSS, PHP basics and full-stack project structure.',
                'price' => 24.99,
                'image' => 'images/item.png',
                'stock' => 22,
                'category' => 'books',
            ],
            [
                'name' => 'Symfony Project Notebook',
                'slug' => 'symfony-project-notebook',
                'description' => 'A compact notebook for planning entities, routes, controllers and project ideas.',
                'price' => 14.99,
                'image' => 'images/thumbnail.png',
                'stock' => 30,
                'category' => 'books',
            ],
        ];
    }
}
