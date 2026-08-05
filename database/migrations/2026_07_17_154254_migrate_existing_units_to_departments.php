<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;


return new class extends Migration
{

    public function up(): void
    {

        /*
        |--------------------------------------------------------------------------
        | Ambil unit unik dari users lama
        |--------------------------------------------------------------------------
        */

        $units = DB::table('users')
            ->select(
                'nama_unit',
                'kode_unit'
            )
            ->whereNotNull('nama_unit')
            ->groupBy(
                'nama_unit',
                'kode_unit'
            )
            ->get();



        foreach($units as $unit)
        {

            $departmentId = DB::table('departments')
                ->insertGetId([

                    'nama_unit' =>
                        $unit->nama_unit,


                    'kode_unit' =>
                        $unit->kode_unit,


                    'status' =>
                        'aktif',


                    'created_at' =>
                        now(),


                    'updated_at' =>
                        now(),

                ]);



            DB::table('users')
                ->where(
                    'nama_unit',
                    $unit->nama_unit
                )
                ->where(
                    'kode_unit',
                    $unit->kode_unit
                )
                ->update([

                    'department_id' =>
                        $departmentId

                ]);

        }

    }



    public function down(): void
    {

        DB::table('users')
            ->update([
                'department_id' => null
            ]);


        DB::table('departments')
            ->truncate();

    }

};