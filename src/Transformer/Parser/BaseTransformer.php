<?php

namespace App\Transformer\Parser;

use Symfony\Component\DomCrawler\Crawler;
use App\Contract\DTO;

abstract class BaseTransformer
{
    public Crawler $node;

    public function __construct(Crawler $node)
    {
        $this->node = $node;
    }

    abstract public function transform(): DTO;

    protected function getTextFromCrawler(Crawler $crawler): string
    {
        return $crawler->count() ? $this->cleanText($crawler->text()) : 'Not available';
    }

    protected function getAttrFromCrawler(Crawler $crawler, string $attribute): string
    {
        return $crawler->count() ? $crawler->attr($attribute) : 'Not available';
    }

    private function cleanText($text) {
        $text = preg_replace('/\s+/', ' ', $text);
        return trim($text);
    }
}
