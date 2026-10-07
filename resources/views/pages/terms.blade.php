<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TrackSave - Learning Resource Management</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    @vite('resources/css/app.css')

<style>
    .header {
            position: fixed;
            width: 100%;
            top: 0;
            left: 0;
            z-index: 1000;
            transition: transform 0.3s ease-in-out;
        }
</style>
</head>
<body class="bg-gray-100 text-gray-800">

    <header class="header fixed w-full z-20 transition-all duration-300" id="header">
        <div class="container mx-auto flex justify-between items-center p-4 bg-gradient-to-r from-gray-800 to-gray-700 text-white">
            <a href="{{url('/')}}" class="logo flex items-center text-white text-xl font-bold">
                <img src="{{asset('images/logo.png')}}" alt="TrackSave Logo" class="h-8 mr-2">
                TrackSave
            </a>
            <nav class="flex gap-4">
                <a href="{{ url('/') }}" class="px-4 py-2 rounded hover:bg-orange-500 transition duration-300">Home</a>
                <a href="{{ route('resources.index') }}" class="px-4 py-2 rounded hover:bg-orange-500 transition duration-300">Resources</a>
                <a href="{{ route('contact') }}" class="px-4 py-2 rounded hover:bg-orange-500 transition duration-300">Contact Us</a>
                <a href="{{ route('terms') }}" class="px-4 py-2 rounded hover:bg-orange-500 transition duration-300">Terms of Service</a>
            </nav>
            @auth
               <div class="user-profile flex items-center gap-2 cursor-pointer">
        <div class="user-avatar w-8 h-8 rounded-full bg-gray-500 overflow-hidden">
            <img src="{{ Auth::check() ? (Auth::user()->profile_picture ? asset('storage/' . Auth::user()->profile_picture) : asset('images/default-avatar.png')) : 'https://static-00.iconduck.com/assets.00/avatar-default-symbolic-icon-479x512-n8sg74wg.png' }}" alt="{{ Auth::check() ? Auth::user()->name : 'Guest' }}" class="w-full h-full object-cover">
        </div>
        <span>{{ Auth::user()->name }}</span>
    </div>
    @else
    <div class="flex gap-4">
        <a href="{{ route('login') }}" class="px-4 py-2 rounded hover:bg-orange-500 transition duration-300">Login</a>
        <a href="{{ route('register') }}" class="px-4 py-2 rounded hover:bg-orange-500 transition duration-300">Sign Up</a>
    </div>
    @endauth
        </div>
    </header>
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold mb-6">Terms of Service</h1>
        
        <div class="prose max-w-none">
            <h2>1. Acceptance of Terms</h2>
            <p>By accessing and using this website, you accept and agree to be bound by the terms and provision of this agreement.</p>

            <h2>2. Use of Website</h2>
            <p>You agree to use the website for lawful purposes only and in a way that does not infringe the rights of, restrict or inhibit anyone else's use and enjoyment of the website.</p>

            <h2>3. Intellectual Property</h2>
            <p>All trademarks, copyright, database rights and other intellectual property rights in the materials on this website are owned by us or our licensors.</p>

            <h2>4. Limitation of Liability</h2>
            <p>We will not be liable for any loss or damage caused by a distributed denial-of-service attack, viruses or other technologically harmful material that may infect your computer equipment, computer programs, data or other proprietary material due to your use of our website or to your downloading of any material posted on it, or on any website linked to it.</p>

            <h2>5. Governing Law</h2>
            <p>These terms and conditions are governed by and construed in accordance with the laws of [Your Country/State]. You agree to submit to the exclusive jurisdiction of the courts located in [Your Country/State].</p>
        </div>
    </div>
</body>
