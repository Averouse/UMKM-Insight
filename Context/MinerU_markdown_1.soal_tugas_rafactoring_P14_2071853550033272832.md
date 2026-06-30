# Penyusunan Laporan Analisis dan Refactoring Kode Aplikasi Web

Studi kasus diarahkan pada aplikasi web tugas besar 

<table><tr><td>Komponen</td><td>Keterangan</td></tr><tr><td>Mata Kuliah</td><td>Rekayasa Perangkat Lunak 2</td></tr><tr><td>Pertemuan</td><td>P14</td></tr><tr><td>Topik</td><td>MVC, SOLID, Clean Code, High Cohesion, Low Coupling, dan Refactoring</td></tr><tr><td>Bentuk Tugas</td><td>Penyusunan laporan teknis berbasis analisis kode</td></tr><tr><td>Output Utama</td><td>Laporan DOCX/PDF dan bukti pendukung sesuai ketentuan dosen</td></tr></table>

## Konteks Tugas

Mahasiswa diminta menyusun laporan analisis dan refactoring kode aplikasi web sesuai kelompok tugas besar (tubes) masing-masing. Laporan harus menunjukkan kemampuan membaca struktur aplikasi, menemukan masalah desain kode, merancang perbaikan, membuat diagram class sebelum dan sesudah refactoring, serta membuktikan aplikasi tetap berjalan. 

## 1. Dokumen Acuan dan Fokus Tugas

Tugas ini disusun berdasarkan contoh laporan yang sudah dibuat sebelumnya, yaitu laporan analisis dan refactoring Portal Kuesioner Psikometrik. Mahasiswa tidak diminta menyalin isi contoh laporan, tetapi mengikuti pola berpikir, urutan analisis, dan bentuk bukti yang digunakan pada contoh tersebut. 

Struktur laporan mahasiswa harus mengikuti 16 bagian utama pada contoh laporan, mulai dari Identitas Proyek sampai Lampiran. Bagian 9 dan 10 wajib memuat class diagram sebelum dan sesudah refactoring yang dibuat dari kode Graphviz DOT. 

<table><tr><td>Acuan pada contoh laporan</td><td>Yang harus dipahami mahasiswa</td></tr><tr><td>Identitas proyek dan deskripsi aplikasi</td><td>Laporan harus dimulai dari konteks aplikasi yang dianalisis, bukan langsung membahas teori.</td></tr><tr><td>Struktur folder dan arsitektur MVC</td><td>Mahasiswa harus membaca repository lalu memetakan controller, model, view, route, database, dan helper/service jika ada.</td></tr><tr><td>Daftar 5 temuan masalah kode</td><td>Mahasiswa wajib menemukan minimal 5 masalah nyata atau realistis berdasarkan file/method yang diperiksa.</td></tr><tr><td>Before-after refactoring</td><td>Setiap temuan harus memiliki kode sebelum, masalah, prinsip terkait, strategi perbaikan, kode sesudah, dan dampak.</td></tr></table>

## PRAKTIKUM REKAYASA PERANGKAT LUNAK 2

<table><tr><td>Acuan pada contoh laporan</td><td>Yang harus dipahami mahasiswa</td></tr><tr><td>Class diagram Graphviz</td><td>Diagram sebelum dan sesudah refactoring dibuat dengan kode DOT, dirender menjadi gambar, lalu ditempatkan pada bagian narasi yang relevan.</td></tr><tr><td>Analisis SOLID, Clean Code, Cohesion, Coupling</td><td>Analisis harus dikaitkan dengan temuan kode, bukan berupa ringkasan teori umum.</td></tr><tr><td>Bukti aplikasi tetap berjalan</td><td>Mahasiswa harus menyertakan tabel uji, screenshot, lint/test, endpoint, atau log yang menunjukkan fitur utama tetap aman.</td></tr></table>

## Inti yang Dinilai

Laporan yang baik menunjukkan hubungan langsung antara kode yang diperiksa, masalah desain yang ditemukan, rancangan refactoring yang diusulkan, diagram sebelum-sesudah, dan bukti bahwa aplikasi tidak rusak. 

## 2. Tujuan Pembelajaran

1) Mahasiswa mampu menjelaskan struktur aplikasi web berbasis MVC berdasarkan repository yang dianalisis. 

2) Mahasiswa mampu mengidentifikasi masalah kode berdasarkan prinsip SOLID, Clean Code, High Cohesion, dan Low Coupling. 

3) Mahasiswa mampu menyusun contoh refactoring before-after yang relevan dengan kode aktual. 

