# Laporan Analisis dan Refactoring Kode Aplikasi Portal Kuesioner Psikometrik PointMarket

<table><tr><td>Jenis Dokumen</td><td>Contoh laporan praktikum/proyek aplikasi web</td></tr><tr><td>Topik</td><td>MVC, SOLID, Clean Code, High Cohesion, Low Coupling</td></tr><tr><td>Sumber Observasi</td><td>Portal Kuesioner PM - PHP native MVC</td></tr><tr><td>Tanggal</td><td>21 Juni 2026</td></tr></table>

Catatan: dokumen ini adalah artefak pembelajaran. Contoh refactoring ditulis sebagai rancangan edukatif dan tidak mengubah kode aplikasi aslinya. 

Dokumen ini disusun sebagai contoh laporan praktikum/proyek untuk topik analisis dan refactoring kode aplikasi web. Studi kasus yang digunakan adalah Portal Kuesioner PM, yaitu portal kuesioner psikometrik yang menjadi bagian dari ekosistem riset PointMarket. 

Catatan penting: contoh refactoring pada dokumen ini bersifat edukatif. Kode aplikasi riset tidak diubah. Potongan "sesudah refactoring" menunjukkan rancangan perbaikan yang dapat dikerjakan pada branch latihan terpisah oleh mahasiswa. 

## 1. Identitas Proyek

<table><tr><td>Komponen</td><td>Isi</td></tr><tr><td>Nama Aplikasi</td><td>Portal Kuesioner Psikometrik PointMarket</td></tr><tr><td>Jenis Aplikasi</td><td>Aplikasi Web</td></tr><tr><td>Pola Arsitektur</td><td>PHP native MVC</td></tr><tr><td>Topik Praktikum</td><td>MVC, SOLID, Clean Code, High Cohesion, Low Coupling</td></tr><tr><td>Nama Kelompok</td><td>Diisi oleh mahasiswa</td></tr><tr><td>Anggota Kelompok</td><td>Diisi oleh mahasiswa</td></tr><tr><td>Repository</td><td>Diisi sesuai repository kelompok atau repository latihan</td></tr><tr><td>Sumber Observasi Kode</td><td>Diisi oleh mahasiswa</td></tr><tr><td>Tanggal Revisi</td><td>Diisi oleh mahasiswa</td></tr></table>

## 2. Deskripsi Singkat Aplikasi

Portal Kuesioner Psikometrik PointMarket merupakan aplikasi web berbasis PHP dan MySQL yang digunakan untuk mengelola pengisian serta pelaporan hasil kuesioner psikometrik mahasiswa. Berdasarkan struktur repository, aplikasi ini memuat modul VARK, MSLQ, dan AMS. Ketiga instrumen tersebut digunakan untuk mencatat profil gaya belajar, skor strategi/motivasi belajar, dan tipe motivasi akademik mahasiswa. 


Fitur yang teridentifikasi dari repository meliputi:


<table><tr><td>No</td><td>Fitur</td><td>Bukti File/Modul</td></tr><tr><td>1</td><td>Login dan session mahasiswa</td><td>app/Controllers/HomeController.php, app/Models/Student.php</td></tr><tr><td>2</td><td>Login dan dashboard admin</td><td>app/Controllers/AdminController.php, app/Views/admin/index.php</td></tr><tr><td>3</td><td>Kuesioner VARK</td><td>app/Controllers/VarkController.php, app/Views/vark/index.php, vark_questions</td></tr><tr><td>4</td><td>Kuesioner MSLQ</td><td>app/Controllers/MslqController.php, app/Views/mslq/index.php, mslq_questions</td></tr><tr><td>5</td><td>Kuesioner AMS</td><td>app/Controllers/AmsController.php, app/Views/ams/index.php, ams_questions</td></tr><tr><td>6</td><td>Penyimpanan respons</td><td>app/Models/Question.php, tabel responses</td></tr><tr><td>7</td><td>Perhitungan dan penyimpanan hasil</td><td>app/Models/Student.php, app/Controllers/ResultsController.php</td></tr><tr><td>8</td><td>API hasil kuesioner</td><td>app/Controllers/ApiController.php, dev-resources/docs/swagger.yaml</td></tr><tr><td>9</td><td>Pengaturan buka/tutup instrumen</td><td>app/Models/Setting.php, app/Controllers/AdminController.php</td></tr><tr><td>10</td><td>Dashboard ringkasan admin</td><td>AdminController::index()</td></tr></table>

Aplikasi berjalan melalui Docker. File docker-compose.yml menunjukkan layanan aplikasi pada port 8085, database MySQL pada port host 3308, dan phpMyAdmin pada port 8086. 

## 3. Tujuan Refactoring

## Refactoring pada studi kasus ini bertujuan untuk:

1) Memperjelas tanggung jawab antara controller, model, service, repository, dan validator. 

2) Mengurangi controller yang terlalu banyak menangani proses sekaligus. 

3) Memisahkan logika submit kuesioner, skoring psikometrik, histori, dan format output. 

4) Mengurangi duplikasi alur submit pada VARK, MSLQ, dan AMS. 

5) Menyatukan validasi jawaban kuesioner agar aturan input lebih konsisten. 

6) Memindahkan query laporan yang kompleks dari controller ke repository/service. 

7) Membuat kode lebih mudah diuji, dibaca, dan dikembangkan untuk instrumen psikometrik baru. 

## 4. Ruang Lingkup Analisis Kode

Analisis difokuskan pada lima area yang paling terlihat dari repository. 

<table><tr><td>No</td><td>Modul</td><td>File/Method</td><td>Alasan Dipilih</td></tr><tr><td>1</td><td>Pengisian VARK</td><td>VarkController::submit()</td><td>Controller menangani simpan jawaban,proses VARK NLP fusion, pencatatan histori, session, dan redirect</td></tr><tr><td>2</td><td>Pengisian MSLQ dan AMS</td><td>MslqController::submit(),AmsController::submit()</td><td>Alur submit mirip dan berulang pada beberapa controller</td></tr><tr><td>3</td><td>Laporan Hasil</td><td>ResultsController::index()</td><td>Controller menghitung hasil, melakukan query, memperbarui skor, dan membentuk JSON</td></tr><tr><td>4</td><td>Dashboard Admin</td><td>AdminController::index()</td><td>Query statistik, agregasi, transformasi label, dan pagination bercampur dalam controller</td></tr><tr><td>5</td><td>Manajemen Butir Pertanyaan</td><td>AdminController::vark(),AdminController::mslq(),AdminController::ams(),delete_question()</td><td>CRUD pertanyaan berulang dan akses tabel tersebar di controller</td></tr></table>

