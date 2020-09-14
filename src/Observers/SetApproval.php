<?php

namespace Chelsymooy\Approval\Observers;

use Eloquent as Model;

class SetApproval
{
    //
    public function saving(Model $model)
    {
        if(is_null($model->approvals) && !is_null($model->submitted_at)){
            $model->approvals = [
                ['required_scopes' => 'CONFIRMED.0', 'authorized_by' => null, 'is_approve' => null],
                ['required_scopes' => 'APPROVAL.0', 'authorized_by' => null, 'is_approve' => null],
            ];
        }
    }
}
