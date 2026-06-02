<?php
require_once 'config/db.php';
require_once 'includes/auth.php';

if (!isLoggedIn() || $_SESSION['role'] !== 'client') {
    header("Location: login.php");
    exit();
}

$user = getCurrentUser($pdo);
$userId = $user['id'];
$activePage = 'langganan';
$pageTitle = 'Pembayaran Instan SmartBank';

$message = '';
$error = '';

// Proses Pembayaran Instan
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['pay_smartbank'])) {
    if (!$user['smartbank_id']) {
        $error = "Anda belum menghubungkan akun SmartBank. Silakan hubungkan di Profil UMKM.";
    } else {
        $pin = $_POST['sb_pin'];
        if ($pin === '123456') { // Dummy PIN
            try {
                $pdo->beginTransaction();
                
                // 1. Simpan pembayaran otomatis disetujui (INSTANT)
                $stmt = $pdo->prepare("INSERT INTO subscription_payments (user_id, proof_image, status) VALUES (?, 'INSTANT_SMARTBANK', 'approved')");
                $stmt->execute([$userId]);
                
                // 2. Upgrade tier
                $expiry = date('Y-m-d', strtotime('+30 days'));
                $stmt2 = $pdo->prepare("UPDATE users SET tier = 'premium', tier_expiry = ? WHERE id = ?");
                $stmt2->execute([$expiry, $userId]);
                
                $pdo->commit();
                
                $_SESSION['tier'] = 'premium';
                $message = "Pembayaran Berhasil! Akun Anda telah di-upgrade ke Premium.";
                $user = getCurrentUser($pdo); // Refresh user
            } catch (Exception $e) {
                $pdo->rollBack();
                $error = "Gagal memproses pembayaran: " . $e->getMessage();
            }
        } else {
            $error = "PIN SmartBank salah!";
        }
    }
}

include 'includes/header.php';
include 'includes/sidebar.php';
include 'includes/topbar.php';
?>

<div class="main-content animated-bg">
    <div style="max-width:600px; margin:0 auto; padding-top:40px;">
        <div class="text-center mb-8 animate-fade-in stagger-1">
            <h1 class="text-3xl font-extrabold text-slate-800 dark:text-white mb-2">Checkout Premium</h1>
            <p class="text-slate-500">Selesaikan pembayaran untuk menikmati fitur analitik tanpa batas.</p>
        </div>

        <?php if($message): ?>
            <div class="toast toast-success mb-6" style="position:static;">
                <i class="ph-fill ph-check-circle text-lg"></i>
                <div><?php echo $message; ?></div>
            </div>
            <div class="text-center mt-4">
                <a href="dashboard.php" class="btn btn-primary">Kembali ke Dashboard</a>
            </div>
        <?php else: ?>
        
            <?php if($error): ?>
                <div class="toast toast-error mb-6" style="position:static;">
                    <i class="ph-fill ph-warning-circle text-lg"></i>
                    <div><?php echo $error; ?></div>
                </div>
            <?php endif; ?>

            <div class="card bg-white dark:bg-slate-800 border-slate-200 dark:border-slate-700 p-8 animate-fade-in stagger-2">
                <div class="flex justify-between items-center border-b border-slate-100 dark:border-slate-700 pb-4 mb-6">
                    <div>
                        <h3 class="font-bold text-lg">Paket Langganan</h3>
                        <p class="text-sm text-slate-500">Premium 30 Hari</p>
                    </div>
                    <div class="text-right">
                        <p class="text-2xl font-black text-brand-600">Rp 99.000</p>
                    </div>
                </div>

                <div class="mb-6">
                    <h4 class="text-sm font-bold mb-3 flex items-center gap-2">
                        <i class="ph-fill ph-bank text-blue-500"></i> Pembayaran via SmartBank (Prioritas)
                    </h4>
                    
                    <?php if($user['smartbank_id']): ?>
                        <div class="bg-blue-50 dark:bg-slate-800 border border-blue-100 dark:border-slate-700 rounded-xl p-4 mb-4">
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-xs text-slate-500">SmartBank Terhubung:</span>
                                <span class="text-sm font-bold text-slate-700 dark:text-slate-200"><?php echo substr($user['smartbank_id'], 0, 2) . str_repeat('*', strlen($user['smartbank_id'])-4) . substr($user['smartbank_id'], -2); ?></span>
                            </div>
                            <form action="pembayaran.php" method="POST">
                                <label class="text-xs font-bold text-slate-600 block mb-1">Masukkan PIN SmartBank untuk otorisasi dana</label>
                                <div class="flex gap-2">
                                    <input type="password" name="sb_pin" class="form-input flex-1" placeholder="******" required>
                                    <button type="submit" name="pay_smartbank" class="btn bg-blue-600 hover:bg-blue-700 text-white shadow-lg">Bayar Sekarang</button>
                                </div>
                            </form>
                        </div>
                    <?php else: ?>
                        <div class="bg-amber-50 dark:bg-slate-800 border border-amber-100 dark:border-slate-700 rounded-xl p-4 mb-4 text-center">
                            <p class="text-sm text-slate-600 mb-3">Akun SmartBank Anda belum terhubung.</p>
                            <a href="profile.php" class="btn btn-sm btn-outline"><i class="ph ph-link"></i> Hubungkan di Profil</a>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="text-center mt-6 pt-4 border-t border-slate-100 dark:border-slate-700">
                    <p class="text-xs text-slate-500 mb-2">Atau ingin transfer manual?</p>
                    <a href="langganan.php" class="text-brand-600 text-sm font-bold hover:underline">Unggah Bukti Transfer Manual</a>
                </div>
            </div>
            
        <?php endif; ?>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
