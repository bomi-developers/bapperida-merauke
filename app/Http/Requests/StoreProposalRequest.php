<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\File;

class StoreProposalRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nama' => 'required|string|max:255',
            'judul' => 'required|string|max:255',
            'latar_belakang' => 'required|min:100',
            'email' => 'required|email',
            'no_hp' => 'required|string|max:20',
            'file' => [
                'required',
                File::types(['pdf', 'doc', 'docx'])
                    ->max(2048)
                    ->mimeTypes([
                        'application/pdf',
                        'application/msword',
                        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                    ]),
            ],
            'g-recaptcha-response' => 'required|captcha',
            'ide_inovasi' => 'required|string',
            'tujuan_inovasi' => 'required|string',
            'target_perubahan' => 'required|string',
            'stakeholder' => 'required|string',
            'sdm' => 'required|string',
            'penerima_manfaat' => 'required|string',
            'kebaruan' => 'required|string',
            'deskripsi_ide' => 'required|string',
        ];
    }

    public function messages(): array
    {
        return [
            'nama.required' => 'Nama wajib diisi.',
            'judul.required' => 'Judul wajib diisi.',
            'latar_belakang.required' => 'Latar belakang wajib diisi.',
            'latar_belakang.min' => 'Latar belakang harus minimal 100 karakter.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'no_hp.required' => 'Nomor HP wajib diisi.',
            'file.required' => 'File proposal wajib diunggah.',
            'file.mimes' => 'File harus berformat PDF, DOC, atau DOCX.',
            'file.max' => 'Ukuran file maksimal 2MB.',
            'file.mime_types' => 'Format file tidak valid. Hanya PDF, DOC, atau DOCX.',
            'g-recaptcha-response.required' => 'Silakan verifikasi CAPTCHA terlebih dahulu.',
            'g-recaptcha-response.captcha' => 'Verifikasi CAPTCHA gagal, coba lagi.',
            'ide_inovasi.required' => 'Ide inovasi wajib diisi',
            'tujuan_inovasi.required' => 'Tujuan inovasi wajib diisi',
            'target_perubahan.required' => 'Target perubahan wajib diisi',
            'stakeholder.required' => 'Stakeholder wajib diisi',
            'sdm.required' => 'Sumber daya manusia wajib diisi',
            'penerima_manfaat.required' => 'Penerima manfaat wajib diisi',
            'kebaruan.required' => 'Kebaruan wajib diisi',
            'deskripsi_ide.required' => 'Deskripsi singkat ide wajib diisi',
        ];
    }
}
