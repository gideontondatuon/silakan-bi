<x-app-layout>

<div class="dashboard-header">

    <h1>
        Buat Pemesanan Ruangan
    </h1>

    <p>
        Ajukan penggunaan fasilitas kantor
    </p>

</div>


<div class="dashboard-section">

    <form method="POST"
          action="{{ route('pemesanan.store') }}">

        @csrf


        <div class="form-group">

            <label>
                Ruangan
            </label>

            <select id="ruangan_id"
                    name="ruangan_id"
                    required>

                <option value="">
                    -- Pilih Ruangan --
                </option>


                @foreach($ruangan as $item)

                    <option value="{{ $item->id }}">
                        {{ $item->nama_ruangan }}
                    </option>

                @endforeach

            </select>

        </div>



        <div class="form-group">

            <label>
                Layout Ruangan
            </label>

            <select id="layout_ruangan_id"
                    name="layout_ruangan_id"
                    required>

                <option value="">
                    -- Pilih Ruangan Dahulu --
                </option>

            </select>

        </div>



        <div class="form-group">

            <label>
                Tanggal Kegiatan
            </label>

            <input type="date"
                   name="tanggal_kegiatan"
                   required>

        </div>



        <div class="form-group">

            <label>
                Waktu Mulai
            </label>

            <input type="time"
                   name="waktu_mulai"
                   required>

        </div>



        <div class="form-group">

            <label>
                Waktu Selesai
            </label>

            <input type="time"
                   name="waktu_selesai"
                   required>

        </div>



        <div class="form-group">

            <label>
                Judul Kegiatan
            </label>

            <input type="text"
                   name="judul_kegiatan"
                   required>

        </div>



        <div class="form-group">

            <label>
                PIC Kegiatan
            </label>

            <input type="text"
                   name="pic_kegiatan"
                   required>

        </div>



        <div class="form-group">

            <label>
                Jenis PIC
            </label>

            <select name="jenis_pic"
                    required>

                <option value="Organik">
                    Organik
                </option>

                <option value="Non Organik">
                    Non Organik
                </option>

            </select>

        </div>



        <div class="form-group">

            <label>
                Jumlah Tamu
            </label>

            <input type="number"
                   name="jumlah_tamu"
                   min="1"
                   required>

        </div>



        <div class="form-group">

            <label>
                Keterangan Layout
            </label>

            <textarea name="keterangan_layout"
                      rows="4"></textarea>

        </div>



        <div class="form-group">

            <label>
                Catatan
            </label>

            <textarea name="catatan_user"
                      rows="4"></textarea>

        </div>



        <div class="form-action">

            <a href="/dashboard"
               class="btn-secondary">

                Batal

            </a>


            <button class="btn-primary">

                Kirim Pengajuan

            </button>

        </div>


    </form>

</div>



<script>

document
    .getElementById('ruangan_id')
    .addEventListener('change', function () {

        const id = this.value;

        const layout =
            document.getElementById('layout_ruangan_id');


        layout.innerHTML =
            '<option value="">Loading...</option>';



        if (!id) {

            layout.innerHTML =
                '<option value="">-- Pilih Ruangan Dahulu --</option>';

            return;

        }



        fetch(`/api/ruangan/${id}/layouts`)

            .then(response => response.json())

            .then(result => {


                layout.innerHTML =
                    '<option value="">-- Pilih Layout --</option>';



                result.forEach(item => {


                    layout.innerHTML += `

                        <option value="${item.id}">
                            ${item.nama_layout}
                            (${item.kapasitas_layout} orang)
                        </option>

                    `;


                });


            })

            .catch(() => {

                layout.innerHTML =
                    '<option value="">Gagal memuat layout</option>';

            });


    });

</script>


</x-app-layout>