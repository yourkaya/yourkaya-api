<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Webmozart\Assert\Assert;

/**
 * @ORM\Entity()
 * @ORM\Table(name="carts")
 */
class Cart
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="guid")
     */
    private string $id;

    /**
     * @var Collection|Product[]
     *
     * @ORM\ManyToMany(targetEntity=Product::class)
     * @ORM\JoinTable(name="carts_products")
     */
    private Collection $products;

    public function __construct(string $id)
    {
        Assert::uuid($id);

        $this->id = $id;
        $this->products = new ArrayCollection();
    }

    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return Product[]|Collection
     */
    public function getProducts(): Collection
    {
        return $this->products;
    }

    public function addProduct(Product $product): self
    {
        $this->products->add($product);

        return $this;
    }
}
