<?php

namespace App\Http\Controllers;

use Arr;

trait SearchTrait
{
    protected function getPager($repository, $conditions = [], $options = [], $additionalQuery = null)
    {
        $builder = $this->generateBuilder($repository, $conditions, $options, $additionalQuery);
        $limit = Arr::get($options, 'limit', 30);

        return $builder->paginate($limit);
    }

    protected function getIterator($repository, $conditions = [], $options = [], $additionalQuery = null)
    {
        $builder = $this->generateBuilder($repository, $conditions, $options, $additionalQuery);
        foreach ($builder->cursor() as $entity) {
            yield $entity;
        }
    }

    protected function getCount($repository, $conditions = [], $options = [], $additionalQuery = null)
    {
        $builder = $this->generateBuilder($repository, $conditions, $options, $additionalQuery);
        return $builder->count();
    }

    protected function generateBuilder($repository, array $conditions, array $options, $additionalQuery)
    {
        foreach ($conditions ?? [] as $key => $val) {
            // 空の値は削除
            if (empty($val) && $val !== "0" && $val !== 0) {
                unset($conditions[$key]);
                continue;
            }

            // >= などを使いたい場合
            // (ge)created_at="20190201" を pimpable が理解できるように
            // created_at => "(ge)20190201" にする処理
            if (preg_match('/^\((.+?)\)/', $key, $match) > 0) {
                unset($conditions[$key]);
                $op = $match[0];
                $newKey = preg_replace('/^\((.+?)\)/', '', $key);
                $newVal = $op . $val;
                if (empty($conditions[$newKey])) {
                    $conditions[$newKey] = [];
                }
                $conditions[$newKey][] = $newVal;
            }

            // val の頭に (!) があり、key に : が存在する場合、whereDoesntHave と解釈する
            if (count(explode(':', $key)) > 1 && ! is_array($val) && strpos($val, '(!)') === 0) {
                unset($conditions[$key]);
                $newVal = str_replace('(!)', '', $val);
                $newKey = '!' . $key;
                $conditions[$newKey] = $newVal;
            }
        }

        // null や空の配列を渡すとページングがおかしくなるため、id  not null を渡す
        if (empty($conditions)) {
            $conditions = ['id' => '!(null)'];
        }

        $orderBy = Arr::get($options, 'orderBy');
        if (empty($orderBy)) {
            if (method_exists($repository, 'getKeyName')) {
                $orderBy = $repository->getKeyName();
            } else {
                $orderBy = 'id';
            }
        }
        $sort = Arr::get($options, 'sortedBy', 'desc');
        if (empty($sort)) {
            $sort = 'desc';
        }

        $builder = $repository->pimp($conditions, "{$orderBy},{$sort}");
        if (! empty(with($limit = Arr::get($options, 'limit')))) {
            $builder->limit($limit);
        }
        if (! empty(with($offset = Arr::get($options, 'offset')))) {
            $builder->offset($offset);
        }
        if (is_callable($additionalQuery)) {
            $builder = $additionalQuery($builder);
        }

        return $builder;
    }
}
