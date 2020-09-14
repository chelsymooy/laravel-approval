<?php

namespace Chelsymooy\Approval\Traits;

use Chelsymooy\Approval\Models\Inbox;
use Illuminate\Validation\ValidationException;

trait HasApproval
{
    public function submimtted() {
        $user  = config()->get('auth.providers.users.model');
        return $this->belongsTo(new $user(), 'submitted_by');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     **/
    public function getStatusApprovalAttribute() {
        if($this->approvals){
            $lines      = array_column($this->approvals, 'is_approve');
            $is_wait    = in_array(null, $lines, true);
            $is_decline = in_array(false, $lines, true);

            if(!is_null($this->submitted_at) && $is_wait){
                return 'SUBMITTED';
            }elseif(!is_null($this->submitted_at) && $is_decline){
                return 'DECLINED';
            }elseif(!is_null($this->submitted_at) && !$is_decline && !$is_wait){
                return 'APPROVED';
            }
        }

        if(is_null($this->submitted_at)){
            return 'DRAFT';
        }
        return 'LOST_TRACK';
    }
}
