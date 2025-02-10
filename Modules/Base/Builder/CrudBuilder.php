<?php

namespace Modules\Base\Builder;

use Modules\Bases\Service\CrudService;
use PhpOption\None;
use PhpOption\Option;

class CrudBuilder
{
    protected ?array $params = null;
    protected ?Option $paginate = null;
    protected ?Option $search = null;
    protected ?Option $perPage = null;

    // Method to set Params
    public function setParams(array $params)
    {
        $this->params = $params;
        return $this;
    }

    // Method to set paginate
    public function setPaginate(Option $paginate)
    {
        $this->paginate = $paginate;
        return $this;
    }

    public function setCompatibleUpdate()
    {
        $this->params['is_compatible_update'] = true;

        return $this;
    }

    public function SetForParent()
    {
        $this->params['is_for_parent'] = true;
        return $this;
    }

    public function setParentRelation($parent_relation)
    {
        $this->params['parent_relation'] = $parent_relation;
        return $this;
    }
    public function setParentId($parent_id)
    {
        $this->params['parent_id'] = $parent_id;
        return $this;
    }

    public function setColumnForParent($column)
    {
        $this->params['column_for_parent'] = $column;
        return $this;
    }

    public function setTableForeignId($foreign_id)
    {
        $this->params['table_foreign_id'] = $foreign_id;
        return $this;
    }

    // Method to set search
    public function setSearch(Option $search)
    {
        $this->search = $search;
        return $this;
    }

    // Method to set perPage
    public function setPerPage(Option $perPage)
    {
        $this->perPage = $perPage;
        return $this;
    }

    // Build the CrudService object
    public function build(): CrudService
    {
        return new CrudService(
            params: $this->params,
            paginate: $this->paginate ?: None::create(),
            search: $this->search ?: None::create(),
            perPage: $this->perPage ?: None::create());
    }
}