## 5. Struktur Folder Aplikasi

## Struktur aktual yang teridentifikasi:

```yaml
kuisioner_pm/
| -- app/
| | -- Controllers/
| | | -- AdminController.php
| | | -- AmsController.php
| | | -- ApiController.php
| | | -- DashboardController.php
| | | -- HomeController.php
| | | -- MslqController.php
| | | -- ResultsController.php
| | | -- VarkController.php
| | -- Core/
| | | -- Controller.php
| | | -- Database.php
| | | -- Model.php
| | | -- Router.php
| | -- Helpers/
| | | -- VarkNlpHelper.php
| | -- Models/
| | | -- ClassModel.php
| | | -- Question.php
| | | -- Setting.php
| | | -- Student.php
| | -- Views/
| | | -- admin/
| | | -- ams/
| | | -- api/
| | | -- dashboard/
| | | -- errors/
| | | -- home/
| | | -- layout/
| | | -- mslq/
| | | -- results/
| | | -- vark/
| -- database/
| | | -- db_schema.sql
| | | -- seed_data.sql
| -- dev-resources/
| | | -- docs/
| | | -- swagger.yaml 
```

<table><tr><td>|</td><td>|-- legacy/</td></tr><tr><td>|--</td><td>Docs/</td></tr><tr><td>|--</td><td>public/</td></tr><tr><td>|</td><td>|-- index.php</td></tr><tr><td>|</td><td>|-- css/</td></tr><tr><td>|--</td><td>scripts/</td></tr><tr><td>|--</td><td>docker-compose.yml</td></tr><tr><td>|--</td><td>Dockerfile</td></tr><tr><td>|--</td><td>README.md</td></tr></table>

Catatan arsitektur: repository belum memperlihatkan folder Services, Repositories, atau Validators. Oleh karena itu, bagian refactoring mengusulkan penambahan lapisan tersebut sebagai arah perbaikan modular, bukan sebagai kondisi yang sudah ada. 

## 6. Ringkasan Arsitektur MVC

Aplikasi menggunakan pola MVC sederhana dengan entry point di public/index.php, autoloader PSR-4 sederhana, dan router pada app/Core/Router.php. 

<table><tr><td>Lapisan</td><td>Contoh File</td><td>Tanggung Jawab Saat Ini</td></tr><tr><td>Entry Point</td><td>public/index.php</td><td>Memulai session, mendaftarkan autoloader, dan membuat router</td></tr><tr><td>Router</td><td>app/Core/Router.php</td><td>Memetakan URL ke controller dan method</td></tr><tr><td>Controller</td><td>VarkController, MslqController, AmsController, AdminController</td><td>Menerima request, memanggil model, menyiapkan data view, melakukan redirect</td></tr><tr><td>Model</td><td>Student, Question, Setting, ClassModel</td><td>Mengakses database dan sebagian logika domain seperti skoring</td></tr><tr><td>Helper</td><td>VarkNlpHelper</td><td>Membantu fusion hasil VARK dengan narasi NLP</td></tr><tr><td>View</td><td>app/Views/...</td><td>Menampilkan form kuesioner, dashboard, halaman admin, dan hasil</td></tr><tr><td>Database</td><td>database/db_schema.sql</td><td>Mendefinisikan tabel students, admins, system_settings, vark_questions, mslq_questions, ams_questions, dan responses</td></tr></table>

Ringkasan alur utama: 

1) Mahasiswa login melalui HomeController. 

2) Mahasiswa memilih instrumen, misalnya VARK, MSLQ, atau AMS. 

3) Controller instrumen mengambil daftar pertanyaan dari Question. 

4) Jawaban disimpan ke tabel responses. 

5) Hasil dihitung dan diperbarui ke tabel students. 

6) Dashboard dan API mengambil ringkasan hasil dari database. 

## 7. Daftar Temuan Masalah Kode

<table><tr><td>No</td><td>File/Method</td><td>Masalah Kode</td><td>Prinsip Terkait</td><td>Dampak Negatif</td></tr><tr><td>1</td><td>VarkController::submit()</td><td>Submit VARK melakukan terlalu banyak proses dalam controller</td><td>SRP, Separation of Concerns, High Cohesion</td><td>Sulit diuji dan sulit dipelihara ketika aturan VARK berubah</td></tr><tr><td>2</td><td>MslqController::submit() dan AmsController::submit()</td><td>Alur submit, simpan jawaban, histori, session, dan redirect berulang</td><td>DRY, Clean Code</td><td>Perubahan format submit harus dilakukan di banyak controller</td></tr><tr><td>3</td><td>ResultsController::index()</td><td>Query hasil, perhitungan fallback, update skor, dan JSON disatukan</td><td>SRP, Low Coupling</td><td>Controller bergantung pada detail query dan aturan hasil</td></tr><tr><td>4</td><td>AdminController::index()</td><td>Query dashboard sangat banyak dan transformasi data dilakukan di controller</td><td>SRP, Low Coupling</td><td>Dashboard sulit diuji dan sulit dikembangkan</td></tr><tr><td>5</td><td>AdminController::vark()/mslq()/ams()</td><td>CRUD pertanyaan per instrumen berulang, validasi input belum terpusat</td><td>DRY, OCP, Clean Code</td><td>Menambah instrumen baru berpotensi menambah method serupa</td></tr></table>

## 8. Analisis Before-After Refactoring

## 8.1 Temuan 1 - Controller Terlalu Gemuk pada Submit VARK

## Lokasi Kode

app/Controllers/VarkController.php, method submit(). 

## Kode Sebelum Refactoring

Blok kode (php) 

