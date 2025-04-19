<div>
    <x-slot name="title">
        Report
    </x-slot>

    <div class="space-y-6">
        <!-- Cartes de statistiques -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <x-stat-card 
                title="Commandes en cours" 
                :value="$reports['daily']['pending_orders']" 
                icon="heroicon-o-clock"
                color="bg-blue-100 text-blue-800"
            />
            <x-stat-card  
                title="Commandes validées" 
                :value="$reports['daily']['completed_orders']" 
                icon="heroicon-o-check-circle"
                color="bg-green-100 text-green-800"
            />
            <x-stat-card 
                title="Recettes journalières" 
                :value="number_format($reports['daily']['revenue'], 2) . ' €'" 
                icon="heroicon-o-currency-euro"
                color="bg-purple-100 text-purple-800"
            />
        </div>
    
</div>
