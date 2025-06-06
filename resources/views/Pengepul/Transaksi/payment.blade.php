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
                                <strong>Total:</strong> Rp {{ number_format($paymentData['total_amount'], 0, ',', '.') }}<br>
                                <strong>Order ID:</strong> {{ $paymentData['transaksi']->order_id }}<br>
                                <strong>Status:</strong> <span id="payment-status" class="badge bg-warning">{{ ucfirst($paymentData['transaksi']->payment_status) }}</span>
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
                            <div id="payment-info" class="mt-3" style="display: none;">
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle"></i> Pembayaran sedang diproses...
                                </div>
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

<!-- Success Modal -->
<div class="modal fade" id="successModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body text-center py-4">
                <div class="text-success mb-3">
                    <i class="fas fa-check-circle fa-3x"></i>
                </div>
                <h5>Pembayaran Berhasil!</h5>
                <p class="text-muted">Transaksi Anda telah berhasil diproses</p>
                <button type="button" class="btn btn-success" onclick="redirectToTransactionList()">
                    Lihat Riwayat Transaksi
                </button>
            </div>
        </div>
    </div>
</div>

<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.clientKey') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
let paymentProcessing = false;
let statusCheckInterval;

document.getElementById('pay-button').addEventListener('click', function () {
    if (paymentProcessing) return;

    paymentProcessing = true;
    const loadingModal = new bootstrap.Modal(document.getElementById('loadingModal'));
    loadingModal.show();

    snap.pay('{{ $paymentData['snap_token'] }}', {
        onSuccess: function(result) {
            loadingModal.hide();
            paymentProcessing = false;

            console.log('Payment Success:', result);

            // Update payment status via AJAX
            updatePaymentStatus('success', result);
        },
        onPending: function(result) {
            loadingModal.hide();
            paymentProcessing = false;

            console.log('Payment Pending:', result);

            // Show pending info and start status checking
            document.getElementById('payment-info').style.display = 'block';
            document.querySelector('#payment-info .alert').className = 'alert alert-warning';
            document.querySelector('#payment-info .alert').innerHTML = '<i class="fas fa-clock"></i> Pembayaran pending. Menunggu konfirmasi...';

            // Start checking payment status
            startStatusCheck();
        },
        onError: function(result) {
            loadingModal.hide();
            paymentProcessing = false;

            console.log('Payment Error:', result);

            alert('Pembayaran gagal. Silakan coba lagi.');

            // Update payment status to failed
            updatePaymentStatus('failed', result);
        },
        onClose: function() {
            loadingModal.hide();
            paymentProcessing = false;

            console.log('Payment popup closed');

            // Check if payment was actually completed
            checkCurrentPaymentStatus();
        }
    });
});

function updatePaymentStatus(status, result) {
    fetch('{{ route('payment.update-status', $paymentData['transaksi']->id) }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            payment_status: status,
            payment_result: result
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            if (status === 'success') {
                showSuccessModal();
            }
            updateStatusDisplay(status);
        } else {
            console.error('Failed to update payment status:', data.message);
        }
    })
    .catch(error => {
        console.error('Error updating payment status:', error);
    });
}

function checkCurrentPaymentStatus() {
    fetch('{{ route('payment.check-status', $paymentData['transaksi']->id) }}')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                updateStatusDisplay(data.payment_status);

                if (data.payment_status === 'success') {
                    showSuccessModal();
                }
            }
        })
        .catch(error => {
            console.error('Error checking payment status:', error);
        });
}

function startStatusCheck() {
    if (statusCheckInterval) {
        clearInterval(statusCheckInterval);
    }

    statusCheckInterval = setInterval(function() {
        checkCurrentPaymentStatus();
    }, 5000); // Check every 5 seconds
}

function stopStatusCheck() {
    if (statusCheckInterval) {
        clearInterval(statusCheckInterval);
        statusCheckInterval = null;
    }
}

function updateStatusDisplay(status) {
    const statusElement = document.getElementById('payment-status');
    const paymentInfo = document.getElementById('payment-info');

    switch(status) {
        case 'success':
            statusElement.className = 'badge bg-success';
            statusElement.textContent = 'Success';
            paymentInfo.style.display = 'block';
            paymentInfo.querySelector('.alert').className = 'alert alert-success';
            paymentInfo.querySelector('.alert').innerHTML = '<i class="fas fa-check-circle"></i> Pembayaran berhasil!';
            stopStatusCheck();
            break;
        case 'pending':
            statusElement.className = 'badge bg-warning';
            statusElement.textContent = 'Pending';
            break;
        case 'failed':
            statusElement.className = 'badge bg-danger';
            statusElement.textContent = 'Failed';
            paymentInfo.style.display = 'block';
            paymentInfo.querySelector('.alert').className = 'alert alert-danger';
            paymentInfo.querySelector('.alert').innerHTML = '<i class="fas fa-times-circle"></i> Pembayaran gagal.';
            stopStatusCheck();
            break;
    }
}

function showSuccessModal() {
    const successModal = new bootstrap.Modal(document.getElementById('successModal'));
    successModal.show();
}

function redirectToTransactionList() {
    window.location.href = '{{ route('pengepul.transaksi.index') }}';
}

// Initial status check when page loads
document.addEventListener('DOMContentLoaded', function() {
    checkCurrentPaymentStatus();
});

// Cleanup interval when page unloads
window.addEventListener('beforeunload', function() {
    stopStatusCheck();
});
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

.badge {
    font-size: 0.75em;
    padding: 0.5em 0.75em;
}

.alert {
    margin-bottom: 0;
    border-radius: 8px;
}

.fa-3x {
    font-size: 3em;
}
</style>
@endsection
