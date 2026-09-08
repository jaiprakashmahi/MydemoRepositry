<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tracking Timeline') }}
        </h2>
    </x-slot>

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Filter card --}}
            <div class="bg-white rounded-xl shadow-sm p-6">
                <div class="flex flex-wrap items-start justify-between gap-4">
                    <div>
                        <h2 class="text-xl font-bold text-gray-800">📍 Location Timeline</h2>
                        <p class="text-sm text-gray-500">Select an employee to view their tracking data</p>
                    </div>

                    <form method="GET" action="{{ route('dashboard') }}" class="flex flex-wrap items-center gap-3">
                        <select name="employee_id" class="border rounded-lg px-3 py-2 text-sm">
                            @foreach ($employees as $employee)
                            <option value="{{ $employee->id }}" @selected($selectedEmployee && $selectedEmployee->id === $employee->id)>
                                {{ $employee->label }}
                            </option>
                            @endforeach
                        </select>

                        <input type="date" name="date" value="{{ $date }}" class="border rounded-lg px-3 py-2 text-sm">

                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-4 py-2 rounded-lg">
                            🔍 Load Timeline
                        </button>
                    </form>
                </div>

                @if ($selectedEmployee)
                <div class="mt-4 border-l-4 border-blue-500 bg-blue-50 text-sm text-blue-800 px-4 py-2 rounded">
                    <span class="font-semibold">Selected Employee:</span> {{ $selectedEmployee->label }}
                </div>
                @endif
            </div>

            {{-- Stat cards --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="bg-white rounded-xl shadow-sm p-5">
                    <p class="text-sm text-gray-500">🚩 Total Distance</p>
                    <p class="text-2xl font-bold text-gray-800 mt-1">
                        {{ number_format($timeline['total_distance_km'] ?? 0, 2) }} KM
                    </p>
                </div>
                <div class="bg-white rounded-xl shadow-sm p-5">
                    <p class="text-sm text-gray-500">Travel Time</p>
                    <p class="text-2xl font-bold text-gray-800 mt-1">
                        {{ $timeline['travel_time_minutes'] ?? 0 }} min
                    </p>
                </div>
                <div class="bg-white rounded-xl shadow-sm p-5">
                    <p class="text-sm text-gray-500">🚏 Places Visited</p>
                    <p class="text-2xl font-bold text-gray-800 mt-1">
                        {{ $timeline['places_visited'] ?? 0 }}
                    </p>
                </div>
                <div class="bg-white rounded-xl shadow-sm p-5">
                    <p class="text-sm text-gray-500">🚗 Trips</p>
                    <p class="text-2xl font-bold text-gray-800 mt-1">
                        {{ $timeline['trips'] ?? 0 }}
                    </p>
                </div>
            </div>

            {{-- Map + Timeline --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="lg:col-span-2 bg-white rounded-xl shadow-sm overflow-hidden">
                    <div class="px-4 py-3 border-b font-semibold text-gray-700">🗺️ Location Route</div>
                    <div id="map" class="w-full h-[500px]"></div>
                </div>

                <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                    <div class="px-4 py-3 border-b font-semibold text-gray-700">📋 Timeline</div>
                    <div class="p-4 space-y-0 max-h-[500px] overflow-y-auto">
                        @forelse (($timeline['stops'] ?? []) as $index => $stop)
                        <div class="flex gap-3 pb-6 relative">
                            @if (!$loop->last)
                            <span class="absolute left-[9px] top-6 bottom-0 w-px bg-gray-200"></span>
                            @endif

                            <span class="mt-1 w-[18px] h-[18px] rounded-full border-4
                                    {{ $loop->first ? 'border-green-500' : ($loop->last ? 'border-red-500' : 'border-blue-500') }}
                                    bg-white flex-shrink-0"></span>

                            <div>
                                <p class="font-semibold text-gray-800">📍 {{ $stop['name'] }}</p>
                                <p class="text-xs text-gray-500">
                                    {{ $stop['arrival']->format('h:i A') }}
                                    @unless ($loop->last)
                                    &ndash; {{ $stop['departure']->format('h:i A') }}
                                    @else
                                    &ndash; Now
                                    @endunless
                                </p>
                                <p class="text-sm text-gray-700">
                                    <span class="font-semibold">{{ $stop['duration_minutes'] }}</span> minutes
                                </p>
                            </div>
                        </div>
                        @empty
                        <p class="text-sm text-gray-400 text-center py-6">No timeline data for this date.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const route = @json($timeline['route'] ?? []);
        const stops = @json(($timeline['stops'] ?? []));

        const map = L.map('map');
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors',
        }).addTo(map);

        if (route.length > 0) {
            const latlngs = route.map(p => [p.lat, p.lng]);

            L.polyline(latlngs, {
                color: '#2563eb',
                weight: 4
            }).addTo(map);

            stops.forEach((stop, i) => {
                const color = i === 0 ? 'green' : (i === stops.length - 1 ? 'red' : '#2563eb');
                L.circleMarker([stop.lat, stop.lng], {
                    radius: 8,
                    color: color,
                    fillColor: color,
                    fillOpacity: 1,
                }).addTo(map).bindPopup(`<b>${stop.name}</b><br>${stop.duration_minutes} min`);
            });

            map.fitBounds(L.latLngBounds(latlngs), {
                padding: [40, 40]
            });
        } else {
            map.setView([25.5941, 85.1376], 13); // default: Patna
        }
    </script>
</x-app-layout>