```php
public function submit()
{
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $studentModel = new \App\Models\Student();
    $questionModel = new Question();
    $answers = $_POST['answers'] ?? [];
    $narrative = $_POST['vark_narrative'] ?? Calculated
    foreach ($answers as $q_id => $val) {
    $questionModel->saveResponse($_SESSION['student_id'], 'VARK', $q_id, $val);
    }
    $result = $studentModel->processVarkNlpFusion($_SESSION['student_id'], $narrative);
    $db = \App\Core\Database::getInstance();
    $stmt = $db->prepare("INSERT INTO quiz_history (student_id, quiz_type, result_label) VALUES (?, 'VARK', ?)");
    $stmt->execute([$_SESSION['student_id'], $result]);
    $_SESSION['quiz_success'] = 'VARK Assessment';
    $this->redirect('/dashboard/profile');
    }
} 
```

## Masalah yang Ditemukan

Method ini tidak hanya menerima request, tetapi juga membaca input mentah, menyimpan jawaban, memanggil proses fusion VARK NLP, menulis histori, mengatur session, dan menentukan redirect. Hal ini membuat controller memiliki terlalu banyak alasan untuk berubah. 

## Prinsip yang Dilanggar

1) Single Responsibility Principle: controller menangani logika request sekaligus proses domain. 

2) Separation of Concerns: penyimpanan, skoring, histori, dan response bercampur. 

3) High Cohesion: tanggung jawab method tidak fokus pada satu jenis pekerjaan. 

## Strategi Refactoring

1) Buat QuestionnaireResponseValidator untuk validasi input. 

2) Buat QuestionnaireSubmissionService untuk alur submit umum. 

3) Buat PsychometricScoringService untuk pemilihan strategi skoring. 

4) Buat QuizHistoryRepository untuk pencatatan histori. 

5) Controller hanya menerima request dan mengarahkan response. 

## Kode Sesudah Refactoring

## Blok kode (php)

```php
public function submit()
{
    if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    $this->redirect('/vark');
    }

    $result = $this->submissionService->submitVark(
    (int) $_SESSION['student_id'],
    $_POST['answers'] ?? [],
    $_POST['vark_narrative'] ??'
    );

    $_SESSION['quiz_success'] = 'VARK Assessment';
    $this->redirect('/dashboard/profile');
} 
```

## Blok kode (php)

```php
class QuestionnaireSubmissionService
{
    public function __construct(
    private QuestionnaireResponseValidator $validator,
    private ResponseRepository $responseRepository,
    private PsychometricScoringService $scoringService,
    private QuizHistoryRepository $historyRepository
    ) {}
    public function submitVark(int $studentId, array $answers, string $narrative): string
    {
    $validAnswers = $this->validator->validateVarkAnswers($answers);
    $this->responseRepository->replaceResponses($studentId, 'VARK', $validAnswers);
    $resultLabel = $this->scoringService->calculateVarkResult($studentId, $narrative);
    $this->historyRepository->recordLabel($studentId, 'VARK', $resultLabel);
    return $resultLabel;
    }
} 
```

## Dampak Perbaikan

Controller menjadi lebih ringkas dan fokus. Perubahan aturan VARK, misalnya perubahan threshold NLP atau cara penyimpanan histori, tidak lagi memerlukan perubahan langsung pada controller. 

## 8.2 Temuan 2 - Duplikasi Alur Submit pada MSLQ dan AMS

## Lokasi Kode

app/Controllers/MslqController.php::submit() dan app/Controllers/AmsController.php::submit(). 

## Kode Sebelum Refactoring

Blok kode (php) 

```php
public function submit()
{
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $studentModel = new \App\Models\Student();
    $questionModel = new Question();
    foreach (($_POST['answers'] ?? []) as $q_id => $val) {
    $questionModel->saveResponse($_SESSION['student_id'], 'MSLQ', $q_id, $val);
    }
    $score = $studentModel->calculateAndSetMslq($_SESSION['student_id']);
    $db = \App\Core\Database::getInstance();
    $stmt = $db->prepare("INSERT INTO quiz_history (student_id, quiz_type, result_label, result_value) VALUES (?, 'MSLQ', ?, ?)");
    $stmt->execute([$_SESSION['student_id'], $score, $score]);
    $_SESSION['quiz_success'] = 'MSLQ Evaluation';
    $this->redirect('/dashboard/profile');
    }
} 
```

## Blok kode (php)

```php
public function submit()
{
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $studentModel = new \App\Models\Student();
    $questionModel = new Question();
    foreach (($_POST['answers'] ?? []) as $q_id => $val) {
    $questionModel->saveResponse($_SESSION['student_id'], 'AMS', $q_id, $val);
    }
    $result = $studentModel->calculateAndSetAms($_SESSION['student_id']);
    $db = \App\Core\Database::getInstance();
    $stmt = $db->prepare("INSERT INTO quiz_history (student_id, quiz_type, result_label) VALUES (?, 'AMS', ?)");
    $stmt->execute([$_SESSION['student_id'], $result]);
    $_SESSION['quiz_success'] = 'AMS Motivation';
    $this->redirect('/dashboard/profile');
    }
} 
```

## Masalah yang Ditemukan

MSLQ dan AMS memiliki pola submit yang hampir sama: membaca answers, menyimpan respons, menghitung hasil, menulis quiz_history, mengatur session, dan redirect. Perbedaannya hanya pada tipe instrumen, metode skoring, dan format hasil. 

Selain itu, variabel seperti $q_id dan $val masih dapat dibuat lebih bermakna dalam contoh pembelajaran, misalnya $questionId dan $answerValue. 

## Prinsip yang Dilanggar

1) DRY: pola submit berulang. 

2) Clean Code: validasi jawaban belum terpusat dan nama variabel input masih terlalu singkat untuk pembelajaran. 

3) OCP: menambah instrumen baru berpotensi menambah controller submit baru dengan pola serupa. 

## Strategi Refactoring

1) Buat satu service submit berbasis tipe instrumen. 

2) Gunakan enum/constant untuk tipe instrumen: VARK, MSLQ, AMS. 

3) Validasi jawaban dipusatkan berdasarkan skala instrumen. 

4) Histori hasil ditulis melalui repository. 

## Kode Sesudah Refactoring

## Blok kode (php)

```php
public function submit()
{
    if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    $this->redirect('/mslq');
    }

    $score = $this->submissionService->submit(
    studentId: (int) $_SESSION['student_id'],
    instrumentType: InstrumentType::MSLQ,
    answers: $_POST['answers'] ?? []
    );

    $_SESSION['quiz_success'] = 'MSLQ Evaluation';
    $this->redirect('/dashboard/profile');
} 
```

## Blok kode (php)

