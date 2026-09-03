<?php

namespace App\Http\Requests;


use App\Models\LayoutRuangan;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;


class StorePemesananRequest extends FormRequest
{


    public function authorize(): bool
    {
        return auth()->check();
    }

    protected function prepareForValidation(): void
    {
        if (empty($this->layout_ruangan_id)) {
            $this->merge([
                'layout_ruangan_id' => null,
            ]);
        }
    }



    public function rules(): array
    {

        return [


            'ruangan_id' => [

                'required',

                'exists:ruangan,id',

            ],



            'layout_ruangan_id' => [
                'nullable',
                'exists:layout_ruangan,id',
                function (
                    $attribute,
                    $value,
                    $fail
                ) {
                    if (!$value || !$this->ruangan_id) {
                        return;
                    }

                    $layout = LayoutRuangan::find($value);
                    if (!$layout) {
                        return;
                    }

                    // 1. Direct relationship check
                    if ($layout->ruangan_id && $layout->ruangan_id == $this->ruangan_id) {
                        return;
                    }

                    // 2. Pivot table relationship check
                    if ($layout->ruangans()->where('ruangan.id', $this->ruangan_id)->exists()) {
                        return;
                    }

                    // 3. Fallback check (if room has no specific layouts assigned, any layout is valid)
                    $ruangan = \App\Models\Ruangan::find($this->ruangan_id);
                    if ($ruangan) {
                        $hasPivot = $ruangan->layouts()->exists();
                        $hasDirect = LayoutRuangan::where('ruangan_id', $this->ruangan_id)->exists();
                        if (!$hasPivot && !$hasDirect) {
                            return;
                        }
                    }

                    $fail('Layout tidak sesuai dengan ruangan yang dipilih.');
                }
            ],

            'tanggal_kegiatan' => [
                'required',
                'date',
                'after_or_equal:today',
            ],

            'waktu_mulai' => [
                'required',
                'date_format:H:i',
                function ($attribute, $value, $fail) {
                    if (!$this->tanggal_kegiatan) {
                        return;
                    }
                    $nowMakassar = \Carbon\Carbon::now('Asia/Makassar');
                    $today = $nowMakassar->toDateString();
                    $currentTime = $nowMakassar->format('H:i');

                    if ($this->tanggal_kegiatan === $today && $value < $currentTime) {
                        $fail("Waktu mulai ({$value} WITA) tidak boleh menggunakan jam yang sudah terlewat untuk hari ini (waktu saat ini: {$currentTime} WITA).");
                    }
                },
            ],

            'waktu_selesai' => [
                'required',
                'date_format:H:i',
                'after:waktu_mulai',
            ],

            'judul_kegiatan' => [
                'required',
                'string',
                'max:150',
            ],



            'pic_kegiatan' => [

                'required',

                'string',

                'max:255',

            ],



            'jenis_pic' => [

                'required',

                Rule::in([
                    'Organik',
                    'Non Organik'
                ]),

            ],

            'no_wa_pic' => [

                'nullable',

                'string',

                'max:20',

            ],



            'jumlah_tamu' => [

                'required',

                'integer',

                'min:1',

            ],



            'keterangan_layout' => [

                'nullable',

                'string',

            ],



            'catatan_user' => [

                'nullable',

                'string',

            ],



            'file_disposisi' => [

                'required',

                'file',

                'mimes:pdf,jpg,jpeg,png',

                'max:5120',

            ],


        ];

    }



    public function messages(): array
    {

        return [

            'tanggal_kegiatan.after_or_equal' =>
                'Tanggal kegiatan tidak boleh sebelum hari ini.',


            'waktu_selesai.after' =>
                'Waktu selesai harus lebih besar dari waktu mulai.',


            'jumlah_tamu.min' =>
                'Jumlah tamu minimal 1 orang.',


            'file_disposisi.required' =>
                'Lembar disposisi / nota dinas wajib diunggah.',

            'file_disposisi.mimes' =>
                'Format file disposisi harus berupa PDF, JPG, atau PNG.',

            'file_disposisi.max' =>
                'Ukuran file disposisi tidak boleh melebihi 5MB.',

        ];

    }

}