<?php
// Simulator Endpoint untuk WarungPOS
header('Content-Type: application/json');

require_once '../../config/db.php';

$smartbank_id = $_GET['smartbank_id'] ?? null;

if (!$smartbank_id) {
    echo json_encode(['status' => 'error', 'message' => 'Missing smartbank_id']);
    exit;
}

try {
    // Ambil data dari tabel simulasi external_sales khusus untuk POS
    $stmt = $pdo->prepare("SELECT id, product_name, amount, transaction_date FROM external_sales WHERE smartbank_id = ? AND source = 'POS' ORDER BY transaction_date DESC");
    $stmt->execute([$smartbank_id]);
    $sales = $stmt->fetchAll();

    echo json_encode([
        'status' => 'success',
        'source_app' => 'WarungPOS',
        'data' => $sales
    ]);
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
?>
