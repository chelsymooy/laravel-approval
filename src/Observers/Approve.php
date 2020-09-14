<?php

namespace Chelsymooy\Approval\Observers;

use Chelsymooy\Approval\Models\Inbox;

class Approve
{
    /**
     * Handle the Inbox "saved" event.
     *
     * @param  \Chelsymooy\Approval\Models\Inbox  $Inbox
     * @return void
     */
    public function saved(Inbox $inb)
    {
        $appr   = $inb->ref->approvals;
        $flag   = false;
        foreach ($appr as $k => $v) {
            if(is_null($v['is_approve']) && !$flag){
                $appr[$k]['is_approve']     = true;
                $appr[$k]['authorized_by']  = $inb->approved_by;
                $flag = true;
            }
        }
        $inb->ref->approvals    = $appr;
        $inb->ref->save();
    }
}
