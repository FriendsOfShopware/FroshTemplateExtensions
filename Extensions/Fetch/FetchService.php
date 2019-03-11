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
    private $pluginConfig;

    /**
     * @var array
     */
    private $_cache = [];

    /**
     * @param Connection $connection
     * @param array      $pluginConfig
     */
    public function __construct(
        Connection $connection,
        $pluginConfig
    ) {
        $this->connection = $connection;
        $this->pluginConfig = $pluginConfig;
    }

    /**
     * @param array $arguments
     *
     * @return mixed
     */
    public function fetchOne($arguments)
    {
        if (!$this->pluginConfig['fetch_functions_active']) {
            return null;
        }

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
        if (!$this->pluginConfig['fetch_functions_active']) {
            return null;
        }

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
        if (!$this->pluginConfig['fetch_functions_active']) {
            return null;
        }

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
                if (is_null($value)) {
                    $qb->andWhere($column);

                    continue;
                }

                $type = is_array($value) ? Connection::PARAM_STR_ARRAY : \PDO::PARAM_STR;
                $qb->andWhere(
                    sprintf(
                        is_array($value) ? '%s (%s)' : '%s %s',
                        $column,
                        $qb->createNamedParameter($value, $type)
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
