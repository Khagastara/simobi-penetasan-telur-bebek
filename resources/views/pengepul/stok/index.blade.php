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

            @if (session('error'))
                <div class="bg-red-500 text-white px-4 py-2 rounded-lg shadow mb-4">
                    {{ session('error') }}
                </div>
                <script>
                    setTimeout(() => {
                        const errorAlert = document.querySelector('.bg-red-500');
                        if (errorAlert) {
                            errorAlert.remove();
                        }
                    }, 3000);
                </script>
            @endif

            <div class="bg-white p-6 rounded-xl shadow">
                <div class="flex justify-between items-center mb-6">
                    <div class="relative" style="width: 400px;">
                        <input type="text" id="searchInput" placeholder="Telusiri nama stok..."
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
                                <div class="mt-2">
                                    <span class="text-xs text-gray-500">Tersedia: {{ $stok->jumlah_stok }} unit</span>
                                </div>
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
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="stokModalLabel">Detail Stok & Pembelian</h5>
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

    <!-- Purchase Form Modal -->
    <div class="modal fade" id="purchaseModal" tabindex="-1" aria-labelledby="purchaseModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="purchaseModalLabel">Konfirmasi Pembelian</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="purchaseForm" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <img id="purchaseImage" src="" alt="" class="img-fluid rounded mb-3">
                            </div>
                            <div class="col-md-6">
                                <h4 id="purchaseStockName"></h4>
                                <p class="text-success fs-5 fw-bold" id="purchaseStockPrice"></p>
                                <p class="text-muted" id="purchaseStockAvailable"></p>

                                <div class="mb-3">
                                    <label for="quantity" class="form-label">Jumlah Pembelian:</label>
                                    <div class="input-group">
                                        <button type="button" class="btn btn-outline-secondary" id="decreaseQty">-</button>
                                        <input type="number" class="form-control text-center" id="quantity" name="kuantitas" value="1" min="1">
                                        <button type="button" class="btn btn-outline-secondary" id="increaseQty">+</button>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="metode_pembayaran" class="form-label">Metode Pembayaran:</label>
                                    <select class="form-select" id="metode_pembayaran" name="metode_pembayaran" required>
                                        <option value="">Pilih Metode Pembayaran</option>
                                        @foreach(\App\Models\MetodePembayaran::all() as $metode)
                                            <option value="{{ $metode->id }}">{{ $metode->nama_metode }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="alert alert-info">
                                    <strong>Total Harga: </strong><span id="totalPrice" class="text-success fs-5"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-success" id="confirmPurchase">
                            <i class="fas fa-shopping-cart"></i> Konfirmasi Pembelian
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        let currentStockData = null;
        let currentQuantity = 1;

        // Ensure DOM is fully loaded
        document.addEventListener('DOMContentLoaded', function() {
            console.log('DOM loaded, starting initialization...');
            setTimeout(() => {
                initializeComponents();
            }, 100);
        });

        function initializeComponents() {
            // Search functionality
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

            // Purchase form handlers
            initializePurchaseForm();
        }

        function showStokDetail(id) {
            console.log('showStokDetail called with ID:', id);

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

            let modal;
            try {
                modal = new bootstrap.Modal(modalElement);
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
            } catch (error) {
                console.error('Error showing modal:', error);
                alert('Error menampilkan modal: ' + error.message);
                return;
            }

            // Fetch data
            fetch(`/stok-distribusi/${id}`, {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                console.log('Response data:', data);
                if (data.success && data.data) {
                    currentStockData = data.data;
                    currentQuantity = 1; // Reset quantity
                    createEnhancedModalContent(data.data);
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

        function createEnhancedModalContent(stok) {
            const totalPrice = stok.harga_stok * currentQuantity;

            // Fix: Ensure proper image path handling
            let imageSrc = stok.gambar_stok;

            // If no image or empty, use default
            if (!imageSrc || imageSrc === '' || imageSrc === null) {
                imageSrc = '{{ asset("images/stok/no-image.png") }}';
            }

            const modalContent = `
                <div class="container-fluid">
                    <div class="row">
                        <!-- Product Image -->
                        <div class="col-lg-6 mb-4">
                            <div class="text-center">
                                <img src="${imageSrc}"
                                    alt="${stok.nama_stok}"
                                    class="img-fluid rounded-3 shadow-sm"
                                    style="max-height: 400px; width: 100%; object-fit: cover;"
                                    onerror="this.src='{{ asset("images/stok/no-image.png") }}'">
                            </div>
                        </div>

                        <!-- Product Details -->
                        <div class="col-lg-6">
                            <div class="stock-details h-100 d-flex flex-column">
                                <!-- Product Info -->
                                <div class="mb-4">
                                    <h3 class="text-primary mb-2">${stok.nama_stok}</h3>
                                    <div class="price-section mb-3">
                                        <h4 class="text-success fw-bold mb-1">Rp ${Number(stok.harga_stok).toLocaleString('id-ID')}</h4>
                                        <small class="text-muted">per unit</small>
                                    </div>
                                    <div class="stock-info mb-3">
                                        <span class="badge bg-info text-dark fs-6">
                                            <i class="fas fa-box"></i> ${stok.jumlah_stok} unit tersedia
                                        </span>
                                    </div>
                                    <div class="description mb-4">
                                        <h6 class="text-muted mb-2">Deskripsi:</h6>
                                        <p class="text-dark">${stok.deskripsi_stok || 'Tidak ada deskripsi'}</p>
                                    </div>
                                </div>

                                <!-- Purchase Button -->
                                <div class="mt-auto">
                                    <div class="d-grid">
                                        <button type="button"
                                                class="btn btn-success btn-lg fw-bold"
                                                onclick="openPurchaseModal()"
                                                ${stok.jumlah_stok <= 0 ? 'disabled' : ''}>
                                            <i class="fas fa-shopping-cart"></i>
                                            ${stok.jumlah_stok <= 0 ? 'Stok Habis' : 'Beli Sekarang'}
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `;

            document.getElementById('modalContent').innerHTML = modalContent;
        }

        function openPurchaseModal() {
            if (!currentStockData) {
                alert('Data stok tidak ditemukan');
                return;
            }

            const detailModal = bootstrap.Modal.getInstance(document.getElementById('stokModal'));
            if (detailModal) {
                detailModal.hide();
            }

            let imageSrc = currentStockData.gambar_stok;
            if (!imageSrc || imageSrc === '' || imageSrc === null) {
                imageSrc = '{{ asset("images/stok/no-image.png") }}';
            }

            const purchaseImage = document.getElementById('purchaseImage');
            purchaseImage.src = imageSrc;
            purchaseImage.onerror = function() {
                this.src = '{{ asset("images/stok/no-image.png") }}';
            };

            document.getElementById('purchaseStockName').textContent = currentStockData.nama_stok;
            document.getElementById('purchaseStockPrice').textContent = `Rp ${Number(currentStockData.harga_stok).toLocaleString('id-ID')} / unit`;
            document.getElementById('purchaseStockAvailable').textContent = `Tersedia: ${currentStockData.jumlah_stok} unit`;

            document.getElementById('purchaseForm').action = `/p/transaksi/store/${currentStockData.id}`;

            document.getElementById('quantity').value = 1;
            document.getElementById('quantity').max = currentStockData.jumlah_stok;

            updateTotalPrice();
            const purchaseModal = new bootstrap.Modal(document.getElementById('purchaseModal'));
            purchaseModal.show();
        }

        function initializePurchaseForm() {
            document.getElementById('decreaseQty').addEventListener('click', function() {
                const qtyInput = document.getElementById('quantity');
                const currentQty = parseInt(qtyInput.value);
                if (currentQty > 1) {
                    qtyInput.value = currentQty - 1;
                    updateTotalPrice();
                }
            });

            document.getElementById('increaseQty').addEventListener('click', function() {
                const qtyInput = document.getElementById('quantity');
                const currentQty = parseInt(qtyInput.value);
                const maxQty = parseInt(qtyInput.max);
                if (currentQty < maxQty) {
                    qtyInput.value = currentQty + 1;
                    updateTotalPrice();
                }
            });

            document.getElementById('quantity').addEventListener('input', function() {
                const qty = parseInt(this.value);
                const maxQty = parseInt(this.max);

                if (qty < 1) {
                    this.value = 1;
                } else if (qty > maxQty) {
                    this.value = maxQty;
                }

                updateTotalPrice();
            });

            // Form submission
            document.getElementById('purchaseForm').addEventListener('submit', function(e) {
                const quantity = parseInt(document.getElementById('quantity').value);
                const paymentMethod = document.getElementById('metode_pembayaran').value;

                if (!paymentMethod) {
                    e.preventDefault();
                    alert('Silakan pilih metode pembayaran');
                    return false;
                }

                if (quantity <= 0 || quantity > currentStockData.jumlah_stok) {
                    e.preventDefault();
                    alert('Jumlah pembelian tidak valid');
                    return false;
                }

                // Show loading state
                const submitBtn = document.getElementById('confirmPurchase');
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="spinner-border spinner-border-sm me-2"></i>Memproses...';
            });
        }

        function updateTotalPrice() {
            if (!currentStockData) return;

            const quantity = parseInt(document.getElementById('quantity').value) || 1;
            const totalPrice = currentStockData.harga_stok * quantity;
            document.getElementById('totalPrice').textContent = `Rp ${totalPrice.toLocaleString('id-ID')}`;
        }
    </script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

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

        .modal-xl {
            max-width: 1200px;
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

        .stock-details {
            min-height: 500px;
        }

        .btn-success {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            border: none;
        }

        .btn-success:hover {
            background: linear-gradient(135deg, #218838 0%, #1e7e6e 100%);
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        }

        .input-group .btn {
            min-width: 40px;
        }

        #quantity {
            max-width: 80px;
        }
    </style>
@endsection
