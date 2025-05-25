@extends('layouts.pengepul')

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
                    <div class="relative" style="width: 400px;">
                        <input type="text" id="searchInput" placeholder="Search items"
                               class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#AFC97E] w-full">
                        <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
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

    <!-- Bootstrap Modal for Stock Detail -->
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
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Debug function
        function debugBootstrap() {
            console.log('Bootstrap available:', typeof bootstrap !== 'undefined');
            console.log('Bootstrap Modal available:', typeof bootstrap?.Modal !== 'undefined');
            console.log('Cards found:', document.querySelectorAll('.card-item').length);
            console.log('Modal element found:', document.getElementById('stokModal') !== null);
        }

        // Ensure DOM is fully loaded
        document.addEventListener('DOMContentLoaded', function() {
            console.log('DOM loaded, starting initialization...');

            // Wait a bit for Bootstrap to load
            setTimeout(() => {
                debugBootstrap();
                initializeComponents();
            }, 100);
        });

        function initializeComponents() {
            // Search functionality
            const searchInput = document.getElementById('searchInput');
            if (searchInput) {
                console.log('Search input found, adding event listener');
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

            // Add click event listeners to all cards
            const cards = document.querySelectorAll('.card-item');
            console.log(`Found ${cards.length} cards, adding click listeners`);

            cards.forEach((card, index) => {
                card.addEventListener('click', function(e) {
                    console.log(`Card ${index + 1} clicked`);
                    e.preventDefault();
                    e.stopPropagation();

                    const stokId = this.getAttribute('data-stok-id');
                    console.log('Stok ID:', stokId);

                    if (!stokId) {
                        console.error('No stok ID found on card');
                        alert('ID stok tidak ditemukan pada kartu ini.');
                        return;
                    }

                    showStokDetail(stokId);
                });
            });
        }

        function showStokDetail(id) {
            console.log('showStokDetail called with ID:', id);

            // Check if Bootstrap is available
            if (typeof bootstrap === 'undefined') {
                console.error('Bootstrap is not loaded');
                alert('Bootstrap tidak terload. Silakan refresh halaman.');
                return;
            }

            const modalElement = document.getElementById('stokModal');
            if (!modalElement) {
                console.error('Modal element not found');
                alert('Modal element tidak ditemukan.');
                return;
            }

            console.log('Creating Bootstrap modal...');

            // Try creating modal with error handling
            let modal;
            try {
                modal = new bootstrap.Modal(modalElement);
                console.log('Modal created successfully');
            } catch (error) {
                console.error('Error creating modal:', error);
                alert('Error membuat modal: ' + error.message);
                return;
            }

            // Show loading state
            const modalContent = document.getElementById('modalContent');
            if (modalContent) {
                modalContent.innerHTML = `
                    <div class="text-center py-4">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-2 text-muted">Memuat detail stok...</p>
                    </div>
                `;
            }

            // Show modal
            try {
                modal.show();
                console.log('Modal shown successfully');
            } catch (error) {
                console.error('Error showing modal:', error);
                alert('Error menampilkan modal: ' + error.message);
                return;
            }

            // Fetch data
            console.log('Fetching data from:', `/stok-distribusi/${id}`);

            fetch(`/stok-distribusi/${id}`, {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                }
            })
            .then(response => {
                console.log('Response status:', response.status);
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                console.log('Response data:', data);
                if (data.success && data.data) {
                    createModalContentFromData(data.data);
                } else {
                    throw new Error('Data tidak valid');
                }
            })
            .catch(error => {
                console.error('Error fetching stock details:', error);
                if (modalContent) {
                    modalContent.innerHTML = `
                        <div class="text-center py-4">
                            <div class="text-danger mb-3">
                                <svg width="48" height="48" fill="currentColor" viewBox="0 0 16 16">
                                    <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
                                </svg>
                            </div>
                            <h5 class="text-danger">Error Loading Data</h5>
                            <p class="text-muted">Terjadi kesalahan saat memuat detail stok: ${error.message}</p>
                            <button type="button" class="btn btn-outline-primary btn-sm" onclick="showStokDetail(${id})">
                                🔄 Coba Lagi
                            </button>
                        </div>
                    `;
                }
            });
        }

        function createModalContentFromData(stok) {
            const modalContent = `
                <div class="modal-content-section">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="text-center">
                                <img src="${stok.gambar_stok || '/images/no-image.png'}"
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
                                    <h5 class="text-success fw-bold mb-0">Rp ${Number(stok.harga_stok).toLocaleString('id-ID')}</h5>
                                </div>
                                <div class="detail-item mb-3">
                                    <span class="text-muted small">Jumlah Stok Tersedia</span>
                                    <h6 class="mb-0">${stok.jumlah_stok} unit</h6>
                                </div>
                                <div class="detail-item mb-3">
                                    <span class="text-muted small">Deskripsi</span>
                                    <p class="mb-0">${stok.deskripsi_stok || 'Tidak ada deskripsi'}</p>
                                </div>
                                ${stok.created_at ? `
                                <div class="detail-item mb-3">
                                    <span class="text-muted small">Ditambahkan pada</span>
                                    <p class="mb-0">${new Date(stok.created_at).toLocaleDateString('id-ID', {
                                        year: 'numeric',
                                        month: 'long',
                                        day: 'numeric',
                                        hour: '2-digit',
                                        minute: '2-digit'
                                    })}</p>
                                </div>
                                ` : ''}
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

        /* Loading spinner animation */
        .spinner-border {
            animation: spinner-border .75s linear infinite;
        }

        @keyframes spinner-border {
            to {
                transform: rotate(360deg);
            }
        }
    </style>
@endsection
