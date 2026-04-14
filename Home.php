<?php
session_start();
include 'koneksi.php'; 

$isLoggedIn = isset($_SESSION['isLoggedIn']) && $_SESSION['isLoggedIn'] === true;
$currentUser = $isLoggedIn ? $_SESSION['username'] : 'Tamu';
$userRole = isset($_SESSION['role']) ? $_SESSION['role'] : 'user';


$total_data = 0; $pending_data = 0; $done_data = 0;

if ($userRole === 'admin') {
    $total_q = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM laporan");
    $total_data = mysqli_fetch_assoc($total_q)['total'] ?? 0;
    $pending_q = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM laporan WHERE status='Menunggu' OR status='PENDING'");
    $pending_data = mysqli_fetch_assoc($pending_q)['total'] ?? 0;
    $done_q = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM laporan WHERE status='Selesai'");
    $done_data = mysqli_fetch_assoc($done_q)['total'] ?? 0;
} else if ($isLoggedIn) {
    $user_total_q = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM laporan WHERE nama_pelapor='$currentUser'");
    $total_data = mysqli_fetch_assoc($user_total_q)['total'] ?? 0;
}

$reports_query = mysqli_query($koneksi, "SELECT * FROM laporan ORDER BY tanggal_laporan DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Laporan Wisata</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-orange-50 text-stone-800">

    <nav class="bg-orange-950 text-white shadow-lg sticky top-0 z-50">
        <div class="container mx-auto px-6 py-4 flex justify-between items-center">
            <h1 class="font-bold text-xl tracking-tight">🍂 Laporan Keluhan Wisata</h1>
            <div class="flex items-center space-x-6 text-sm">
                <a href="Home.php" class="hover:text-amber-400 transition">Home</a>
                <a href="Tentang.php" class="hover:text-amber-400 transition">Tentang</a>
                <?php if ($isLoggedIn): ?>
                    <span class="text-amber-300 font-bold bg-orange-900/40 px-3 py-1 rounded-full">Hi, <?php echo htmlspecialchars($currentUser); ?></span>
                    <a href="Login.php?logout=true" class="bg-red-600 px-4 py-2 rounded-lg hover:bg-red-700 transition text-xs font-bold">Logout</a>
                <?php else: ?>
                    <a href="Login.php" class="bg-amber-600 px-5 py-2 rounded-lg hover:bg-amber-700 transition font-bold">Login</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <div class="container mx-auto px-4 py-10 max-w-6xl">

        <?php if ($userRole === 'admin'): ?>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10 w-full">
                <div class="bg-white p-4 rounded-2xl shadow-sm border-l-4 border-orange-950 flex flex-col justify-center min-h-[100px] w-full">
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Total Laporan</p>
                    <h4 class="text-3xl font-black text-slate-800 mt-1"><?php echo $total_data; ?></h4>
                </div>
                <div class="bg-white p-4 rounded-2xl shadow-sm border-l-4 border-amber-500 flex flex-col justify-center min-h-[100px] w-full">
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Menunggu</p>
                    <h4 class="text-3xl font-black text-slate-800 mt-1"><?php echo $pending_data; ?></h4>
                </div>
                <div class="bg-white p-4 rounded-2xl shadow-sm border-l-4 border-green-600 flex flex-col justify-center min-h-[100px] w-full">
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Selesai</p>
                    <h4 class="text-3xl font-black text-slate-800 mt-1"><?php echo $done_data; ?></h4>
                </div>
            </div>
        <?php else: ?>
            <div class="relative rounded-[2rem] overflow-hidden shadow-xl mb-8 h-48">
                <img src="https://static.promediateknologi.id/crop/0x0:0x0/0x0/webp/photo/p2/220/2024/04/04/CaptureJPG-1596998515.jpg" class="w-full h-full object-cover" alt="Banner">
                <div class="absolute inset-0 bg-black/30 flex items-end p-8">
                    <h2 class="text-2xl font-bold text-white">Wisata Kota Madiun</h2>
                </div>
            </div>

            <div class="bg-orange-600 w-48 p-4 rounded-2xl shadow-lg mb-8 flex items-center justify-between text-white transition hover:scale-105">
                <div>
                    <p class="text-[10px] font-bold uppercase opacity-80">Laporan Saya</p>
                    <h4 class="text-2xl font-black"><?php echo $total_data; ?></h4>
                </div>
                <span class="text-2xl">📂</span>
            </div>
        <?php endif; ?>

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
            <div class="lg:col-span-3 space-y-8">
                
                <div class="bg-white p-6 rounded-3xl shadow-sm border border-orange-100">
                    <h3 class="text-lg font-bold text-orange-950 mb-4 px-2 border-l-4 border-orange-600">Info Kategori Keluhan</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="bg-orange-50/50 p-4 rounded-2xl border border-orange-100">
                            <span class="text-orange-700 font-bold text-sm block mb-1">📍 Fasilitas</span>
                            <p class="text-[11px] text-stone-500">Toilet, Parkir, Lampu Jalan, Bangku Taman, dll.</p>
                        </div>
                        <div class="bg-orange-50/50 p-4 rounded-2xl border border-orange-100">
                            <span class="text-orange-700 font-bold text-sm block mb-1">🧹 Kebersihan</span>
                            <p class="text-[11px] text-stone-500">Sampah menumpuk, bau tidak sedap.</p>
                        </div>
                    </div>
                </div>

                <div>
                    <h3 class="text-lg font-bold text-orange-950 mb-4 px-2 border-l-4 border-orange-800">
                        <?php echo ($userRole === 'admin' ? 'Daftar Laporan Masuk' : 'Daftar Laporan Anda'); ?>
                    </h3>
                    <div class="bg-white rounded-2xl shadow-sm border border-orange-100 overflow-hidden">
                        <table class="w-full text-left">
                            <thead class="bg-orange-900 text-white text-[10px] font-bold uppercase">
                                <tr>
                                    <th class="p-4">Tanggal</th>
                                    <th class="p-4">Pelapor</th>
                                    <th class="p-4">Lokasi</th>
                                    <th class="p-4">Keluhan</th>
                                    <th class="p-4 text-center">Status</th>
                                    <?php if ($userRole === 'admin'): ?> <th class="p-4 text-center">Aksi</th> <?php endif; ?>
                                </tr>
                            </thead>
                            <tbody class="text-sm divide-y divide-orange-50">
                                <?php while ($r = mysqli_fetch_assoc($reports_query)): ?>
                                <tr class="hover:bg-orange-50/30 transition">
                                    <td class="p-4 text-gray-400 text-xs"><?php echo $r['tanggal_laporan']; ?></td>
                                    <td class="p-4 font-bold text-orange-800"><?php echo htmlspecialchars($r['nama_pelapor']); ?></td>
                                    <td class="p-4 text-xs"><?php echo htmlspecialchars($r['lokasi_wisata']); ?></td>
                                    <td class="p-4 text-gray-600 text-xs italic"><?php echo htmlspecialchars($r['isi_laporan']); ?></td>
                                    <td class="p-4 text-center">
                                        <span class="px-2 py-1 bg-amber-100 text-orange-900 rounded-md text-[9px] font-bold uppercase">
                                            <?php echo $r['status']; ?>
                                        </span>
                                    </td>
                                    <?php if ($userRole === 'admin'): ?>
                                    <td class="p-4">
                                        <div class="flex flex-col gap-1 items-center">
                                            <a href="update_status.php?id=<?php echo $r['id_laporan']; ?>&status=Selesai" 
                                               class="bg-green-600 text-white px-3 py-1.5 rounded text-[9px] font-bold w-full text-center hover:bg-green-700 transition">
                                               SELESAIKAN
                                            </a>
                                            <a href="hapus_laporan.php?id=<?php echo $r['id_laporan']; ?>" 
                                               class="bg-red-500 text-white px-3 py-1.5 rounded text-[9px] font-bold w-full text-center hover:bg-red-600 transition"
                                               onclick="return confirm('Hapus data ini?')">
                                               HAPUS
                                            </a>
                                        </div>
                                    </td>
                                    <?php endif; ?>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="space-y-6">
                <?php if ($userRole === 'admin'): ?>
                    <div class="bg-white p-5 rounded-3xl shadow-sm border-t-8 border-orange-950">
                        <h3 class="font-bold text-orange-950 mb-4 text-center">Panel Admin</h3>
                        <div class="grid grid-cols-1 gap-3">
                            <a href="#" class="flex items-center justify-between p-3 bg-stone-50 hover:bg-orange-50 rounded-xl border border-stone-100 transition group">
                                <span class="text-xs font-bold text-stone-600 group-hover:text-orange-800">Cetak Laporan</span>
                                <span>🖨️</span>
                            </a>
                            <a href="#" class="flex items-center justify-between p-3 bg-stone-50 hover:bg-orange-50 rounded-xl border border-stone-100 transition group">
                                <span class="text-xs font-bold text-stone-600 group-hover:text-orange-800">Kelola Pengguna</span>
                                <span>👥</span>
                            </a>
                        </div>
                    </div>

                    <div class="bg-white p-5 rounded-3xl shadow-sm border border-orange-100">
                        <h3 class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-4">Efektivitas Penyelesaian</h3>
                        <div class="space-y-4">
                            <div>
                                <div class="flex justify-between text-[10px] font-bold mb-1">
                                    <span>Laporan Selesai</span>
                                    <span class="text-green-600">
                                        <?php echo ($total_data > 0) ? round(($done_data / $total_data) * 100) : 0; ?>%
                                    </span>
                                </div>
                                <div class="w-full bg-stone-100 h-1.5 rounded-full overflow-hidden">
                                    <div class="bg-green-500 h-full" style="width: <?php echo ($total_data > 0) ? ($done_data / $total_data) * 100 : 0; ?>%"></div>
                                </div>
                            </div>
                            <p class="text-[9px] text-stone-400 italic text-center">Monitor performa penyelesaian keluhan secara real-time.</p>
                        </div>
                    </div>

                <?php elseif ($isLoggedIn): ?>
                    <div class="bg-white p-5 rounded-3xl shadow-xl border-t-8 border-orange-600">
                        <h3 class="font-bold text-orange-900 mb-4 text-center">Buat Pengaduan</h3>
                        <form action="proses_simpan.php" method="POST" class="space-y-3">
                            <div>
                                <label class="text-[10px] font-bold text-gray-400 uppercase">Pelapor</label>
                                <input type="text" name="nama_pelapor" value="<?php echo htmlspecialchars($currentUser); ?>" class="w-full p-2 bg-stone-100 border rounded-xl text-xs outline-none cursor-not-allowed" readonly>
                            </div>
                            <input type="text" name="lokasi_wisata" placeholder="Lokasi Wisata" class="w-full p-3 bg-stone-50 border rounded-xl text-sm outline-none" required>
                            <textarea name="isi_laporan" rows="3" placeholder="Isi Keluhan" class="w-full p-3 bg-stone-50 border rounded-xl text-sm outline-none" required></textarea>
                            <button type="submit" class="w-full bg-orange-600 text-white py-3 rounded-xl font-bold shadow-lg shadow-orange-100 hover:bg-orange-700 transition">Kirim Laporan</button>
                        </form>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>