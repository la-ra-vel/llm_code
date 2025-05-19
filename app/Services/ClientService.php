<?php
namespace App\Services;

use App\Models\Client;
use FlyingApesInc\DeepSearch\DeepSearch;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\Cache;


class ClientService
{
    public function getAllClients()
    {
        $users = Cache::remember('clients', 60, function () {
            return Client::orderBy('id','DESC')->get();
        });
        return $users;
    }
    /******************************************************************************/
    public function store($validatedData, $id = '')
    {
        $userData = [
            'title' => $validatedData['title'],
            'fname' => $validatedData['fname'],
            'lname' => $validatedData['lname'],
            'mobile' => $validatedData['mobile'],
            'wp_no' => $validatedData['wp_no'],
            'email' => $validatedData['email'],
            'address' => $validatedData['address'],
            'pincode' => $validatedData['pincode'],
            // 'visiting_date' => date('Y-m-d', strtotime($validatedData['visiting_date'])),
            'gender' => $validatedData['gender'],
            'occupation' => isset($validatedData['occupation']) ? $validatedData['occupation'] : null,
            'city' => $validatedData['city'],
        ];
        if ($id) {
            $client = Client::find($id);
            $client->update($userData);
        } else {
            $client = Client::create($userData);
        }
        return $client;
    }
    /******************************************************************************/
    public function updateStatus($data)
    {
        $client = Client::find($data['ID']);

        if ($client) {
            $client->status = $data['status'];
            $client->save();  // This will trigger the 'updated' event and call the observer
            Cache::forget('clients');
            return $client;
        }

    }
    /******************************************************************************/
    public function findClient($id = null)
    {
        $client = Client::find($id);
        return $client;
    }
    /******************************************************************************/
    public function searchInClients($search)
    {

        $searchSchema = [
            'fields' => ['fname', 'lname', 'mobile'],
            // Fields where you want to search in the main model

        ];
        $splitSearch = preg_split('/\s+/', trim($search));
        $query = Client::query();
        // Apply search criteria
        $query->where(function ($query) use ($searchSchema, $splitSearch) {
            foreach ($splitSearch as $word) {
                foreach ($searchSchema['fields'] as $field) {
                    $query->orWhere($field, 'LIKE', '%' . $word . '%');
                }
            }
        });

        // Perform the search using DeepSearch
        $data = DeepSearch::find($search, $query, $searchSchema)
            ->where('status', 'active')
            ->get();
        $returnData = [];
        // $data = Client::where(function($query) use ($search) {
        //     $query->where('fname', 'LIKE', "%" . $search . "%")
        //           ->orWhere('lname', 'LIKE', "%" . $search . "%")
        //           ->orWhere('mobile', 'LIKE', "%" . $search . "%");
        // })->get();
        if ($data) {
            foreach ($data as $key => $value) {
                $returnData[] = [
                    "value" => (string) $value->fname . ' ' . $value->lname,
                    "id" => $value->id,
                    "name" => (string) $value->fname . ' ' . $value->lname,
                    "mobile" => $value->mobile
                ];
            }
            return $returnData;
        }
    }
}
