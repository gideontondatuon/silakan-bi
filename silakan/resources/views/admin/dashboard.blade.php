<x-app-layout>

<div class="dashboard-header">

    <h1>
        Dashboard SILAKAN
    </h1>

    <p>
        Selamat datang,
        {{ auth()->user()->name }}
    </p>

</div>

<div class="stat-grid">

    <x-stat-card
        title="Total Ruangan"
        :value="$totalRuangan"
        icon="building"
    />


    <x-stat-card
        title="Menunggu Approval"
        :value="$waitingApproval"
        icon="clock-history"
    />


    <x-stat-card
        title="Kegiatan Hari Ini"
        :value="$kegiatanHariIni"
        icon="calendar-check"
    />


    <x-stat-card
        title="Sedang Berlangsung"
        :value="$kegiatanBerlangsung->count()"
        icon="play-circle"
    />

</div>


<div class="dashboard-section">

    <h2>
        Waiting List
    </h2>


    <table class="data-table">

        <thead>

            <tr>
                <th>Kode</th>
                <th>Kegiatan</th>
                <th>Unit</th>
                <th>Ruangan</th>
                <th>Status</th>
            </tr>

        </thead>


        <tbody>

        @forelse($waitingList as $item)

            <tr>

                <td>
                    {{ $item->kode_pemesanan }}
                </td>

                <td>
                    {{ $item->judul_kegiatan }}
                </td>

                <td>
                    {{ $item->user->nama_unit }}
                </td>

                <td>
                    {{ $item->ruangan->nama_ruangan }}
                </td>

                <td>

                    <span class="badge badge-warning">
                        {{ $item->status->label() }}
                    </span>

                </td>

            </tr>

        @empty

            <tr>

                <td colspan="5">
                    Belum ada pemesanan.
                </td>

            </tr>

        @endforelse

        </tbody>

    </table>

</div>

</x-app-layout>