<?php

declare(strict_types=1);

namespace App\Application\Repositories;

use App\Application\Database\Facades\Db;
use Doctrine\DBAL\Query\QueryBuilder;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

/**
 * @template T
 */
abstract class Repository
{
    abstract public function getEntityClass(): string;
    abstract public function getTableName(): string;

    /**
     * @return Collection<T>
     */
    public function all(): Collection
    {
        return collect($this->queryBuilder()->executeQuery()->fetchAllAssociative())
            ->map(function ($row) {
                $camelArray = collect($row)->mapWithKeys(function ($value, $key) {
                    return [Str::camel($key) => $value];
                })->all();

                return new ($this->getEntityClass())(...$camelArray);
            });
    }

    public function queryBuilder(): QueryBuilder
    {
        return Db::getConnection()->createQueryBuilder()->from($this->getTableName());
    }
}