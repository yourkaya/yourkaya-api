<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Cart;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\Persistence\ManagerRegistry;

final class CartRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Cart::class);
    }

    public function countProducts(string $cart): int
    {
        $builder = $this->createQueryBuilder('cart');
        $builder
            ->select('COUNT(products.id)')
            ->join('cart.products', 'products')
            ->where('cart.id = :cart')
            ->setParameters(
                [
                    'cart' => $cart,
                ]
            );

        try {
            return $builder->getQuery()->getSingleScalarResult();
        } catch (NoResultException) {
            return 0;
        } catch (NonUniqueResultException) {
            throw new \LogicException('Impossibru!!! Query should return signe scalar result by design!');
        }
    }
}
