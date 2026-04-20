<?php
session_start();
include 'koneksi.php'; 

if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: Login.php");
    exit();
}

$error_message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action']; 
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $password = mysqli_real_escape_string($koneksi, $_POST['password']);

    if ($action === 'register') {
        $checkUser = mysqli_query($koneksi, "SELECT * FROM register WHERE username='$username'");
        if (mysqli_num_rows($checkUser) > 0) {
            $error_message = "Username sudah terdaftar!";
        } else {
            $query = "INSERT INTO register (username, password, role) VALUES ('$username', '$password', 'user')";
            if (mysqli_query($koneksi, $query)) {
                $_SESSION['isLoggedIn'] = true;
                $_SESSION['username'] = $username;
                $_SESSION['role'] = 'user';
                header("Location: Home.php");
                exit();
            }
        }
    } elseif ($action === 'login') {
        $query = "SELECT * FROM register WHERE username='$username' AND password='$password'";
        $result = mysqli_query($koneksi, $query);
        if (mysqli_num_rows($result) > 0) {
            $user_data = mysqli_fetch_assoc($result);
            $_SESSION['isLoggedIn'] = true;
            $_SESSION['username'] = $user_data['username'];
            $_SESSION['role'] = $user_data['role'];
            header("Location: Home.php");
            exit();
        } else {
            $error_message = "Username atau Password salah!";
        }
    }
}
$isLoggedIn = isset($_SESSION['isLoggedIn']) && $_SESSION['isLoggedIn'] === true;
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Auth - Laporan Keluha Wisata </title>
</head>
<body class="bg-stone-100 flex items-center justify-center min-h-screen p-4 md:p-10">
    
    <div class="bg-white rounded-[32px] shadow-2xl w-full max-w-5xl flex overflow-hidden min-h-[600px]">
        
        <div class="hidden md:flex md:w-1/2 relative bg-orange-950 items-center justify-center p-12 overflow-hidden">
            <img src="https://madiunkota.go.id/wp-content/uploads/2022/10/PSC-1.jpg" 
                 class="absolute inset-0 w-full h-full object-cover opacity-40 scale-110" alt="Madiun">
            
            <div class="relative z-10 text-white space-y-4">
                <div class="bg-orange-600/20 backdrop-blur-md border border-white/20 p-8 rounded-3xl">
                    <h2 class="text-4xl font-black italic tracking-tighter">Laporan Wisata <span class="text-orange-500">Madiun</span></h2>
                    <p class="text-orange-100/80 text-sm mt-4 leading-relaxed">
                        Aspirasi Anda adalah energi kami untuk membangun fasilitas wisata Kota Madiun yang lebih nyaman dan modern.
                    </p>
                    <div class="flex gap-2 mt-6">
                        <div class="w-8 h-1 bg-orange-500 rounded-full"></div>
                        <div class="w-2 h-1 bg-orange-500/30 rounded-full"></div>
                        <div class="w-2 h-1 bg-orange-500/30 rounded-full"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="w-full md:w-1/2 p-8 md:p-16 flex flex-col justify-center">
            
            <?php if ($isLoggedIn): ?>
                <div class="text-center">
                    <div class="inline-flex items-center justify-center w-20 h-20 bg-orange-100 rounded-full mb-6">
                        <span class="text-4xl">👋</span>
                    </div>
                    <h1 class="text-3xl font-black text-stone-900">Halo, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h1>
                    <p class="text-stone-500 mt-2">Anda telah masuk sebagai <span class="text-orange-600 font-bold uppercase text-xs"><?php echo $_SESSION['role']; ?></span></p>
                    
                    <div class="mt-10 space-y-3">
                        <a href="Home.php" class="block w-full bg-orange-600 text-white font-bold py-4 rounded-2xl shadow-lg shadow-orange-200 hover:bg-orange-700 transition-all hover:-translate-y-1">
                            Ke Dashboard
                        </a>
                        <a href="Login.php?logout=true" class="block w-full bg-stone-100 text-stone-600 font-bold py-4 rounded-2xl hover:bg-red-50 hover:text-red-600 transition-all">
                            Keluar Akun
                        </a>
                    </div>
                </div>

            <?php else: ?>
                <div id="authContainer">
                    <?php if ($error_message): ?>
                        <div class="mb-6 p-4 bg-red-50 text-red-600 text-xs font-bold rounded-xl border border-red-100 flex items-center gap-3">
                            <span>⚠️</span> <?php echo $error_message; ?>
                        </div>
                    <?php endif; ?>

                    <div id="loginSection" class="hidden animate-in fade-in duration-500">
                        <div class="mb-8 text-left">
                            <h1 class="text-3xl font-black text-stone-900 tracking-tight">Selamat Datang!</h1>
                            <p class="text-stone-400 text-sm mt-1">Silakan masuk untuk melanjutkan laporan Anda.</p>
                        </div>
                        <form action="Login.php" method="POST" class="space-y-5">
                            <input type="hidden" name="action" value="login">
                            <div class="space-y-1">
                                <label class="text-[10px] font-bold text-stone-400 uppercase tracking-widest ml-1">Username</label>
                                <input type="text" name="username" class="w-full px-5 py-4 bg-stone-50 border border-stone-200 rounded-2xl outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all" placeholder="Masukkan username" required>
                            </div>
                            <div class="space-y-1">
                                <label class="text-[10px] font-bold text-stone-400 uppercase tracking-widest ml-1">Password</label>
                                <input type="password" name="password" class="w-full px-5 py-4 bg-stone-50 border border-stone-200 rounded-2xl outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all" placeholder="••••••••" required>
                            </div>
                            <button type="submit" class="w-full bg-orange-600 text-white font-bold py-4 rounded-2xl shadow-xl shadow-orange-100 hover:bg-orange-700 transition-all active:scale-95">Masuk Sekarang</button>
                        </form>
                        <p class="mt-8 text-center text-sm text-stone-500">
                            Belum punya akun? <button onclick="toggleAuth()" class="text-orange-600 font-bold hover:underline">Daftar di sini</button>
                        </p>
                    </div>

                    <div id="registerSection" class="animate-in fade-in duration-500">
                        <div class="mb-8 text-left">
                            <h1 class="text-3xl font-black text-stone-900 tracking-tight">Buat Akun</h1>
                            <p class="text-stone-400 text-sm mt-1">Bergabunglah untuk menjaga keindahan Kota Madiun.</p>
                        </div>
                        <form action="Login.php" method="POST" class="space-y-5">
                            <input type="hidden" name="action" value="register">
                            <div class="space-y-1">
                                <label class="text-[10px] font-bold text-stone-400 uppercase tracking-widest ml-1">Username Baru</label>
                                <input type="text" name="username" class="w-full px-5 py-4 bg-stone-50 border border-stone-200 rounded-2xl outline-none focus:ring-2 focus:ring-orange-500 transition-all" placeholder="Pilih username" required>
                            </div>
                            <div class="space-y-1">
                                <label class="text-[10px] font-bold text-stone-400 uppercase tracking-widest ml-1">Password</label>
                                <input type="password" name="password" class="w-full px-5 py-4 bg-stone-50 border border-stone-200 rounded-2xl outline-none focus:ring-2 focus:ring-orange-500 transition-all" placeholder="Buat password kuat" required>
                            </div>
                            <button type="submit" class="w-full bg-stone-900 text-white font-bold py-4 rounded-2xl shadow-xl shadow-stone-200 hover:bg-black transition-all active:scale-95">Daftar & Masuk</button>
                        </form>
                        <p class="mt-8 text-center text-sm text-stone-500">
                            Sudah punya akun? <button onclick="toggleAuth()" class="text-orange-600 font-bold hover:underline">Login di sini</button>
                        </p>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script>
        function toggleAuth() {
            const login = document.getElementById('loginSection');
            const register = document.getElementById('registerSection');
            login.classList.toggle('hidden');
            register.classList.toggle('hidden');
        }
    </script>
</body>
</html>