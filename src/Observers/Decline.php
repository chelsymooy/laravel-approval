<?php

namespace Chelsymooy\Approval\Observers;

use Chelsymooy\Approval\Models\Inbox;

class Decline
{
    /**
     * Handle the Inbox "saved" event.
     *
     * @param  \Chelsymooy\Approval\Models\Inbox  $Inbox
     * @return void
     */
    public function deleted(Inbox $inb)
    {
        $appr   = $inb->ref->approvals;
        $flag   = false;
        foreach ($appr as $k => $v) {
            if(is_null($v['is_approve']) && !$flag){
                $appr[$k]['is_approve']     = false;
                $appr[$k]['authorized_by']  = $inb->approved_by;
                $flag = true;
            }
        }
        $inb->ref->approvals    = $appr;
        $inb->ref->save();
    }
}
