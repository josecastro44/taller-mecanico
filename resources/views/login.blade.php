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
    <div class="hidden md:block md:w-1/2">
        <img class="w-full h-full object-cover" src="{{ asset('png/fondo_login.png') }}" alt="Fondo Taller">
    </div>

    <div class="w-full md:w-1/2 flex flex-col items-center justify-center bg-white">
        <div class="w-full flex flex-col items-center justify-center">

            <form method="POST" action="{{ route('login.post') }}" class="md:w-96 w-80 flex flex-col items-center justify-center">
                @csrf

                <h2 class="text-4xl text-[#263A47] font-medium">Iniciar sesión</h2>
                <p class="text-sm text-gray-500/90 mt-3">¡Bienvenido de nuevo! Inicia sesión para continuar.</p>

                <div class="flex items-center gap-4 w-full my-5">
                    <div class="w-full h-px bg-gray-300/90"></div>
                    <p class="w-full text-nowrap text-sm text-gray-500/90">Inicia sesión con tu correo</p>
                    <div class="w-full h-px bg-gray-300/90"></div>
                </div>

                @if($errors->any())
                    <div class="w-full bg-red-100 border border-red-400 text-red-700 px-4 py-2 rounded-lg text-sm mb-4 text-center">
                        {{ $errors->first() }}
                    </div>
                @endif

                <div class="flex items-center w-full bg-transparent border border-gray-300/60 h-12 rounded-full overflow-hidden pl-6 gap-2">
                    <svg width="16" height="11" viewBox="0 0 16 11" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M0 .55.571 0H15.43l.57.55v9.9l-.571.55H.57L0 10.45zm1.143 1.138V9.9h13.714V1.69l-6.503 4.8h-.697zM13.749 1.1H2.25L8 5.356z" fill="#728495"/>
                    </svg>
                    <input type="email" name="email" placeholder="Correo electrónico" class="bg-transparent text-gray-500/80 placeholder-gray-500/80 outline-none text-sm w-full h-full" required>   
                 </div>

                <div class="flex items-center mt-6 w-full bg-transparent border border-gray-300/60 h-12 rounded-full overflow-hidden pl-6 gap-2">
                    <svg width="13" height="17" viewBox="0 0 13 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M13 8.5c0-.938-.729-1.7-1.625-1.7h-.812V4.25C10.563 1.907 8.74 0 6.5 0S2.438 1.907 2.438 4.25V6.8h-.813C.729 6.8 0 7.562 0 8.5v6.8c0 .938.729 1.7 1.625 1.7h9.75c.896 0 1.625-.762 1.625-1.7zM4.063 4.25c0-1.406 1.093-2.55 2.437-2.55s2.438 1.144 2.438 2.55V6.8H4.061z" fill="#728495"/>
                    </svg>
                    <input type="password" name="password" placeholder="Contraseña" class="bg-transparent text-gray-500/80 placeholder-gray-500/80 outline-none text-sm w-full h-full" required>
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