4) Mahasiswa mampu membuat class diagram sebelum dan sesudah refactoring menggunakan Graphviz DOT dan gambar hasil render. 

5) Mahasiswa mampu menyusun laporan teknis akademik yang sistematis, berbasis bukti, dan dapat diuji ulang. 

## 3. Skenario Tugas

Anda bertindak sebagai analis kode dan dokumentator teknis. Anda memperoleh sebuah aplikasi web yang sudah berjalan. Tugas Anda bukan membuat aplikasi baru, tetapi menyusun laporan analisis dan rancangan refactoring terhadap aplikasi tersebut. Gunakan aplikasi web yang ditentukan dosen atau aplikasi kelompok masing-masing. Jika dosen menyediakan aplikasi contoh, gunakan aplikasi tersebut sebagai objek analisis. 

Contoh acuan laporan dapat dilihat pada dokumen contoh laporan refactoring Portal Kuesioner Psikometrik. Acuan tersebut digunakan untuk memahami format, kedalaman analisis, dan cara menyajikan bukti, bukan untuk disalin mentah. 

## 4. Ketentuan Umum

1) Kerjakan secara kelompok sesuai pembagian kelas. 

2) Gunakan repository/salinan kerja yang aman. Jangan mengubah aplikasi produksi atau artefak tubes aktif. 

3) Mahasiswa tidak diwajibkan mengubah kode sumber aplikasi utama; contoh before-after dapat berupa rancangan refactoring atau implementasi pada branch/salinan latihan. 

4) Jika melakukan refactoring kode, lakukan pada branch atau salinan latihan, bukan pada sumber utama yang masih digunakan. 

5) Analisis harus berdasarkan file, class, method, route, database, atau modul yang benar-benar ditemukan. 

6) Jangan mengklaim fitur yang tidak ada pada repository. Jika bagian tertentu bersifat asumsi, beri catatan eksplisit. 

7) Setiap temuan wajib dilengkapi lokasi kode, potongan kode sebelum refactoring, strategi refactoring, potongan kode sesudah refactoring, dan dampaknya. 

8) Class diagram wajib menggunakan Graphviz DOT serta disertai gambar hasil render di dalam laporan. 

9) Laporan wajib memuat bukti bahwa fitur utama tetap berjalan setelah refactoring atau setelah simulasi refactoring pada branch latihan. 

## 5. Alur Kerja yang Dianjurkan

<table><tr><td>Langkah</td><td>Aktivitas</td><td>Output Sementara</td></tr><tr><td>1</td><td>Baca README, struktur folder, route/entry point, controller, model, view, dan skema database.</td><td>Catatan struktur aplikasi dan daftar file penting.</td></tr><tr><td>2</td><td>Pilih minimal 5 modul/method yang paling layak dianalisis.</td><td>Tabel ruang lingkup analisis kode.</td></tr><tr><td>3</td><td>Cari masalah kode pada modul yang dipilih.</td><td>Daftar temuan: lokasi, masalah, prinsip terkait, dampak.</td></tr><tr><td>4</td><td>Tulis contoh refactoring before-after untuk setiap temuan.</td><td>Potongan kode sebelum dan sesudah refactoring.</td></tr><tr><td>5</td><td>Buat class diagram sebelum refactoring.</td><td>File DOT dan gambar diagram yang menunjukkan masalah struktur awal.</td></tr><tr><td>6</td><td>Buat class diagram sesudah refactoring.</td><td>File DOT dan gambar diagram yang menunjukkan rancangan controller-service-repository-validator.</td></tr><tr><td>7</td><td>Hubungkan temuan dengan SOLID, Clean Code, High Cohesion, dan Low Coupling.</td><td>Tabel dan narasi analisis prinsip desain.</td></tr><tr><td>8</td><td>Uji atau dokumentasikan bukti aplikasi tetap berjalan.</td><td>Tabel uji, screenshot, log command, lint/test, atau endpoint.</td></tr><tr><td>9</td><td>Susun laporan final sesuai struktur wajib.</td><td>Dokumen laporan siap dikumpulkan.</td></tr></table>

## 6. Tugas yang Harus Dikerjakan

