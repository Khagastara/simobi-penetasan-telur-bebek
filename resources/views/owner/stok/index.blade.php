@extends('layouts.owner')

@section('content')
    <main class="flex-1 flex flex-col font-['Poppins'] bg-[#D4E6B5] min-h-screen">
        <section class="p-8">
            @if (session('success'))
                <div class="bg-green-500 text-white px-4 py-2 rounded-lg shadow mb-4">
                    {{ session('success') }}
                </div>
                <script>
                    setTimeout(() => {
                        const successAlert = document.querySelector('.bg-green-500');
                        if (successAlert) {
                            successAlert.remove();
                        }
                    }, 3000);
                </script>
            @endif

            <div class="bg-white p-6 rounded-xl shadow">
                <div class="flex justify-between items-center mb-6">
                    <div class="relative w-96">
                        <input type="text" id="searchInput" placeholder="Telusuri nama stok..."
                            class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#AFC97E] w-full">
                        <div class="absolute left-3 top-0 h-full flex items-center pointer-events-none">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <a href="{{ route('owner.stok.create') }}" class="bg-[#AFC97E] text-white hover:bg-[#8fa866] px-4 py-2 rounded-lg shadow transition flex items-center ml-4">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Tambah Stok Distribusi
                    </a>
                </div>
                <div id="cardsContainer" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                    @forelse ($stokDistribusi as $stok)
                        <div class="card-item bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow cursor-pointer border border-gray-200"
                            data-stok-id="{{ $stok->id }}"
                            data-stok-nama="{{ $stok->nama_stok }}"
                            data-stok-harga="{{ $stok->harga_stok }}"
                            data-stok-jumlah="{{ $stok->jumlah_stok }}"
                            data-stok-deskripsi="{{ $stok->deskripsi_stok ?? '' }}"
                            data-stok-gambar="{{ asset($stok->gambar_stok) }}"
                            data-stok-created="{{ $stok->created_at ?? '' }}"
                            data-stok-updated="{{ $stok->updated_at ?? '' }}">
                            <div class="aspect-square overflow-hidden rounded-t-lg">
                                <img src="{{ asset($stok->gambar_stok) }}"
                                    alt="{{ $stok->nama_stok }}"
                                    class="w-full h-full object-cover hover:scale-105 transition-transform duration-300">
                            </div>
                            <div class="p-4">
                                <h3 class="font-semibold text-gray-800 mb-1 truncate">{{ $stok->nama_stok }}</h3>
                                <p class="text-[#AFC97E] font-bold">Rp {{ number_format($stok->harga_stok, 0, ',', '.') }}</p>
                                <p class="text-sm text-gray-500">/stok</p>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full text-center py-12">
                            <div class="text-gray-400 mb-2">
                                <svg class="w-12 h-12 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2M4 13h2m0 0V9a2 2 0 012-2h2m0 0V6a2 2 0 012-2h2.586a1 1 0 01.707.293l2.414 2.414a1 1 0 01.293.707V9M16 13v2a2 2 0 01-2 2h-2m0 0h-2m0 0a2 2 0 01-2-2v-2m0 0h2m0 0V9"></path>
                                </svg>
                            </div>
                            <p class="text-gray-500">Tidak ada data stok distribusi</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </section>
    </main>

    <div class="modal fade" id="stokModal" tabindex="-1" aria-labelledby="stokModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="stokModalLabel">Detail Stok Distribusi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="modalContent">
                    <div class="text-center py-4">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-2 text-muted">Memuat detail stok...</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="button" id="editButton" class="btn btn-primary" style="display: none;">Edit</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        function waitForBootstrap(callback) {
            if (typeof bootstrap !== 'undefined') {
                callback();
            } else {
                setTimeout(() => waitForBootstrap(callback), 100);
            }
        }

        waitForBootstrap(function() {
            console.log('Bootstrap loaded successfully');

            const searchInput = document.getElementById('searchInput');
            if (searchInput) {
                searchInput.addEventListener('input', function(e) {
                    const searchTerm = e.target.value.toLowerCase();
                    const cards = document.querySelectorAll('.card-item');

                    cards.forEach(card => {
                        const title = card.querySelector('h3')?.textContent?.toLowerCase() || '';
                        if (title.includes(searchTerm)) {
                            card.style.display = 'block';
                        } else {
                            card.style.display = 'none';
                        }
                    });
                });
            }

            const cards = document.querySelectorAll('.card-item');
            console.log('Found', cards.length, 'cards');

            cards.forEach((card, index) => {
                card.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();

                    const stokId = this.getAttribute('data-stok-id');
                    console.log(`Card ${index} clicked, stok ID:`, stokId);

                    if (!stokId) {
                        console.error('No stok ID found on card');
                        alert('ID stok tidak ditemukan pada kartu ini.');
                        return;
                    }

                    showStokDetail(stokId, this);
                });
            });
        });

        function showStokDetail(id, cardElement) {
            console.log('showStokDetail called with ID:', id);

            try {
                const modalElement = document.getElementById('stokModal');
                if (!modalElement) {
                    console.error('Modal element not found');
                    alert('Modal element tidak ditemukan.');
                    return;
                }

                const modal = new bootstrap.Modal(modalElement);
                modal.show();

                const modalContent = document.getElementById('modalContent');
                const editButton = document.getElementById('editButton');

                if (!modalContent || !editButton) {
                    console.error('Modal content elements not found');
                    return;
                }

                modalContent.innerHTML = `
                    <div class="text-center py-4">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-2 text-muted">Memuat detail stok...</p>
                    </div>
                `;
                editButton.style.display = 'none';

                setTimeout(() => {
                    createModalContentFromCard(cardElement);
                    editButton.style.display = 'inline-block';
                    editButton.onclick = () => {
                        window.location.href = `/stok/${id}/edit`;
                    };
                }, 100);

                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

                fetch(`/stok/${id}`, {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        ...(csrfToken && { 'X-CSRF-TOKEN': csrfToken })
                    }
                })
                .then(response => {
                    console.log('Response status:', response.status);
                    if (response.ok) {
                        return response.json();
                    }
                    throw new Error(`HTTP error! status: ${response.status}`);
                })
                .then(data => {
                    console.log('AJAX success, data:', data);
                    if (data.success && data.data) {
                        createModalContentFromData(data.data);
                        console.log('Modal content updated with server data');
                        editButton.onclick = () => {
                            window.location.href = `/stok/${data.data.id}/edit`;
                        };
                    }
                })
                .catch(error => {
                    console.log('AJAX failed, using fallback data:', error.message);
                });

            } catch (error) {
                console.error('Modal Error:', error);
                alert('Terjadi kesalahan saat membuka detail. Error: ' + error.message);
            }
        }

        function createModalContentFromData(stok) {
            console.log('Creating modal content from server data');

            const createdAt = stok.created_at ? new Date(stok.created_at).toLocaleDateString('id-ID', {
                year: 'numeric',
                month: 'short',
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            }) : 'Tidak tersedia';

            const updatedAt = stok.updated_at ? new Date(stok.updated_at).toLocaleDateString('id-ID', {
                year: 'numeric',
                month: 'short',
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            }) : 'Tidak tersedia';

            const totalValue = stok.harga_stok * stok.jumlah_stok;

            const modalContent = `
                <div class="modal-content-section">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="text-center">
                                <img src="${stok.gambar_stok ? (stok.gambar_stok.startsWith('/') ? stok.gambar_stok : '/' + stok.gambar_stok) : '/images/no-image.png'}"
                                     alt="${stok.nama_stok}"
                                     class="img-fluid rounded shadow-sm"
                                     style="max-height: 300px; width: 100%; object-fit: cover;"
                                     onerror="this.src='/images/no-image.png'">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="stock-details">
                                <h4 class="text-primary mb-3">${stok.nama_stok}</h4>

                                <div class="detail-item mb-3">
                                    <span class="text-muted small">Harga per stok</span>
                                    <h5 class="text-success fw-bold mb-0">Rp ${parseInt(stok.harga_stok).toLocaleString('id-ID')}</h5>
                                </div>

                                <div class="detail-item mb-3">
                                    <span class="text-muted small">Jumlah Stok Tersedia</span>
                                    <h6 class="mb-0">${parseInt(stok.jumlah_stok).toLocaleString('id-ID')} unit</h6>
                                </div>

                                ${stok.deskripsi_stok ? `
                                <div class="detail-item mb-3">
                                    <span class="text-muted small">Deskripsi</span>
                                    <p class="mb-0">${stok.deskripsi_stok}</p>
                                </div>
                                ` : ''}

                                <div class="detail-item mb-3">
                                    <span class="text-muted small">Total Nilai Stok</span>
                                    <h6 class="text-info mb-0">Rp ${totalValue.toLocaleString('id-ID')}</h6>
                                </div>

                                <hr>

                                <div class="d-flex gap-2 flex-wrap">
                                    <span class="badge bg-primary">ID: ${stok.id}</span>
                                    ${stok.jumlah_stok > 0 ?
                                        '<span class="badge bg-success">Tersedia</span>' :
                                        '<span class="badge bg-danger">Stok Habis</span>'
                                    }
                                    ${stok.jumlah_stok < 10 && stok.jumlah_stok > 0 ?
                                        '<span class="badge bg-warning">Stok Terbatas</span>' : ''
                                    }
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h6 class="card-title">Informasi Tambahan</h6>
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <small class="text-muted">Dibuat pada:</small><br>
                                            <span>${createdAt}</span>
                                        </div>
                                        <div class="col-sm-6">
                                            <small class="text-muted">Terakhir diupdate:</small><br>
                                            <span>${updatedAt}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `;

            document.getElementById('modalContent').innerHTML = modalContent;
        }

        function createModalContentFromCard(cardElement) {
            console.log('Creating modal content from card data (fallback)');

            const stokId = cardElement.getAttribute('data-stok-id');
            const stokNama = cardElement.getAttribute('data-stok-nama');
            const stokHarga = parseInt(cardElement.getAttribute('data-stok-harga'));
            const stokJumlah = parseInt(cardElement.getAttribute('data-stok-jumlah'));
            const stokDeskripsi = cardElement.getAttribute('data-stok-deskripsi');
            const stokGambar = cardElement.getAttribute('data-stok-gambar');
            const totalValue = stokHarga * stokJumlah;

            const modalContent = `
                <div class="modal-content-section">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="text-center">
                                <img src="${stokGambar}"
                                     alt="${stokNama}"
                                     class="img-fluid rounded shadow-sm"
                                     style="max-height: 300px; width: 100%; object-fit: cover;"
                                     onerror="this.src='/images/no-image.png'">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="stock-details">
                                <h4 class="text-primary mb-3">${stokNama}</h4>

                                <div class="detail-item mb-3">
                                    <span class="text-muted small">Harga per stok</span>
                                    <h5 class="text-success fw-bold mb-0">Rp ${stokHarga.toLocaleString('id-ID')}</h5>
                                </div>

                                <div class="detail-item mb-3">
                                    <span class="text-muted small">Jumlah Stok Tersedia</span>
                                    <h6 class="mb-0">${stokJumlah.toLocaleString('id-ID')} unit</h6>
                                </div>

                                ${stokDeskripsi ? `
                                <div class="detail-item mb-3">
                                    <span class="text-muted small">Deskripsi</span>
                                    <p class="mb-0">${stokDeskripsi}</p>
                                </div>
                                ` : ''}

                                <div class="detail-item mb-3">
                                    <span class="text-muted small">Total Nilai Stok</span>
                                    <h6 class="text-info mb-0">Rp ${totalValue.toLocaleString('id-ID')}</h6>
                                </div>

                                <hr>

                                <div class="d-flex gap-2 flex-wrap">
                                    <span class="badge bg-primary">ID: ${stokId}</span>
                                    ${stokJumlah > 0 ?
                                        '<span class="badge bg-success">Tersedia</span>' :
                                        '<span class="badge bg-danger">Stok Habis</span>'
                                    }
                                    ${stokJumlah < 10 && stokJumlah > 0 ?
                                        '<span class="badge bg-warning">Stok Terbatas</span>' : ''
                                    }
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle me-2"></i>
                                Data dimuat dari cache lokal. Untuk informasi terlengkap, silakan refresh halaman.
                            </div>
                        </div>
                    </div>
                </div>
            `;

            document.getElementById('modalContent').innerHTML = modalContent;
        }
    </script>

    <style>
        .card-item {
            transition: all 0.3s ease;
        }

        .card-item:hover {
            transform: translateY(-2px);
        }

        .aspect-square {
            aspect-ratio: 1 / 1;
        }

        .modal-content {
            border-radius: 15px;
        }

        .modal-header {
            background: linear-gradient(135deg, #AFC97E 0%, #8fa866 100%);
            color: white;
            border-radius: 15px 15px 0 0;
        }

        .modal-header .btn-close {
            filter: brightness(0) invert(1);
        }

        .detail-item {
            border-left: 3px solid #AFC97E;
            padding-left: 12px;
        }

        .stock-details .badge {
            font-size: 0.75em;
        }

        .modal-content-section .card {
            border: none;
            background-color: #f8f9fa;
        }

        .modal {
            z-index: 1050;
        }

        .modal-backdrop {
            z-index: 1040;
        }
    </style>
@endsection
