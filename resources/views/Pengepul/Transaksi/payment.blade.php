@extends('layouts.pengepul')

@section('content')
<main class="flex-1 flex flex-col font-['Poppins'] bg-[#D4E6B5] min-h-screen">
    <section class="p-8">
        <div class="bg-white p-6 rounded-xl shadow max-w-4xl mx-auto">
            <div class="text-center mb-6">
                <h2 class="text-2xl font-bold text-gray-800">Pembayaran Digital</h2>
                <p class="text-gray-600 mt-2">Silakan selesaikan pembayaran untuk melanjutkan pesanan Anda</p>
            </div>

            <div class="row">
                <div class="col-md-6 mb-4">
                    <div class="card">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0">Detail Pesanan</h5>
                        </div>
                        <div class="card-body">
                            <h6 class="card-title">{{ $paymentData['stok_name'] }}</h6>
                            <p class="card-text">
                                <strong>Jumlah:</strong> {{ $paymentData['quantity'] }} unit<br>
                                <strong>Total:</strong> Rp {{ number_format($paymentData['total_amount'], 0, ',', '.') }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header bg-success text-white">
                            <h5 class="mb-0">Pembayaran</h5>
                        </div>
                        <div class="card-body text-center">
                            <button id="pay-button" class="btn btn-success btn-lg">
                                <i class="fas fa-credit-card"></i> Bayar Sekarang
                            </button>
                            <div class="mt-3">
                                <small class="text-muted">Klik tombol di atas untuk melanjutkan pembayaran</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="text-center mt-4">
                <a href="{{ route('pengepul.transaksi.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali ke Riwayat Transaksi
                </a>
            </div>
        </div>
    </section>
</main>

<!-- Loading Modal -->
<div class="modal fade" id="loadingModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body text-center py-4">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p class="mt-3 mb-0">Memproses pembayaran...</p>
            </div>
        </div>
    </div>
</div>

<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.clientKey') }}"></script>

<script>
document.getElementById('pay-button').addEventListener('click', function () {
    // Show loading modal
    const loadingModal = new bootstrap.Modal(document.getElementById('loadingModal'));
    loadingModal.show();

    // Trigger Midtrans Snap
    snap.pay('{{ $paymentData['snap_token'] }}', {
        onSuccess: function(result) {
            loadingModal.hide();
            alert('Pembayaran berhasil!');
            window.location.href = '{{ route('pengepul.transaksi.show', $paymentData['transaksi']->id) }}';
        },
        onPending: function(result) {
            loadingModal.hide();
            alert('Pembayaran pending. Silakan selesaikan pembayaran Anda.');
            window.location.href = '{{ route('pengepul.transaksi.show', $paymentData['transaksi']->id) }}';
        },
        onError: function(result) {
            loadingModal.hide();
            alert('Pembayaran gagal. Silakan coba lagi.');
            console.log(result);
        },
        onClose: function() {
            loadingModal.hide();
            alert('Anda menutup popup pembayaran tanpa menyelesaikan pembayaran');
        }
    });
});

// Check payment status periodically
function checkPaymentStatus() {
    fetch('{{ route('payment.status', $paymentData['transaksi']->id) }}')
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success' && data.payment_status === 'success') {
                window.location.href = '{{ route('pengepul.transaksi.show', $paymentData['transaksi']->id) }}';
            }
        })
        .catch(error => {
            console.error('Error checking payment status:', error);
        });
}

// Check payment status every 5 seconds
setInterval(checkPaymentStatus, 5000);
</script>

<style>
.card {
    border: none;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.card-header {
    border-bottom: none;
    font-weight: 600;
}

#pay-button {
    padding: 12px 30px;
    font-weight: 600;
    border-radius: 8px;
    transition: all 0.3s ease;
}

#pay-button:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.2);
}

.spinner-border {
    width: 3rem;
    height: 3rem;
}
</style>
@endsection