<table><tr><td>No</td><td>Bagian Tugas</td><td>Instruksi</td></tr><tr><td>1</td><td>Identifikasi aplikasi</td><td>Jelaskan nama aplikasi, tujuan, teknologi, framework/pola arsitektur, dan fitur utama.</td></tr><tr><td>2</td><td>Pemetaan struktur kode</td><td>Tampilkan struktur folder aktual dan jelaskan peran controller, model, view, route, service/helper, repository, atau database.</td></tr><tr><td>3</td><td>Analisis MVC</td><td>Jelaskan bagaimana request mengalir dari route/controller menuju model dan view.</td></tr><tr><td>4</td><td>Temuan masalah kode</td><td>Temukan minimal 5 masalah kode yang relevan, misalnya controller terlalu gemuk, query tersebar, duplikasi validasi, magic value, atau nama method kurang jelas.</td></tr><tr><td>5</td><td>Before-after refactoring</td><td>Untuk setiap temuan, sertakan potongan kode sebelum dan sesudah refactoring serta alasan perbaikannya.</td></tr><tr><td>6</td><td>Class diagram</td><td>Buat class diagram sebelum dan sesudah refactoring menggunakan Graphviz DOT, lalu masukkan gambar hasil render ke laporan.</td></tr><tr><td>7</td><td>Analisis prinsip desain</td><td>Bahas penerapan SOLID, Clean Code, High Cohesion, dan Low Coupling berdasarkan temuan kode.</td></tr><tr><td>8</td><td>Bukti aplikasi berjalan</td><td>Sertakan hasil pengujian, screenshot, log, lint, atau bukti endpoint yang menunjukkan fitur utama tetap berjalan.</td></tr><tr><td>9</td><td>Kesimpulan</td><td>Simpulkan dampak refactoring terhadap maintainability, readability, testability, dan risiko perubahan.</td></tr></table>

## 7. Struktur Laporan Wajib

Gunakan 16 bagian berikut agar urutan laporan mahasiswa konsisten dengan contoh laporan Portal Kuesioner Psikometrik. 

<table><tr><td>No</td><td>Bab/Bagian Laporan</td><td>Isi Minimal</td></tr><tr><td>1</td><td>Identitas Proyek</td><td>Nama aplikasi, jenis aplikasi, topik, anggota, repository, tanggal.</td></tr><tr><td>2</td><td>Deskripsi Singkat Aplikasi</td><td>Tujuan aplikasi, pengguna, fitur utama, dan batasan analisis.</td></tr><tr><td>3</td><td>Tujuan Refactoring</td><td>Alasan refactoring dan sasaran kualitas kode.</td></tr><tr><td>4</td><td>Ruang Lingkup Analisis Kode</td><td>Minimal 5 modul/file/method yang dianalisis.</td></tr><tr><td>5</td><td>Struktur Folder Aplikasi</td><td>Struktur aktual, bukan struktur asumsi.</td></tr><tr><td>6</td><td>Ringkasan Arsitektur MVC</td><td>Pemetaan controller, model, view, route, database, helper/service.</td></tr><tr><td>7</td><td>Daftar Temuan Masalah Kode</td><td>Tabel temuan, prinsip terkait, dan dampak negatif.</td></tr><tr><td>8</td><td>Analisis Before-After Refactoring</td><td>Minimal 5 temuan, setiap temuan memuat kode sebelum-sesudah.</td></tr><tr><td>9</td><td>Class Diagram Sebelum</td><td>Kode Graphviz DOT dan gambar diagram.</td></tr></table>

## PRAKTIKUM REKAYASA PERANGKAT LUNAK 2

<table><tr><td>No</td><td>Bab/Bagian Laporan</td><td>Isi Minimal</td></tr><tr><td></td><td>Refactoring</td><td></td></tr><tr><td>10</td><td>Class Diagram Sesudah Refactoring</td><td>Kode Graphviz DOT dan gambar diagram.</td></tr><tr><td>11</td><td>Analisis SOLID</td><td>Bahas SRP, OCP, LSP, ISP, DIP secara jujur sesuai bukti kode.</td></tr><tr><td>12</td><td>Analisis Clean Code</td><td>Naming, small functions, duplication, magic value, separation of concerns.</td></tr><tr><td>13</td><td>High Cohesion dan Low Coupling</td><td>Bandingkan kondisi sebelum dan sesudah refactoring.</td></tr><tr><td>14</td><td>Bukti Aplikasi Tetap Berjalan</td><td>Tabel pengujian, screenshot, command, log, atau hasil endpoint.</td></tr><tr><td>15</td><td>Kesimpulan</td><td>Ringkasan manfaat dan batasan refactoring.</td></tr><tr><td>16</td><td>Lampiran</td><td>Link repository, branch, commit, screenshot, file DOT, dan bukti lain.</td></tr></table>

## 8. Ketentuan Khusus Graphviz

1) Gunakan format Graphviz DOT, bukan hanya gambar manual. 

2) Sediakan kode DOT pada laporan atau lampiran, dan simpan file diagram dengan ekstensi .dot. 

