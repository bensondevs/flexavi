<?php

namespace App\Services\Template\Adapters;

use App\Models\WorkContract\WorkContract;

class WorkContractAdapter
{
    /**
     * Get work contract date
     *
     * @param string $workContractId
     * @return string
     */
    public function getDate(string $workContractId): string
    {
        return WorkContract::find($workContractId)->created_at->format('Y-m-d');
    }

    /**
     * Get current date
     *
     * @return string
     */
    public function getCurrentDate(): string
    {
        return now()->format('Y-m-d');
    }

    /**
     * Get work contract total amount excluding tax
     *
     * @param string $workContractId
     * @return string
     */
    public function getTotalAmountExcludingTax(string $workContractId): string
    {
        return WorkContract::find($workContractId)->total_amount_excluding_tax;
    }

    /**
     * Get work contract total amount excluding tax
     *
     * @param string $workContractId
     * @return string
     */
    public function getTotalAmount(string $workContractId): string
    {
        return WorkContract::find($workContractId)->total_amount;
    }

    /**
     * Get work contract total amount excluding tax
     *
     * @param string $workContractId
     * @return string
     */
    public function getFormattedTotalAmount(string $workContractId): string
    {
        return WorkContract::find($workContractId)->formatted_total_amount;
    }

    /**
     * Get work contract amount
     *
     * @param string $workContractId
     * @return string
     */
    public function getAmount(string $workContractId): string
    {
        return WorkContract::find($workContractId)->amount;
    }

    /**
     * Get work contract amount
     *
     * @param string $workContractId
     * @return string
     */
    public function getFormattedAmount(string $workContractId): string
    {
        return WorkContract::find($workContractId)->formatted_amount;
    }

    /**
     * Get work contract total amount excluding tax
     *
     * @param string $workContractId
     * @return string
     */
    public function getFormattedTotalAmountExcludingTax(string $workContractId): string
    {
        return WorkContract::find($workContractId)->formatted_total_amount_excluding_tax;
    }

    /**
     * Get work contract tax percentage
     *
     * @param string $workContractId
     * @return string
     */
    public function getTaxPercentage(string $workContractId): string
    {
        return WorkContract::find($workContractId)->tax_percentage;
    }

    /**
     * Get work contract tax percentage
     *
     * @param string $workContractId
     * @return string
     */
    public function getFormattedTaxPercentage(string $workContractId): string
    {
        return WorkContract::find($workContractId)->formatted_tax_percentage;
    }

    /**
     * Get work contract tax amount
     *
     * @param string $workContractId
     * @return string
     */
    public function getTaxAmount(string $workContractId): string
    {
        return WorkContract::find($workContractId)->tax_amount;
    }

    /**
     * Get work contract formatted tax amount
     *
     * @param string $workContractId
     * @return string
     */
    public function getFormattedTaxAmount(string $workContractId): string
    {
        return WorkContract::find($workContractId)->formatted_tax_amount;
    }
}
