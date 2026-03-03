<?php

namespace App\Services;

use App\Models\FeeRuleModel;

class PricingService{
	protected $feeRuleModel;

	public function __construct(){
		$this->feeRuleModel = new FeeRuleModel();
	}

	public function CalculateFee($transactionType, $location){
		$rule = $this->feeRuleModel
			->where('transaction_type', $transactionType)
			->where('location', $location)
			->first();

		if(!$rule){
			throw new \Exception("No fee rule configured for this location.");
		}

		return (float) $rule['fixed_fee'];
	}
}
