<?php

namespace FroshTemplateExtensions\Extensions\Fetch;

interface FetchServiceInterface
{
    /**
     * @param array $arguments
     *
     * @return mixed
     */
    public function fetchOne($arguments);

    /**
     * @param array $arguments
     *
     * @return mixed
     */
    public function fetchRow($arguments);

    /**
     * @param array $arguments
     *
     * @return mixed
     */
    public function fetchAll($arguments);

    /**
     * @param array  $arguments
     * @param string $method
     * @param int    $fetchMode
     *
     * @return mixed
     */
    public function fetch($arguments, $method, $fetchMode);
}
