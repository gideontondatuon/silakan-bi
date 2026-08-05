<!DOCTYPE html>

<html>

<head>

<meta charset="utf-8">


<title>
Laporan Penggunaan Ruangan
</title>


<style>

body {

    font-family: Arial, sans-serif;

    font-size:12px;

}


.header {

    text-align:center;

    margin-bottom:20px;

}


.header h1 {

    font-size:18px;

    margin:0;

}


.header p {

    margin:5px 0;

}


.info {

    margin-bottom:20px;

}


.summary {

    width:100%;

    margin-bottom:20px;

}


.summary td {

    border:1px solid #ddd;

    padding:10px;

}


table {

    width:100%;

    border-collapse:collapse;

}


table th {

    background:#005baa;

    color:white;

    padding:8px;

}


table td {

    border:1px solid #ddd;

    padding:8px;

}


.footer {

    margin-top:30px;

    text-align:right;

}


</style>


</head>



<body>



<div class="header">


<h1>
SISTEM INFORMASI LAYANAN KANTOR
</h1>


<p>
Laporan Penggunaan Ruangan
</p>


</div>




<div class="info">


<strong>
Periode:
</strong>


{{ request('tanggal_mulai')
?
date(
'd-m-Y',
strtotime(request('tanggal_mulai'))
)
:
'-'
}}


s/d


{{ request('tanggal_selesai')
?
date(
'd-m-Y',
strtotime(request('tanggal_selesai'))
)
:
'-'
}}



</div>




<table class="summary">


<tr>


<td>

<strong>
Total Kegiatan
</strong>

<br>

{{ $totalKegiatan }}

</td>



<td>

<strong>
Total Jam
</strong>

<br>

{{ $totalJam }}

</td>


</tr>


</table>






<table>


<thead>

<tr>

<th>
Kode
</th>

<th>
Kegiatan
</th>

<th>
Ruangan
</th>

<th>
Tanggal
</th>

<th>
Durasi
</th>


</tr>


</thead>



<tbody>


@foreach($pemesanan as $item)


<tr>


<td>
{{ $item->kode_pemesanan }}
</td>


<td>
{{ $item->judul_kegiatan }}
</td>


<td>
{{ $item->ruangan->nama_ruangan }}
</td>


<td>
{{ $item->tanggal_kegiatan->format('d-m-Y') }}
</td>


<td>
{{ $item->durasi_format }}
</td>


</tr>


@endforeach


</tbody>


</table>





<div class="footer">


Dicetak oleh:

<br>


{{ auth()->user()->name }}


<br><br>


{{ now()->format('d-m-Y') }}



</div>



</body>


</html>