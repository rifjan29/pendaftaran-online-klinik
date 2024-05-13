<x-app-layout>
    @push('js')
        <script>
            var urlProvinsi = "https://ibnux.github.io/data-indonesia/provinsi.json";
            var urlKabupaten = "https://ibnux.github.io/data-indonesia/kabupaten/";
            var urlKecamatan = "https://ibnux.github.io/data-indonesia/kecamatan/";

            let user_provinsi_id = `{{ $data->provinsi_id }}`; // Ubah nama variabel menjadi user_provinsi_id
            let user_kabupaten_id = `{{ $data->kabupaten_id }}`; // Ubah nama variabel menjadi user_provinsi_id
            let user_kecamatan_id = `{{ $data->kecamatan_id }}`; // Ubah nama variabel menjadi user_provinsi_id

            $.getJSON(urlProvinsi, function (res) {
                let provinsi_name;
                let selected_provinsi_id; // Ubah nama variabel menjadi selected_provinsi_id
                res = $.map(res, function (obj) {
                    if (obj.id == user_provinsi_id) {
                        obj.text = obj.nama;
                        provinsi_name = obj.text;
                        selected_provinsi_id = obj.id; // Simpan id provinsi yang sesuai dengan id dari sesi pengguna
                        $('#provinsi').html(provinsi_name);
                    }
                    return obj; // Perlu mengembalikan objek dalam fungsi map
                });
                $.getJSON(urlKabupaten + selected_provinsi_id + ".json", function(res) {
                    let kabupaten_id;
                    res = $.map(res, function (obj) {
                        if (obj.id == user_kabupaten_id) {
                            obj.text = obj.nama;
                            kabupaten_id = obj.id;
                            $('#kabupaten').html(obj.text); // Tampilkan nama kabupaten yang sesuai
                        }
                        return obj;
                    });
                    $.getJSON(urlKecamatan + kabupaten_id + ".json", function(res) {
                        res = $.map(res, function (obj) {
                            if (obj.id == user_kecamatan_id) {
                                obj.text = obj.nama;
                                $('#kecamatan').html(obj.text);
                            }
                            return obj;
                        })
                    })
                });
            });
            $(document).ready(function() {
                let url = `{{ route('pendaftaran-offline.list-dokter') }}`
                $('#poliklinik').on('change', function() {
                    let id = $(this).val();
                    $('#dokter').empty()
                    $('#dokter').append(`<option value="0">Pilih Dokter</option>`)
                    if (id != 0 || id != '0') {
                        $.ajax({
                            type: "GET",
                            url: url,
                            data: {
                                id:id
                            },
                            success: function(data) {
                                console.log(data);
                                $.map(data, function(obj) {
                                    $('#dokter').append(`<option value="${obj.id}">${obj.name}</option>`)
                                })
                            }
                        })
                    }
                })
                // cara pembayaran
                $('#cara_pembayaran').on('change', function() {
                    let id = $(this).val();
                    if (id != 0 || id != '0') {
                       if (id == 'bpjs') {
                            $('.no_kartu').removeClass('hidden')
                       }else{
                            $('.no_kartu').addClass('hidden')
                       }
                    }else{
                        $('.no_kartu').addClass('hidden')
                    }
                })
            })
        </script>
    @endpush
    <div class="p-4 sm:ml-64 pt-20 h-screen">
        <section class="p-5 overflow-y-auto mt-5">
            <div class="card bg-white p-5 mt-4 border rounded-md w-full relative overflow-x-auto">
                <div class="bg-blue-800 p-4 rounded-t-lg flex gap-2">
                    <a href="{{ route('pasien.ketentuan') }}" class="flex align-middle content-center items-center gap-2">
                        <svg class="w-6 h-6 text-white dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M5 12l4-4m-4 4 4 4"/>
                        </svg>
                        <h4 class="text-white">{{ $title }}</h4>
                    </a>
                </div>
                <div class="card bg-white p-5 mt-4 border rounded-md w-full relative overflow-x-auto">
                    <div class="heading flex-auto">
                        <h4 class="font-bold tracking-tighter text-base text-theme-text mb-3">
                            Data Pasien
                        </h4>
                        <hr>
                    </div>
                    <div class="w-full relative mt-5">
                        <div class="grid grid-cols-2 gap-2">
                            <div class="grid grid-cols-1 gap-2">
                                <div class="flex content-center font-medium text-sm text-gray-900 whitespace-nowrap dark:text-white">
                                    <div class="w-40 pr-4">
                                        <h4 class="font-medium text-sm text-gray-900 whitespace-nowrap dark:text-white">Nama</h4>
                                    </div>
                                    <div class="px-4">
                                        :
                                    </div>
                                    <div class="font-bold">
                                        {{ ucwords($data->name) }}
                                    </div>
                                </div>
                                <div class="flex content-center font-medium text-sm text-gray-900 whitespace-nowrap dark:text-white">
                                    <div class="w-40 pr-4">
                                        <h4 class="font-medium text-sm text-gray-900 whitespace-nowrap dark:text-white">Tempat/Tgl Lahir</h4>
                                    </div>
                                    <div class="px-4">
                                        :
                                    </div>
                                    <div class="font-bold">
                                       {{ $data->tempat_lahir }} /  {{ \Carbon\Carbon::parse($data->tgl_lahir)->translatedFormat('d F Y') }}
                                    </div>
                                </div>
                                <div class="flex content-center font-medium text-sm text-gray-900 whitespace-nowrap dark:text-white">
                                    <div class="w-40 pr-4">
                                        <h4 class="font-medium text-sm text-gray-900 whitespace-nowrap dark:text-white">Alamat</h4>
                                    </div>
                                    <div class="px-4">
                                        :
                                    </div>
                                    <div class="font-bold">
                                        {{ $data->alamat }} <br>
                                        <span id="provinsi">-</span>,<span id="kabupaten"></span> <span id="kecamatan"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="grid grid-cols-1 gap-2">
                                <div class="flex content-center font-medium text-sm text-gray-900 whitespace-nowrap dark:text-white">
                                    <div class="w-40 pr-4">
                                        <h4 class="font-medium text-sm text-gray-900 whitespace-nowrap dark:text-white">No. RM</h4>
                                    </div>
                                    <div class="px-4">
                                        :
                                    </div>
                                    <div class="font-bold">
                                        {{ ucwords($data->no_rm) }}
                                    </div>
                                </div>
                                <div class="flex content-center font-medium text-sm text-gray-900 whitespace-nowrap dark:text-white">
                                    <div class="w-40 pr-4">
                                        <h4 class="font-medium text-sm text-gray-900 whitespace-nowrap dark:text-white">Jenis Kelamin</h4>
                                    </div>
                                    <div class="px-4">
                                        :
                                    </div>
                                    <div class="font-bold">
                                      {{ $data->jenis_kelamin == 'l' ? 'Laki-Laki' : 'Perempuan' }}
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="card bg-white p-5 mt-4 border rounded-md w-full relative overflow-x-auto">
                    <form action="{{ route('pendaftaran-offline.store') }}" method="POST" class="w-full mx-auto space-y-4" enctype="multipart/form-data">
                        @csrf
                        <input type="text" hidden name="id" value="{{ $data->id }}">
                        <div class="w-full mx-auto space-y-4">
                            <div class="grid grid-cols-2 gap-2">
                                <div>
                                    <x-label-default for="" value="Cara Pembayaran">Cara Pembayaran</x-label-default>
                                    <select id="cara_pembayaran" name="cara_pembayaran" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                        <option value="0">Pilih Cara Pembayaran</option>
                                        <option value="bpjs">BPJS</option>
                                        <option value="umum">Umum</option>
                                    </select>
                                </div>
                                <div>
                                    <div class="no_kartu hidden">
                                        <x-label-default for="" value="No. Kartu">No. Kartu</x-label-default>
                                        <x-input-default name="no_bpjs" type="text" value="{{ old('no_bpjs') }}" placeholder="Masukkan No. Kartu"></x-input-default>
                                    </div>
                                </div>
                                <div>
                                    <x-label-default for="" value="Nama Poliklinik">Nama Poliklinik</x-label-default>
                                    <select id="poliklinik" name="poliklinik" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                        <option value="0">Pilih Poliklinik</option>
                                        @foreach ($poliklinik as $item)
                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <x-label-default for="" value="Pilih Dokter">Pilih Dokter</x-label-default>
                                    <select id="dokter" name="dokter" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                        <option value="0">Pilih Dokter</option>
                                    </select>
                                </div>
                            </div>

                        </div>
                        <div class="flex justify-end align-middle content-center bg-slate-100 p-3 rounded-md mt-5">
                            <div>
                                <x-primary-button type="submit">Daftar</x-primary-button>
                            </div>
                            <div>
                                <x-danger-button type="reset">Batal</x-danger-button>
                            </div>
                        </div>
                    </form>
                </div>

            </div>
        </section>
    </div>
</x-app-layout>
