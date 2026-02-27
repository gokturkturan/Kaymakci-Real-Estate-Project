<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Kaymakci Real Estate</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">
    <div class="w-full max-w-md">
        <div class="bg-white rounded-xl shadow-lg p-8">
            <div class="text-center mb-8">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-20 w-auto mx-auto mb-4">
                <h1 class="text-2xl font-bold text-gray-900">Admin Login</h1>
                <p class="text-gray-600 mt-1">Kaymakci Real Estate GmbH</p>
            </div>

            @if($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6">
                    @foreach($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <form action="{{ route('admin.login.post') }}" method="POST" class="space-y-6">
                @csrf

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">E-Mail</label>
                    <input type="email" id="email" name="email" required value="{{ old('email') }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                           placeholder="admin@example.com">
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Passwort</label>
                    <input type="password" id="password" name="password" required
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                           placeholder="********">
                </div>

                <div class="flex items-center">
                    <input type="checkbox" id="remember" name="remember"
                           class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                    <label for="remember" class="ml-2 text-sm text-gray-600">Angemeldet bleiben</label>
                </div>

                <button type="submit"
                        class="w-full bg-blue-600 text-white px-6 py-3 rounded-lg font-medium hover:bg-blue-700 transition">
                    Anmelden
                </button>
            </form>

            <div class="mt-6 text-center">
                <a href="{{ route('properties.index') }}" class="text-sm text-gray-500 hover:text-blue-600 transition">
                    Zurück zur Website
                </a>
            </div>
        </div>
    </div>
</body>
</html>
