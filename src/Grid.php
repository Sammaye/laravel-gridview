<?php

namespace sammaye\Grid;

use Closure;
use Illuminate\Http\Request;

class Grid
{
    protected $name;

    protected $view;

    protected $data;

    protected $pageName;

    protected $pageSize;

    protected $sortName;

    protected $withInput;

    protected $columns;

    protected $emptyData;

    protected $emptyDataCell;

    protected $attributes;

    protected $headerRowAttributes;

    protected $filterRowAttributes;

    protected $dataRowAttributes;

    protected $footerRowAttributes;

    protected $showHeaderRow;

    protected $showFilterRow;

    protected $showFooterRow;

    public static function make($name, $options = [])
    {
        return new static($name, $options);
    }

    public function __construct($name, $options = [])
    {
        $this->name = $name;

        $options += [
            'view' => 'grid::grid',
            'data' => [],
            'pageName' => 'page',
            'pageSize' => 15,
            'sortName' => 'sort',
            'withInput' => [],
            'columns' => [],
            'emptyData' => __('No results'),
            'attributes' => [
                'class' => 'table table-hover table-bordered'
            ],
            'headerRowAttributes' => [],
            'filterRowAttributes' => [],
            'dataRowAttributes' => [],
            'footerRowAttributes' => [],
            'showHeaderRow' => true,
            'showFilterRow' => true,
            'showFooterRow' => true,
        ];

        foreach ($options as $k => $v) {
            $this->{'set' . ucfirst($k)}($v);
        }
    }

    public function getName()
    {
        return $this->getPropertyValue($this->name);
    }

    public function getView()
    {
        return $this->getPropertyValue($this->view);
    }

    public function setView($view)
    {
        $this->view = $view;
        return $this;
    }

    public function getData()
    {
        return $this->getPropertyValue($this->data);
    }

    public function setData($data)
    {
        $this->data = $data;
        return $this;
    }

    public function getPageName()
    {
        return $this->getPropertyValue($this->pageName);
    }

    public function setPageName($pageName)
    {
        $this->pageName = $pageName;
        return $this;
    }

    public function getPageSize()
    {
        return $this->getPropertyValue($this->pageSize);
    }

    public function setPageSize($pageSize)
    {
        $this->pageSize = $pageSize;
        return $this;
    }

    public function getSortName()
    {
        return $this->getPropertyValue($this->sortName);
    }

    public function setSortName($sortName)
    {
        $this->sortName = $sortName;
        return $this;
    }

    public function getWithInput()
    {
        return $this->getPropertyValue($this->withInput);
    }

    public function setWithInput($withInput)
    {
        $this->withInput = $withInput;
        return $this;
    }

    public function getColumns()
    {
        return $this->getPropertyValue($this->columns);
    }

    public function setColumns(array $columns)
    {
        foreach ($columns as $column) {
            $column->setGrid($this);
            $column->setElementPrefix($this->getName());

            $emptyDataCell = $this->getEmptyDataCell();
            if ($emptyDataCell) {
                $column->setEmptyDataContent($emptyDataCell);
            }

            $column->setSortName($this->getSortName());
            $column->setWithInput($this->getWithInput());

            $this->columns[$column->getName()] = $column;
        }
        return $this;
    }

    public function getEmptyData()
    {
        return $this->getPropertyValue($this->emptyData);
    }

    public function setEmptyData($emptyData)
    {
        $this->emptyData = $emptyData;
        return $this;
    }

    public function getEmptyDataCell()
    {
        return $this->getPropertyValue($this->emptyDataCell);
    }

    public function setEmptyDataCell($emptyDataCell)
    {
        $this->emptyDataCell = $emptyDataCell;
        return $this;
    }

    public function getAttributes()
    {
        return $this->getFormattedAttributes($this->attributes);
    }

    public function setAttributes($attributes)
    {
        $this->attributes = $attributes;
        return $this;
    }

