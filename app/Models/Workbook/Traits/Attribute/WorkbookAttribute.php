<?php

namespace App\Models\Workbook\Traits\Attribute;

/**
 * Class MenuAttribute.
 */
trait WorkbookAttribute
{
    /**
     * @return string
     */
    public function getActionButtonsAttribute()
    {
        return '<div class="btn-group action-btn">
                    '.$this->getEditButtonAttribute('view-frontend', 'frontend.workbook.edit').'
                </div>';
				//'.$this->getDeleteButtonAttribute('view-frontend', 'frontend.workbook.destroy').'
    }
	
    public function getDownloadButtonAttribute()
    {
            return '<a class="btn btn-flat btn-default" href="'.route('frontend.workbook.download', $this).'">
                    <i data-toggle="tooltip" data-placement="top" title="Edit" class="fa fa-download"></i>
                </a>';
    }	
    public function getYamlDownloadButtonAttribute()
    {
            return '<a class="btn btn-flat btn-default" href="'.route('frontend.workbook.yamldownload', $this).'">
                    <i data-toggle="tooltip" data-placement="top" title="Edit" class="fa fa-download"></i>
                </a>';
    }		
}
