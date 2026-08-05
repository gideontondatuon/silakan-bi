<?php

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\Models\AuditLog;

use Illuminate\Http\Request;
use Illuminate\View\View;



class AuditLogController extends Controller
{


    public function index(
        Request $request
    ): View
    {


        $auditLog = AuditLog::with(
            'user'
        );



        if(
            $request->tanggal_mulai
        ){

            $auditLog->whereDate(
                'created_at',
                '>=',
                $request->tanggal_mulai
            );

        }



        if(
            $request->tanggal_selesai
        ){

            $auditLog->whereDate(
                'created_at',
                '<=',
                $request->tanggal_selesai
            );

        }



        if(
            $request->modul
        ){

            $auditLog->where(
                'modul',
                $request->modul
            );

        }



        $auditLog =
            $auditLog
            ->latest()
            ->paginate(10)
            ->withQueryString();



        return view(

            'admin.audit-log.index',

            compact(
                'auditLog'
            )

        );


    }


}