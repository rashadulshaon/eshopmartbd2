<?php

namespace App\Controller;

use App\Entity\Brand;
use App\Entity\Category;
use App\Entity\Product;
use App\Repository\BrandRepository;
use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use App\Repository\ShippingMethodRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;

class ProductController extends AbstractController
{
    private $productRepo;
    private $categoryRepo;
    private $brandRepo;
    private $paginator;
    private $shippingMethodRepo;

    public function __construct(
        ProductRepository $productRepo,
        CategoryRepository $categoryRepo,
        BrandRepository $brandRepo,
        PaginatorInterface $paginator,
        ShippingMethodRepository $shippingMethodRepo
    ) {
        $this->productRepo = $productRepo;
        $this->categoryRepo = $categoryRepo;
        $this->paginator = $paginator;
        $this->brandRepo = $brandRepo;
        $this->shippingMethodRepo = $shippingMethodRepo;
    }

    // Home controller
    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        $latestProducts = $this->productRepo->findBy([], ['id' => 'DESC'], 12);
        $randomProducts = $this->productRepo->findBy([], null, 30);
        shuffle($randomProducts);
        $categories = $this->categoryRepo->findBy([], null, 12);

        return $this->render('product/index_2.html.twig', [
            'latestProducts' => $latestProducts,
            'products' => $randomProducts,
            'categories' => $categories
        ]);
    }

    // All products archive
    #[Route('/all_products', name: 'app_product_archive')]
    public function products(Request $request): Response
    {
        $productsQuery = $this->productRepo->createQueryBuilder('p');

        $pagination = $this->paginator->paginate(
            $productsQuery,
            $request->query->getInt('page', 1),
            60
        );

        $categories = $this->categoryRepo->findBy([], null, 4);

        return $this->render('product/index_2.html.twig', [
            'products' => $pagination,
            'categories' => $categories
        ]);
    }

    // Product category controller
    #[Route('/category/{id}', name: 'app_product_category')]
    public function category(Category $category, Request $request): Response
    {
        $productList = $category->getProducts();

        $pagination = $this->paginator->paginate(
            $productList,
            $request->query->getInt('page', 1),
            60
        );

        return $this->render('product/index_2.html.twig', [
            'category' => $category,
            'products' => $pagination
        ]);
    }

    // Product brand controller
    #[Route('/brand/{id}', name: 'app_product_brand')]
    public function brand(Brand $brand, Request $request): Response
    {
        $productList = $brand->getProducts();

        $pagination = $this->paginator->paginate(
            $productList,
            $request->query->getInt('page', 1),
            60
        );

        return $this->render('product/index_2.html.twig', [
            'brand' => $brand,
            'products' => $pagination,
        ]);
    }

    // Product details controller
    #[Route('/product/{id}', name: 'app_product_details')]
    public function details(Product $product): Response
    {
        return $this->render('product/details.html.twig', [
            'product' => $product,
            'shippingMethods' => $this->shippingMethodRepo->findAll(),
            'relatedProducts' => $this->productRepo->findBy(
                ['category' => $product->getCategory()],
                ['id' => 'DESC'],
                5
            )
        ]);
    }

    // Product search
    #[Route('/product_search/{query}', name: 'app_product_search')]
    public function search(Request $req): Response
    {
        $searchQuery = $req->query->get('searchQuery');

        $searchResults = $this->productRepo->createQueryBuilder('p')
            ->where('LOWER(p.name) LIKE :searchQuery')
            ->setParameter('searchQuery', '%' . strtolower($searchQuery) . '%')
            ->getQuery()
            ->getResult();

        $pagination = $this->paginator->paginate(
            $searchResults,
            $req->query->getInt('page', 1),
            30
        );

        return $this->render('product/index_2.html.twig', [
            'products' => $pagination,
            'shippingMethods' => $this->shippingMethodRepo->findAll(),
            'searchQuery' => $searchQuery
        ]);
    }
}
