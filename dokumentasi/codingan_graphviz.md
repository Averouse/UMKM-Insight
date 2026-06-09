digraph AlgoritmaGreedyBundling {
    // Pengaturan gaya grafik
    node [fontname="Helvetica,Arial,sans-serif", shape=box, style="rounded,filled", fillcolor="#f8fafc", color="#cbd5e1"];
    edge [fontname="Helvetica,Arial,sans-serif", fontsize=10, color="#64748b"];
    
    // Node Start dan End
    Start [shape=oval, fillcolor="#10b981", color="#059669", fontcolor=white, label="Mulai Engine Analitik"];
    End [shape=oval, fillcolor="#f43f5e", color="#e11d48", fontcolor=white, label="Cetak Insight Bundling ke Layar\n(Selesai)"];
    EndBatal [shape=oval, fillcolor="#94a3b8", color="#475569", fontcolor=white, label="Batal\n(Selesai)"];

    // Data Pipeline
    LoadData [label="Tarik Dataset Produk\n(Data: Stok & Total Terjual)"];
    HitungRasio [label="Hitung Rasio 'Barang Mati'\n(Rumus: Stok / Total Terjual)"];
    
    CekJumlah [shape=diamond, fillcolor="#bae6fd", color="#0284c7", label="Apakah jumlah jenis\nproduk >= 2?"];

    // Proses Inti Greedy (Sorting)
    SortDeadStock [fillcolor="#fef08a", color="#ca8a04", label="GREEDY SORT 1:\nUrutkan Daftar A (Dead Stock)\nberdasarkan Rasio Tertinggi ke Terendah"];
    SortBestSeller [fillcolor="#fef08a", color="#ca8a04", label="GREEDY SORT 2:\nUrutkan Daftar B (Best Seller)\nberdasarkan Penjualan Tertinggi ke Terendah"];
    
    // Proses Inti Greedy (Selection)
    AmbilDeadStock [fillcolor="#bbf7d0", color="#16a34a", label="GREEDY SELECT 1:\nAmbil Produk Paling Mati (Rank #1 Daftar A)"];
    AmbilBestSeller [fillcolor="#bbf7d0", color="#16a34a", label="GREEDY SELECT 2:\nAmbil Produk Paling Laris (Rank #1 Daftar B)"];
    
    CekSama [shape=diamond, fillcolor="#bae6fd", color="#0284c7", label="Apakah Produk #1 Daftar A\nSAMA DENGAN\nProduk #1 Daftar B?"];
    
    AmbilBestSeller2 [fillcolor="#fed7aa", color="#ea580c", label="Ambil Produk Laris Rank #2\n(Mencegah bundling produk yang sama)"];
    
    // Pemasangan (Pairing)
    BuatBundling [fillcolor="#c084fc", color="#7e22ce", fontcolor=white, label="PROSES BUNDLING:\nGabungkan Produk Mati + Produk Laris"];

    // Alur Garis
    Start -> LoadData;
    LoadData -> HitungRasio;
    HitungRasio -> CekJumlah;
    
    CekJumlah -> EndBatal [label="Tidak (Data Kurang)"];
    CekJumlah -> SortDeadStock [label="Ya (Cukup)"];
    
    SortDeadStock -> SortBestSeller;
    SortBestSeller -> AmbilDeadStock;
    AmbilDeadStock -> AmbilBestSeller;
    
    AmbilBestSeller -> CekSama;
    
    CekSama -> BuatBundling [label="Tidak (Aman)"];
    CekSama -> AmbilBestSeller2 [label="Ya (Produk Sama)"];
    
    AmbilBestSeller2 -> BuatBundling;
    
    BuatBundling -> End;
}

\