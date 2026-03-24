@extends('layouts.public')

@section('title', 'History')

@section('content')
<div class="py-8 md:py-12 lg:py-16 bg-white">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-4xl font-extrabold text-deep-forest mb-8 border-l-4 border-tiger-orange pl-4">History of Buguey</h1>
        
        <div class="prose prose-lg max-w-none">
            <div class="bg-sea-green bg-opacity-10 border-l-4 border-sea-green p-6 rounded-r-lg mb-8">
                <p class="text-gray-800 leading-relaxed font-medium">
                    The Municipality of Buguey is a 3rd-class municipality and a coastal town in the province of Cagayan, Philippines. 
                    Its rich history dates back to the Spanish colonial period, and the town has grown into a vibrant 
                    community where about 80% of families are engaged in farming, fishing, and related activities.
                </p>
            </div>
            
            <h2 class="text-2xl font-bold text-deep-forest mt-8 mb-4">Etymology - Origin of the Name</h2>
            <p class="text-gray-700 leading-relaxed mb-6">
                Buguey derived its name from the Ibanag word <strong>"Navugay,"</strong> which means <strong>capsized</strong>. 
                In the early 1600s, sea pirates stole the town's largest bell, but a strong gust of wind caused their 
                vinta (boat) to sink in the Babuyan Channel. The locals shouted <em>"Navugay Ira"</em> in joy, and the 
                word eventually evolved into "Buguey".
            </p>

            <h2 class="text-2xl font-bold text-deep-forest mt-8 mb-4">Historical Timeline</h2>
            <div class="space-y-6 mt-6">
                <div class="flex gap-4">
                    <div class="flex-shrink-0 w-40 font-bold text-tiger-orange text-lg">May 20, 1623</div>
                    <div class="flex-1 text-gray-700">
                        <strong>Foundation by Royal Decree:</strong> The town was officially founded by a Royal Decree from the King of Spain, 
                        establishing Buguey as an important settlement in Northern Luzon during the Spanish colonial period.
                    </div>
                </div>
                <div class="flex gap-4">
                    <div class="flex-shrink-0 w-40 font-bold text-tiger-orange text-lg">1901</div>
                    <div class="flex-1 text-gray-700">
                        <strong>Barrio Status:</strong> During the American colonial period, Buguey was reduced to a barrio (village) 
                        and administratively attached to the municipality of Camalaniugan.
                    </div>
                </div>
                <div class="flex gap-4">
                    <div class="flex-shrink-0 w-40 font-bold text-tiger-orange text-lg">July 26, 1915</div>
                    <div class="flex-1 text-gray-700">
                        <strong>Municipal Status Restored:</strong> Buguey's municipal status was officially restored, 
                        re-establishing its independence and local governance.
                    </div>
                </div>
                <div class="flex gap-4">
                    <div class="flex-shrink-0 w-40 font-bold text-tiger-orange text-lg">Modern Era</div>
                    <div class="flex-1 text-gray-700">
                        <strong>Progressive Municipality:</strong> Today, Buguey continues to thrive as a 3rd-class municipality with a strong focus on 
                        agriculture, fisheries, and sustainable development. The town serves as an important agricultural hub in Cagayan Province.
                    </div>
                </div>
            </div>

            <h2 class="text-2xl font-bold text-deep-forest mt-8 mb-4">Socio-Economic Profile</h2>
            <p class="text-gray-700 leading-relaxed mb-6">
                Buguey is a predominantly agricultural municipality. Approximately <strong>80% of its families</strong> are engaged 
                in farming, fishing, and related livelihood activities. The town's economy is deeply rooted in its coastal and 
                agricultural resources, making it a vital contributor to the province's food production.
            </p>
        </div>
    </div>
</div>
@endsection
