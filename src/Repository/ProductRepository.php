<?php

namespace App\Repository;

use App\Entity\Product;
use App\Repository\Sort\LimiterInterface;
use App\Repository\Sort\OrderByInterface;
use App\Service\Serializer\SerializerInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;

/**
 * @extends ServiceEntityRepository<Product>
 *
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{
	private string $entityName;
	private SerializerInterface $serializer;
    public function __construct(ManagerRegistry $registry, SerializerInterface $serializer)
    {
        parent::__construct($registry, Product::class);
		$this->entityName = strtolower( (new \ReflectionClass($this->_entityName))->getShortName() );
		$this->serializer = $serializer;
    }

    public function save(Product $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Product $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

	public function findByEntityParam(
		?int $id = null,
		mixed $createdAt = null,
		mixed $updateAt = null,
		?string $name = null,
		?string $description = null,
		?float $price = null,
		?bool $isDelete = null,
		?bool $isHide = null,

        OrderByInterface $orderBy = null,
        LimiterInterface $limiter = null
    ) : array {
		$entityName = $this->entityName;

		$query = $this->createQueryBuilder($entityName);

        if(isset($orderBy)) {
			$query->orderBy(
				$orderBy->getSort(),
				$orderBy->getOrder()
			);
		}

		if(isset($limiter)) {
			$query->setMaxResults($limiter->getLimit());
			if(!empty($limiter->getOffset())) {
				$query->setFirstResult(($limiter->getOffset() - 1) * $limiter->getLimit());
			}
		}

		$resultList = $this->filter(
			query: $query,
			id: $id,
			createdAt: $createdAt,
			updateAt: $updateAt,
			name: $name,
			price: $price,
			description: $description,
			isDelete: $isDelete,
			isHide: $isHide,
		)->getQuery()->getResult(AbstractQuery::HYDRATE_OBJECT) ?? [];


		$ret = [];
		foreach ($resultList as $result) {
			array_push($ret, json_decode($this->serializer->serialize($result, 'json', [AbstractObjectNormalizer::GROUPS => ['product']])));
		}
        return $ret;
    }

	private function filter(
		QueryBuilder $query,
		?int $id = null,
		mixed $createdAt = null,
		mixed $updateAt = null,
		?string $name = null,
		?string $description = null,
		?float $price = null,
		?bool $isDelete = null,
		?bool $isHide = null,
    ) : QueryBuilder {
		$entityName = $this->entityName;

        if(isset($id)) {
            $query->andWhere("$entityName.id IN (:id)")
            ->setParameter("id", $id);
        }
        if(isset($createdAt)) {
            $query->andWhere("$entityName.createdAt LIKE :createdAt")
            ->setParameter("createdAt", "%$createdAt%");
        }
        if(isset($updateAt)) {
            $query->andWhere("$entityName.updateAt LIKE :updateAt")
            ->setParameter("updateAt", "%$updateAt%");
        }
		if(isset($name)) {
            $query->andWhere("$entityName.name LIKE :title")
            ->setParameter("title", "%$name%");
        }
		if(isset($description)) {
            $query->andWhere("$entityName.description LIKE :description")
            ->setParameter("description", "%$description%");
        }
		if(isset($price)) {
            $query->andWhere("$entityName.price = :breadcrumb")
            ->setParameter("breadcrumb", $price);
        }
		if(isset($isDelete)) {
            $query->andWhere("$entityName.isDelete = :isDelete")
            ->setParameter("isDelete", $isDelete);
        }
		if(isset($isHide)) {
            $query->andWhere("$entityName.isHide = :isHide")
            ->setParameter("isHide", $isHide);
        }

        return $query;
    }

	public function getCountByEntityParam(
		?int $id = null,
		mixed $createdAt = null,
		mixed $updateAt = null,
		?string $name = null,
		?string $description = null,
		?float $price = null,
		?bool $isDelete = null,
		?bool $isHide = null,

        OrderByInterface $orderBy = null,
        LimiterInterface $limiter = null
    ) : int {
        $entityName = $this->entityName;
        $query = $this->createQueryBuilder($entityName)->select("COUNT($entityName)");
        
        if(isset($orderBy)) {
            $query->orderBy(
                $orderBy->getSort(),
                $orderBy->getOrder()
            );
        }

        if(isset($limiter)) {
            $query->setMaxResults($limiter->getLimit());
            if(!empty($limiter->getOffset())) {
                $query->setFirstResult(($limiter->getOffset() - 1) * $limiter->getLimit());
            }
        }

        return (int)$this->filter(
			query: $query,
			id: $id,
			createdAt: $createdAt,
			updateAt: $updateAt,
			name: $name,
			price: $price,
			description: $description,
			isDelete: $isDelete,
			isHide: $isHide,
		)->getQuery()->useQueryCache(true)->enableResultCache(3600)->getSingleScalarResult();
    }
}