```txt
class QuestionnaireSubmissionService
{
    public function submit(int $studentId, string $instrumentType, array $answers):
    QuestionnaireResult
    {
    $validAnswers = $this->validator->validateByInstrument($instrumentType, $answers);
    $this->responseRepository->replaceResponses($studentId, $instrumentType, $validAnswers);
    $result = $this->scoringService->calculate($studentId, $instrumentType);
    $this->historyRepository->record($studentId, $instrumentType, $result);
    return $result;
    } 
```

## Blok kode (php)

```php
final class QuestionnaireResponseValidator
{
    public function validateByInstrument(string $instrumentType, array $answers): array
    {
    if ($answers === []) {
    throw new InvalidOperationException('Jawaban kuesioner tidak boleh kosong.');
    }
    foreach ($answers as $questionId => $answerValue) {
    $this->assertValidQuestionId($questionId);
    $this->assertValidAnswerValue($instrumentType, $answerValue);
    }
    return $answers;
    }
} 
```

## Dampak Perbaikan

Alur submit menjadi konsisten. Jika ada instrumen baru, mahasiswa cukup menambahkan konfigurasi dan strategi skoring baru tanpa menggandakan struktur controller. 

## 8.3 Temuan 3 - Logika Hasil dan Query Bercampur di ResultsController

## Lokasi Kode

```txt
app/Controllers/ResultsController.php::index(). 
```

## Kode Sebelum Refactoring

## Blok kode (php)

```php
public function index()
{
    if (!isset($_SESSION['student_id'])) {
    $this->redirect('/home');
    }
    $db = Database::getInstance();
    $student_id = $_SESSION['student_id']

    $current = $db->prepare("SELECT vark_type FROM students WHERE id = ?");
    $current->execute(['student_id]);
    $vark_type = $current->fetchColumn();

    if (!$vark_type) {
    $vark = $db->prepare("SELECT nilai_jawaban FROM responses WHERE student_id=? AND tipe_pertanyaan='VARK' GROUP BY nilai_jawaban ORDER BY COUNT(*) DESC LIMIT 1");
    $vark->execute(['student_id]);
    $vark_type = $vark->fetchColumn() ?: 'Unknown';
    }

    $mslq = $db->prepare("SELECT AVG(CAST(nilai_jawaban AS UNSIGNED)) FROM responses WHERE student_id=? AND tipe_pertanyaan='MSLQ Amph);
    $mslq->execute(['student_id]);
    $mslq_score = round($mslq->fetchColumn() ?: 0, 2);

    $ams = $db->prepare("SELECT q.kategori FROM responses r JOIN ams_questions q ON r.question_id=q.id WHERE r.student_id=? AND r.tipe_pertanyaan='AMS' GROUP BY q.kategori ORDER BY AVG(CAST(r.nilai_jawaban AS UNSIGNED)) DESC LIMIT 1");
    $ams->execute(['student_id]);
    $ams_type = $ams->fetchColumn() ?: 'Unknown'; 
```

```perl
(new Student())->updateScores($student_id, $vark_type, $mslq_score, $ams_type);
$this->view('results/index', [
    'vark' => $vark_type,
    'mslq' => $mslq_score,
    'ams' => $ams_type,
    'json' => json_encode(['npm' => $_SESSION['npm'], 'vark' => $vark_type, 'mslq' => $mslq_score, 'ams' => $ams_type], JSON_PRETTY_PRINT)
]); 
```

## Masalah yang Ditemukan

Method index() menanggung banyak tugas: mengecek session, menjalankan query VARK, menghitung MSLQ, menentukan AMS, memperbarui tabel students, dan membentuk data JSON untuk view. Controller menjadi sangat bergantung pada struktur tabel responses, students, dan ams_questions. 

## Prinsip yang Dilanggar

SRP: controller tidak hanya mengatur halaman hasil. 

• Low Coupling: controller terlalu dekat dengan detail database. 

• Clean Code: query panjang menurunkan keterbacaan method. 

## Strategi Refactoring

Pindahkan query hasil ke QuestionnaireResultRepository. 

• Pindahkan aturan fallback dan agregasi ke PsychometricResultService. 

• Bentuk payload JSON melalui presenter/formatter, misalnya QuestionnaireResultPresenter. 

## Kode Sesudah Refactoring

Blok kode (php) 

```php
public function index()
{
    if (!isset($_SESSION['student_id'])) {
    $this->redirect('/home');
    }

    $result = $this->resultService->getCurrentResult((int) $_SESSION['student_id']);
    $this->view('results/index', [
    'vark' => $result->varkType,
    'mslq' => $result->mslqScore,
    'ams' => $result->amsType,
    'json' => $this->resultPresenter->toJson($result, $_SESSION['npm'])
    ]);
} 
```

## Blok kode (php)

```powershell
class PsychometricResultService
{
    public function __construct(
    private QuestionnaireResultRepository $resultRepository,
    private StudentRepository $studentRepository
    ) {}
    public function getCurrentResult(int $studentId): PsychometricResult
    {
    $result = new PsychometricResult(
    varkType: $this->resultRepository->findVarkResult($studentId), 
```

```txt
mslqScore: $this->resultRepository->calculateMslqAverage($studentId), amsType: $this->resultRepository->findDominantAmsType($studentId);  
$this->studentRepository->updatePsychometricSummary($studentId, $result);  
return $result; 
```

## Dampak Perbaikan

Controller hanya menampilkan hasil. Detail query dan aturan perhitungan dapat diuji secara terpisah pada service/repository. 

## 8.4 Temuan 4 - Query Dashboard Admin Terlalu Banyak di Controller

## Lokasi Kode

app/Controllers/AdminController.php::index(). 

## Kode Sebelum Refactoring

## Blok kode (php)

