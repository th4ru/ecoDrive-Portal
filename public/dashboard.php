<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['user_role'])) {
    header("Location: login.php");
    exit();
}

$role = $_SESSION['user_role'];
$search = $_GET['search'] ?? '';
$edit_id = $_GET['edit_id'] ?? null; // Keeps track of which driver is being edited
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>System Panel - EcoDrive</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 min-h-screen">
    <nav class="bg-gray-900 p-4 text-white shadow flex justify-between items-center">
        <h1 class="text-lg font-bold tracking-wide">EcoDrive Portal <span class="text-xs bg-emerald-500 px-2 py-0.5 rounded ml-2 uppercase"><?= $role ?> Dashboard</span></h1>
        <a href="../actions/admin_action.php?logout=true" class="bg-red-600 px-3 py-1.5 text-sm rounded hover:bg-red-700 transition">Logout</a>
    </nav>

    <main class="max-w-7xl mx-auto my-10 p-6">
        
        <?php if ($role === 'admin'): ?>
            <div class="bg-white rounded-xl shadow border p-6">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
                    <div>
                        <h2 class="text-xl font-bold text-gray-900">Fleet Operations Administration</h2>
                        <p class="text-sm text-gray-500">Manage, inspect, and filter structural independent drivers accounts.</p>
                    </div>
                    <form method="GET" class="flex gap-2 w-full md:w-auto">
                        <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" placeholder="Search drivers by name..." class="border rounded-md px-3 py-1.5 text-sm focus:ring-2 focus:ring-emerald-500 w-full md:w-64">
                        <button type="submit" class="bg-emerald-600 text-white text-sm px-4 py-1.5 rounded hover:bg-emerald-700">Filter</button>
                    </form>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full border-collapse text-left text-sm">
                        <thead>
                            <tr class="bg-gray-100 border-b border-gray-200 text-gray-700 font-medium">
                                <th class="p-3">Driver Name</th>
                                <th class="p-3">Birthday</th>
                                <th class="p-3">Gender</th>
                                <th class="p-3">License Info</th>
                                <th class="p-3">Vehicle Details</th>
                                <th class="p-3 text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            <?php
                            if (!empty($search)) {
                                $stmt = $pdo->prepare("SELECT * FROM users WHERE role = 'driver' AND name LIKE :search ORDER BY id DESC");
                                $stmt->execute([':search' => "%$search%"]);
                            } else {
                                $stmt = $pdo->query("SELECT * FROM users WHERE role = 'driver' ORDER BY id DESC");
                            }

                            $drivers = $stmt->fetchAll();
                            if (count($drivers) === 0):
                            ?>
                                <tr><td colspan="6" class="p-4 text-center text-gray-400">No registered profiles matched your current matrix metrics.</td></tr>
                            <?php else: foreach ($drivers as $driver): ?>
                                
                                <?php if ($edit_id == $driver['id']): ?>
                                    <form action="../actions/admin_action.php" method="POST">
                                        <input type="hidden" name="action" value="update_driver">
                                        <input type="hidden" name="id" value="<?= $driver['id'] ?>">
                                        <tr class="bg-amber-50/50">
                                            <td class="p-2"><input type="text" name="name" value="<?= htmlspecialchars($driver['name']) ?>" required class="border p-1 text-xs rounded w-full"></td>
                                            <td class="p-2"><input type="date" name="birthday" value="<?= htmlspecialchars($driver['birthday']) ?>" required class="border p-1 text-xs rounded w-full"></td>
                                            <td class="p-2">
                                                <select name="gender" required class="border p-1 text-xs rounded w-full">
                                                    <option value="Male" <?= $driver['gender'] === 'Male' ? 'selected' : '' ?>>Male</option>
                                                    <option value="Female" <?= $driver['gender'] === 'Female' ? 'selected' : '' ?>>Female</option>
                                                    <option value="Other" <?= $driver['gender'] === 'Other' ? 'selected' : '' ?>>Other</option>
                                                </select>
                                            </td>
                                            <td class="p-2">
                                                <select name="license_class" required class="border p-1 text-xs rounded w-full">
                                                    <option value="Class A" <?= $driver['license_class'] === 'Class A' ? 'selected' : '' ?>>Class A</option>
                                                    <option value="Class B" <?= $driver['license_class'] === 'Class B' ? 'selected' : '' ?>>Class B</option>
                                                    <option value="Class C" <?= $driver['license_class'] === 'Class C' ? 'selected' : '' ?>>Class C</option>
                                                </select>
                                            </td>
                                            <td class="p-2">
                                                <div class="flex gap-1">
                                                    <input type="text" name="vehicle_model" value="<?= htmlspecialchars($driver['vehicle_model']) ?>" placeholder="Model" required class="border p-1 text-xs rounded w-1/2">
                                                    <input type="text" name="fuel_type" value="<?= htmlspecialchars($driver['fuel_type']) ?>" placeholder="Fuel" required class="border p-1 text-xs rounded w-1/2">
                                                </div>
                                            </td>
                                            <td class="p-2 text-center space-x-1">
                                                <button type="submit" class="bg-blue-600 text-white px-2 py-1 rounded text-xs hover:bg-blue-700">Save</button>
                                                <a href="dashboard.php" class="bg-gray-400 text-white px-2 py-1 rounded text-xs hover:bg-gray-500">Cancel</a>
                                            </td>
                                        </tr>
                                    </form>
                                <?php else: ?>
                                    <tr class="hover:bg-gray-50 transition">
                                        <td class="p-3 font-semibold text-gray-900"><?= htmlspecialchars($driver['name']) ?></td>
                                        <td class="p-3 text-gray-600"><?= htmlspecialchars($driver['birthday']) ?></td>
                                        <td class="p-3 text-gray-600"><?= htmlspecialchars($driver['gender']) ?></td>
                                        <td class="p-3"><span class="px-2 py-0.5 bg-blue-50 text-blue-700 border border-blue-200 rounded text-xs"><?= htmlspecialchars($driver['license_class']) ?></span></td>
                                        <td class="p-3 text-gray-600"><?= htmlspecialchars($driver['vehicle_model']) ?> (<?= htmlspecialchars($driver['fuel_type']) ?>)</td>
                                        <td class="p-3 text-center space-x-3">
                                            <a href="dashboard.php?edit_id=<?= $driver['id'] ?>" class="text-blue-600 hover:underline font-medium text-xs">Edit</a>
                                            <a href="../actions/admin_action.php?delete_id=<?= $driver['id'] ?>" onclick="return confirm('Confirm removal execution sequence?')" class="text-red-600 hover:underline text-xs">Delete</a>
                                        </td>
                                    </tr>
                                <?php endif; ?>

                            <?php endforeach; endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

        <?php else: ?>
            <div class="bg-white rounded-xl shadow border p-6 max-w-xl mx-auto text-center">
                <div class="w-16 h-16 bg-emerald-100 text-emerald-600 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-8 h-8"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                </div>
                <h2 class="text-2xl font-bold text-gray-900 mb-1">Registration Complete!</h2>
                <p class="text-sm text-gray-500 mb-6">Your driver record structure is compiled successfully into our operations ledger.</p>
                <div class="text-left bg-gray-50 p-4 rounded-lg border text-sm space-y-2">
                    <div><span class="font-bold text-gray-700">Driver Assignment:</span> <?= htmlspecialchars($_SESSION['user_name']) ?></div>
                </div>
            </div>
        <?php endif; ?>

    </main>
</body>
</html>