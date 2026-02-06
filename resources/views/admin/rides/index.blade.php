<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RideFlow Admin | Premium Ride Intelligence</title>
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
        .gradient-text {
            background: linear-gradient(135deg, #6366f1 0%, #a855f7 50%, #ec4899 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .status-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            display: inline-block;
            margin-right: 8px;
            box-shadow: 0 0 10px currentColor;
        }
        .bg-dark { background-color: #0a0a0a; }
        .status-completed { background-color: rgba(16, 185, 129, 0.1); color: #34d399; border: 1px solid rgba(16, 185, 129, 0.2); }
        .status-pending { background-color: rgba(245, 158, 11, 0.1); color: #fbbf24; border: 1px solid rgba(245, 158, 11, 0.2); }
        .status-default { background-color: rgba(99, 102, 241, 0.1); color: #818cf8; border: 1px solid rgba(99, 102, 241, 0.2); }
        .table-row:hover { background: rgba(255, 255, 255, 0.03); }
        .avatar-gradient { background: linear-gradient(135deg, #6366f1, #a855f7); }
    </style>
</head>
<body class="bg-dark text-gray-200 min-h-screen">
    <!-- Orbital Background Decorations -->
    <div class="fixed -top-20 -left-20 w-96 h-96 bg-purple-900 opacity-20 blur-3xl rounded-full z-0"></div>
    <div class="fixed -bottom-20 -right-20 w-96 h-96 bg-blue-900 opacity-20 blur-3xl rounded-full z-0"></div>

    <div class="relative z-10 max-w-7xl mx-auto px-6 py-12">
        <!-- Header Section -->
        <header class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-12">
            <div>
                <div class="flex items-center gap-3 mb-2">
                    <div class="p-2.5 bg-indigo-600 bg-opacity-20 rounded-xl border border-indigo-500 border-opacity-30">
                        <i data-lucide="route" class="w-6 h-6 text-indigo-400"></i>
                    </div>
                    <span class="text-indigo-400 font-semibold tracking-wider text-sm uppercase">Admin Console</span>
                </div>
                <h1 class="text-4xl md:text-5xl font-bold text-white tracking-tight">
                    Ride <span class="gradient-text">Flow</span> Intelligence
                </h1>
                <p class="text-gray-400 mt-2 text-lg">Real-time ride management & fleet analytics dashboard.</p>
            </div>
            <div class="flex gap-4">
                <div class="glass px-6 py-4 rounded-2xl flex flex-col items-end">
                    <span class="text-gray-400 text-xs uppercase tracking-widest mb-1">Total Active Rides</span>
                    <span class="text-3xl font-bold text-white">{{ $rides->where('status', '!=', 'completed')->count() }}</span>
                </div>
                <div class="glass px-6 py-4 rounded-2xl flex flex-col items-end border-indigo-500 border-opacity-30">
                    <span class="text-gray-400 text-xs uppercase tracking-widest mb-1">Total Completed</span>
                    <span class="text-3xl font-bold text-indigo-400">{{ $rides->where('status', 'completed')->count() }}</span>
                </div>
            </div>
        </header>

        <!-- Main Action Bar -->
        <div class="flex justify-between items-center mb-6">
            <div class="flex items-center gap-2">
                <span class="h-2 w-2 rounded-full bg-green-500 animate-pulse"></span>
                <span class="text-sm font-medium text-gray-300">System Live</span>
            </div>
            <div class="flex gap-3">
                <button class="glass px-4 py-2 rounded-lg text-sm font-medium hover:bg-white hover:bg-opacity-5 transition-all flex items-center gap-2">
                    <i data-lucide="filter" class="w-4 h-4"></i> Filter
                </button>
                <button class="glass px-4 py-2 rounded-lg text-sm font-medium hover:bg-white hover:bg-opacity-5 transition-all flex items-center gap-2">
                    <i data-lucide="download" class="w-4 h-4"></i> Export
                </button>
            </div>
        </div>

        <!-- Rides Grid/Table -->
        <div class="glass rounded-3xl overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full border-collapse">
                    <thead>
                        <tr class="text-left border-b border-white border-opacity-5 bg-white bg-opacity-5">
                            <th class="px-8 py-5 text-xs font-semibold text-gray-500 uppercase tracking-widest">Tracking ID</th>
                            <th class="px-8 py-5 text-xs font-semibold text-gray-500 uppercase tracking-widest">Passenger Entity</th>
                            <th class="px-8 py-5 text-xs font-semibold text-gray-500 uppercase tracking-widest">Assigned Driver</th>
                            <th class="px-8 py-5 text-xs font-semibold text-gray-500 uppercase tracking-widest">Live Status</th>
                            <th class="px-8 py-5 text-xs font-semibold text-gray-500 uppercase tracking-widest">Timestamp</th>
                            <th class="px-8 py-5 text-xs font-semibold text-gray-500 uppercase tracking-widest text-right">Operations</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white divide-opacity-5">
                        @forelse($rides as $ride)
                        <tr class="table-row transition-colors">
                            <td class="px-8 py-6">
                                <span class="font-mono text-indigo-400 font-medium">#RD-{{ str_pad($ride->id, 4, '0', STR_PAD_LEFT) }}</span>
                            </td>
                            <td class="px-8 py-6">
                                <div class="flex items-center gap-3">
                                    <div class="avatar-gradient w-10 h-10 rounded-full flex items-center justify-center text-white font-bold shadow-lg shadow-indigo-500/20">
                                        {{ substr($ride->passenger->name ?? '?', 0, 1) }}
                                    </div>
                                    <div>
                                        <div class="text-white font-semibold">{{ $ride->passenger->name ?? 'Anonymized' }}</div>
                                        <div class="text-gray-500 text-xs">{{ $ride->passenger->email ?? 'N/A' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-8 py-6">
                                @if($ride->driver)
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-lg bg-gray-800 flex items-center justify-center border border-white border-opacity-10">
                                        <i data-lucide="user" class="w-4 h-4 text-gray-400"></i>
                                    </div>
                                    <span class="text-gray-300">{{ $ride->driver->name }}</span>
                                </div>
                                @else
                                <div class="flex items-center gap-2 text-yellow-500 text-sm">
                                    <i data-lucide="loader" class="w-4 h-4 animate-spin"></i> Awaiting Driver...
                                </div>
                                @endif
                            </td>
                            <td class="px-8 py-6">
                                @if($ride->status === 'completed')
                                <div class="status-completed inline-flex items-center px-3 py-1 rounded-full text-xs font-bold tracking-wider">
                                    <span class="status-dot bg-emerald-400"></span> {{ strtoupper($ride->status) }}
                                </div>
                                @elseif($ride->status === 'pending')
                                <div class="status-pending inline-flex items-center px-3 py-1 rounded-full text-xs font-bold tracking-wider">
                                    <span class="status-dot bg-amber-400"></span> {{ strtoupper($ride->status) }}
                                </div>
                                @else
                                <div class="status-default inline-flex items-center px-3 py-1 rounded-full text-xs font-bold tracking-wider">
                                    <span class="status-dot bg-indigo-400"></span> {{ strtoupper($ride->status) }}
                                </div>
                                @endif
                            </td>
                            <td class="px-8 py-6 text-gray-400 text-sm">
                                <div class="flex flex-col">
                                    <span>{{ $ride->created_at->format('M d, Y') }}</span>
                                    <span class="text-xs text-gray-600">{{ $ride->created_at->format('h:i A') }}</span>
                                </div>
                            </td>
                            <td class="px-8 py-6 text-right">
                                <a href="{{ route('admin.rides.show', $ride->id) }}" 
                                   class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-white bg-opacity-5 hover:bg-indigo-600 text-white font-medium transition-all hover:shadow-lg hover:shadow-indigo-500/20">
                                    Inspect <i data-lucide="chevron-right" class="w-4 h-4"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-8 py-20 text-center">
                                <div class="flex flex-col items-center gap-4">
                                    <div class="w-16 h-16 bg-white bg-opacity-5 rounded-full flex items-center justify-center">
                                        <i data-lucide="ghost" class="w-8 h-8 text-gray-600"></i>
                                    </div>
                                    <h3 class="text-xl font-semibold text-gray-400">Neutral Environment Detected</h3>
                                    <p class="text-gray-600 max-w-xs">No ride activity recorded in the stream. Initialize fleet operations to begin tracking.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Footer Controls -->
            <div class="px-8 py-6 bg-white bg-opacity-5 border-t border-white border-opacity-5 flex items-center justify-between">
                <span class="text-gray-500 text-sm">Synchronized with Node 01-Alpha-RideFlow</span>
                <div class="flex gap-2">
                    <button class="p-2 rounded-lg bg-white bg-opacity-5 text-gray-400 hover:text-white transition-colors cursor-not-allowed"><i data-lucide="chevron-left"></i></button>
                    <button class="p-2 rounded-lg bg-white bg-opacity-5 text-gray-400 hover:text-white transition-colors cursor-not-allowed"><i data-lucide="chevron-right"></i></button>
                </div>
            </div>
        </div>
    </div>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>
