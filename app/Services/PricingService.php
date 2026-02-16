<?php

namespace App\Services;

use App\Models\FeeRuleModel;

class PricingService{
	protected $feeRuleModel;

	public function __construct(){
		$this->feeRuleModel = new FeeRuleModel();
	}

	public function CalculateFee($transactionType, $amount){
		$rule = $this->feeRuleModel
			->where('transaction_type', $transactionType)
			->where('min_amount <=', $amount)
			->where('max_amount >=', $amount)
			->first();

		if(!$rule){
			throw new \Exception("No fee rule configured.");
		}

		$percentage_fee = ($rule['percentage_fee'] / 100) * $amount;
		$fixed_fee = $rule['fixed_fee'];

		return round($percentage_fee + $fixed_fee, 2);
	}
}