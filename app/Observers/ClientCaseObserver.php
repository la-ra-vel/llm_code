<?php

namespace App\Observers;

use App\Models\ClientCase;
use Illuminate\Support\Facades\File;

class ClientCaseObserver
{
    /**
     * Handle the ClientCase "created" event.
     */
    public function created(ClientCase $clientCase): void
    {
        //
    }

    /**
     * Handle the ClientCase "updated" event.
     */
    public function updated(ClientCase $clientCase): void
    {
        if ($clientCase->isDirty('status')) {
            if ($clientCase->status === 'closexxx') {
                // Soft delete related fee_details and note_details
                $clientCase->fee_details()->delete();
                $clientCase->action_details()->delete();
                $clientCase->payment_details()->delete();
                $clientCase->document_details()->delete();
                // Soft delete court_detail itself
                $clientCase->delete();
            } elseif ($clientCase->status === 'openxxx') {
                // Restore related fee_details and note_details
                $clientCase->fee_details()->withTrashed()->restore();
                $clientCase->action_details()->withTrashed()->restore();
                $clientCase->payment_details()->withTrashed()->restore();
                $clientCase->document_details()->withTrashed()->restore();
                // Restore court_detail itself
                $clientCase->restore();
            }
        }
    }

    /**
     * Handle the ClientCase "deleted" event.
     */
    public function deleted(ClientCase $clientCase): void
    {
        // Delete associated document files
        $this->deleteDocumentFiles($clientCase);

        $clientCase->fee_details()->delete();
        $clientCase->action_details()->delete();
        $clientCase->payment_details()->delete();
        $clientCase->document_details()->delete();
        // $clientCase->delete();
    }

    /**
     * Handle the ClientCase "restored" event.
     */
    public function restored(ClientCase $clientCase): void
    {
        $clientCase->fee_details()->withTrashed()->restore();
        $clientCase->action_details()->withTrashed()->restore();
        $clientCase->payment_details()->withTrashed()->restore();
        $clientCase->document_details()->withTrashed()->restore();
        // $clientCase->withTrashed()->restore();
    }

    /**
     * Handle the ClientCase "force deleted" event.
     */
    public function forceDeleted(ClientCase $clientCase): void
    {
        $clientCase->fee_details()->withTrashed()->forceDelete();
        $clientCase->action_details()->withTrashed()->forceDelete();
        $clientCase->payment_details()->withTrashed()->forceDelete();
        $clientCase->document_details()->withTrashed()->forceDelete();
        // $clientCase->withTrashed()->forceDelete();
    }

    protected function deleteDocumentFiles(ClientCase $clientCase): void
    {
        // echo "<pre>"; print_r($clientCase->document_details); exit;
        foreach ($clientCase->document_details as $document) {
            unLinkFile('case_documents',$document->file);
            // Assuming the file path is stored in the 'file_path' attribute
            // $fullPath = public_path('uploads/case_documents/'.$document->file);

            // if (File::exists($fullPath)) {
            //     File::delete($fullPath);
            // }
        }
    }
}
