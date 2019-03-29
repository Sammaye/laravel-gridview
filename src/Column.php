<?php

namespace sammaye\Grid;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class Column
{
    protected $name;

    protected $grid;

    protected $label;

    protected $sortable;

    protected $sortName;

    protected $withInput;

    protected $elementPrefix;

    protected $dataContent;

    protected $emptyDataContent;

    protected $footerContent;

    protected $escapeDataContent;

    protected $escapeEmptyDataContent;

    protected $escapeFooterContent;

    protected $headerCellWith;

    protected $filterCellWith;

    protected $dataCellWith;

    protected $footerCellWith;

    protected $dataCellTag;

    protected $attributes;

    protected $headerCellAttributes;

    protected $filterCellAttributes;

    protected $dataCellAttributes;

    protected $footerCellAttributes;

    protected $headerCell;

    protected $filterCell;

    protected $dataCell;

    protected $footerCell;

    protected $emptyHeaderCell;

    protected $emptyFilterCell;

    protected $emptyDataCell;

    protected $emptyFooterCell;

    public static function make($name, $options = [])
    {
        return new static($name, $options);
    }

    public function __construct($name, $options = [])
    {
        $this->name = $name;

        $options += [
            'sortable' => true,
            'sortName' => 'sort',
            'withInput' => [],
            'emptyDataContent' => '<span class="text-muted">NULL</span>',
            'headerCellWith' => [],
            'filterCellWith' => [],
            'dataCellWith' => [],
            'footerCellWith' => [],
            'dataCellTag' => 'td',
            'attributes' => [],
            'headerCellAttributes' => [],
            'filterCellAttributes' => [],
            'dataCellAttributes' => [],
            'footerCellAttributes' => [],
            'escapeDataContent' => true,
            'escapeEmptyDataContent' => false,
            'escapeFooterContent' => true,
            'headerCell' => 'grid::headerCell.default',
            'filterCell' => 'grid::filterCell.default',
            'dataCell' => 'grid::dataCell.default',
            'footerCell' => 'grid::footerCell.default',
            'emptyHeaderCell' => 'grid::headerCell.empty',
            'emptyFilterCell' => 'grid::filterCell.empty',
            'emptyDataCell' => 'grid::dataCell.empty',
            'emptyFooterCell' => 'grid::footerCell.empty',
        ];

        foreach ($options as $k => $v) {
            $this->{'set' . ucfirst($k)}($v);
        }
    }

    public function getName()
    {
        return $this->getPropertyValue($this->name);
    }

    public function getElementPrefix()
    {
        return $this->getPropertyValue($this->elementPrefix);
    }

    public function setElementPrefix($ElementPrefix)
    {
        $this->elementPrefix = $ElementPrefix;
        return $this;
    }

    public function getInputValue($name = null)
    {
        $name = $this->getInputName($name);
        return request($name);
    }

    public function getInputName($name = null)
    {
        return ltrim($this->getElementPrefix() . '-' . ($name ?? $this->getName()), '-');
    }

    public function getSortUrl($name = null)
    {
        $name = $name ?? $this->getName();
        $sortName = $this->getInputName($this->getSortName());

        $sortInput = request($sortName);
        $sortDirection = strpos($sortInput, '-') === 0 ? -1 : 1;
        $sortInput = ltrim($sortInput, '-');
        $input = $this->getInputValues();

        if ($sortInput === null || $sortInput !== $name) {
            $input[$sortName] = $name;
            return request()->url() . '?' . Arr::query($input);
        }

        $input[$sortName] = ($sortDirection === 1 ? "-$name" : $name);

        return request()->url() . '?' . Arr::query($input);
    }

    public function getSortDirection($name = null)
    {
        $name = $name ?? $this->getName();
        $sortInput = request($this->getInputName($this->getSortName()));

        if ($sortInput === null || ltrim($sortInput, '-') !== $name) {
            return null;
        }
        return strpos($sortInput, '-') === 0 ? -1 : 1;
    }

    public function getGrid()
    {
        return $this->getPropertyValue($this->grid);
    }

    public function setGrid($grid)
    {
        $this->grid = $grid;
        return $this;
    }

    public function getLabel()
    {
        return $this->getPropertyValue($this->label) ??
            __(str_replace('_', ' ', Str::title($this->getPropertyValue($this->name))));
    }

    public function setLabel($label)
    {
        $this->label = $label;
        return $this;
    }

    public function getSortable()
    {
        return $this->getPropertyValue($this->sortable);
    }

