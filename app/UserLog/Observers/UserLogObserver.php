<?php
/**
 * Created by PhpStorm.
 * User: mohamedzaki
 * Date: 09/10/2021
 * Time: 10:30 PM
 */

namespace App\UserLog\Observers;

use App\UserLog\Models\EntityName;
use App\UserLog\Models\LogAction;
use App\UserLog\Models\UserLog;
use Illuminate\Database\Eloquent\Model;

class UserLogObserver
{
    /**
     * @param $model Model
     */
    public function created($model)
    {
        $this->addNewLog($model->getTable(), LogAction::insert, $model->id);
    }

    /**
     * @param $model Model
     */
    public function updated($model)
    {
        $this->addNewLog($model->getTable(), LogAction::update, $model->id, json_encode($model->getDirty()));
    }

    /**
     * @param $model Model
     */
    public function deleted($model)
    {
        $this->addNewLog($model->getTable(), LogAction::delete, $model->id);
    }

    /**
     * @param $model Model
     */
    public function restored($model)
    {
        $this->addNewLog($model->getTable(), LogAction::restored, $model->id);
    }

    public function addNewLog($entityName, $actionId, $rowId, $logData = null) {
        $entityId = EntityName::where('name', $entityName)->first()->id;
        UserLog::create([
            'entity_id' => $entityId,
            'action_id' => $actionId,
            'row_id'    => $rowId,
            'user_id'   => \Auth::user()->id,
            'log_data'  => $logData
        ]);
    }
}