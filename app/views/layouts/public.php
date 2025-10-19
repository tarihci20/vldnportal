<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Vildan Portal' ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background: #f5f5f5;
            min-height: 100vh;
        }
    </style>
</head>
<body class="antialiased">
    <div class="min-h-screen py-8 px-4">
        <div class="max-w-7xl mx-auto">
            <!-- Logo/Header -->
            <div class="text-center mb-6">
                <h1 class="text-3xl font-bold text-gray-800 mb-2">
                    <?= isset($title) ? $title : 'Mutlu Ortaokul Etüt İstek Formu' ?>
                </h1>
            </div>
            
            <!-- Content -->
            <div class="bg-white rounded-lg shadow-md p-8">
                <?= $content ?>
            </div>
        </div>
    </div>
    
    <script>
        // Flash message fonksiyonu
        function showFlashMessage(message, type = 'success') {
            const colors = {
                success: 'bg-green-100 border-green-400 text-green-700',
                error: 'bg-red-100 border-red-400 text-red-700',
                warning: 'bg-yellow-100 border-yellow-400 text-yellow-700'
            };
            
            const flash = document.createElement('div');
            flash.className = `${colors[type]} border px-4 py-3 rounded relative mb-4`;
            flash.innerHTML = `
                <span class="block sm:inline">${message}</span>
                <span class="absolute top-0 bottom-0 right-0 px-4 py-3 cursor-pointer" onclick="this.parentElement.remove()">
                    <svg class="fill-current h-6 w-6" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                        <title>Kapat</title>
                        <path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z"/>
                    </svg>
                </span>
            `;
            
            const container = document.querySelector('.bg-white.rounded-lg');
            container.insertBefore(flash, container.firstChild);
            
            setTimeout(() => flash.remove(), 5000);
        }
    </script>
</body>
</html>