3) Render kode DOT menjadi gambar PNG/SVG, lalu sisipkan gambar tersebut pada narasi bagian class diagram. 

4) Diagram sebelum refactoring harus menunjukkan masalah desain, misalnya controller terlalu banyak bergantung pada model/database. 

5) Diagram sesudah refactoring harus menunjukkan pemisahan tanggung jawab, misalnya controller, service, repository, validator, dan presenter. 

6) Nama class dalam diagram harus konsisten dengan class/method yang dibahas pada laporan. 

Contoh Ringkas Graphviz DOT 

digraph ClassDiagram { rankdir=LR; node [shape=record]; Controller [label="{Controller|+ submit()}"]; Service [label="{Service|+ process()}"]; Controller -> Service; } 

## 9. Format Pengumpulan

<table><tr><td>Komponen</td><td>Ketentuan</td></tr><tr><td>Nama file laporan</td><td>KelompokXX_Laporan_Refactoring_NamaAplikasi.docx atau sesuai instruksi dosen</td></tr><tr><td>Format utama</td><td>DOCX; PDF boleh ditambahkan jika diminta</td></tr><tr><td>Lampiran kode</td><td>Branch repository, ZIP, atau link commit sebelum-sesudah refactoring</td></tr><tr><td>Lampiran diagram</td><td>File DOT dan gambar PNG/SVG class diagram</td></tr><tr><td>Lampiran bukti uji</td><td>Screenshot aplikasi, hasil lint/test, hasil endpoint, atau log command</td></tr><tr><td>Batas waktu</td><td>Sesuai yang dinyatakan pada edlink</td></tr></table>

## 10. Rubrik Penilaian

<table><tr><td>No</td><td>Aspek Penilaian</td><td>Bobot</td><td>Indikator</td></tr><tr><td>1</td><td>Kesesuaian konteks aplikasi</td><td>10</td><td>Deskripsi aplikasi, fitur, dan struktur kode sesuai repository.</td></tr><tr><td>2</td><td>Analisis MVC dan struktur folder</td><td>10</td><td>Pemetaan lapisan aplikasi jelas dan berbasis file aktual.</td></tr><tr><td>3</td><td>Temuan masalah kode</td><td>20</td><td>Minimal 5 temuan kuat, spesifik, dan terkait prinsip desain.</td></tr><tr><td>4</td><td>Before-after refactoring</td><td>20</td><td>Setiap temuan memiliki kode sebelum-sesudah dan alasan perbaikan.</td></tr><tr><td>5</td><td>Class diagram Graphviz</td><td>10</td><td>Kode DOT dan gambar sebelum-sesudah relevan serta mudah dibaca.</td></tr><tr><td>6</td><td>Analisis SOLID/Clean Code/Cohesion/Coupling</td><td>15</td><td>Analisis tidak hanya teori, tetapi dikaitkan dengan kode.</td></tr><tr><td>7</td><td>Bukti aplikasi tetap berjalan</td><td>10</td><td>Ada bukti uji fitur utama setelah refactoring/simulasi refactoring.</td></tr><tr><td>8</td><td>Kerapian laporan dan etika akademik</td><td>5</td><td>Bahasa formal, sitasi/bukti jelas, tidak asal klaim, tidak plagiarisme.</td></tr></table>

## 11. Checklist Sebelum Dikumpulkan

1) Laporan memiliki seluruh bagian wajib dari identitas proyek sampai lampiran. 

2) Minimal ada 5 temuan masalah kode. 

3) Setiap temuan memiliki kode sebelum dan sesudah refactoring. 

4) Analisis SOLID dan Clean Code dikaitkan langsung dengan kode. 

5) Class diagram dibuat dengan Graphviz DOT dan gambar diagram sudah disisipkan di laporan. 

6) Ada bukti aplikasi tetap berjalan atau bukti hasil pengujian branch latihan. 

7) Bagian class diagram, before-after, rubrik, dan bukti uji sudah selaras dengan contoh laporan acuan. 

8) Tidak ada klaim fitur yang tidak ditemukan pada repository. 

9) Nama file dan format pengumpulan sudah sesuai instruksi. 

## 12. Catatan Kejujuran Akademik

Mahasiswa wajib menulis laporan berdasarkan hasil pemeriksaan sendiri terhadap repository kelompok tubes. Contoh laporan boleh digunakan sebagai acuan struktur dan kedalaman, tetapi isi temuan, kode, diagram, dan bukti pengujian harus disesuaikan dengan aplikasi yang dianalisis. 