    public function getHeaderRowAttributes()
    {
        return $this->getFormattedAttributes($this->headerRowAttributes);
    }

    public function setHeaderRowAttributes($headerRowAttributes)
    {
        $this->headerRowAttributes = $headerRowAttributes;
        return $this;
    }

    public function getFilterRowAttributes()
    {
        return $this->getFormattedAttributes($this->filterRowAttributes);
    }

    public function setFilterRowAttributes($filterRowAttributes)
    {
        $this->filterRowAttributes = $filterRowAttributes;
        return $this;
    }

    public function getDataRowAttributes($row)
    {
        return $this->getFormattedAttributes($this->dataRowAttributes, $row);
    }

    public function setDataRowAttributes($dataRowAttributes)
    {
        $this->dataRowAttributes = $dataRowAttributes;
        return $this;
    }

    public function getFooterRowAttributes()
    {
        return $this->getFormattedAttributes($this->footerRowAttributes);
    }

    public function setFooterRowAttributes($footerRowAttributes)
    {
        $this->footerRowAttributes = $footerRowAttributes;
        return $this;
    }

    public function getShowHeaderRow()
    {
        return $this->getPropertyValue($this->showHeaderRow);
    }

    public function setShowHeaderRow($showHeaderRow)
    {
        $this->showHeaderRow = $showHeaderRow;
        return $this;
    }

    public function getShowFilterRow()
    {
        return $this->getPropertyValue($this->showFilterRow);
    }

    public function setShowFilterRow($showFilterRow)
    {
        $this->showFilterRow = $showFilterRow;
        return $this;
    }

    public function getShowFooterRow()
    {
        return $this->getPropertyValue($this->showFooterRow);
    }

    public function setShowFooterRow($showFooterRow)
    {
        $this->showFooterRow = $showFooterRow;
        return $this;
    }

    public function getFilterFormActionUrl()
    {
        return url()->current();
    }

    public function getTable()
    {
        $this->setData(
            $this->getData()->paginate(
                $this->getPageSize(),
                ['*'],
                $this->getName() . '-' . $this->getPageName()
            )->appends(
                $this->getInputValues([
                    $this->getName() . '-' . $this->getPageName()
                ])
            )
        );

        return view($this->getView())
            ->with([
                'grid' => $this,
            ]);
    }

    public function hasHeaderRow()
    {
        foreach ($this->getColumns() as $column) {
            if ($column->getHeaderCell()) {
                return true;
            }
        }
        return false;
    }

    public function hasFilterRow()
    {
        foreach ($this->getColumns() as $column) {
            if ($column->getFilterCell()) {
                return true;
            }
        }
        return false;
    }

    public function hasFooterRow()
    {
        foreach ($this->getColumns() as $column) {
            if ($column->getFooterContent()) {
                return true;
            }
        }
        return false;
    }

    protected function getFormattedAttributes($attributes, ...$params)
    {
        if ($attributes instanceof Closure) {
            array_unshift($params, $this);
            $attributes = call_user_func_array($attributes, $params);
        }

        $parsedAttributes = [];
        foreach ($attributes as $k => $v) {
            $parsedAttributes[] = $k . '="' . $v . '"';
        }
        return implode(' ', $parsedAttributes);
    }

    protected function getPropertyValue($value, ...$params)
    {
        if ($value instanceof Closure) {
            array_unshift($params, $this);
            $value = call_user_func_array($value, $params);
        }
        return $value;
    }

    protected function getInputValues(array $exclude = [])
    {
        $inputValues = collect(request()->all())->except($exclude)->toArray();

        foreach ($inputValues as $name => $value) {
            if (
                (
                    !in_array($name, $this->getWithInput(), true) &&
                    !preg_match("#^{$this->getName()}-#", $name)
                ) || is_null($value)
            ) {
                unset($inputValues[$name]);
            }
        }

        return $inputValues;
    }
}
