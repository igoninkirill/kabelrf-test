<?php

namespace App\Transformer\Parser;

use App\DTO\ParsedProductDTO;

class ProductTransformer extends BaseTransformer
{
    public function transform(): ParsedProductDTO
    {
        $name = $this->getTextFromCrawler($this->node->filter('.trigran__item__title'));
        $image = $this->getAttrFromCrawler($this->node->filter('.trigran__item__img'), 'src');
        $product = new ParsedProductDTO();
        $product->setName($name);
        $product->setImageUrl($_ENV['PODTRADE_URL'] . $image);
        return $product;
    }
}
