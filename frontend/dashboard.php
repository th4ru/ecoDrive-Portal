<?php
session_start();
// Point directly across the folder division into backend config layer
require_once '../backend/config/database.php';

if (!isset($_SESSION['user_role'])) {
    header("Location: login.php");
    exit();
}

$role = $_SESSION['user_role'];
$search = $_GET['search'] ?? '';
$edit_id = $_GET['edit_id'] ?? null;
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
        <a href="../backend/actions/admin_action.php?logout=true" class="bg-red-600 px-3 py-1.5 text-sm rounded hover:bg-red-700 transition">Logout</a>
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
                                    <form action="../backend/actions/admin_action.php" method="POST">
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
                                            <a href="../backend/actions/admin_action.php?delete_id=<?= $driver['id'] ?>" onclick="return confirm('Confirm removal execution sequence?')" class="text-red-600 hover:underline text-xs">Delete</a>
                                        </td>
                                    </tr>
                                <?php endif; ?>

                            <?php endforeach; endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

        <?php else: ?>
            <div class="bg-white rounded-xl shadow border p-8 max-w-2xl mx-auto">
                <div class="text-center mb-6">
                    <div class="w-16 h-16 bg-emerald-100 text-emerald-600 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-8 h-8"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-900 mb-1">Registration Complete!</h2>
                    <p class="text-sm text-gray-500">Your driver record structure is compiled successfully into our operations ledger.</p>
                </div>
                
                <?php if (isset($_SESSION['driver_data'])): $d = $_SESSION['driver_data']; ?>
                    <div class="space-y-6">
                        <div class="bg-gray-50 p-5 rounded-lg border border-gray-100">
                            <h3 class="text-sm font-bold uppercase tracking-wider text-emerald-700 mb-3 border-b pb-1">Personal Account Ledger</h3>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-sm">
                                <div><span class="font-semibold text-gray-500">Full Name:</span> <span class="text-gray-900 font-medium"><?= htmlspecialchars($_SESSION['user_name']) ?></span></div>
                                <div><span class="font-semibold text-gray-500">Date of Birth:</span> <span class="text-gray-900"><?= htmlspecialchars($d['birthday']) ?></span></div>
                                <div><span class="font-semibold text-gray-500">Gender Identity:</span> <span class="text-gray-900"><?= htmlspecialchars($d['gender']) ?></span></div>
                                <div class="sm:col-span-2"><span class="font-semibold text-gray-500">Physical Address:</span> <span class="text-gray-900"><?= htmlspecialchars($d['address']) ?></span></div>
                            </div>
                        </div>

                        <div class="bg-gray-50 p-5 rounded-lg border border-gray-100">
                            <h3 class="text-sm font-bold uppercase tracking-wider text-emerald-700 mb-3 border-b pb-1">Auto-Fetched Location Details</h3>
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 text-sm">
                                <div><span class="font-semibold text-gray-500">City:</span> <span class="text-gray-900 font-medium"><?= htmlspecialchars($d['city']) ?></span></div>
                                <div><span class="font-semibold text-gray-500">Region:</span> <span class="text-gray-900"><?= htmlspecialchars($d['region']) ?></span></div>
                                <div><span class="font-semibold text-gray-500">Country Code:</span> <span class="text-gray-900 font-mono"><?= htmlspecialchars($d['country']) ?></span></div>
                            </div>
                        </div>

                        <div class="bg-gray-50 p-5 rounded-lg border border-gray-100">
                            <h3 class="text-sm font-bold uppercase tracking-wider text-emerald-700 mb-3 border-b pb-1">Fleet Vehicle Specification</h3>
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 text-sm">
                                <div><span class="font-semibold text-gray-500">License Class:</span> <span class="px-2 py-0.5 bg-emerald-50 text-emerald-700 border border-emerald-200 rounded text-xs font-semibold"><?= htmlspecialchars($d['license_class']) ?></span></div>
                                <div><span class="font-semibold text-gray-500">Vehicle Model:</span> <span class="text-gray-900"><?= htmlspecialchars($d['vehicle_model']) ?></span></div>
                                <div><span class="font-semibold text-gray-500">Fuel Compound:</span> <span class="text-gray-900"><?= htmlspecialchars($d['fuel_type']) ?></span></div>
                            </div>
                        </div>
                    </div>
                <?php else: ?>
                    <p class="text-xs text-center text-gray-400 italic">Profile tracking variables cleared from state cache.</p>
                <?php endif; ?>

                <div class="mt-8 pt-4 border-t border-gray-100 flex justify-between items-center text-xs text-gray-400 font-mono">
                    <span>EcoDrive Pvt Ltd Onboarding Platform</span>
                    <span>Ver 1.1</span>
                </div>
            </div>
        <?php endif; ?>

    </main>
</body>
</html>