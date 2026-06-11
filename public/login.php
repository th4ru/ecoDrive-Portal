<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EcoDrive System Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen">
    <div class="bg-white p-8 rounded-xl shadow-lg border max-w-sm w-full">
        <div class="text-center mb-6">
            <h2 class="text-2xl font-bold text-gray-900">Administrative Access</h2>
            <p class="text-sm text-gray-500">Sign in to control driver parameters</p>
        </div>

        <?php if(isset($_SESSION['login_error'])): ?>
            <div class="bg-red-50 text-red-700 border border-red-200 text-sm p-3 rounded-md mb-4">
                <?= htmlspecialchars($_SESSION['login_error']); unset($_SESSION['login_error']); ?>
            </div>
        <?php endif; ?>

        <form action="../actions/auth_login.php" method="POST" class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Username</label>
                <input type="text" name="username" required class="w-full border rounded-md p-2 focus:ring-2 focus:ring-emerald-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                <input type="password" name="password" required class="w-full border rounded-md p-2 focus:ring-2 focus:ring-emerald-500">
            </div>
            <button type="submit" class="w-full bg-emerald-600 text-white py-2 rounded-md font-semibold hover:bg-emerald-700 transition">Authenticate Profile</button>
        </form>
        <div class="mt-4 text-center">
            <a href="index.php" class="text-sm text-emerald-600 hover:underline">← Back to Registration</a>
        </div>
    </div>
</body>
</html>