<?php

namespace Chelsymooy\Approval\Observers;

use Eloquent as Model;
use Chelsymooy\Approval\Models\Inbox;

class SendToInboxFromTable
{
    //
    public function updating(Model $model)
    {
        if($model->isDirty('approvals')){

            $prev   = 0;
            foreach ($model->approvals as $k => $v) {
                if(!is_null($v['is_approve'])){
                    $prev++;
                }elseif(!is_null($v['is_approve']) && !$v['is_approve']){
                    return true;
                }
            }

            if(isset($model->approvals[$prev])){
                //SEND TO INBOX
                $user   = config()->get('auth.providers.users.model');
                $user   = $user::where('scopes', 'like', '%'.$model->approvals[$prev]['required_scopes'].'%')->first();

                //INBOX
                $inbox  = Inbox::where('ref_id', $model->id)->where('ref_type', get_class($model))->where('approved_by', $user->id)
                    ->wherenull('approved_by')->first();

                if(!$inbox){
                    $inbox  = new Inbox;
                }
                $inbox->title   = $model->submitted->name.' requested authorization';
                $inbox->ref_id  = $model->id;
                $inbox->ref_type= get_class($model);
                $inbox->approved_by  = $user->id;
                $inbox->save();
            }
        }
    }
}
