<?php

namespace Chelsymooy\Approval\Models;

use DB, Carbon\Carbon;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Inbox
 * @package Chelsymooy\Approval\Models
 * @version May 2, 2020, 1:26 pm UTC
 *
 */
class Inbox extends Model
{
    use SoftDeletes;
    
    // --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
    // TRAITS
    // --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------

    // --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
    // VARIABLES
    // --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
    protected $fillable = [
        'title',
        'ref_id',
        'ref_type',
        'approved_by',
        'approved_at',
    ];

    public $timestamps = true;

    protected $hidden = [
    ];

    protected $casts = [
    ];

    protected $dates = [
        'deleted_at',
        'approved_at',
    ];

    protected $touches = [
    ];

    protected $observables = [
    ];

    protected $appends = [
        'render'
    ];

    // --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
    // CONFIGURATIONS
    // --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------

    // --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
    // BOOT
    // --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------

    // --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
    // CONSTRUCT
    // --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
    
    // --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
    // RELATIONSHIP
    // --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
    public function ref() {
        return $this->morphTo();
    }

    public function approver() {
        $user  = config()->get('auth.providers.users.model');
        return $this->belongsTo(new $user(), 'approved_by');
    }
    // --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
    // BOOT
    // --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------

    // --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
    // STATIC FUNCTION
    // --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------

    // --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
    // FUNCTION
    // --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
    public function getRules()
    {
        $user  = config()->get('auth.providers.users.model');

        $rules['title']         = ['required', 'string'];
        $rules['ref_id']        = ['required', 'int'];
        $rules['ref_type']      = ['required', 'string'];

        $rules['approved_by']   = ['nullable', 'exists:' . app()->make(new $user)->getTable() . ',id'];
        $rules['approved_at']   = ['nullable', 'date'];

        return $rules;
    }
    
    // --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
    // ACCESSOR
    // --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
    public function getRenderAttribute($val){
        $data = '';
        foreach ($this->ref->lines as $v) {
            if(isset($v['description'])) {
                if(!isset($v['amount'])){
                    $data   = $data.'
                        <div class="row" style="font-size:12px !important;">
                            <div class="col">'.$v['description'].' ('.number_format($v['qty'], 2).' '.$v['unit'].')</div>
                            <div class="col" style="text-align:right !important;">IDR '.number_format($v['qty'] * ($v['price'] - $v['discount'])).'</div>
                        </div>';
                }else{
                    $data   = $data.'
                        <div class="row" style="font-size:12px !important;">
                            <div class="col">'.$v['description'].'</div>
                            <div class="col" style="text-align:right !important;">IDR '.number_format($v['amount']).'</div>
                        </div>';
                }
            }
        }
        return $data;
    }

    // --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
    // MUTATOR
    // --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------

    // --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
    // QUERY
    // --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
}