```php
public function index()
{
    $studentModel = new Student();
    $db = Database::getInstance();

    $approvedCount = $studentModel->countApproved();
    $pendingCount = $studentModel->countPending();

    $data = [
    'total' => $approvedCount + $pendingCount,
    'pending' => $pendingCount,
    'settings' => (new Setting())->getAllRaw()

   ];

    $varkRows = $db->query("SELECT vark_type as label, COUNT(*) as value FROM students WHERE vark_type IS NOT NULL AND vark_type != '' GROUP BY vark_type")->fetchAll();
    $data['ams_dist'] = $db->query("SELECT ams_type as label, COUNT(*) as value FROM students WHERE ams_type IS NOT NULL AND ams_type != '' GROUP BY ams_type")->fetchAll();

    $data['mslq_avg'] = $db->query(
    SELECT DATE_FORMAT(submitted_at, '%b %Y') as label, AVG(result_value) as value FROM quiz_history
    WHERE quiz_type = 'MSLQ'
    GROUP BY DATE_FORMAT(submitted_at, '%b %Y'), DATE_FORMAT(submitted_at, '%Y-%m')
    ORDER BY MIN(submitted_at) ASC
    LIMIT 6
    ")->fetchAll();

    $data['recent_history'] = $db->query(
    SELECT h.*, s.nama, s.npm, c.class_name
    FROM quiz_history h
    JOIN students s ON h.student_id = s.id
    LEFT JOIN classes c ON s.keras = c.class_name
    ORDER BY h.submitted_at DESC
    LIMIT $perPage OFFSET $offset
    ")->fetchAll();

    $this->view('admin/index', $data);
} 
```

## Masalah yang Ditemukan

Dashboard admin membutuhkan banyak data agregat: distribusi VARK, distribusi AMS, rata-rata MSLQ, aktivitas, kelengkapan kuesioner, dan histori terbaru. Saat semua query dan transformasi label diletakkan di controller, controller menjadi pusat laporan sekaligus pengendali view. 

## Prinsip yang Dilanggar

1) SRP: controller juga menjadi report builder. 

2) Low Coupling: controller bergantung langsung pada detail SQL. 

3) Clean Code: method panjang dan sulit dibaca sebagai satu alur bisnis. 

## Strategi Refactoring

1) Buat AdminDashboardService sebagai koordinator data dashboard. 

2) Buat AdminDashboardRepository untuk query statistik. 

3) Buat VarkLabelMapper atau formatter untuk pemetaan label V/A/R/K. 

4) Validasi page, perPage, dan offset sebelum dipakai. 

## Kode Sesudah Refactoring

## Blok kode (php)

```php
public function index()
{
    $page = max(1, (int) ($_GET['page'] ?? 1));
    $dashboard = $this->dashboardService->buildDashboard($page, 10);
    $this->view('admin/index', $dashboard->toArray());
} 
```

## Blok kode (php)

```php
class AdminDashboardService
{
    public function __construct(
    private AdminDashboardRepository $repository,
    private VarkLabelMapper $varkLabelMapper
    ) {}
    public function buildDashboard(int $page, int $perPage): AdminDashboardData
    {
    $completion = $this->repository->getQuestionnaireCompletion();
    return new AdminDashboardData(
    totalStudents: $this->repository->countAllStudents(),
    pendingStudents: $this->repository->countPendingStudents(),
    varkDistribution: $this->varkLabelMapper->mapRows($this->repository->getVarkDistribution()),
    amsDistribution: $this->repository->getAmsDistribution(),
    mslqTrend: $this->repository->getMslqTrend(6),
    activity: $this->repository->getRecentActivity(30),
    completion: $completion,
    recentHistory: $this->repository->getRecentHistory($page, $perPage)
    );
    }
} 
```

## Dampak Perbaikan

Dashboard menjadi lebih mudah diuji. Query statistik dapat diubah tanpa menyentuh controller atau view selama format data service tetap sama. 

## 8.5 Temuan 5 - CRUD Pertanyaan Berulang dan Validasi Belum Terpusat

## Lokasi Kode

app/Controllers/AdminController.php::vark(), mslq(), ams(), dan delete_question() 

## Kode Sebelum Refactoring

Blok kode (php) 

```php
public function vark()
{
    $db = Database::getInstance();
    if ($SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'] ?? '';
    $teks = $_POST['teks_pertanyaan'] ?? '';
    if ($id) {
    $stmt = $db->prepare("UPDATE vark_questions SET teks_pertanyaan=?, opt_v=?, opt_a=?, opt_r=?, opt_k=? WHERE id=?");
    $stmt->execute([$teks, $_POST['opt_v'], $_POST['opt_a'], $_POST['opt_r'], $_POST['opt_k'], $id]);
    } else {
    $stmt = $db->prepare("INSERT INTO vark_questions (teks_pertanyaan, opt_v, opt_a, opt_r, opt_k) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$teks, $_POST['opt_v'], $_POST['opt_a'], $_POST['opt_r'], $_POST['opt_k']]);
    }
    $_SESSION['success'] = "Data soal VARK berhasil disimpan!";
    $this->redirect('/admin/vark');
    }
    $questions = $db->query("SELECT * FROM vark_questions")->fetchAll();
    $this->view('admin/questions_vark', ['questions' => $questions, 'title' => 'Manajemen Soal VARK']);
} 
```

## Blok kode (php)

```perl
public function delete_question($type, $id)
{
    $db = Database::getInstance();
    $table = $type . '_questions';
    if (in_array($type, ['vark', 'mslq', 'ams'])) {
    $stmt = $db->prepare("DELETE FROM $table WHERE id = ?");
    $stmt->execute([\$id]);
    $_SESSION['success'] = "Soal berhasil dihapus!";
    }
    $this->redirect('/admin/' . $type);
} 
```

## Masalah yang Ditemukan

CRUD pertanyaan VARK, MSLQ, dan AMS memiliki pola serupa, tetapi ditulis sebagai method terpisah dengan query langsung di controller. Ada whitelist pada delete_question(), tetapi pemilihan tabel tetap dibentuk dari string. Validasi field belum dipusatkan, misalnya validasi teks_pertanyaan, opsi VARK, dimensi MSLQ, atau kategori AMS. 

## Prinsip yang Dilanggar

1) DRY: pola CRUD berulang. 

2) OCP: menambah instrumen baru memerlukan penambahan method controller baru. 

3) Clean Code: validasi input dan query berada di tempat yang sama dengan flow controller. 

## Strategi Refactoring

1) Buat konfigurasi metadata instrumen. 

2) Buat QuestionRepository yang mengetahui tabel dan field per instrumen. 

3) Buat QuestionAdminService untuk proses create/update/delete. 

4) Buat QuestionRequestValidator untuk validasi field. 

## Kode Sesudah Refactoring

## Blok kode (php)

