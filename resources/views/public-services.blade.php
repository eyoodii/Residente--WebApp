@extends('layouts.public')

@section('title', 'Services Directory')

@section('content')
<div class="py-8 md:py-12 lg:py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h1 class="text-4xl font-extrabold text-deep-forest mb-4">LGU Buguey Services</h1>
            <p class="text-lg text-gray-600">Complete directory of available government services by department</p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-6">
            
            <!-- Municipal Health Office -->
            <div class="bg-white rounded-xl shadow-sm border-t-4 border-sea-green overflow-hidden hover:shadow-md transition">
                <div class="p-6 bg-gray-50 border-b border-gray-100 flex items-center gap-3">
                    <div class="w-12 h-12 bg-sea-green bg-opacity-20 text-sea-green rounded-full flex items-center justify-center text-2xl">⚕️</div>
                    <h3 class="font-bold text-deep-forest text-lg">Municipal Health Office</h3>
                </div>
                <ul class="p-4 divide-y divide-gray-100 text-sm text-gray-700 max-h-72 overflow-y-auto">
                    <li class="py-2 hover:text-tiger-orange transition flex justify-between items-center">Availing of Anti-Rabies <span>→</span></li>
                    <li class="py-2 hover:text-tiger-orange transition flex justify-between items-center">Availing of Immunization Services <span>→</span></li>
                    <li class="py-2 hover:text-tiger-orange transition flex justify-between items-center">Availing of Laboratory Services <span>→</span></li>
                    <li class="py-2 hover:text-tiger-orange transition flex justify-between items-center">Maternal and Child Health Care <span>→</span></li>
                    <li class="py-2 hover:text-tiger-orange transition flex justify-between items-center">Anti-Tuberculosis Drugs/Medicines <span>→</span></li>
                    <li class="py-2 hover:text-tiger-orange transition flex justify-between items-center">Availing of Dental Services <span>→</span></li>
                    <li class="py-2 hover:text-tiger-orange transition flex justify-between items-center">Availing STD/STI Services <span>→</span></li>
                    <li class="py-2 hover:text-tiger-orange transition flex justify-between items-center">Securing Medical/Death Certificate <span>→</span></li>
                    <li class="py-2 hover:text-tiger-orange transition flex justify-between items-center">Out-Patient Department <span>→</span></li>
                    <li class="py-2 hover:text-tiger-orange transition flex justify-between items-center">Issuance of Sanitary Permit <span>→</span></li>
                </ul>
            </div>

            <!-- Municipal Civil Registrar -->
            <div class="bg-white rounded-xl shadow-sm border-t-4 border-tiger-orange overflow-hidden hover:shadow-md transition">
                <div class="p-6 bg-gray-50 border-b border-gray-100 flex items-center gap-3">
                    <div class="w-12 h-12 bg-tiger-orange bg-opacity-20 text-tiger-orange rounded-full flex items-center justify-center text-2xl">📜</div>
                    <h3 class="font-bold text-deep-forest text-lg">Municipal Civil Registrar</h3>
                </div>
                <ul class="p-4 divide-y divide-gray-100 text-sm text-gray-700 max-h-72 overflow-y-auto">
                    <li class="py-2 hover:text-tiger-orange transition">Registration of Birth (Timely/Delayed, Legitimate/Illegitimate, Out-of-Town)</li>
                    <li class="py-2 hover:text-tiger-orange transition">Registration of Marriage (Timely/Delayed) & License Application</li>
                    <li class="py-2 hover:text-tiger-orange transition">Registration of Death (Timely/Delayed)</li>
                    <li class="py-2 hover:text-tiger-orange transition">Issuance of Forms (1A, 1B, 1C, 2A, 2B, 2C, 3A, 3B, CTC)</li>
                    <li class="py-2 hover:text-tiger-orange transition">Petition for Clerical Error/Change of Name (RA9048 & 10172)</li>
                    <li class="py-2 hover:text-tiger-orange transition">Registration of Court Orders & Legal Instruments</li>
                    <li class="py-2 hover:text-tiger-orange transition">Supplemental Report</li>
                </ul>
            </div>

            <!-- Mayor's Office -->
            <div class="bg-white rounded-xl shadow-sm border-t-4 border-golden-glow overflow-hidden hover:shadow-md transition">
                <div class="p-6 bg-gray-50 border-b border-gray-100 flex items-center gap-3">
                    <div class="w-12 h-12 bg-golden-glow bg-opacity-30 text-deep-forest rounded-full flex items-center justify-center text-2xl">🏛️</div>
                    <h3 class="font-bold text-deep-forest text-lg">Mayor's Office</h3>
                </div>
                <ul class="p-4 divide-y divide-gray-100 text-sm text-gray-700">
                    <li class="py-3 hover:text-tiger-orange transition flex justify-between items-center">Issuance of Mayor's Clearance <span>→</span></li>
                    <li class="py-3 hover:text-tiger-orange transition flex justify-between items-center">Issuance of Business Permit <span>→</span></li>
                    <li class="py-3 hover:text-tiger-orange transition flex justify-between items-center">Issuance of Working Permit <span>→</span></li>
                    <li class="py-3 hover:text-tiger-orange transition flex justify-between items-center">Motorized Tricycle Operator's Permit <span>→</span></li>
                </ul>
            </div>

            <!-- Planning and Development -->
            <div class="bg-white rounded-xl shadow-sm border-t-4 border-burnt-tangerine overflow-hidden hover:shadow-md transition">
                <div class="p-6 bg-gray-50 border-b border-gray-100 flex items-center gap-3">
                    <div class="w-12 h-12 bg-burnt-tangerine bg-opacity-20 text-burnt-tangerine rounded-full flex items-center justify-center text-2xl">🗺️</div>
                    <h3 class="font-bold text-deep-forest text-lg">Planning and Development</h3>
                </div>
                <ul class="p-4 divide-y divide-gray-100 text-sm text-gray-700">
                    <li class="py-3 hover:text-tiger-orange transition flex justify-between items-center">Zoning Certification/Land Issuance <span>→</span></li>
                    <li class="py-3 hover:text-tiger-orange transition flex justify-between items-center">Locational Clearance for Business Permit <span>→</span></li>
                </ul>
            </div>

        </div>

        <!-- Call to Action -->
        <div class="mt-12 text-center">
            <div class="bg-gradient-to-r from-sea-green to-deep-forest text-white rounded-xl shadow-lg p-8 inline-block">
                <h3 class="text-2xl font-bold mb-3">Ready to Request Services?</h3>
                <p class="text-gray-100 mb-6">Create your RESIDENTE account to access online services</p>
                @auth
                    <a href="{{ route('services') }}" class="bg-golden-glow hover:bg-white text-deep-forest px-8 py-3 rounded-lg font-bold shadow-lg transition inline-block">
                        View Full Directory (Resident)
                    </a>
                @else
                    <a href="{{ route('register') }}" class="bg-golden-glow hover:bg-white text-deep-forest px-8 py-3 rounded-lg font-bold shadow-lg transition inline-block">
                        Register Now
                    </a>
                @endauth
            </div>
        </div>
    </div>
</div>
@endsection
