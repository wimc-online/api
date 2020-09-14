<?php

namespace App\Filter;

use ApiPlatform\Core\Bridge\Doctrine\Common\Filter\BooleanFilterTrait;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\AbstractContextAwareFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Util\QueryNameGeneratorInterface;
use App\Entity\Task;
use Doctrine\ORM\QueryBuilder;

final class FinishedTaskFilter extends AbstractContextAwareFilter
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
        if (Task::class !== $resourceClass || 'is_finished' !== $property) {
            return;
        }

        $value = $this->normalizeValue($value, 'is_finished');
        if (null === $value) {
            return;
        }

        $entityManager = $queryBuilder->getEntityManager();
        $subQueryBuilder = $entityManager->createQueryBuilder();

        $rootAlias = $queryBuilder->getRootAliases()[0];
        $tableAlias = $queryNameGenerator->generateJoinAlias('subtasks');
        $subQuery = $subQueryBuilder->select($subQueryBuilder->expr()->count(sprintf('%s.id', $tableAlias)))
            ->from('App:Subtask', $tableAlias)
            ->where(sprintf('%s.task = %s', $tableAlias, $rootAlias))
            ->andWhere(sprintf('%s.is_finished = 0', $tableAlias));
        $count = $subQuery->getDQL();

        $where = $value ? $queryBuilder->expr()->eq("({$count})", 0) : $queryBuilder->expr()->gt("({$count})", 0);
        $queryBuilder->andWhere($where);
    }

    /**
     * {@inheritdoc}
     */
    public function getDescription(string $resourceClass): array
    {
        if (Task::class !== $resourceClass) {
            return [];
        }

        $propertyName = $this->normalizePropertyName('is_finished');
        $description[$propertyName] = [
            'property' => $propertyName,
            'type' => 'bool',
            'required' => false,
        ];

        return $description;
    }
}
