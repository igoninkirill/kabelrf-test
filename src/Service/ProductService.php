<?php

namespace App\Service;

use App\DTO\ParsedProductDTO;
use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Cocur\Slugify\Slugify;

class ProductService
{
    private EntityManagerInterface $entityManager;
    private string $imageDir;
    private Slugify $slugify;

    public function __construct(EntityManagerInterface $entityManager, ParameterBagInterface $params, Slugify $slugify)
    {
        $this->entityManager = $entityManager;
        $this->imageDir = $params->get('kernel.project_dir') . '/public/images/products';
        $this->slugify = $slugify;
    }

    public function saveParsed(ParsedProductDTO $parsedProductDTO): Product
    {
        $product = new Product();
        $product->setName($parsedProductDTO->getName());
        $product->setImageName($this->saveFile($parsedProductDTO->getImageUrl()));
        $product->setCode($this->slugify->slugify($parsedProductDTO->getName()));
        $product->setSortOrder($this->getNextSortOrder());
        $product->setIsTagged(true);
        $product->setIsActive(true);

        $this->entityManager->persist($product);
        $this->entityManager->flush();
        return $product;
    }

    protected function getNextSortOrder(): int
    {
        $productRepository = $this->entityManager->getRepository(Product::class);
        return $productRepository->getNextSortOrder();
    }

    protected function saveFile(string $url): string
    {
        $path = $this->imageDir . '/' . basename($url);
        $content = file_get_contents($url);
        if ($content !== false) {
            file_put_contents($path, $content);
        } else {
            throw new \Exception('Не удалось загрузить файл.');
        }
        return basename($url);
    }
}
