<?php

namespace App\Observers;

use App\Models\Client;

class ClientObserver
{
    /**
     * Handle the Client "created" event.
     */
    public function created(Client $client): void
    {
        //
    }

    /**
     * Handle the Client "updated" event.
     */
    public function updated(Client $client): void
    {

        if ($client->isDirty('status')) {
            if ($client->status === 'inactivexxx') {
                // Soft delete related court_details and their related fee_details and note_details
                $client->client_cases()->each(function ($clientCase) {
                    $clientCase->fee_details()->delete();
                    $clientCase->action_details()->delete();
                    $clientCase->payment_details()->delete();
                    $clientCase->document_details()->delete();
                    $clientCase->delete();
                });
            } elseif ($client->status === 'activexxx') {
                // Restore related court_details and their related fee_details and note_details
                $client->client_cases()->withTrashed()->each(function ($clientCase) {
                    $clientCase->restore();
                    $clientCase->fee_details()->withTrashed()->restore();
                    $clientCase->action_details()->withTrashed()->restore();
                    $clientCase->payment_details()->withTrashed()->restore();
                    $clientCase->document_details()->withTrashed()->restore();
                });
            }
        }

    }

    /**
     * Handle the Client "deleted" event.
     */
    public function deleted(Client $client): void
    {
        // $client->client_cases()->delete();
        $client->client_cases()->each(function ($clientCase) {
            $clientCase->fee_details()->delete();
            $clientCase->action_details()->delete();
            $clientCase->payment_details()->delete();
            $clientCase->document_details()->delete();
            $clientCase->delete();
        });
    }

    /**
     * Handle the Client "restored" event.
     */
    public function restored(Client $client): void
    {
        // $client->client_cases()->withTrashed()->restore();
        $client->client_cases()->withTrashed()->each(function ($clientCase) {
            $clientCase->restore();
            $clientCase->fee_details()->withTrashed()->restore();
            $clientCase->action_details()->withTrashed()->restore();
            $clientCase->payment_details()->withTrashed()->restore();
            $clientCase->document_details()->withTrashed()->restore();
        });
    }

    /**
     * Handle the Client "force deleted" event.
     */
    public function forceDeleted(Client $client): void
    {
        // $client->client_cases()->withTrashed()->forceDelete();
        $client->client_cases()->withTrashed()->each(function ($clientCase) {
            $clientCase->fee_details()->withTrashed()->forceDelete();
            $clientCase->action_details()->withTrashed()->forceDelete();
            $clientCase->payment_details()->withTrashed()->forceDelete();
            $clientCase->document_details()->withTrashed()->forceDelete();
            $clientCase->forceDelete();
        });
    }
}
