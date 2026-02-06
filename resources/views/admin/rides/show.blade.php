<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ride #{{ $ride->id }} | Operations Intelligence</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        body { font-family: 'Outfit', sans-serif; }
        .glass {
            background: rgba(23, 23, 23, 0.7);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        .coord-pill {
            background: rgba(99, 102, 241, 0.1);
            border: 1px solid rgba(99, 102, 241, 0.2);
            font-family: monospace;
        }
        .bg-dark { background-color: #0a0a0a; }
        .status-completed { background-color: rgba(16, 185, 129, 0.2); color: #34d399; border: 1px solid rgba(16, 185, 129, 0.3); }
        .status-pending { background-color: rgba(245, 158, 11, 0.2); color: #fbbf24; border: 1px solid rgba(245, 158, 11, 0.3); }
        .status-default { background-color: rgba(99, 102, 241, 0.2); color: #818cf8; border: 1px solid rgba(99, 102, 241, 0.3); }
        .proposal-card { background: rgba(255, 255, 255, 0.03); border: 1px solid rgba(255, 255, 255, 0.05); }
        .proposal-card:hover { border-color: rgba(255, 255, 255, 0.1); }
    </style>
</head>
<body class="bg-dark text-gray-200 min-h-screen">
    <!-- Orbital Background Decorations -->
    <div class="fixed -top-20 -right-20 w-96 h-96 bg-indigo-900 opacity-20 blur-3xl rounded-full z-0"></div>
    <div class="fixed -bottom-20 -left-20 w-96 h-96 bg-fuchsia-900 opacity-20 blur-3xl rounded-full z-0"></div>

    <div class="relative z-10 max-w-5xl mx-auto px-6 py-12">
        <!-- Navigation & Breadcrumbs -->
        <nav class="flex items-center gap-4 mb-8">
            <a href="{{ route('admin.rides.index') }}" class="glass p-2 rounded-xl text-indigo-400 hover:text-white transition-all">
                <i data-lucide="arrow-left" class="w-6 h-6"></i>
            </a>
            <div class="flex items-center gap-2 text-sm">
                <span class="text-gray-500">Fleet Control</span>
                <i data-lucide="chevron-right" class="w-4 h-4 text-gray-700"></i>
                <span class="text-white font-medium">Session #{{ $ride->id }}</span>
            </div>
        </nav>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Column: Core Intelligence -->
            <div class="lg:col-span-2 space-y-8">
                <!-- Session Identity Card -->
                <div class="glass rounded-3xl p-8 relative overflow-hidden">
                    <div class="absolute top-0 right-0 p-8">
                        @if($ride->status === 'completed')
                        <div class="status-completed inline-flex items-center px-4 py-1.5 rounded-full text-xs font-bold tracking-widest">
                            {{ strtoupper($ride->status) }}
                        </div>
                        @elseif($ride->status === 'pending')
                        <div class="status-pending inline-flex items-center px-4 py-1.5 rounded-full text-xs font-bold tracking-widest">
                            {{ strtoupper($ride->status) }}
                        </div>
                        @else
                        <div class="status-default inline-flex items-center px-4 py-1.5 rounded-full text-xs font-bold tracking-widest">
                            {{ strtoupper($ride->status) }}
                        </div>
                        @endif
                    </div>

                    <h2 class="text-gray-500 text-xs uppercase tracking-widest font-bold mb-2">Operational Session</h2>
                    <h1 class="text-4xl font-bold text-white mb-8 tracking-tight">Intelligence Payload #{{ $ride->id }}</h1>

                    <!-- Data Grid -->
                    <div class="grid grid-cols-2 gap-8 border-t border-white border-opacity-5 pt-8">
                        <div>
                            <div class="flex items-center gap-2 text-gray-500 text-xs uppercase font-bold mb-3">
                                <i data-lucide="user" class="w-3.5 h-3.5"></i> Requester
                            </div>
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-white bg-opacity-5 flex items-center justify-center font-bold text-white border border-white border-opacity-10">
                                    {{ substr($ride->passenger->name ?? '?', 0, 1) }}
                                </div>
                                <div>
                                    <div class="text-white font-semibold">{{ $ride->passenger->name ?? 'Unknown' }}</div>
                                    <div class="text-xs text-indigo-400">{{ $ride->passenger->email ?? 'N/A' }}</div>
                                </div>
                            </div>
                        </div>
                        <div>
                            <div class="flex items-center gap-2 text-gray-500 text-xs uppercase font-bold mb-3">
                                <i data-lucide="truck" class="w-3.5 h-3.5"></i> Execution Unit
                            </div>
                            @if($ride->driver)
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-white bg-opacity-5 flex items-center justify-center font-bold text-white border border-white border-opacity-10">
                                    {{ substr($ride->driver->name, 0, 1) }}
                                </div>
                                <div class="text-white font-semibold">{{ $ride->driver->name }}</div>
                            </div>
                            @else
                            <div class="text-amber-500 text-sm py-2">Assigned Driver: NULL</div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Navigation Geometry (Route) -->
                <div class="glass rounded-3xl p-8">
                    <h3 class="text-white font-bold text-lg mb-6 flex items-center gap-2">
                        <i data-lucide="map-pin" class="text-indigo-400"></i> Route Geometry
                    </h3>
                    
                    <div class="space-y-6">
                        <div class="flex items-start gap-6">
                            <div class="flex flex-col items-center">
                                <div class="w-4 h-4 rounded-full border-2 border-indigo-500 bg-dark"></div>
                                <div class="w-0.5 h-16 bg-linear-to-b from-indigo-500 to-fuchsia-500"></div>
                            </div>
                            <div class="flex-1">
                                <span class="text-gray-500 text-xs uppercase font-bold">Inception Point</span>
                                <div class="mt-1 flex items-center gap-3">
                                    <div class="coord-pill px-3 py-1 rounded text-xs text-indigo-300">{{ $ride->pickup_lat }}</div>
                                    <div class="coord-pill px-3 py-1 rounded text-xs text-indigo-300">{{ $ride->pickup_lng }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="flex items-start gap-6">
                            <div class="flex flex-col items-center">
                                <div class="w-4 h-4 rounded-full border-2 border-fuchsia-500 bg-dark"></div>
                            </div>
                            <div class="flex-1">
                                <span class="text-gray-500 text-xs uppercase font-bold">Terminal Coordinate</span>
                                <div class="mt-1 flex items-center gap-3">
                                    <div class="coord-pill px-3 py-1 rounded text-xs text-fuchsia-300">{{ $ride->dest_lat }}</div>
                                    <div class="coord-pill px-3 py-1 rounded text-xs text-fuchsia-300">{{ $ride->dest_lng }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Status & Candidates -->
            <div class="space-y-8">
                <!-- Timeline/Status Card -->
                <div class="glass rounded-3xl p-8">
                    <h3 class="text-white font-bold text-lg mb-6 flex items-center gap-2">
                        <i data-lucide="activity" class="text-emerald-400"></i> Operations Sync
                    </h3>
                    <div class="space-y-6 text-sm">
                        <div class="flex items-center justify-between">
                            <span class="text-gray-400">Launched</span>
                            <span class="text-white font-medium">{{ $ride->created_at->format('H:i:s') }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-gray-400">P. Confirmation</span>
                            @if($ride->passenger_completed_at)
                            <span class="text-emerald-400">{{ $ride->passenger_completed_at->format('H:i') }}</span>
                            @else
                            <span class="text-gray-600">AWAITING</span>
                            @endif
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-gray-400">D. Confirmation</span>
                            @if($ride->driver_completed_at)
                            <span class="text-emerald-400">{{ $ride->driver_completed_at->format('H:i') }}</span>
                            @else
                            <span class="text-gray-600">AWAITING</span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Proposals Queue -->
                <div class="glass rounded-3xl p-8">
                    <h3 class="text-white font-bold text-lg mb-6 flex items-center justify-between">
                        <span class="flex items-center gap-2"><i data-lucide="users" class="text-purple-400"></i> Unit Bids</span>
                        <span class="text-xs bg-white bg-opacity-5 px-2 py-1 rounded-md text-gray-400 font-mono">{{ $ride->proposals->count() }}</span>
                    </h3>
                    <div class="space-y-3">
                        @forelse($ride->proposals as $proposal)
                        <div class="proposal-card p-4 rounded-2xl transition-colors">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-white font-semibold">{{ $proposal->driver->name }}</span>
                                <span class="text-xs text-gray-500">{{ $proposal->created_at->diffForHumans() }}</span>
                            </div>
                            <div class="text-xs text-indigo-400 flex items-center gap-1">
                                <i data-lucide="check-circle" class="w-3 h-3"></i> Integrity Verified
                            </div>
                        </div>
                        @empty
                        <div class="py-8 text-center text-gray-600 text-sm">No unit bids detected.</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>
