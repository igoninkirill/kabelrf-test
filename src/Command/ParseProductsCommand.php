<?php

namespace App\Command;

use App\Transformer\Parser\ProductTransformer;
use Goutte\Client;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use App\Service\ProductService;

class ParseProductsCommand extends Command
{
    public ProductService $service;

    public function __construct(ProductService $service, string $name = null)
    {
        parent::__construct($name);
        $this->service = $service;
    }

    protected static $defaultName = 'app:parse-products';

    protected function execute(InputInterface $input, OutputInterface $output): void
    {
        $this->request(new Client());
    }

    protected function request(Client $client, int $page = 1, int $count = 0): void
    {
        try {
            $crawler = $client->request('GET', $_ENV['PODTRADE_URL'] .
                '/catalog/01_sharikovye_podshipniki/' . "?PAGEN_1={$page}");
            $crawler->filter('.trigran__item')->each(function ($node) use (&$count) {
                $productTransformer = new ProductTransformer($node);
                $this->service->saveParsed($productTransformer->transform());
                $count++;
                dump($count);
            });
            if ($count < 300) {
                $page++;
                $this->request($client, $page, $count);
            }
        } catch (\Exception $e) {
            dump($e->getMessage());
        }
    }
}
