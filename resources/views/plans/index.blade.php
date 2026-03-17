<x-profile-layout>
    <div class="vs-wrapper">
        <div class="vs-container" style="max-width: 900px;">
            <h1 class="vs-title" style="font-size: 1.8rem; margin-bottom: 2rem;">Choisissez votre abonnement</h1>

            @if(session('error'))
                <div style="background: rgba(153,27,27,0.2); border: 1px solid #991b1b; color: #e2c9b0; padding: 10px; margin-bottom: 20px; text-align: center;">
                    {{ session('error') }}
                </div>
            @endif

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem;">
                @foreach($plans as $plan)
                    <div style="background: var(--vs-input-bg); border: 2px solid var(--vs-border); padding: 1.5rem; text-align: center;">
                        <h2 style="text-transform: uppercase; letter-spacing: 2px; margin-bottom: 0.5rem;">{{ $plan->name }}</h2>
                        <p style="color: var(--vs-text-muted); font-style: italic; margin-bottom: 1rem;">{{ $plan->description }}</p>
                        <p style="font-size: 2rem; font-weight: bold; margin-bottom: 1.5rem;">
                            {{ $plan->price }}€
                            <span style="font-size: 0.9rem; color: var(--vs-text-muted);">/ mois</span>
                        </p>
                        <a href="{{ route('subscribe', $plan) }}"
                           style="display: inline-block; background: #4a5c3f; border-bottom: 4px solid #2b3d20; padding: 0.8rem 2rem; text-decoration: none; text-transform: uppercase; font-weight: bold; color: var(--vs-text-parchment); letter-spacing: 1px;">
                            S'abonner   
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</x-profile-layout>