```txt
public function questions(string $instrumentType)
{
    if ($SERVER['REQUEST_METHOD'] === 'POST') {
    $this->questionAdminService->save($instrumentType, $_POST);
    $_SESSION['success'] = 'Data soal berhasil simipan.';
    $this->redirect('/admin/' . strtolower($instrumentType));
    }

    $questions = $this->questionAdminService->list($instrumentType);

    $this->view($this->instrumentViewResolver->questionsView($instrumentType), [
    'questions' => $questions,
    'title' => $this->instrumentViewResolver->title($instrumentType)
    ]);
} 
```

## Blok kode (php)

```php
class QuestionRepository
{
    private const TABLES = [
    'VARK' => 'vark_questions',
    'MSLQ' => 'mslq_questions',
    'AMS' => 'ams_questions',
    ];
    public function findAll(string $instrumentType): array
    {
    $table = $this->tableFor($instrumentType);
    return $this->db->query("SELECT * FROM{$table}")->fetchAll();
    }

    public function delete(string $instrumentType, int $id): bool
    {
    $table = $this->tableFor($instrumentType);
    $stmt = $this->db->prepare("DELETE FROM{$table} WHERE id =?");
    return $stmt->execute([$id]);
    }

    private function tableFor(string $instrumentType): string
    {
    if (!isset(self::TABLES[$instrumentType])) {
    throw new InvalidOperationException('Tipe instrumen tidak dikenal.'); 
    }
    return self::TABLES[$instrumentType];
    }
} 
```

## Dampak Perbaikan

Controller admin lebih kecil. Tabel dan field per instrumen dikelola di satu tempat yang eksplisit. Validasi input lebih mudah dikontrol dan instrumen baru dapat ditambahkan dengan perubahan yang lebih terarah. 

## 9. Class Diagram Sebelum Refactoring

Diagram berikut menggambarkan kondisi ringkas sebelum refactoring. Setiap class ditulis dalam format UML tiga kompartemen, yaitu nama kelas, atribut atau dependency yang digunakan kelas, dan fungsi/metode utama. Gambar dibuat dari kode Graphviz DOT agar dapat direplikasi sebagai artefak laporan teknis. Kode lengkap diagram tersedia pada docs/assets/class_diagram_sebelum_refactoring.dot. 

