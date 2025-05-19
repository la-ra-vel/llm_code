<?php

namespace App\Services\NewCase;

use App\Models\DocumentDetail;

class DocumentService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function getCaseDocuments($id)
    {
        $data = DocumentDetail::with([
            'user' => function ($query) {
                // You can specify columns for the related model here if needed
                $query->select('id', 'fname', 'lname'); // Example: Only select 'id' and 'name' columns from relation table
            }
        ])
            ->select('id', 'document_name', 'file', 'createdBy') // Specify the columns you need from the model
            ->where('client_case_pid', $id)
            ->orderBy('id', 'DESC')
            ->get();

        return $data;

    }
    /******************************************************************************/
    public function store($validatedData, $id = '')
    {
        if (isset($validatedData['file']) && !empty($validatedData['file'])) {
            // Store the file and get the file path

            $filePath = (new DocumentDetail)->storeFile($validatedData['file']);

            // Add the file path to the validated data array
            $validatedData['file'] = $filePath;
        }

        if ($id) {
            $data = DocumentDetail::find($id);
            if (isset($validatedData['file']) && !empty($validatedData['file'])) {
                unLinkFile('case_documents', $data->file);
            }
            $data->update($validatedData);
        } else {
            $data = DocumentDetail::create($validatedData);
        }

        return $data;
    }
    /******************************************************************************/
    public function findData($id = null)
    {
        $findData = DocumentDetail::find($id);
        return $findData;
    }
}
