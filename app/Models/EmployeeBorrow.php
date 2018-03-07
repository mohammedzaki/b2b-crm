<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Events\EmployeeBorrowCreatedEvent;

/**
 * App\Models\EmployeeBorrow
 *
 * @property int $id
 * @property int $employee_id
 * @property float $amount
 * @property string|null $borrow_reason
 * @property string|null $pay_amount
 * @property int $is_active
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \App\Models\Employee $employee
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\EmployeeBorrowBilling[] $paidAmounts
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\EmployeeBorrowBilling[] $payAmounts
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\EmployeeBorrowBilling[] $unPaidAmounts
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\EmployeeBorrow whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\EmployeeBorrow whereBorrowReason($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\EmployeeBorrow whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\EmployeeBorrow whereEmployeeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\EmployeeBorrow whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\EmployeeBorrow whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\EmployeeBorrow wherePayAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\EmployeeBorrow whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class EmployeeBorrow extends Model {

    protected $fillable = [
        'employee_id',
        'amount',
        'borrow_reason',
        'pay_amount',
        'is_active'
    ];

    const ACTIVE = 1;
    const CLOSED = 2;

    protected $events = [
        'created' => EmployeeBorrowCreatedEvent::class,
    ];

    public function employee()
    {
        return $this->belongsTo('App\Models\Employee');
    }

    public function payAmounts()
    {
        return $this->hasMany(EmployeeBorrowBilling::class)->orderBy('due_date');
    }

    public function unPaidAmounts()
    {
        return $this->hasMany(EmployeeBorrowBilling::class)->where('paying_status', EmployeeBorrowBilling::UN_PAID);
    }

    public function paidAmounts()
    {
        return $this->hasMany(EmployeeBorrowBilling::class)->where('paying_status', EmployeeBorrowBilling::PAID);
    }

    public function totalPaid()
    {
        return $this->paidAmounts()->sum('paid_amount');
    }

    public function totalRemaining()
    {
        return $this->payAmounts()->where([
                    ['paying_status', '!=', EmployeeBorrowBilling::POSTPONED]
                ])->sum('pay_amount') - $this->totalPaid();
    }

}
