<?php

namespace sammaye\Grid;

use Illuminate\Database\Eloquent\SoftDeletes;

class ActionColumn extends Column
{
    protected $editButtonCaption;

    protected $viewButtonCaption;

    protected $deleteButtonCaption;

    protected $restoreButtonCaption;

    protected $editButtonUrl;

    protected $viewButtonUrl;

    protected $deleteButtonUrl;

    protected $restoreButtonUrl;

    protected $showEditButton;

    protected $showViewButton;

    protected $showDeleteButton;

    protected $showRestoreButton;

    public function __construct($name, $options = [])
    {
        parent::__construct($name);

        $options += [
            'label' => false,
            'sortable' => false,
            'headerCell' => false,
            'filterCell' => false,
            'editButtonCaption' => __('Edit'),
            'viewButtonCaption' => __('View'),
            'deleteButtonCaption' => __('Delete'),
            'restoreButtonCaption' => __('Restore'),
            'showEditButton' => false,
            'showViewButton' => false,
            'showDeleteButton' => false,
            'showRestoreButton' => false,
            'dataCell' => 'grid::dataCell.action',
        ];

        foreach ($options as $k => $v) {
            $this->{'set' . ucfirst($k)}($v);
        }
    }

    public function setEditButton($url, $caption = null)
    {
        if ($url !== false) {
            $this->setShowEditButton(true);
            $this->setEditButtonUrl($url);

            if ($caption) {
                $this->setEditButtonCaption($caption);
            }
        } else {
            $this->setShowEditButton(false);
        }
        return $this;
    }

    public function setViewButton($url, $caption = null)
    {
        if ($url !== false) {
            $this->setShowViewButton(true);
            $this->setViewButtonUrl($url);

            if ($caption) {
                $this->setViewButtonCaption($caption);
            }
        } else {
            $this->setShowViewButton(false);
        }
        return $this;
    }

    public function setDeleteButton($url, $caption = null)
    {
        if ($url !== false) {
            $this->setShowDeleteButton(true);
            $this->setDeleteButtonUrl($url);

            if ($caption) {
                $this->setDeleteButtonCaption($caption);
            }
        } else {
            $this->setShowDeleteButton(false);
        }
        return $this;
    }

    public function setRestoreButton($url, $caption = null)
    {
        if ($url !== false) {
            $this->setShowRestoreButton(true);
            $this->setRestoreButtonUrl($url);

            if ($caption) {
                $this->setRestoreButtonCaption($caption);
            }
        } else {
            $this->setShowRestoreButton(false);
        }
        return $this;
    }

    public function getEditButtonCaption($row)
    {
        return $this->getPropertyValue($this->editButtonCaption, $row);
    }

    public function setEditButtonCaption($editButtonCaption)
    {
        $this->editButtonCaption = $editButtonCaption;
        return $this;
    }

    public function getViewButtonCaption($row)
    {
        return $this->getPropertyValue($this->viewButtonCaption, $row);
    }

    public function setViewButtonCaption($viewButtonCaption)
    {
        $this->viewButtonCaption = $viewButtonCaption;
        return $this;
    }

    public function getDeleteButtonCaption($row)
    {
        return $this->getPropertyValue($this->deleteButtonCaption, $row);
    }

    public function setDeleteButtonCaption($deleteButtonCaption)
    {
        $this->deleteButtonCaption = $deleteButtonCaption;
        return $this;
    }

    public function getRestoreButtonCaption($row)
    {
        return $this->getPropertyValue($this->restoreButtonCaption, $row);
    }

    public function setRestoreButtonCaption($restoreButtonCaption)
    {
        $this->restoreButtonCaption = $restoreButtonCaption;
        return $this;
    }

    public function getEditButtonUrl($row)
    {
        return $this->getPropertyValue($this->editButtonUrl, $row);
    }

    public function setEditButtonUrl($editButtonUrl)
    {
        $this->editButtonUrl = $editButtonUrl;
        return $this;
    }

    public function getViewButtonUrl($row)
    {
        return $this->getPropertyValue($this->viewButtonUrl, $row);
    }

    public function setViewButtonUrl($viewButtonUrl)
    {
        $this->viewButtonUrl = $viewButtonUrl;
        return $this;
    }

    public function getDeleteButtonUrl($row)
    {
        return $this->getPropertyValue($this->deleteButtonUrl, $row);
    }

    public function setDeleteButtonUrl($deleteButtonUrl)
    {
        $this->deleteButtonUrl = $deleteButtonUrl;
        return $this;
    }

    public function getRestoreButtonUrl($row)
    {
        return $this->getPropertyValue($this->restoreButtonUrl, $row);
    }

    public function setRestoreButtonUrl($restoreButtonUrl)
    {
        $this->restoreButtonUrl = $restoreButtonUrl;
        return $this;
    }

    public function getShowEditButton($row)
    {
        return $this->getPropertyValue($this->showEditButton, $row);
    }

    public function setShowEditButton($showEditButton)
    {
        $this->showEditButton = $showEditButton;
        return $this;
    }

    public function getShowViewButton($row)
    {
        return $this->getPropertyValue($this->showViewButton, $row);
    }

    public function setShowViewButton($showViewButton)
    {
        $this->showViewButton = $showViewButton;
        return $this;
    }

    public function getShowDeleteButton($row)
    {
        return $this->getPropertyValue($this->showDeleteButton, $row);
    }

    public function setShowDeleteButton($showDeleteButton)
    {
        $this->showDeleteButton = $showDeleteButton;
        return $this;
    }

    public function getShowRestoreButton($row)
    {
        return $this->getPropertyValue($this->showRestoreButton, $row) &&
            in_array(
                SoftDeletes::class,
                class_uses($row),
                true
            ) &&
            $row->trashed();
    }

    public function setShowRestoreButton($showRestoreButton)
    {
        $this->showRestoreButton = $showRestoreButton;
        return $this;
    }
}
