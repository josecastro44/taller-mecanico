<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <title>Login</title>
</head>
<body>

<div class="flex h-screen w-full">
 <div class="hidden md:flex md:w-1/2 relative items-start justify-center pt-12 h-full">
            <img class="w-full h-full object-cover absolute inset-0 z-0" src="{{ asset('png/tallersito.avif') }}" alt="Fondo Taller">
            
            <div class="relative z-10 text-center px-6 w-full flex flex-col mt-24" style="height: 600px; justify-content: flex-start;">
                
                <h1 style="color: #263A47; font-weight: 900; text-shadow: 2px 2px 6px rgba(255, 255, 255, 0.95); font-size: 3.5rem; letter-spacing: 2px; text-transform: uppercase; margin: 0;">
                    Bienvenido
                </h1>
                
                <h1 style="color: #263A47; font-weight: 900; text-shadow: 2px 2px 6px rgba(255, 255, 255, 0.95); font-size: 3.5rem; letter-spacing: 2px; text-transform: uppercase; margin: 40px 0;">
                    a
                </h1>
                
                <h1 style="color: #263A47; font-weight: 950; text-shadow: 3px 3px 8px rgba(255, 255, 255, 0.95); font-size: 4.2rem; letter-spacing: 4px; text-transform: uppercase; margin: 0;">
                    AutoSys
                </h1>
                
            </div>
        </div>

   <div class="w-full md:w-1/2 flex flex-col items-center justify-between bg-gray-300 py-16" style="min-h: 600px;">
            
           <div class="text-center w-full">
                <h1 style="color: #263A47; font-weight: 950; font-size: 3.8rem; letter-spacing: 3px; text-transform: uppercase; margin: 0;">
                    AutoSys
                </h1>
                <p style="color: #1F2937; font-size: 1.1rem; font-weight: 500; font-style: italic; margin-top: 8px; letter-spacing: 0.3px; padding: 0 15px;">
                    "Precisión en cada proceso, perfección en cada ingreso."
                </p>
            </div>

            <div class="w-full flex flex-col items-center justify-center">
                <form method="POST" action="{{ route('login.post') }}" class="md:w-96 w-80 flex flex-col items-center justify-center">
                    @csrf

                    <h2 class="text-4xl text-[#263A47] font-medium">Iniciar sesión</h2>
                    <p class="text-sm text-gray-500/90 mt-3">¡Bienvenido de nuevo! Inicia sesión para continuar.</p>

                @if($errors->any())
                    <div class="w-full bg-red-100 border border-red-400 text-red-700 px-4 py-2 rounded-lg text-sm mb-4 text-center">
                        {{ $errors->first() }}
                    </div>
                @endif

               <div class="flex items-center w-full bg-white border border-gray-300 h-12 rounded-full overflow-hidden pl-4 gap-2 focus-within:border-[#263A47] shadow-sm">
                    <svg width="16" height="11" viewBox="0 0 16 11" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M0 .55.571 0l7.43 5.7.57.43.571-.43L15.43.01 16 .55v9.91H0V.55ZM1.136V9.43h13.728V.91L8 5.4 1.136.91Z" fill="#263A47"/>
                    </svg>
                    <input type="email" name="email" placeholder="Correo electrónico" class="w-full h-full bg-white text-gray-700 placeholder-gray-400 text-sm outline-none pr-4">
                </div>

                <div class="flex items-center mt-6 w-full bg-white border border-gray-300 h-12 rounded-full overflow-hidden pl-4 gap-2 focus-within:border-[#263A47] shadow-sm">
                    <svg width="13" height="17" viewBox="0 0 13 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M13 8.5c0-.938-.729-1.7-1.625-1.7H11.37V4.25C11.37 1.907 9.4 0 7 0S2.63 1.907 2.63 4.25V6.8H1.625C.73 6.8 0 7.562 0 8.5v6.8c0 .938.73 1.7 1.625 1.7h9.75c.895 0 1.625-.762 1.625-1.7V8.5ZM3.656 4.25c0-1.785 1.5-3.23 3.344-3.23 1.844 0 3.344 1.445 3.344 3.23V6.8H3.656V4.25Z" fill="#263A47"/>
                    </svg>
                    <input type="password" name="password" placeholder="Contraseña" class="w-full h-full bg-white text-gray-700 placeholder-gray-400 text-sm outline-none pr-4">
                </div>

                <div class="w-full flex items-center justify-center mt-8 text-gray-500/80">
                     <a class="text-sm hover:text-[#263A47] transition-colors" href="#">¿Has olvidado tu contraseña?</a>
                </div>

                <button type="submit" class="mt-8 w-full h-11 rounded-full text-white bg-[#263A47] hover:bg-[#4A5B6A] transition-opacity">
                    Iniciar
                </button>

            </form>
        </div>
    </div>
</div>

</body>
</html>