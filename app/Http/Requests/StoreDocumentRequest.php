<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\File;

class StoreDocumentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'judul' => 'required|string|max:255',
            'kategori_document_id' => 'required|exists:kategori_documents,id',
            'cover' => [
                'nullable',
                File::image()
                    ->max(2048)
                    ->mimeTypes(['image/jpeg', 'image/png', 'image/jpg', 'image/webp']),
            ],
            'file' => [
                'required',
                File::types(['pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx'])
                    ->max(10240)
                    ->mimeTypes([
                        'application/pdf',
                        'application/msword',
                        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                        'application/vnd.ms-excel',
                        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                        'application/vnd.ms-powerpoint',
                        'application/vnd.openxmlformats-officedocument.presentationml.presentation',
                    ]),
            ],
            'lainnya' => 'nullable|json',
        ];
    }

    public function messages(): array
    {
        return [
            'judul.required' => 'Judul dokumen wajib diisi.',
            'kategori_document_id.required' => 'Kategori dokumen wajib dipilih.',
            'kategori_document_id.exists' => 'Kategori tidak valid.',
            'cover.image' => 'Cover harus berupa gambar.',
            'cover.mimes' => 'Format cover harus JPEG, PNG, JPG, atau WEBP.',
            'cover.max' => 'Ukuran cover maksimal 2MB.',
            'file.required' => 'File dokumen wajib diunggah.',
            'file.mimes' => 'Format file tidak valid. Hanya PDF, DOC, DOCX, XLS, XLSX, PPT, PPTX.',
            'file.max' => 'Ukuran file maksimal 10MB.',
            'file.mime_types' => 'Format file tidak valid.',
        ];
    }
}
