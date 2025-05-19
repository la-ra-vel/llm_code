<?php

namespace App\Services;

use App\Models\Quotation;
use App\Models\QuotationDescription;
use Illuminate\Support\Facades\Cache;

class QuotationService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }
    public function getAllData()
    {

        $data = Cache::remember('quotations', 60, function () {
            return Quotation::with([
                'quotation_description' => function ($query) {
                    $query->select('id', 'quotation_id', 'description', 'amount', 'date');
                }
            ])
                ->select('id', 'quotation_no', 'date', 'time', 'subject', 'client_name', 'client_mobile', 'client_address', 'createdBy', 'status')
                ->orderBy('id', 'DESC')
                ->get()
                ->map(function ($quotation) {
                    // Add the edit and delete URLs to each quotation_description
                    $quotation->quotation_description = $quotation->quotation_description->map(function ($description) {
                        $description->editUrl = route('store.quotation.description', $description->id);
                        $description->deleteUrl = route('delete.quotation.description', $description->id);
                        return $description;
                    });
                    return $quotation;
                });
        });

        return $data;

    }
    /******************************************************************************/
    public function store($validatedData, $id = '')
    {
        if (!isset($validatedData['time'])) {
            $validatedData['time'] = date('H:i:s');
        }
        if ($id) {
            $data = Quotation::find($id);
            $data->update($validatedData);
        } else {
            $data = Quotation::create($validatedData);
        }
        return $data;
    }
    /******************************************************************************/
    public function findData($id = null)
    {
        $findData = Quotation::with([
            'quotation_description' => function ($query) {
                $query->select('id', 'quotation_id', 'description', 'amount', 'date');
            }
        ])
            ->select('id', 'quotation_no', 'date', 'time', 'subject', 'client_name', 'client_mobile', 'client_address', 'createdBy', 'status')
            ->orderBy('id', 'DESC')
            ->find($id);
        return $findData;
    }
    /******************************************************************************/
    public function storeQuotationDesc($validatedData, $id = '')
    {
        if (!isset($validatedData['date'])) {
            $validatedData['date'] = date('H:i:s');
        }
        if ($id) {
            $data = QuotationDescription::find($id);
            $data->update($validatedData);
        } else {
            $data = QuotationDescription::create($validatedData);
        }
        return $data;
    }
    /******************************************************************************/
    public function findQuotationDescriptionsByQuotationID($id = null)
    {
        $findData = QuotationDescription::where('quotation_id', $id)->get();
        if ($findData) {
            // Add the edit and delete URLs to the found data
            $findData->map(function ($description) {
                $description->editUrl = route('store.quotation.description', $description->id);
                $description->deleteUrl = route('delete.quotation.description', $description->id);
                return $description;
            });
        }
        return $findData;
    }

    /******************************************************************************/
    public function findQuotationDescriptions($id = null)
    {
        $findData = QuotationDescription::find($id);

        if ($findData) {
            // Add the edit and delete URLs to the found data
            $findData->editUrl = route('store.quotation.description', $findData->id);
            $findData->deleteUrl = route('delete.quotation.description', $findData->id);
        }

        return $findData;
    }
    /******************************************************************************/
    public function updateStatus($data)
    {
        $findData = Quotation::find($data['ID']);

        if ($findData) {
            $findData->status = $data['status'];
            $findData->save();  // This will trigger the 'updated' event and call the observer
            Cache::forget('quotations');
            return $findData;
        }

    }
}
