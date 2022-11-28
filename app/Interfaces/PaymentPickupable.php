<?php

namespace App\Interfaces;

interface PaymentPickupable
{
	/**
	 * Get which columns is the target of payment
	 * This selected column will be the column that become
	 * target of substraction when payment is done
	 *
	 * @return string
	 */
	public function getPayableColumnAttibute();

	/**
	 * Get amount that should be paid
	 *
	 * @return float
	 */
	public function getShouldBePaidAmountAttribute();

	/**
	 * Set added paid amount after the payment
	 *
	 * @param float  $amount
	 * @return void
	 */
	public function setAddedPaidAmountAttribute(float $amount);
}
