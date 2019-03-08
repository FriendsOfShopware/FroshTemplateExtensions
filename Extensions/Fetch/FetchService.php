<?php

namespace FroshTemplateExtensions\Extensions\Fetch;

use Doctrine\DBAL\Connection;

class FetchService implements FetchServiceInterface
{
    /**
     * @var Connection
     */
    private $connection;

    /**
     * @var array
     */
    private $_cache = [];

    /**
     * @param Connection $connection
     */
    public function __construct(
        Connection $connection
    ) {
        $this->connection = $connection;
    }

    /**
     * @param array $arguments
     *
     * @return mixed
     */
    public function fetchOne($arguments)
    {
        return $this->fetch(
            $arguments,
            'fetch',
            \PDO::FETCH_COLUMN
        );
    }

    /**
     * @param array $arguments
     *
     * @return mixed
     */
    public function fetchRow($arguments)
    {
        return $this->fetch(
            $arguments,
            'fetch',
            \PDO::FETCH_ASSOC
        );
    }

    /**
     * @param array $arguments
     *
     * @return mixed
     */
    public function fetchAll($arguments)
    {
        return $this->fetch(
            $arguments,
            'fetchAll',
            \PDO::FETCH_ASSOC
        );
    }

    /**
     * @param array  $arguments
     * @param string $method
     * @param int    $fetchMode
     *
     * @return mixed
     */
    public function fetch($arguments, $method, $fetchMode)
    {
        $hash = md5($method . serialize($arguments));
        $cache = $this->getCurrentStackCache($hash);

        if ($cache) {
            return $cache;
        }

        try {
            $value = $this->getDbSelect($arguments)
                ->execute()
                ->$method($fetchMode);

            $this->_cache[$hash] = $value;

            return $value;
        } catch (\Exception $exception) {
            return $exception->getMessage();
        }
    }

    /**
     * @param string $hash
     *
     * @return bool|mixed
     */
    private function getCurrentStackCache($hash)
    {
        if (isset($this->_cache[$hash])) {
            return $this->_cache[$hash];
        }

        return false;
    }

    /**
     * @param array  $arguments
     *
     * @return \Doctrine\DBAL\Query\QueryBuilder
     */
    private function getDbSelect($arguments)
    {
        $qb = $this->connection->createQueryBuilder();

        $qb->select($arguments['select'])
            ->from($arguments['from']);

        if (isset($arguments['where'])) {
            foreach ($arguments['where'] as $column => $value) {
                $qb->andWhere(
                    sprintf(
                        '%s %s',
                        $column,
                        $qb->createNamedParameter($value)
                    )
                );
            }
        }

        if (isset($arguments['order'])) {
            foreach ($arguments['order'] as $column => $order) {
                $qb->addOrderBy($column, $order);
            }
        }

        if (isset($arguments['offset'])) {
            $qb->setFirstResult($arguments['offset']);
        }

        if (isset($arguments['limit'])) {
            $qb->setMaxResults($arguments['limit']);
        }

        return $qb;
    }
}
