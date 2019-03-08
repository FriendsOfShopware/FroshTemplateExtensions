<?php

namespace FroshTemplateExtensions\Extensions\Fetch;

use FroshTemplateExtensions\Components\AbstractSmartyExtension;

class Extension extends AbstractSmartyExtension
{
    /**
     * @var FetchService
     */
    private $service;

    public function __construct(FetchService $service)
    {
        $this->service = $service;
    }

    public function getFunctions()
    {
        return [
            'fetchOne' => [$this, 'fetchOne'],
            'fetchRow' => [$this, 'fetchRow'],
            'fetchAll' => [$this, 'fetchAll'],
        ];
    }

    public function fetchOne(array $params)
    {
        if (!isset($params['select'])) {
            throw new \RuntimeException('Select is required');
        }

        $this->checkParams($params);

        return $this->service->fetchOne($params);
    }

    public function fetchRow(array $params, \Smarty_Internal_Template $template)
    {
        if (!isset($params['select'])) {
            $params['select'] = '*';
        }

        $this->checkParams($params);

        if (!isset($params['var']) || !is_string($params['var'])) {
            throw new \RuntimeException('Var parameter missing or must be of type string');
        }

        $template->assign($params['var'], $this->service->fetchRow($params));
    }

    public function fetchAll(array $params, \Smarty_Internal_Template $template)
    {
        if (!isset($params['select'])) {
            $params['select'] = '*';
        }

        $this->checkParams($params);

        if (isset($params['var']) && !is_string($params['var'])) {
            throw new \RuntimeException('Var parameter missing or must be of type string');
        }

        $template->assign($params['var'], $this->service->fetchAll($params));
    }

    private function checkParams($params)
    {
        if (!is_string($params['select']) && !is_array($params['select'])) {
            throw new \RuntimeException('Select must either be of type string or array');
        }

        if (!isset($params['from'])) {
            throw new \RuntimeException('From table is required');
        }

        if (isset($params['where']) && !is_array($params['where'])) {
            throw new \RuntimeException('Where parameter must be of type array');
        }

        if (isset($params['order']) && !is_array($params['order'])) {
            throw new \RuntimeException('Order parameter must be of type array');
        }

        if (isset($params['offset']) && !is_int($params['offset'])) {
            throw new \RuntimeException('Offset parameter must be of type integer');
        }

        if (isset($params['limit']) && !is_int($params['limit'])) {
            throw new \RuntimeException('Limit parameter must be of type integer');
        }
    }
}
