<?php
require_once 'config/db.php';
require_once 'includes/auth.php';

// Jika sudah login, lempar ke dashboard sesuai role
if (isLoggedIn()) {
    if ($_SESSION['role'] === 'admin')
        header("Location: admin.php");
    elseif ($_SESSION['role'] === 'operator')
        header("Location: operator.php");
    else
        header("Location: dashboard.php");
    exit();
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = sanitize($_POST['username']);
    $password = $_POST['password'];

    if (empty($username) || empty($password)) {
        $error = "Username dan password wajib diisi.";
    } else {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            // Login Berhasil
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['nama_lengkap'] = $user['nama_lengkap'];
            $_SESSION['tier'] = $user['tier'];

            // Redirect sesuai role
            if ($user['role'] === 'admin')
                header("Location: admin.php");
            elseif ($user['role'] === 'operator')
                header("Location: operator.php");
            else
                header("Location: dashboard.php");
            exit();
        } else {
            $error = "Username atau password salah.";
        }
    }
}

$pageTitle = "Masuk";
include 'includes/header.php';
?>

<style>
    body {
        min-height: 100vh;
        display: flex;
    }

    .auth-left {
        flex: 1;
        background: linear-gradient(135deg, #0f172a 0%, #134e4a 50%, #0d9488 100%);
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        padding: 48px;
        position: relative;
        overflow: hidden;
    }

    .auth-left-content {
        position: relative;
        z-index: 2;
        max-width: 400px;
        text-align: center;
        color: white;
    }

    .auth-right {
        flex: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 48px 24px;
        background: var(--surface);
    }

    .auth-card {
        width: 100%;
        max-width: 420px;
    }

    @media(max-width:768px) {
        .auth-left {
            display: none;
        }
    }
</style>

<div class="auth-left">
    <div class="auth-left-content">
        <h2 class="text-3xl font-extrabold mb-4">Analitik Bisnis Powerful</h2>
        <p class="text-slate-300 mb-8">Pantau kinerja UMKM Anda dengan dashboard interaktif yang terhubung langsung ke
            SmartBank.</p>
        <div class="flex flex-col gap-3">
            <div class="bg-white/10 p-4 rounded-xl text-left flex gap-3 items-center border border-white/10">
                <i class="ph-fill ph-trend-up text-brand-400 text-xl"></i>
                <span class="text-sm">Laporan penjualan real-time</span>
            </div>
            <span class="text-xl font-extrabold tracking-tight">UMKM Insight</span>
        </div>

        <!-- Headline -->
        <div class="mb-10 animate-pop-in stagger-2">
            <h2 class="text-4xl font-black leading-tight tracking-tight mb-4">
                Analitik Bisnis<br>
                <span class="bg-clip-text text-transparent bg-gradient-to-r from-brand-300 to-teal-400">Berbasis Data</span>
            </h2>
            <p class="text-slate-300 text-sm leading-relaxed">
                Pantau kinerja UMKM Anda secara real-time. Dashboard interaktif terhubung langsung ke transaksi SmartBank Anda.
            </p>
        </div>

        <!-- Feature pills -->
        <div class="flex flex-col gap-3 mb-10 animate-pop-in stagger-3">
            <div class="feature-pill">
                <div class="feature-pill-icon bg-emerald-500/20 text-emerald-400">
                    <i class="ph-fill ph-trend-up"></i>
                </div>
                <div>
                    <p class="text-sm font-bold">Laporan Penjualan Real-time</p>
                    <p class="text-[11px] text-slate-400 mt-0.5">Tren harian hingga bulanan secara otomatis</p>
                </div>
            </div>
            <div class="feature-pill">
                <div class="feature-pill-icon bg-blue-500/20 text-blue-400">
                    <i class="ph-fill ph-money"></i>
                </div>
                <div>
                    <p class="text-sm font-bold">Analisis Arus Kas Mendalam</p>
                    <p class="text-[11px] text-slate-400 mt-0.5">Pantau pemasukan, pengeluaran & proyeksi</p>
                </div>
            </div>
            <div class="feature-pill">
                <div class="feature-pill-icon bg-amber-500/20 text-amber-400">
                    <i class="ph-fill ph-crown"></i>
                </div>
                <div>
                    <p class="text-sm font-bold">Wawasan Premium untuk Scale-up</p>
                    <p class="text-[11px] text-slate-400 mt-0.5">Optimalkan strategi bisnis dengan data nyata</p>
                </div>
            </div>
        </div>

        <!-- Stats ticker -->
        <div class="stat-ticker animate-pop-in stagger-4">
            <div class="stat-item">
                <span class="text-2xl font-black text-brand-300">500+</span>
                <span class="text-[10px] text-slate-400 uppercase tracking-widest">UMKM Aktif</span>
            </div>
            <div class="w-px bg-white/10"></div>
            <div class="stat-item">
                <span class="text-2xl font-black text-indigo-300">99.9%</span>
                <span class="text-[10px] text-slate-400 uppercase tracking-widest">Uptime</span>
            </div>
            <div class="w-px bg-white/10"></div>
            <div class="stat-item">
                <span class="text-2xl font-black text-amber-300">4.9 ★</span>
                <span class="text-[10px] text-slate-400 uppercase tracking-widest">Rating</span>
            </div>
        </div>
    </div>
</div>

<!-- ============ RIGHT PANEL ============ -->
<div class="auth-right-panel">

    <!-- Floating particles -->
    <div class="particle" style="--dur:9s;--dx:40px;--dy:-60px;--op:0.5; width:8px;height:8px;background:#14b8a6; top:20%;left:15%;animation-delay:0s;"></div>
    <div class="particle" style="--dur:7s;--dx:-30px;--dy:-50px;--op:0.4; width:5px;height:5px;background:#6366f1; top:60%;left:80%;animation-delay:-2s;"></div>
    <div class="particle" style="--dur:11s;--dx:50px;--dy:-30px;--op:0.5; width:6px;height:6px;background:#f59e0b; top:75%;left:10%;animation-delay:-4s;"></div>
    <div class="particle" style="--dur:8s;--dx:-40px;--dy:-70px;--op:0.35; width:4px;height:4px;background:#14b8a6; top:30%;left:75%;animation-delay:-1s;"></div>
    <div class="particle" style="--dur:13s;--dx:20px;--dy:-40px;--op:0.45; width:7px;height:7px;background:#a78bfa; top:85%;left:55%;animation-delay:-6s;"></div>
    <div class="particle" style="--dur:10s;--dx:-50px;--dy:-20px;--op:0.3; width:5px;height:5px;background:#34d399; top:10%;left:45%;animation-delay:-3s;"></div>
    <div class="particle" style="--dur:6s;--dx:30px;--dy:-55px;--op:0.5; width:6px;height:6px;background:#f472b6; top:50%;left:30%;animation-delay:-5s;"></div>
    <div class="particle" style="--dur:12s;--dx:-20px;--dy:-45px;--op:0.4; width:4px;height:4px;background:#38bdf8; top:90%;left:85%;animation-delay:-7s;"></div>

    <!-- Pulsing rings centered behind the form -->
    <div class="ring" style="--dur:5s; width:380px;height:380px;border-color:rgba(20,184,166,0.15); top:50%;left:50%;"></div>
    <div class="ring" style="--dur:7s; width:520px;height:520px;border-color:rgba(99,102,241,0.1);  top:50%;left:50%;animation-delay:-2s;"></div>
    <div class="ring" style="--dur:9s; width:660px;height:660px;border-color:rgba(245,158,11,0.07); top:50%;left:50%;animation-delay:-4s;"></div>

    <!-- Form card -->
    <div class="w-full max-w-[400px] relative z-10 form-card">

        <div class="mb-8">
            <h1 class="text-2xl font-extrabold text-slate-800 dark:text-white mb-2 typing-cursor">Selamat Datang Kembali</h1>
            <p class="text-slate-500 dark:text-slate-400 text-sm">Masukkan kredensial Anda untuk mengakses dasbor.</p>
        </div>

        <!-- Animated trust badges -->
        <div class="flex gap-2 mb-6">
            <div class="flex items-center gap-1.5 bg-emerald-50 dark:bg-emerald-900/30 border border-emerald-200 dark:border-emerald-700/50 text-emerald-700 dark:text-emerald-400 text-[10px] font-bold px-3 py-1.5 rounded-full animate-pop-in stagger-1">
                <i class="ph-fill ph-shield-check text-sm"></i> SSL Aman
            </div>
            <div class="flex items-center gap-1.5 bg-brand-50 dark:bg-teal-900/30 border border-brand-200 dark:border-teal-700/50 text-brand-700 dark:text-brand-400 text-[10px] font-bold px-3 py-1.5 rounded-full animate-pop-in stagger-2">
                <i class="ph-fill ph-lock text-sm"></i> Enkripsi BCrypt
            </div>
            <div class="flex items-center gap-1.5 bg-indigo-50 dark:bg-indigo-900/30 border border-indigo-200 dark:border-indigo-700/50 text-indigo-700 dark:text-indigo-400 text-[10px] font-bold px-3 py-1.5 rounded-full animate-pop-in stagger-3">
                <i class="ph-fill ph-cloud-check text-sm"></i> Real-time
            </div>
        </div>

        <?php if ($error): ?>
            <div
                class="bg-rose-50 border border-rose-100 text-rose-600 p-3 rounded-lg text-sm mb-6 flex items-center gap-2">
                <i class="ph ph-warning-circle"></i> <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <form action="login.php" method="POST" class="flex flex-col gap-5">
            <div class="form-group animate-pop-in stagger-3">
                <label class="form-label text-slate-700 dark:text-slate-300 font-bold text-xs uppercase tracking-wider">Username</label>
                <div class="relative group">
                    <i class="ph ph-user absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-brand-500 transition-colors duration-200 z-10"></i>
                    <input type="text" name="username" class="form-input auth-input pl-9 dark:bg-slate-800/60" placeholder="Masukkan username" required>
                </div>
            </div>
            <div class="form-group animate-pop-in stagger-4">
                <div class="flex justify-between items-center mb-1">
                    <label class="form-label text-slate-700 dark:text-slate-300 font-bold text-xs uppercase tracking-wider mb-0">Password</label>
                    <a href="#" class="text-[10px] text-brand-600 dark:text-brand-400 font-bold hover:underline">Lupa Password?</a>
                </div>
                <div class="relative group">
                    <i class="ph ph-lock absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-brand-500 transition-colors duration-200 z-10"></i>
                    <input type="password" name="password" id="pw-input" class="form-input auth-input pl-9 pr-10 dark:bg-slate-800/60" placeholder="••••••••" required>
                    <button type="button" onclick="togglePw()" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-brand-500 dark:hover:text-brand-400 transition-colors z-10">
                        <i class="ph ph-eye" id="pw-eye"></i>
                    </button>
                </div>
            </div>

            <div class="animate-pop-in stagger-5">
                <button type="submit" id="login-btn" class="btn-login btn bg-gradient-to-r from-brand-600 to-teal-500 hover:from-brand-700 hover:to-teal-600 text-white shadow-lg shadow-brand-500/25 btn-full py-3 mt-1 transform hover:-translate-y-1 hover:shadow-xl hover:shadow-brand-500/30 transition-all font-bold rounded-xl">
                    <i class="ph-bold ph-sign-in" id="btn-icon"></i>
                    <span id="btn-text">Masuk ke Dasbor</span>
                </button>
            </div>
        </form>

        <p class="text-center text-sm text-slate-500 dark:text-slate-400 mt-8 animate-pop-in stagger-5">
            Belum punya akun? <a href="register.php" class="text-brand-600 dark:text-brand-400 font-bold hover:underline">Daftar Gratis →</a>
        </p>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
