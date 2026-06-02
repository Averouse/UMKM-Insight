<?php
// Simulate Sync Data from external sources to UMKM Insight
// Suppress notices/warnings so they don't contaminate JSON output
error_reporting(E_ERROR);

require_once '../config/db.php';
require_once '../includes/auth.php';

header('Content-Type: application/json');

// Only logged in client can sync
if (!isLoggedIn() || $_SESSION['role'] !== 'client') {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
    exit;
}

$user = getCurrentUser($pdo);
$userId = $user['id'];
$smartbankId = $user['smartbank_id'];

if (!$smartbankId) {
    echo json_encode(['status' => 'error', 'message' => 'SmartBank ID belum terhubung. Hubungkan di Profil terlebih dahulu.']);
    exit;
}

try {
    // A. Sync from SmartBank
    $stmt = $pdo->prepare("SELECT id, type, amount, description, transaction_date FROM smartbank_transactions WHERE smartbank_id = ?");
    $stmt->execute([$smartbankId]);
    $sbTransactions = $stmt->fetchAll();

    foreach ($sbTransactions as $trx) {
        $extId = 'SB-' . $trx['id'];
        $ins = $pdo->prepare("INSERT IGNORE INTO transaction_cache 
            (user_id, external_id, source, type, amount, transaction_date, description) 
            VALUES (?, ?, 'SmartBank', ?, ?, ?, ?)");
        $ins->execute([$userId, $extId, $trx['type'], $trx['amount'], $trx['transaction_date'], $trx['description']]);
    }

    // B. Sync from WarungPOS (uses same smartbank_id to link)
    $stmt = $pdo->prepare("SELECT id, product_name, amount, transaction_date FROM external_sales WHERE smartbank_id = ? AND source = 'POS'");
    $stmt->execute([$smartbankId]);
    $posSales = $stmt->fetchAll();

    foreach ($posSales as $sale) {
        $extId = 'POS-' . $sale['id'];
        $ins = $pdo->prepare("INSERT IGNORE INTO transaction_cache 
            (user_id, external_id, source, type, amount, transaction_date, description) 
            VALUES (?, ?, 'WarungPOS', 'Income', ?, ?, ?)");
        $ins->execute([$userId, $extId, $sale['amount'], $sale['transaction_date'], $sale['product_name']]);
    }

    // C. Sync Global Trends from PasarKita (Marketplace)
    $stmt = $pdo->prepare("
        SELECT 
            product_name, 
            COUNT(*) as total_sold,
            AVG(amount) as avg_price
        FROM external_sales 
        WHERE smartbank_id = 'GLOBAL' AND source = 'Marketplace' AND platform_name = 'PasarKita'
        GROUP BY product_name
        ORDER BY total_sold DESC
        LIMIT 10
    ");
    $stmt->execute();
    $globalTrends = $stmt->fetchAll();

    // Clear old trends and insert fresh data (outside transaction, TRUNCATE auto-commits)
    $pdo->exec("DELETE FROM market_trends_cache");
    foreach ($globalTrends as $trend) {
        $ins = $pdo->prepare("INSERT INTO market_trends_cache 
            (product_name, category, total_sold_global, avg_price, trend_direction) 
            VALUES (?, 'Umum', ?, ?, 'up')");
        $ins->execute([$trend['product_name'], $trend['total_sold'], $trend['avg_price']]);
    }

    echo json_encode([
        'status' => 'success', 
        'message' => 'Data berhasil disinkronisasi dari SmartBank, WarungPOS, dan Tren PasarKita.'
    ]);

} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => 'Sync failed: ' . $e->getMessage()]);
}
?>
