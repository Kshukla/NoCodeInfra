<?php

namespace App\Repositories\Frontend\Workbook;

use App\Exceptions\GeneralException;
use App\Models\Workbook\Workbook;
use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Model;

/**
 * Class MenuRepository.
 */
class WorkbookRepository extends BaseRepository
{
    /**
     * Associated Repository Model.
     */
    const MODEL = Workbook::class;

    /**
     * @return mixed
     */
    public function getForDataTable()
    {
        return $this->query()
            ->select([
                config('access.workbooks_table').'.id',
                config('access.workbooks_table').'.name',
                config('access.workbooks_table').'.type',
                config('access.workbooks_table').'.created_at',
                config('access.workbooks_table').'.updated_at',
            ])->where('created_by', access()->id())->get();
    }

    /**
     * @param array $input
     *
     * @throws \App\Exceptions\GeneralException
     *
     * @return bool
     */
    public function create(array $input)
    {
        if ($this->query()->where('name', $input['name'])->first()) {
            throw new GeneralException(trans('exceptions.frontend.workbook.already_exists'));
        }
        $input['created_by'] = access()->id();

        if (Workbook::create($input)) {
            return true;
        }

        throw new GeneralException(trans('exceptions.frontend.workbook.create_error'));
    }

    /**
     * @param \App\Models\Workbook\Workbook $workbook
     * @param  $input
     *
     * @throws \App\Exceptions\GeneralException
     *
     * return bool
     */
    public function update(Workbook $workbook, array $input)
    {
        if ($this->query()->where('name', $input['name'])->where('id', '!=', $workbook->id)->first()) {
            throw new GeneralException(trans('exceptions.frontend.workbook.already_exists'));
        }

        $input['updated_by'] = access()->id();

        if ($workbook->update($input)) {
            return true;
        }

        throw new GeneralException(trans('exceptions.frontend.workbook.update_error'));
    }

    /**
     * @param \App\Models\Workbook\Workbook $workbook
     *
     * @throws \App\Exceptions\GeneralException
     *
     * @return bool
     */
    public function delete(Workbook $workbook)
    {
        if ($workbook->delete()) {
            return true;
        }

        throw new GeneralException(trans('exceptions.frontend.workbook.delete_error'));
    }
}
