<?php

namespace Cartelo\Models;

use App\Models\User;
use Payu\Models\Payment;
use Payu\Interfaces\PayuOrderInterface;
use Cartelo\Models\Area;
use Cartelo\Models\Restaurant;
use Cartelo\Models\OrderProduct;
use Database\Factories\OrderFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model implements PayuOrderInterface
{
	use HasFactory, SoftDeletes;

	protected $guarded = [];

	protected $dateFormat = 'Y-m-d H:i:s';

	protected static function newFactory()
	{
		return OrderFactory::new();
	}

	public function restaurant()
	{
		return $this->belongsTo(Restaurant::class);
	}

	public function products()
	{
		return $this->hasMany(OrderProduct::class);
	}

	function rewards()
	{
		return $this->hasMany(Reward::class);
	}

	function coupon()
	{
		return $this->hasOne(Coupon::class);
	}

	function area()
	{
		return $this->belongsTo(Area::class);
	}

	function user()
	{
		return $this->belongsTo(User::class);
	}

	function worker()
	{
		return $this->belongsTo(User::class, 'worker_id', 'id');
	}

	/* payu payments */

	function payments()
	{
		return $this->hasMany(Payment::class)->withTrashed();
	}

	function paidPayment()
	{
		return $this->hasOne(Payment::class)->where('status', 'COMPLETED')->withTrashed()->latest();
	}

	function orderId()
	{
		return $this->id;
	}

	function orderCost()
	{
		return $this->cost;
	}

	function orderFirstname()
	{
		return $this->firstname;
	}

	function orderLastname()
	{
		return $this->lastname;
	}

	function orderPhone()
	{
		return $this->mobile;
	}

	function orderEmail()
	{
		return $this->email;
	}

	protected function serializeDate(\DateTimeInterface $date)
	{
		return $date->format($this->dateFormat);
	}
}
