<?php
function hitungNilaiAkhir($tugas, $uts, $uas) {
    return ($tugas * 0.30) + ($uts * 0.30) + ($uas * 0.40);
}

function tentukanGrade($nilaiAkhir) {
    if ($nilaiAkhir >= 85) {
        return "A";
    } elseif ($nilaiAkhir >= 75) {
        return "B";
    } elseif ($nilaiAkhir >= 65) {
        return "C";
    } elseif ($nilaiAkhir >= 50) {
        return "D";
    }
    return "E";
}

function tentukanStatus($nilaiAkhir) {
    return $nilaiAkhir >= 65 ? "Lulus" : "Tidak Lulus";
}

function tentukanPredikat($grade) {
    $predikat = [
        "A" => "Sangat Baik",
        "B" => "Baik",
        "C" => "Cukup",
        "D" => "Kurang",
        "E" => "Sangat Kurang"
    ];

    return $predikat[$grade] ?? "-";
}

$nama = "";
$nim = "";
$tugas = "";
$uts = "";
$uas = "";
$hasil = null;
$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nama = trim($_POST["nama"] ?? "");
    $nim = trim($_POST["nim"] ?? "");
    $tugas = $_POST["tugas"] ?? "";
    $uts = $_POST["uts"] ?? "";
    $uas = $_POST["uas"] ?? "";

    if ($nama === "" || $nim === "") {
        $error = "Nama dan NIM wajib diisi.";
    } elseif (!is_numeric($tugas) || !is_numeric($uts) || !is_numeric($uas)) {
        $error = "Nilai tugas, UTS, dan UAS harus berupa angka.";
    } elseif (
        $tugas < 0 || $tugas > 100 ||
        $uts < 0 || $uts > 100 ||
        $uas < 0 || $uas > 100
    ) {
        $error = "Setiap nilai harus berada dalam rentang 0 sampai 100.";
    } else {
        $nilaiAkhir = hitungNilaiAkhir((float)$tugas, (float)$uts, (float)$uas);
        $grade = tentukanGrade($nilaiAkhir);

        $hasil = [
            "nilaiAkhir" => $nilaiAkhir,
            "grade" => $grade,
            "status" => tentukanStatus($nilaiAkhir),
            "predikat" => tentukanPredikat($grade)
        ];
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Penghitung Nilai Mahasiswa</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <main class="page-shell">
        <header class="topbar">
            <div class="brand-mark">N</div>
            <div>
                <p class="eyebrow">PEMROGRAMAN BERBASIS WEB · TUGAS 2</p>
                <h1>Penghitung Nilai Mahasiswa</h1>
            </div>
        </header>

        <section class="intro">
            <div>
                <span class="tag">Kalkulasi akademik</span>
                <h2>Hitung nilai akhir dengan lebih mudah.</h2>
                <p>Masukkan identitas dan nilai komponen mahasiswa. Nilai akhir, grade, predikat, serta status kelulusan akan dihitung otomatis.</p>
            </div>
            <div class="intro-icon" aria-hidden="true">
                <span>✓</span>
                <small>Nilai<br>terhitung</small>
            </div>
        </section>

        <section class="content-grid">
            <form class="card form-card" method="POST" action="">
                <div class="section-heading">
                    <span class="step">01</span>
                    <div>
                        <h3>Data mahasiswa</h3>
                        <p>Lengkapi identitas dan nilai berikut.</p>
                    </div>
                </div>

                <?php if ($error !== ""): ?>
                    <div class="alert" role="alert"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>

                <label for="nama">Nama lengkap</label>
                <input type="text" id="nama" name="nama" placeholder="Masukkan nama"
                    value="<?= htmlspecialchars($nama) ?>" required>

                <label for="nim">NIM</label>
                <input type="text" id="nim" name="nim" placeholder="Masukkan NIM"
                    value="<?= htmlspecialchars($nim) ?>" required>

                <div class="section-heading score-heading">
                    <span class="step">02</span>
                    <div>
                        <h3>Nilai komponen</h3>
                        <p>Isi nilai dari 0 sampai 100.</p>
                    </div>
                </div>

                <div class="score-grid">
                    <div class="score-field">
                        <label for="tugas">Nilai tugas <span>30%</span></label>
                        <input type="number" id="tugas" name="tugas" min="0" max="100" step="any"
                            placeholder="0–100" value="<?= htmlspecialchars((string)$tugas) ?>" required>
                    </div>
                    <div class="score-field">
                        <label for="uts">Nilai UTS <span>30%</span></label>
                        <input type="number" id="uts" name="uts" min="0" max="100" step="any"
                            placeholder="0–100" value="<?= htmlspecialchars((string)$uts) ?>" required>
                    </div>
                    <div class="score-field full-width">
                        <label for="uas">Nilai UAS <span>40%</span></label>
                        <input type="number" id="uas" name="uas" min="0" max="100" step="any"
                            placeholder="0–100" value="<?= htmlspecialchars((string)$uas) ?>" required>
                    </div>
                </div>

                <div class="weight-note">
                    <strong>Bobot penilaian</strong>
                    <div><span>Tugas 30%</span><span>UTS 30%</span><span>UAS 40%</span></div>
                </div>

                <button type="submit">Hitung nilai akhir <span aria-hidden="true">→</span></button>
            </form>

            <aside class="card result-card">
                <div class="section-heading">
                    <span class="step">03</span>
                    <div>
                        <h3>Hasil perhitungan</h3>
                        <p>Ringkasan hasil akan tampil di sini.</p>
                    </div>
                </div>

                <?php if ($hasil !== null): ?>
                    <div class="result-hero">
                        <p>Nilai akhir</p>
                        <div class="final-score"><?= number_format($hasil["nilaiAkhir"], 2, ",", ".") ?></div>
                        <span class="grade-badge">Grade <?= htmlspecialchars($hasil["grade"]) ?></span>
                    </div>
                    <div class="result-details">
                        <div><span>Nama</span><strong><?= htmlspecialchars($nama) ?></strong></div>
                        <div><span>NIM</span><strong><?= htmlspecialchars($nim) ?></strong></div>
                        <div><span>Nilai tugas</span><strong><?= htmlspecialchars((string)$tugas) ?></strong></div>
                        <div><span>Nilai UTS</span><strong><?= htmlspecialchars((string)$uts) ?></strong></div>
                        <div><span>Nilai UAS</span><strong><?= htmlspecialchars((string)$uas) ?></strong></div>
                        <div><span>Predikat</span><strong><?= htmlspecialchars($hasil["predikat"]) ?></strong></div>
                    </div>
                    <div class="status <?= $hasil["status"] === "Lulus" ? "pass" : "fail" ?>">
                        <span class="status-dot"></span>
                        <div>
                            <strong><?= htmlspecialchars($hasil["status"]) ?></strong>
                            <small><?= $hasil["status"] === "Lulus" ? "Nilai akhir memenuhi batas kelulusan." : "Nilai akhir belum mencapai batas 65." ?></small>
                        </div>
                    </div>
                    <p class="formula">Rumus: (Tugas × 0,30) + (UTS × 0,30) + (UAS × 0,40)</p>
                <?php else: ?>
                    <div class="empty-state">
                        <div class="empty-icon">∑</div>
                        <h4>Hasilmu akan muncul di sini</h4>
                        <p>Isi semua kolom di sebelah kiri, lalu tekan tombol <strong>Hitung nilai akhir</strong>.</p>
                        <div class="mini-rule"><span></span><span></span><span></span></div>
                        <small>Grade dan kelulusan ditentukan otomatis.</small>
                    </div>
                <?php endif; ?>
            </aside>
        </section>

        <footer>
            <span>Diya Naura Alifa</span>
            <span>Belajar · Hitung · Berkembang</span>
        </footer>
    </main>
</body>
</html>
