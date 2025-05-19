<?php

namespace App\Observers;

use App\Models\Quotation;
use App\Models\QuotationDescription;

class QuotationObserver
{
    /**
     * Handle the Quotation "created" event.
     */
    public function created(Quotation $quotation): void
    {
        //
    }

    /**
     * Handle the Quotation "updated" event.
     */
    public function updated(Quotation $quotation): void
    {
        //
    }

    /**
     * Handle the Quotation "deleted" event.
     */
    public function deleted(Quotation $quotation): void
    {
        //
    }

    /**
     * Handle the Quotation "restored" event.
     */
    public function restored(Quotation $quotation): void
    {
        //
    }

    /**
     * Handle the Quotation "force deleted" event.
     */
    public function forceDeleted(Quotation $quotation): void
    {
        //
    }

    public function deleting(Quotation $quotation)
    {
        // Delete related quotation descriptions
        QuotationDescription::where('quotation_id', $quotation->id)->delete();
    }
}
