<template>
  <div class="h-[100dvh] w-screen bg-moveo-bg flex flex-col overflow-hidden select-none font-sans relative">
    
    <header class="absolute top-4 left-4 right-4 z-50 h-14 bg-moveo-surface/80 backdrop-blur-sm rounded-full flex items-center justify-between p-1.5 shadow-sm border border-moveo-blue-dark/5">
      
      <div class="h-full w-2/5 rounded-full bg-gradient-to-r from-moveo-blue-light to-moveo-blue-dark/80 flex items-center justify-center">
        <div class="w-8 h-8 rounded-full border border-white/50 flex items-center justify-center">
          <span class="text-white text-lg"><i class="fas fa-user"></i></span> </div>
      </div>

      <div class="h-full flex items-center gap-1.5 p-1 pr-1.5">
        <img src="/src/assets/logoblue.png" alt="moveo logo" class="h-6 w-auto" />
        
        <div class="h-full px-3 rounded-full bg-moveo-traffic-orange text-white flex items-center gap-1 font-logo font-bold">
          <span class="text-lg"><i class="fas fa-fire"></i></span> <span class="text-lg">12</span>
        </div>
      </div>
    </header>

    <main class="flex-1 flex flex-col justify-end items-center relative p-6 pt-24">
      
      <div class="absolute bottom-2/5 -translate-y-10 z-10">
        <img src="/src/assets/saludo.png" alt="leo saludando" class="w-72 h-auto" />
      </div>

      <div class="w-full bg-moveo-surface rounded-[40px] p-8 pt-20 flex flex-col items-center shadow-lg border border-moveo-blue-dark/5 space-y-7 z-20">
        
        <h3 class="font-logo text-3xl font-bold tracking-tight text-center text-moveo-blue-light uppercase">
          ¡CUANTO TIEMPO!
        </h3>

        <p class="text-lg text-center font-medium leading-relaxed max-w-xs text-moveo-text-base/80">
          Como sigas así la única <span class="font-bold">“L”</span> que vas a tener será la de <span class="font-bold">“Lerdo”</span>
        </p>

        <button class="w-full h-16 rounded-full bg-gradient-to-r from-moveo-blue-light to-moveo-blue-light flex items-center justify-center shadow-md active:scale-95 transition-transform duration-100">
          <span class="font-logo text-xl font-semibold text-white uppercase tracking-wider">
            empezar desde cero
          </span>
        </button>

      </div>
    </main>

  </div>
</template>