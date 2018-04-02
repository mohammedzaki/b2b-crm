<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use DB;

/**
 * App\Models\Client
 *
 * @property int $id
 * @property string $name
 * @property string $address
 * @property string|null $telephone
 * @property string $mobile
 * @property int|null $referral_id
 * @property float|null $referral_percentage
 * @property string $credit_limit
 * @property int $is_client_company
 * @property \Carbon\Carbon|null $deleted_at
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\ClientProcess[] $closedProcess
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\ClientProcess[] $invoiceProcesses
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\ClientProcess[] $openProcess
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\ClientProcess[] $processes
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\ClientProcess[] $unInvoiceProcesses
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Client onlyTrashed()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Client whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Client whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Client whereCreditLimit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Client whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Client whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Client whereIsClientCompany($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Client whereMobile($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Client whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Client whereReferralId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Client whereReferralPercentage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Client whereTelephone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Client whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Client withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Client withoutTrashed()
 * @mixin \Eloquent
 */
class Client extends Model {

    use SoftDeletes;

    protected $dates = ['deleted_at'];
    protected $fillable = [
        'name',
        'address',
        'telephone',
        'mobile',
        'referral_id',
        'referral_percentage',
        'credit_limit',
        'is_client_company'
    ];

    public function processes() {
        return $this->hasMany(ClientProcess::class);
    }

    public function unInvoiceProcesses() {
        return $this->hasMany(ClientProcess::class)->where([
                    ['invoice_billed', '=', ClientProcess::invoiceUnBilled],
                    ['require_invoice', '=', TRUE],
        ]);
    }

    public function invoiceProcesses() {
        return $this->hasMany(ClientProcess::class)->where([
                    //['invoice_billed', '=', ClientProcess::invoiceUnBilled],
                    ['require_invoice', '=', TRUE],
        ]);
    }

    public function closedProcess() {
        return $this->hasMany(ClientProcess::class)->where('status', ClientProcess::statusClosed);
    }

    public function openProcess() {
        return $this->hasMany(ClientProcess::class)->where('status', ClientProcess::statusOpened);
    }

    public function hasOpenProcess() {
        $client = Client::join('client_processes', 'client_processes.client_id', '=', 'clients.id')
                ->select('clients.*')->where([
                    ['client_processes.status', '=', ClientProcess::statusOpened],
                    ['clients.id', '=', $this->id]])
                ->distinct()
                ->get();

        if ($client->count() > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function hasClosedProcess() {
        $client = Client::join('client_processes', 'client_processes.client_id', '=', 'clients.id')
                ->select('clients.*')->where([
                    ['client_processes.status', '=', ClientProcess::statusClosed],
                    ['clients.id', '=', $this->id]])
                ->distinct()
                ->get();

        if ($client->count() > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public static function allHasOpenProcess() {
        $clients = Client::join('client_processes', 'client_processes.client_id', '=', 'clients.id')
                ->select('clients.*')->where('client_processes.status', ClientProcess::statusOpened)
                ->distinct()
                ->get();
        return $clients;
    }

    public static function allHasUnBilledInvoiceProcess() {
        $clients = Client::join('client_processes', 'client_processes.client_id', '=', 'clients.id')
                ->select('clients.*')->where([
                    //['client_processes.status', ClientProcess::statusOpened],
                    ['client_processes.invoice_billed', '=', ClientProcess::invoiceUnBilled],
                    ['client_processes.require_invoice', '=', TRUE],
                ])
                ->distinct()
                ->get();
        return $clients;
    }

    public static function allHasInvoiceProcess() {
        $clients = Client::join('client_processes', 'client_processes.client_id', '=', 'clients.id')
                ->select('clients.*')->where([
                    //['client_processes.status', ClientProcess::statusOpened],
                    //['client_processes.invoice_billed', '=', ClientProcess::invoiceUnBilled],
                    ['client_processes.require_invoice', '=', TRUE],
                ])
                ->distinct()
                ->get();
        return $clients;
    }

    public function getTotalPaid() {
        return DB::table('clients')
                        ->join('client_processes', 'client_processes.client_id', '=', 'clients.id')
                        ->join('deposit_withdraws', 'client_processes.id', '=', 'deposit_withdraws.cbo_processes')
                        ->join('deposit_withdraws as dw', 'clients.id', '=', 'deposit_withdraws.client_id')
                        ->distinct()
                        ->where([
                            ['clients.id', '=', $this->id]
                        ])
                        ->select(['deposit_withdraws.depositValue', 'deposit_withdraws.cbo_processes'])
                        ->get()->sum('depositValue');
    }

    public function getTotalRemaining() {
        return $this->getTotalDeal() - $this->getTotalPaid();
    }

    public function getTotalDeal() {
        return $this->processes()->sum('total_price_taxes');
    }

}
