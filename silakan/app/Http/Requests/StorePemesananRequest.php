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



    public function rules(): array
    {

        return [


            'ruangan_id' => [

                'required',

                'exists:ruangan,id',

            ],



            'layout_ruangan_id' => [

                'required',

                'exists:layout_ruangan,id',


                function (
                    $attribute,
                    $value,
                    $fail
                ) {


                    $layout = LayoutRuangan::find(
                        $value
                    );


                    if (!$layout) {

                        return;

                    }



                    if (
                        $layout->ruangan_id
                        !=
                        $this->ruangan_id
                    ) {


                        $fail(
                            'Layout tidak sesuai dengan ruangan yang dipilih.'
                        );

                    }


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

            ],



            'waktu_selesai' => [

                'required',

                'date_format:H:i',

                'after:waktu_mulai',

            ],



            'judul_kegiatan' => [

                'required',

                'string',

                'max:255',

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

        ];

    }

}