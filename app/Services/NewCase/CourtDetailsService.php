<?php

namespace App\Services\NewCase;
use App\Models\ClientCase;

class CourtDetailsService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {

    }
    /******************************************************************************/
    public function store($validatedData, $id = '')
    {
        // echo "<pre>"; print_r($id); exit;
        if ($id) {
            $data = ClientCase::find($id);
            $data->update($validatedData);
        } else {
            $data = ClientCase::create($validatedData);
        }
        return $data;
    }
}