    public function setSortable($sortable)
    {
        $this->sortable = $sortable;
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

    public function setWithInput($withInput)
    {
        $this->withInput = $withInput;
        return $this;
    }

    public function getWithInput()
    {
        return $this->getPropertyValue($this->withInput);
    }

    public function getDataContent($row)
    {
        $dataContent = $this->getPropertyValue($this->dataContent, $row);

        if (!$dataContent) {
            if (is_object($row)) {
                $dataContent = $row->{$this->name} ?? null;
            } elseif (is_array($row)) {
                $dataContent = $row[$this->name] ?? null;
            }
        }

        return $this->getEscapeDataContent() && $dataContent
            ? e($dataContent)
            : $dataContent;
    }

    public function setDataContent($dataContent, $escape = null)
    {
        $this->dataContent = $dataContent;

        if ($escape === null && $dataContent instanceof Closure) {
            $escape = false;
        } elseif ($escape === null) {
            $escape = true;
        }
        $this->escapeDataContent = $escape;

        return $this;
    }

    public function getEmptyDataContent($row)
    {
        $emptyDataContent = $this->getPropertyValue($this->emptyDataContent, $row);
        return $this->getEscapeEmptyDataContent() && $emptyDataContent
            ? e($emptyDataContent)
            : $emptyDataContent;
    }

    public function setEmptyDataContent($emptyDataContent, $escape = null)
    {
        $this->emptyDataContent = $emptyDataContent;

        $this->escapeEmptyDataContent = $escape ?? false;

        return $this;
    }

    public function getFooterContent()
    {
        $footerContent = $this->getPropertyValue($this->footerContent);
        return $this->getEscapeFooterContent() && $footerContent
            ? e($footerContent)
            : $footerContent;
    }

    public function setFooterContent($footerContent, $escape = null)
    {
        $this->footerContent = $footerContent;

        if ($escape === null && $footerContent instanceof Closure) {
            $escape = false;
        } elseif ($escape === null) {
            $escape = true;
        }
        $this->escapeFooterContent = $escape;

        return $this;
    }

    public function getEscapeDataContent()
    {
        return $this->getPropertyValue($this->escapeDataContent);
    }

    public function setEscapeDataContent($escapeDataContent)
    {
        $this->escapeDataContent = $escapeDataContent;
        return $this;
    }

    public function getEscapeEmptyDataContent()
    {
        return $this->getPropertyValue($this->escapeEmptyDataContent);
    }

    public function setEscapeEmptyDataContent($escapeEmptyDataContent)
    {
        $this->escapeEmptyDataContent = $escapeEmptyDataContent;
        return $this;
    }

    public function getEscapeFooterContent()
    {
        return $this->getPropertyValue($this->escapeFooterContent);
    }

    public function setEscapeFooterContent($escapeFooterContent)
    {
        $this->escapeFooterContent = $escapeFooterContent;
        return $this;
    }

    public function getHeaderCellWith()
    {
        return $this->getPropertyValue($this->headerCellWith);
    }

    public function setHeaderCellWith($headerCellWith)
    {
        $this->headerCellWith = $headerCellWith;
        return $this;
    }

    public function getFilterCellWith()
    {
        return $this->getPropertyValue($this->filterCellWith);
    }

    public function setFilterCellWith($filterCellWith)
    {
        $this->filterCellWith = $filterCellWith;
        return $this;
    }

    public function getDataCellWith($row)
    {
        return $this->getPropertyValue($this->dataCellWith, $row);
    }

    public function setDataCellWith($dataCellWith)
    {
        $this->dataCellWith = $dataCellWith;
        return $this;
    }

    public function getFooterCellWith()
    {
        return $this->getPropertyValue($this->footerCellWith);
    }

    public function setFooterCellWith($footerCellWith)
    {
        $this->footerCellWith = $footerCellWith;
        return $this;
    }

    public function setDataCellTag($dataCellTag)
    {
        $this->dataCellTag = $dataCellTag;
        return $this;
    }

    public function getDataCellTag($row)
    {
        return $this->getPropertyValue($this->dataCellTag, $row);
    }

    public function setAttributes($attributes)
    {
        $this->attributes = $attributes;
        return $this;
    }

    public function getAttributes()
    {
        return $this->getFormattedAttributes($this->attributes);
    }

    public function setHeaderCellAttributes($headerCellAttributes)
    {
        $this->headerCellAttributes = $headerCellAttributes;
        return $this;
    }

    public function getHeaderCellAttributes()
    {
        $attributes = array_merge(
            $this->getPropertyValue($this->attributes),
            $this->getPropertyValue($this->headerCellAttributes)
        );
        return $this->getFormattedAttributes($attributes);
    }

    public function setFilterCellAttributes($filterCellAttributes)
    {
        $this->filterCellAttributes = $filterCellAttributes;
        return $this;
    }

    public function getFilterCellAttributes()
    {
        $attributes = array_merge(
            $this->getPropertyValue($this->attributes),
            $this->getPropertyValue($this->filterCellAttributes)
        );
        return $this->getFormattedAttributes($attributes);
    }

    public function setDataCellAttributes($dataCellAttributes)
    {
        $this->dataCellAttributes = $dataCellAttributes;
        return $this;
    }

    public function getDataCellAttributes($row)
    {
        $attributes = array_merge(
            $this->getPropertyValue($this->attributes),
            $this->getPropertyValue($this->dataCellAttributes, $row)
        );
        return $this->getFormattedAttributes($attributes);
    }

    public function setFooterCellAttributes($footerCellAttributes)
    {
        $this->footerCellAttributes = $footerCellAttributes;
        return $this;
    }

    public function getFooterCellAttributes()
    {
        $attributes = array_merge(
            $this->getPropertyValue($this->attributes),
            $this->getPropertyValue($this->footerCellAttributes)
        );
        return $this->getFormattedAttributes($attributes);
    }

    public function setHeaderCell($headerCell)
    {
        $this->headerCell = $headerCell;
        return $this;
    }

    public function getHeaderCell()
    {
        $headerCellView = $this->getPropertyValue($this->headerCell);
        if ($headerCellView === false) {
            return false;
        }

        return view($headerCellView)
            ->with([
                'column' => $this,
            ] + $this->getHeaderCellWith());
    }

    public function setFilterCell($filterCell)
    {
        $this->filterCell = $filterCell;
        return $this;
    }

    public function getFilterCell()
    {
        $filterCellView = $this->getPropertyValue($this->filterCell);
        if ($filterCellView === false) {
            return false;
        }

        return view($filterCellView)
            ->with([
                'column' => $this,
            ] + $this->getFilterCellWith());
    }

    public function setDataCell($dataCell)
    {
        $this->dataCell = $dataCell;
        return $this;
    }

    public function getDataCell($row)
    {
        $dataCellView = $this->getPropertyValue($this->dataCell);
        if ($dataCellView === false) {
            return false;
        }

        return view($dataCellView)
            ->with([
                'column' => $this,
                'row' => $row,
            ] + $this->getDataCellWith($row));
    }

    public function setFooterCell($footerCell)
    {
        $this->footerCell = $footerCell;
        return $this;
    }

    public function getFooterCell()
    {
        $footerCellView = $this->getPropertyValue($this->filterCell);
        if ($footerCellView === false) {
            return false;
        }

        return view($footerCellView)
            ->with([
                'column' => $this,
            ] + $this->getFooterCellWith());
    }

    public function getEmptyHeaderCell()
    {
        $emptyHeaderCellView = $this->getPropertyValue($this->emptyHeaderCell);
        return view($emptyHeaderCellView)
            ->with([
                    'column' => $this,
                ] + $this->getHeaderCellWith());
    }

    public function setEmptyHeaderCell($emptyHeaderCell)
    {
        $this->emptyHeaderCell = $emptyHeaderCell;
        return $this;
    }

    public function getEmptyFilterCell()
    {
        $emptyFilterCellView = $this->getPropertyValue($this->emptyFilterCell);
        return view($emptyFilterCellView)
            ->with([
                'column' => $this,
            ] + $this->getFilterCellWith());
    }

    public function setEmptyFilterCell($emptyFilterCell)
    {
        $this->emptyFilterCell = $emptyFilterCell;
        return $this;
    }

    public function getEmptyDataCell($row)
    {
        $emptyDataCellView = $this->getPropertyValue($this->emptyDataCell);
        return view($emptyDataCellView)
            ->with([
                'column' => $this,
                'row' => $row,
            ] + $this->getDataCellWith($row));
    }

    public function setEmptyDataCell($emptyDataCell)
    {
        $this->emptyDataCell = $emptyDataCell;
        return $this;
    }

    public function getEmptyFooterCell()
    {
        $emptyFooterCellView = $this->getPropertyValue($this->emptyFooterCell);
        return view($emptyFooterCellView)
            ->with([
                'column' => $this,
            ] + $this->getFooterCellWith());
    }

    public function setEmptyFooterCell($emptyFooterCell)
    {
        $this->emptyFooterCell = $emptyFooterCell;
        return $this;
    }

    public function hasHeaderCell()
    {
        if ($this->getPropertyValue($this->headerCell)) {
            return true;
        }
        return false;
    }

    public function hasFilterCell()
    {
        if ($this->getPropertyValue($this->filterCell)) {
            return true;
        }
        return false;
    }

    public function hasDataCell()
    {
        if ($this->getPropertyValue($this->dataCell)) {
            return true;
        }
        return false;
    }

    public function hasFooterCell()
    {
        if ($this->getPropertyValue($this->footerCell)) {
            return true;
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
                    !preg_match("#^{$this->getElementPrefix()}-#", $name)
                ) || is_null($value)
            ) {
                unset($inputValues[$name]);
            }
        }

        return $inputValues;
    }
}
