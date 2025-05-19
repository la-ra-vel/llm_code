<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Database\Eloquent\SoftDeletes;

class DocumentDetail extends Model
{
    use HasFactory;
    protected $fillable = [
        'client_case_pid',
        'document_name',
        'file',
        'createdBy'
    ];

    public function client_case()
    {
        return $this->belongsTo(ClientCase::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'createdBy');
    }

    // public function storeFile(UploadedFile $file)
    // {
    //     // Generate a unique filename
    //     $filename = $file->getClientOriginalName();
    //     $destinationPath = public_path('uploads/case_documents');

    //     // Store the file
    //     // $filePath = $file->storeAs($directory, $filename, 'public');
    //     $file->move($destinationPath, $filename);

    //     return $filename;
    // }

    public function storeFile(UploadedFile $file)
    {
        // Get the original filename
        $originalFilename = $file->getClientOriginalName();

        // Replace '#' with '_' in the filename
        $sanitizedFilename = str_replace('#', '_', $originalFilename);

        // Define the destination path
        $destinationPath = public_path('uploads/case_documents');

        // Move the file to the destination with the sanitized filename
        $file->move($destinationPath, $sanitizedFilename);

        // Return the sanitized filename
        return $sanitizedFilename;
    }
}
