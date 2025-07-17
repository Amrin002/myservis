<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - Service Management System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>

<body class="bg-gradient-to-br from-blue-50 to-indigo-100 min-h-screen">
    <div class="min-h-screen flex items-center justify-center px-4">
        <div class="max-w-md w-full">
            <!-- Header -->
            <div class="text-center mb-8">
                <div class="mb-6">
                    <i class="fas fa-tools text-6xl text-indigo-600"></i>
                </div>
                <h1 class="text-3xl font-bold text-gray-800 mb-2">Service Management System</h1>
                <p class="text-gray-600">Pilih jenis akses untuk melanjutkan</p>
            </div>

            <!-- Selection Cards -->
            <div class="space-y-4">
                <!-- Admin Button -->
                <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300">
                    <a href="{{ route('home.admin') }}" class="block p-6 text-center group">
                        <div class="flex items-center justify-center mb-4">
                            <div
                                class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center group-hover:bg-red-200 transition-colors">
                                <i class="fas fa-user-shield text-2xl text-red-600"></i>
                            </div>
                        </div>
                        <h2 class="text-xl font-semibold text-gray-800 mb-2">Admin</h2>
                        <p class="text-gray-600 text-sm">Akses penuh untuk mengelola sistem, data pelanggan, dan laporan
                        </p>
                        <div class="mt-4">
                            <span
                                class="inline-flex items-center px-4 py-2 bg-red-600 text-white rounded-lg group-hover:bg-red-700 transition-colors">
                                Masuk sebagai Admin
                                <i class="fas fa-arrow-right ml-2"></i>
                            </span>
                        </div>
                    </a>
                </div>

                <!-- Tuser Button -->
                <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300">
                    <a href="{{ route('home.tuser') }}" class="block p-6 text-center group">
                        <div class="flex items-center justify-center mb-4">
                            <div
                                class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center group-hover:bg-blue-200 transition-colors">
                                <i class="fas fa-user-cog text-2xl text-blue-600"></i>
                            </div>
                        </div>
                        <h2 class="text-xl font-semibold text-gray-800 mb-2">Teknisi</h2>
                        <p class="text-gray-600 text-sm">Akses untuk teknisi mengelola pekerjaan dan status servis</p>
                        <div class="mt-4">
                            <span
                                class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg group-hover:bg-blue-700 transition-colors">
                                Masuk sebagai Teknisi
                                <i class="fas fa-arrow-right ml-2"></i>
                            </span>
                        </div>
                    </a>
                </div>
            </div>

            <!-- Footer -->
            <div class="text-center mt-8 text-gray-500 text-sm">
                <p>&copy; 2024 Service Management System. All rights reserved.</p>
            </div>
        </div>
    </div>
</body>

</html>
