<x-guest-layout>
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-[#1a1614] bg-opacity-95" 
         style="background-image: url('https://www.vintagestory.at/uploads/monthly_2023_05/bg-stone.jpg'); background-blend-mode: overlay;">
        
        <div class="w-full sm:max-w-md mt-6 px-8 py-8 bg-[#2d241f] border-4 border-[#5c4a3f] shadow-[0_0_20px_rgba(0,0,0,0.8)] rounded-sm">
            
            <div class="flex justify-center mb-6">
                <h1 class="text-[#e2c9b0] text-3xl font-serif uppercase tracking-widest border-b-2 border-[#5c4a3f] pb-2">
                    Nouveau Survivant
                </h1>
            </div>

            <form method="POST" action="{{ route('register') }}" class="space-y-6">
                @csrf

                <div>
                    <label class="block font-serif text-[#a69382] uppercase text-sm mb-1">Nom du Survivant</label>
                    <input id="name" type="text" name="name" required autofocus 
                           class="w-full bg-[#1e1a17] border-2 border-[#4a3c34] text-[#e2c9b0] focus:border-[#8e7a68] focus:ring-0 rounded-none px-4 py-2">
                </div>
                    
                <div>
                    <label class="block font-serif text-[#a69382] uppercase text-sm mb-1">Adresse Email</label>
                    <input id="email" type="email" name="email" required 
                           class="w-full bg-[#1e1a17] border-2 border-[#4a3c34] text-[#e2c9b0] focus:border-[#8e7a68] focus:ring-0 rounded-none px-4 py-2">
                </div>

                <div>
                    <label class="block font-serif text-[#a69382] uppercase text-sm mb-1">Mot de Passe</label>
                    <input id="password" type="password" name="password" required 
                           class="w-full bg-[#1e1a17] border-2 border-[#4a3c34] text-[#e2c9b0] focus:border-[#8e7a68] focus:ring-0 rounded-none px-4 py-2">
                </div>

                <div class="flex flex-col items-center justify-end mt-8">
                    <button type="submit" 
                            class="w-full bg-[#5c4a3f] hover:bg-[#7a6354] text-[#e2c9b0] font-serif uppercase py-3 px-4 border-b-4 border-[#3d2e28] active:border-b-0 transition-all">
                        Forger mon destin
                    </button>

                    <a class="mt-4 text-xs text-[#8e7a68] hover:text-[#e2c9b0] uppercase tracking-tighter transition-colors" href="{{ route('login') }}">
                        Déjà membre de la guilde ?
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-guest-layout>