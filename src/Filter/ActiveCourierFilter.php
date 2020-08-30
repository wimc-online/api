<?php

namespace App\Filter;

use ApiPlatform\Core\Bridge\Doctrine\Common\Filter\BooleanFilterTrait;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\AbstractContextAwareFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Util\QueryNameGeneratorInterface;
use App\Entity\Courier;
use Doctrine\ORM\QueryBuilder;

final class ActiveCourierFilter extends AbstractContextAwareFilter
{
    use BooleanFilterTrait;

    /**
     * {@inheritdoc}
     */
    protected function filterProperty(
        string $property,
        $value,
        QueryBuilder $queryBuilder,
        QueryNameGeneratorInterface $queryNameGenerator,
        string $resourceClass,
        string $operationName = null
    ) {
        if (Courier::class !== $resourceClass || 'active' !== $property) {
            return;
        }

        $value = $this->normalizeValue($value, 'active');
        if (null === $value) {
            return;
        }

        $entityManager = $queryBuilder->getEntityManager();
        $subQueryBuilder = $entityManager->createQueryBuilder();

        $rootAlias = $queryBuilder->getRootAliases()[0];
        $tableAlias = $queryNameGenerator->generateJoinAlias('last_position');
        $parameterName = $queryNameGenerator->generateParameterName('max_tmstp');
        $subQuery = $subQueryBuilder->select($tableAlias)
            ->from('App:Position', $tableAlias)
            ->where(sprintf('%s.courier = %s', $tableAlias, $rootAlias))
            ->andWhere(sprintf('%s.tmstp > :%s', $tableAlias, $parameterName));
        $where = $queryBuilder->expr()->exists($subQuery->getDQL());
        if (false === $value) {
            $where = $queryBuilder->expr()->not($where);
        }

        $now = new \DateTimeImmutable();
        $paramValue = $now->modify('-300 seconds');
        $queryBuilder
            ->andWhere($where)
            ->setParameter($parameterName, $paramValue);
    }

    /**
     * {@inheritdoc}
     */
    public function getDescription(string $resourceClass): array
    {
        if (Courier::class !== $resourceClass) {
            return [];
        }

        $propertyName = $this->normalizePropertyName('active');
        $description[$propertyName] = [
            'property' => $propertyName,
            'type' => 'bool',
            'required' => false,
        ];

        return $description;
    }
}