![image](https://cdn-mineru.openxlab.org.cn/result/2026-06-30/be6cc1a9-ba00-4bf6-9f06-c7113040fd99/a89d82ed1b8801020306e137c555b8d3e33f5f09b5d5c7d88769e0c9169a6fed.jpg)



Gambar 1. Class Diagram Sebelum Refactoring


Interpretasi: controller masih sering menjadi pusat koordinasi request, query, skoring, histori, dan format output. Atribut pada diagram sebelum refactoring dipakai untuk memperlihatkan dependency dan state yang digunakan class; sebagian dependency pada kode aktual masih dibuat sebagai objek lokal di dalam method, sehingga coupling terhadap model dan database tetap terlihat tinggi. 

## 10. Class Diagram Sesudah Refactoring

Diagram berikut merupakan rancangan refactoring yang disarankan untuk branch latihan. Setiap class juga menggunakan tiga kompartemen UML, sehingga pembaca dapat melihat nama kelas, atribut/dependency hasil pemisahan tanggung jawab, serta fungsi/metode utama. Diagram dirender dari kode Graphviz DOT supaya struktur class dapat dibaca sebagai gambar dan tetap memiliki sumber kode yang dapat disunting ulang. Kode lengkap diagram tersedia pada docs/assets/class_diagram_sesudah_refactoring.dot. 

![image](https://cdn-mineru.openxlab.org.cn/result/2026-06-30/be6cc1a9-ba00-4bf6-9f06-c7113040fd99/f32155a24e6f54f1e01b88e3122542e07980c34a1ac709e9e799cec7508e49e0.jpg)



Gambar 2. Class Diagram Sesudah Refactoring


Interpretasi: controller menjadi lapisan koordinasi HTTP. Service menangani alur bisnis. Repository menangani query. Validator menangani validasi input. Struktur ini membuat cohesion meningkat dan coupling controller terhadap database menurun. 

## 11. Analisis Penerapan SOLID

<table><tr><td>Prinsip SOLID</td><td>Kondisi Sebelum</td><td>Perbaikan yang Disarankan</td><td>Dampak</td></tr><tr><td>SRP</td><td>Controller submit dan dashboard memiliki banyak tanggung jawab</td><td>Pindahkan submit, skoring, histori, dan laporan ke service/repository</td><td>Class lebih fokus dan lebih mudah diuji</td></tr><tr><td>OCP</td><td>Menambah instrumen baru cenderung menambah controller/method baru dengan pola mirip</td><td>Gunakan konfigurasi instrumen dan strategi skoring</td><td>Instrumen baru dapat ditambahkan lebih terarah</td></tr><tr><td>LSP</td><td>Belum ada hierarchy service/interface yang dominan untuk dianalisis</td><td>Jika dibuat ScoringStrategyInterface, setiap strategi harus dapat dipakai secara substitutif</td><td>Substitusi skoring VARK/MSLQ/AMS lebih aman</td></tr><tr><td>ISP</td><td>Belum terlihat interface khusus karena aplikasi masih sederhana</td><td>Pisahkan interface repository/service sesuai kebutuhan modul</td><td>Class tidak dipaksa bergantung pada method yang tidak dipakai</td></tr><tr><td>DIP</td><td>Controller bergantung langsung pada Database, Question, dan Student</td><td>Controller bergantung pada service; service bergantung pada interface repository</td><td>Dependency terhadap detail teknis berkurang</td></tr></table>

sederhana, SRP, OCP, dan DIP adalah area yang paling terlihat untuk latihan refactoring. 

## 12. Analisis Clean Code

<table><tr><td>Aspek Clean Code</td><td>Masalah Sebelum</td><td>Perbaikan</td><td>Dampak</td></tr><tr><td>Meaningful Names</td><td>Variabel singkat seperti $q_id,$val, $res kurang ideal untuk pembelajaran</td><td>Gunakan $questionId,$answerValue, $score,$resultLabel</td><td>Maksud kode lebih cepat dipahami</td></tr><tr><td>Small Functions</td><td>AdminController::index () memuat banyak query dan data shaping</td><td>Pecah ke AdminDashboardService dan repository</td><td>Method controller lebih pendek</td></tr><tr><td>Avoid Duplication</td><td>Submit MSLQ dan AMS memiliki pola berulang</td><td>Gunakan QuestionnaireSubmissionService</td><td>Perubahan alur submit cukup di satu tempat</td></tr><tr><td>Clear Responsibility</td><td>Controller mencampur request, query, skoring, histori, dan output</td><td>Pisahkan ke controller, service, repository, validator, presenter</td><td>Tanggung jawab class lebih jelas</td></tr><tr><td>Avoid Magic Values</td><td>Tipe instrumen seperti &#x27;VARK&#x27;, &#x27;MSLQ&#x27;, &#x27;AMS&#x27; tersebar sebagai string</td><td>Gunakan constant atau enum-like class InstrumentType</td><td>Risiko typo berkurang</td></tr><tr><td>Error Handling</td><td>Validasi jawaban belum terlihat eksplisit pada submit controller</td><td>Tambahkan validator yang melempar exception/domain error terkontrol</td><td>Input tidak valid lebih mudah ditangani</td></tr><tr><td>Query Readability</td><td>Query panjang berada langsung di controller</td><td>Pindahkan ke repository dengan nama method bermakna</td><td>Controller lebih mudah dibaca</td></tr></table>

## Contoh perbaikan penamaan sederhana:

Blok kode (php) 

// Sebelum foreach (($_POST['answers'] ?? []) as $q_id => $val) { $questionModel->saveResponse($_SESSION['student_id'], 'MSLQ', $q_id, $val); 

## Blok kode (php)

```scss
// Sesudah
foreach ($answers as $questionId => $answerValue) {
    $responseRepository->saveResponse($studentId, InstrumentType::MSLQ, $questionId, $answerValue);
} 
```

## 13. Analisis High Cohesion dan Low Coupling

<table><tr><td>Aspek</td><td>Sebelum Refactoring</td><td>Sesudah Refactoring</td></tr><tr><td>Cohesion Controller</td><td>Rendah sampai sedang, karena controller mengurus HTTP, query, skoring, histori, dan format output</td><td>Lebih tinggi, karena controller fokus pada request dan response</td></tr><tr><td>Cohesion Service</td><td>Belum tersedia lapisan service khusus</td><td>Service fokus pada business logic kuesioner, skoring, dan dashboard</td></tr><tr><td>Coupling Database</td><td>Controller sering memanggilDatabase::getInstance() dan query langsung</td><td>Controller tidak bergantung langsung pada SQL</td></tr><tr><td>Coupling Instrumen</td><td>VARK/MSLQ/AMS dikelola dengan controller/method terpisah yang mirip</td><td>Instrumen dikelola melalui service dan konfigurasi tipe instrumen</td></tr><tr><td>Maintainability</td><td>Perubahan aturan skoring atau laporan berisiko menyentuh banyak file</td><td>Perubahan lebih terlokalisasi pada service/repository</td></tr><tr><td>Testability</td><td>Controller sulit diuji tanpa database</td><td>Service dapat diuji dengan repository mock/fake</td></tr></table>

Peningkatan cohesion terjadi karena setiap class memiliki fokus yang lebih jelas. Penurunan coupling terjadi karena controller tidak lagi mengetahui detail tabel dan query. Struktur ini juga membantu mahasiswa memahami bahwa refactoring bukan hanya "memindahkan kode", tetapi memperjelas batas tanggung jawab. 

## 14. Bukti Aplikasi Tetap Berjalan

Karena dokumen ini dibuat sebagai contoh laporan tanpa mengubah kode aplikasi riset, pengujian "sesudah refactoring" harus dilakukan pada branch latihan terpisah. Bagian ini menunjukkan format bukti yang perlu diisi mahasiswa setelah menerapkan refactoring pada salinan/branch non-produksi. 

## 14.1 Lingkungan Uji

<table><tr><td>Komponen</td><td>Nilai</td></tr><tr><td>Runtime</td><td>Docker</td></tr><tr><td>URL Aplikasi</td><td>http://localhost:8085</td></tr><tr><td>URL Admin</td><td>http://localhost:8085/admin</td></tr><tr><td>URL API Hasil</td><td>http://localhost:8085/api/results</td></tr><tr><td>Database</td><td>MySQL 8.0</td></tr><tr><td>Port Database Host</td><td>3308</td></tr><tr><td>phpMyAdmin</td><td>http://localhost:8086</td></tr></table>

## 14.2 Perintah Verifikasi

## Blok kode (powershell)

```txt
docker compose up -d 
```

## Blok kode (powershell)

```batch
php -l app/Controllers/VarkController.php  
php -l app/Controllers/MslqController.php  
php -l app/Controllers/AmsController.php  
php -l app/Controllers/ResultsController.php  
php -l app/Controllers/AdminController.php  
php -l app/Models/Student.php  
php -l app/Models/Question.php 
```

## Blok kode (powershell)

```shell
curl http://localhost:8085/
curl http://localhost:8085/api/results 
```

## 14.3 Tabel Bukti Fungsional

<table><tr><td>No</td><td>Fitur yang Diuji</td><td>Kondisi Sebelum</td><td>Kondisi Sesudah Refactoring</td><td>Status</td></tr><tr><td>1</td><td>Login mahasiswa</td><td>Berjalan pada baseline aplikasi</td><td>Diisi setelah uji branch refactoring</td><td>Perlu diuji</td></tr><tr><td>2</td><td>Login admin</td><td>Berjalan pada baseline aplikasi</td><td>Diisi setelah uji branch refactoring</td><td>Perlu diuji</td></tr><tr><td>3</td><td>Pengisian VARK</td><td>Berjalan melalui VarkController</td><td>Diisi setelah uji branch refactoring</td><td>Perlu diuji</td></tr><tr><td>4</td><td>Pengisian MSLQ</td><td>Berjalan melalui MslqController</td><td>Diisi setelah uji branch refactoring</td><td>Perlu diuji</td></tr><tr><td>5</td><td>Pengisian AMS</td><td>Berjalan melalui AmsController</td><td>Diisi setelah uji branch refactoring</td><td>Perlu diuji</td></tr><tr><td>6</td><td>Penyimpanan respons</td><td>Menggunakan tabel responses</td><td>Diisi setelah uji branch refactoring</td><td>Perlu diuji</td></tr><tr><td>7</td><td>Perhitungan hasil</td><td>Menggunakan Student dan ResultsController</td><td>Diisi setelah uji branch refactoring</td><td>Perlu diuji</td></tr><tr><td>8</td><td>Dashboard admin</td><td>Menggunakan AdminController::index()</td><td>Diisi setelah uji branch refactoring</td><td>Perlu diuji</td></tr><tr><td>9</td><td>API /api/results</td><td>Mengembalikan JSON hasil kuesioner</td><td>Diisi setelah uji branch refactoring</td><td>Perlu diuji</td></tr></table>

## 14.4 Placeholder Screenshot

Gunakan screenshot aktual dari branch latihan setelah refactoring. 

## Blok kode (markdown)

```fortran
! [Screenshot Login] (screenshots/login.png)
! [Screenshot Dashboard Mahasiswa] (screenshots/dashboard-mahasiswa.png)
! [Screenshot Form VARK] (screenshots/form-vark.png)
! [Screenshot Hasil Kuesioner] (screenshots/hasil-kuesioner.png)
! [Screenshot Dashboard Admin] (screenshots/dashboard-admin.png)
! [Screenshot API Results] (screenshots/api-results.png) 
```

## 15. Kesimpulan

Berdasarkan analisis kode, Portal Kuesioner Psikometrik PointMarket sudah menggunakan struktur MVC sederhana yang memisahkan controller, model, view, core, dan helper. Aplikasi juga memiliki modul psikometrik nyata, yaitu VARK, MSLQ, dan AMS, serta endpoint API untuk hasil kuesioner. 

Namun, beberapa bagian masih dapat diperbaiki untuk kepentingan maintainability. Controller submit instrumen, controller hasil, dan controller dashboard admin masih memuat terlalu banyak tanggung jawab. Refactoring yang disarankan adalah menambahkan lapisan service, repository, validator, dan presenter/formatter agar kode lebih modular. 

Dengan pemisahan tersebut, controller menjadi lebih ringkas, logika skoring lebih mudah diuji, query laporan lebih terpusat, dan penambahan instrumen psikometrik baru dapat dilakukan dengan risiko perubahan yang lebih rendah. Refactoring juga membantu kita memahami penerapan praktis SOLID, Clean Code, High Cohesion, dan Low Coupling pada aplikasi web nyata. 

## 16. Lampiran

## 16.1 Link Repository

Diisi sesuai repository kelas atau repository latihan yang digunakan mahasiswa. 

Contoh: 

Blok kode (text) 

```txt
https://github.com/nama-kelas/portal-kuesioner-psikometrik 
```

## 16.2 Branch Sebelum dan Sesudah Refactoring

<table><tr><td>Jenis Branch</td><td>Nama Branch</td></tr><tr><td>Branch sebelum refactoring</td><td>before-refactoring</td></tr><tr><td>Branch sesudah refactoring</td><td>after-refactoring</td></tr></table>

## 16.3 Daftar Commit Penting

<table><tr><td>No</td><td>Commit</td><td>Deskripsi Perubahan</td></tr><tr><td>1</td><td>[hash]</td><td>Menambahkan QuestionnaireResponseValidator</td></tr><tr><td>2</td><td>[hash]</td><td>Memindahkan alur submit ke QuestionnaireSubmissionService</td></tr><tr><td>3</td><td>[hash]</td><td>Memindahkan query hasil ke QuestionnaireResultRepository</td></tr><tr><td>4</td><td>[hash]</td><td>Memindahkan query dashboard ke AdminDashboardRepository</td></tr><tr><td>5</td><td>[hash]</td><td>Merapikan CRUD pertanyaan melalui QuestionAdminService</td></tr></table>

## 16.4 Daftar File yang Dianalisis

<table><tr><td>No</td><td>File</td><td>Peran</td></tr><tr><td>1</td><td>README.md</td><td>Deskripsi fitur dan struktur proyek</td></tr><tr><td>2</td><td>public/index.php</td><td>Entry point aplikasi</td></tr><tr><td>3</td><td>app/Core/Router.php</td><td>Routing sederhana berbasis controller/method</td></tr><tr><td>4</td><td>app/Core/Database.php</td><td>Koneksi PDO singleton</td></tr><tr><td>5</td><td>app/Controllers/VarkController.php</td><td>Controller instrumen VARK</td></tr><tr><td>6</td><td>app/Controllers/MslqController.php</td><td>Controller instrumen MSLQ</td></tr><tr><td>7</td><td>app/Controllers/AmsController.php</td><td>Controller instrumen AMS</td></tr><tr><td>8</td><td>app/Controllers/ResultsController.php</td><td>Tampilan hasil psikometrik mahasiswa</td></tr><tr><td>9</td><td>app/Controllers/AdminController.php</td><td>Dashboard dan manajemen admin</td></tr><tr><td>10</td><td>app/Controllers/ApiController.php</td><td>Endpoint JSON hasil kuesioner</td></tr><tr><td>11</td><td>app/Models/Question.php</td><td>Pengambilan pertanyaan dan penyimpanan respons</td></tr><tr><td>12</td><td>app/Models/Student.php</td><td>Login, sinkronisasi, dan perhitungan hasil</td></tr><tr><td>13</td><td>app/Models/Setting.php</td><td>Status buka/tutup instrumen</td></tr><tr><td>14</td><td>database/db_schema.sql</td><td>Skema tabel utama</td></tr><tr><td>15</td><td>dev-resources/docs/swagger.yaml</td><td>Dokumentasi API</td></tr></table>

## 16.5 Rekomendasi Struktur Folder Setelah Refactoring

Blok kode (text) 

```txt
app/
|-- Controllers/
|-- Core/
|-- Helpers/
|-- Models/ 
```

## |-- Repositories/

|-- AdminDashboardRepository.php 

|-- QuestionRepository.php 

|-- QuestionnaireResultRepository.php 

|-- QuizHistoryRepository.php 

|-- ResponseRepository.php 

## |-- Services/

|-- AdminDashboardService.php 

|-- PsychometricResultService.php 

|-- PsychometricScoringService.php 

|-- QuestionnaireSubmissionService.php 

|-- QuestionAdminService.php 

|-- Validators/ 

|-- QuestionnaireResponseValidator.php 

|-- QuestionRequestValidator.php 

|-- Presenters/ 

| |-- QuestionnaireResultPresenter.php 

|-- Views/ 