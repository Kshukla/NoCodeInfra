<?php

namespace App\Http\Requests\Frontend\Workbook;

use App\Http\Requests\Request;

/**
 * Class EditMenuRequest.
 */
class EditWorkbookRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return access()->allow('view-frontend');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //
        ];
    }
}
