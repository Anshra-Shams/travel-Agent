<x-admin-layout title="Leads">
    <div class="space-y-6" x-data="{
        showModal: @js($errors->any()),
        modalMode: 'add',
        editingLead: null,
        agents: @js($agents->map(fn ($a) => ['id' => $a->id, 'name' => $a->name])->values()),
        availableServices: @js($services),
        form: @js([
            'full_name' => old('full_name', ''),
            'phone' => old('phone', ''),
            'source' => old('source', ''),
            'agent_id' => old('agent_id', ''),
            'status' => old('status', 'New'),
            'follow_up_date' => old('follow_up_date', ''),
            'follow_up_time' => old('follow_up_time', ''),
            'notes' => old('notes', ''),
        ]),
        selectedServices: @js(old('service', [])),
        serviceSearch: '',
        serviceDropdownOpen: false,
        addService(service) {
            if (!this.selectedServices.includes(service)) {
                this.selectedServices.push(service);
            }
            this.serviceSearch = '';
            this.serviceDropdownOpen = false;
        },
        removeService(service) {
            this.selectedServices = this.selectedServices.filter(s => s !== service);
        },
        get filteredServices() {
            const q = this.serviceSearch.toLowerCase();
            return this.availableServices.filter(s =>
                s.toLowerCase().includes(q) && !this.selectedServices.includes(s)
            );
        },
        openModal() {
            this.modalMode = 'add';
            this.editingLead = null;
            this.form = { full_name: '', phone: '', source: '', agent_id: '', status: 'New', follow_up_date: '', follow_up_time: '', notes: '' };
            this.selectedServices = [];
            this.serviceSearch = '';
            this.serviceDropdownOpen = false;
            this.showModal = true;
        },
        openEditLead(lead) {
            this.modalMode = 'edit';
            this.editingLead = lead;
            this.form = {
                full_name: lead.full_name,
                phone: lead.phone,
                source: lead.source || '',
                agent_id: lead.agent_id != null ? String(lead.agent_id) : '',
                status: lead.status || 'New',
                follow_up_date: lead.follow_up_date || '',
                follow_up_time: lead.follow_up_time || '',
                notes: lead.notes || ''
            };
            this.selectedServices = [...(lead.services_list || [])];
            this.serviceSearch = '';
            this.serviceDropdownOpen = false;
            this.showModal = true;
        },
        csrfToken: @js(csrf_token()),
        menuOpen: false,
        menuStyle: '',
        menuData: { view: '', edit: {}, convert: '', destroy: '' },
        openMenu(btn, data) {
            const r = btn.getBoundingClientRect();
            const w = 215;
            const estimate = 4 * 40 + 16;
            let top = r.bottom + 4;
            if (window.innerHeight - top < estimate) {
                top = Math.max(8, r.top - estimate - 4);
            }
            const right = Math.max(8, Math.min(window.innerWidth - r.right, w));
            this.menuStyle = 'position: fixed; top:' + top + 'px; right:' + right + 'px; width:' + w + 'px;';
            this.menuData = data;
            this.menuOpen = true;
        }
    }">
        <!-- Page header -->
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Leads</h1>
                <p class="mt-1 text-sm text-gray-500">Manage and track customer inquiries</p>
            </div>
            <button type="button" @click="openModal()" class="inline-flex shrink-0 items-center justify-center gap-2 rounded-lg bg-sky-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-sky-700 focus:outline-none focus:ring-2 focus:ring-sky-500 focus:ring-offset-2">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                </svg>
                Add Lead
            </button>
        </div>

        <!-- Toolbar -->
        <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
            <form method="GET" action="{{ route('leads.index') }}" class="flex flex-col gap-3 sm:flex-row sm:items-center">
                <div class="relative flex-1">
                    <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </span>
                    <input type="search" name="search" value="{{ $filters['search'] ?? '' }}" placeholder="Search by Name, Phone or Lead ID" class="w-full rounded-lg border-gray-300 py-2.5 pl-10 pr-3 text-sm shadow-sm focus:border-sky-500 focus:ring-sky-500">
                </div>

                <div class="flex items-center gap-3">
                    <select name="status" class="rounded-lg border-gray-300 py-2.5 text-sm shadow-sm focus:border-sky-500 focus:ring-sky-500">
                        <option value="">All Status</option>
                        @foreach ($statuses as $status)
                            <option value="{{ $status }}" @selected(($filters['status'] ?? '') === $status)>{{ $status }}</option>
                        @endforeach
                    </select>

                    <button type="submit" class="inline-flex items-center justify-center gap-2 rounded-lg bg-slate-800 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-slate-700 focus:outline-none focus:ring-2 focus:ring-slate-500 focus:ring-offset-2">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                        </svg>
                        Filter
                    </button>
                    @if ($leads->total() > 0 && ($filters['search'] ?? $filters['status'] ?? null))
                        <a href="{{ route('leads.index') }}" class="inline-flex items-center justify-center rounded-lg border border-gray-300 px-4 py-2.5 text-sm font-semibold text-gray-600 transition hover:bg-gray-50">
                            Clear
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Leads table -->
        <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Lead ID</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Customer Name</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Phone Number</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Interested Service</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Lead Source</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Status</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Next Follow-up</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Created Date</th>
                            <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wide text-gray-500">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 bg-white">
                        @forelse ($leads as $lead)
                            <tr class="transition hover:bg-gray-50">
                                <td class="px-4 py-4">
                                    <span class="text-sm font-medium text-gray-900">#{{ $lead->id }}</span>
                                </td>
                                <td class="px-4 py-4">
                                    <div class="flex items-center gap-3">
                                        <span class="flex h-8 w-8 flex-shrink-0 items-center justify-center rounded-full bg-sky-100 text-xs font-bold text-sky-700">
                                            {{ strtoupper(substr($lead->full_name, 0, 1)) }}
                                        </span>
                                        <div class="min-w-0">
                                            <a href="{{ route('leads.show', $lead) }}" class="truncate text-sm font-medium text-gray-900 hover:text-sky-600">{{ $lead->full_name }}</a>
                                            @if ($lead->email)
                                                <p class="truncate text-xs text-gray-400">{{ $lead->email }}</p>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap">
                                    <span class="text-sm text-gray-700">{{ $lead->phone }}</span>
                                </td>
                                <td class="px-4 py-4">
                                    @php $svcs = $lead->services_list; @endphp
                                    @if (count($svcs) > 0)
                                        <div class="flex flex-wrap gap-1">
                                            @foreach (array_slice($svcs, 0, 3) as $svc)
                                                <span class="inline-flex items-center rounded-full bg-sky-50 px-2 py-0.5 text-[11px] font-medium text-sky-700 ring-1 ring-inset ring-sky-200">{{ $svc }}</span>
                                            @endforeach
                                            @if (count($svcs) > 3)
                                                <span class="inline-flex items-center rounded-full bg-gray-100 px-2 py-0.5 text-[11px] font-medium text-gray-500">+{{ count($svcs) - 3 }}</span>
                                            @endif
                                        </div>
                                    @else
                                        <span class="text-xs text-gray-400">—</span>
                                    @endif
                                </td>
                                <td class="px-4 py-4">
                                    <span class="text-sm text-gray-700">{{ $lead->source ?: '—' }}</span>
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap">
                                    <x-status-badge :status="$lead->status" />
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap">
                                    @if ($lead->follow_up_date)
                                        <span class="text-sm text-gray-700">{{ $lead->follow_up_date->format('d M Y') }}</span>
                                        @if ($lead->follow_up_time)
                                            <span class="block text-xs text-gray-400">{{ \Carbon\Carbon::parse($lead->follow_up_time)->format('h:i A') }}</span>
                                        @endif
                                    @else
                                        <span class="text-sm text-gray-400">—</span>
                                    @endif
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap">
                                    <span class="text-sm text-gray-500">{{ $lead->created_at->format('d M Y') }}</span>
                                </td>
                                <td class="px-4 py-4">
                                    <div class="flex items-center justify-end">
                                        <button type="button" @click="openMenu($el, @js([
                                            'view' => route('leads.show', $lead),
                                            'convert' => route('leads.convert', $lead),
                                            'destroy' => route('leads.destroy', $lead),
                                            'edit' => [
                                                'id' => $lead->id,
                                                'full_name' => $lead->full_name,
                                                'phone' => $lead->phone,
                                                'source' => $lead->source,
                                                'services_list' => $lead->services_list,
                                                'agent_id' => $lead->agent_id,
                                                'status' => $lead->status,
                                                'follow_up_date' => $lead->follow_up_date?->format('Y-m-d'),
                                                'follow_up_time' => $lead->follow_up_time ? \Carbon\Carbon::parse($lead->follow_up_time)->format('H:i') : '',
                                                'notes' => $lead->notes,
                                            ],
                                        ]))" class="inline-flex items-center justify-center rounded-lg p-2 text-gray-500 transition hover:bg-gray-100 hover:text-gray-700" title="Actions">
                                            <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z" />
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="px-4 py-16 text-center">
                                    <span class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-gray-100">
                                        <svg class="h-7 w-7 text-gray-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                                        </svg>
                                    </span>
                                    <p class="mt-4 text-sm font-medium text-gray-500">No leads found</p>
                                    <p class="mt-1 text-xs text-gray-400">Add your first lead or adjust the filters.</p>
                                    <button type="button" @click="openModal()" class="mt-4 inline-flex items-center gap-2 rounded-lg bg-sky-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-sky-700">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                                        </svg>
                                        Add Lead
                                    </button>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($leads->hasPages() || $leads->total() > 0)
                <div class="border-t border-gray-200 px-4 py-3">
                    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                        <p class="text-sm text-gray-500">
                            Showing <span class="font-medium text-gray-700">{{ $leads->firstItem() ?: 0 }}</span> to <span class="font-medium text-gray-700">{{ $leads->lastItem() ?: 0 }}</span> of <span class="font-medium text-gray-700">{{ $leads->total() }}</span> leads
                        </p>
                        {{ $leads->links() }}
                    </div>
                </div>
            @endif
        </div>

        <!-- Lead Modal (Add / Edit) -->
        <div x-show="showModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto">
            <div @click="showModal = false" class="fixed inset-0 bg-gray-900/40 backdrop-blur-sm"></div>
            <div class="flex min-h-full items-center justify-center p-4">
                <div class="relative w-full max-w-4xl overflow-hidden rounded-2xl bg-white shadow-xl" @keydown.escape.window="showModal = false">
                    <!-- Modal header -->
                    <div class="flex items-center justify-between border-b border-gray-100 px-6 py-4">
                        <h3 class="text-lg font-bold text-gray-900" x-text="modalMode === 'edit' ? 'Edit Lead' : 'Add New Lead'"></h3>
                        <button type="button" @click="showModal = false" class="rounded-lg p-1 text-gray-400 transition hover:bg-gray-100 hover:text-gray-600">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <!-- Modal body -->
                    <form method="POST" :action="modalMode === 'edit' ? '/leads/' + editingLead.id : '{{ route('leads.store') }}'" class="px-6 py-5">
                        @csrf
                        <input type="hidden" name="_method" value="PATCH" :disabled="modalMode !== 'edit'" />

                        <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                            <!-- Row 1: Customer Name + Phone + Lead Source -->
                            <div>
                                <x-input-label for="lead_full_name" value="Customer Name *" />
                                <x-text-input id="lead_full_name" class="mt-1.5 block w-full !py-1.5" type="text" name="full_name" x-model="form.full_name" placeholder="e.g. Ahmed Khan" required />
                                <x-input-error :messages="$errors->get('full_name')" class="mt-1" />
                            </div>
                            <div>
                                <x-input-label for="lead_phone" value="Phone Number *" />
                                <x-text-input id="lead_phone" class="mt-1.5 block w-full !py-1.5" type="text" name="phone" x-model="form.phone" placeholder="e.g. +92 300 1234567" required />
                                <x-input-error :messages="$errors->get('phone')" class="mt-1" />
                            </div>
                            <div>
                                <x-input-label for="lead_source" value="Lead Source *" />
                                <select id="lead_source" name="source" x-model="form.source" class="mt-1.5 block w-full rounded-lg border-gray-300 !py-1.5 shadow-sm focus:border-sky-500 focus:ring-sky-500">
                                    <option value="">Select a source</option>
                                    @foreach ($sources as $src)
                                        <option value="{{ $src }}">{{ $src }}</option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('source')" class="mt-1" />
                            </div>

                            <!-- Row 2: Interested Service + Assigned Agent + Status -->
                            <div>
                                <x-input-label for="lead_service" value="Interested Service *" />
                                <div class="relative" @click.away="serviceDropdownOpen = false">
                                    <div class="mt-1.5 rounded-lg border border-gray-300 bg-white p-2 shadow-sm focus-within:border-sky-500 focus-within:ring-1 focus-within:ring-sky-500" @click="serviceDropdownOpen = true">
                                        <div class="flex flex-wrap gap-1.5" x-show="selectedServices.length > 0">
                                            <template x-for="svc in selectedServices" :key="svc">
                                                <span class="inline-flex items-center gap-1 rounded-full bg-sky-100 px-2.5 py-0.5 text-xs font-medium text-sky-700">
                                                    <span x-text="svc"></span>
                                                    <button type="button" @click.prevent="removeService(svc)" class="ml-0.5 rounded-full p-0.5 hover:bg-sky-200">
                                                        <svg class="h-3 w-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                                                    </button>
                                                </span>
                                            </template>
                                        </div>
                                        <input type="text" x-model="serviceSearch" @focus="serviceDropdownOpen = true" placeholder="Search services..." class="mt-1 w-full border-0 p-0 text-sm text-gray-700 shadow-none focus:ring-0 focus:outline-none" x-show="serviceDropdownOpen" />
                                        <p x-show="!serviceDropdownOpen && selectedServices.length === 0" class="cursor-text text-sm text-gray-400">Select services...</p>
                                    </div>
                                    <template x-for="svc in selectedServices" :key="'input-' + svc">
                                        <input type="hidden" name="service[]" :value="svc" />
                                    </template>
                                    <div x-show="serviceDropdownOpen && filteredServices.length > 0" x-transition class="absolute z-30 mt-1 w-full overflow-hidden rounded-lg border border-gray-200 bg-white py-1 shadow-lg">
                                        <template x-for="svc in filteredServices" :key="svc">
                                            <button type="button" @click.prevent="addService(svc)" class="flex w-full items-center px-4 py-2 text-sm text-gray-700 hover:bg-sky-50 hover:text-sky-700" x-text="svc"></button>
                                        </template>
                                    </div>
                                    <div x-show="serviceDropdownOpen && filteredServices.length === 0 && serviceSearch.length > 0" class="absolute z-30 mt-1 w-full rounded-lg border border-gray-200 bg-white py-3 text-center text-sm text-gray-400 shadow-lg">
                                        No matching services
                                    </div>
                                </div>
                                <x-input-error :messages="$errors->get('service')" class="mt-1" />
                            </div>
                            <div>
                                <x-input-label for="lead_agent" value="Assigned Agent" />
                                <select id="lead_agent" name="agent_id" x-model="form.agent_id" class="mt-1.5 block w-full rounded-lg border-gray-300 !py-1.5 shadow-sm focus:border-sky-500 focus:ring-sky-500">
                                    <option value="">Assign an agent</option>
                                    <template x-for="agent in agents" :key="agent.id">
                                        <option :value="String(agent.id)" x-text="agent.name"></option>
                                    </template>
                                </select>
                                <x-input-error :messages="$errors->get('agent_id')" class="mt-1" />
                            </div>
                            <div>
                                <x-input-label for="lead_status" value="Status *" />
                                <select id="lead_status" name="status" x-model="form.status" class="mt-1.5 block w-full rounded-lg border-gray-300 !py-1.5 shadow-sm focus:border-sky-500 focus:ring-sky-500">
                                    @foreach ($statuses as $st)
                                        <option value="{{ $st }}">{{ $st }}</option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('status')" class="mt-1" />
                            </div>

                            <!-- Row 3: Next Follow-up Date + Time -->
                            <div>
                                <x-input-label for="lead_follow_up_date" value="Next Follow-up Date" />
                                <input id="lead_follow_up_date" type="date" name="follow_up_date" x-model="form.follow_up_date" class="mt-1.5 block w-full rounded-lg border-gray-300 !py-1.5 text-sm shadow-sm focus:border-sky-500 focus:ring-sky-500" />
                                <x-input-error :messages="$errors->get('follow_up_date')" class="mt-1" />
                            </div>
                            <div>
                                <x-input-label for="lead_follow_up_time" value="Next Follow-up Time" />
                                <input id="lead_follow_up_time" type="time" name="follow_up_time" x-model="form.follow_up_time" class="mt-1.5 block w-full rounded-lg border-gray-300 !py-1.5 text-sm shadow-sm focus:border-sky-500 focus:ring-sky-500" />
                                <x-input-error :messages="$errors->get('follow_up_time')" class="mt-1" />
                            </div>
                        </div>

                        <!-- Full width: Requirements / Notes -->
                        <div class="mt-4">
                            <x-input-label for="lead_notes" value="Requirements / Notes" />
                            <textarea id="lead_notes" name="notes" rows="2" x-model="form.notes" class="mt-1.5 block w-full rounded-lg border-gray-300 shadow-sm focus:border-sky-500 focus:ring-sky-500" placeholder="Write any additional requirements or notes here..."></textarea>
                            <x-input-error :messages="$errors->get('notes')" class="mt-1" />
                        </div>

                        <!-- Modal footer -->
                        <div class="mt-6 flex items-center justify-end gap-3 border-t border-gray-100 pt-4">
                            <button type="button" @click="showModal = false" class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-semibold text-gray-700 transition hover:bg-gray-50">
                                Cancel
                            </button>
                            <button type="submit" class="inline-flex items-center justify-center rounded-lg bg-sky-600 px-5 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-sky-700 focus:outline-none focus:ring-2 focus:ring-sky-500 focus:ring-offset-2" x-text="modalMode === 'edit' ? 'Update Lead' : 'Save Lead'"></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Floating action dropdown (teleported to body, no table scroll) -->
        <template x-teleport="body">
            <div x-show="menuOpen" x-cloak x-transition :style="menuStyle" class="overflow-hidden rounded-lg border border-gray-200 bg-white py-1 shadow-xl" @click.outside="menuOpen = false" @keydown.escape.window="menuOpen = false">
                <a :href="menuData.view" class="flex w-full items-center gap-2.5 px-4 py-2.5 text-sm text-gray-700 transition hover:bg-gray-50 hover:text-sky-600">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0zM2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                    View
                </a>
                <button type="button" @click="openEditLead(menuData.edit); menuOpen = false" class="flex w-full items-center gap-2.5 px-4 py-2.5 text-sm text-gray-700 transition hover:bg-gray-50 hover:text-indigo-600">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    Edit
                </button>
                <form :action="menuData.convert" method="POST" @submit.prevent="Swal.fire({ title: 'Convert to Customer?', text: 'This lead will be converted to a customer.', icon: 'question', showCancelButton: true, confirmButtonColor: '#0ea5e9', cancelButtonColor: '#6b7280', confirmButtonText: 'Yes, convert' }).then((r) => { if (r.isConfirmed) $el.submit(); })">
                    <input type="hidden" name="_token" :value="csrfToken" />
                    <button type="submit" class="flex w-full items-center gap-2.5 px-4 py-2.5 text-sm text-gray-700 transition hover:bg-gray-50 hover:text-emerald-600">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        Convert to Customer
                    </button>
                </form>
                <form :action="menuData.destroy" method="POST" @submit.prevent="Swal.fire({ title: 'Delete Lead?', text: 'This cannot be undone.', icon: 'warning', showCancelButton: true, confirmButtonColor: '#ef4444', cancelButtonColor: '#6b7280', confirmButtonText: 'Yes, delete' }).then((r) => { if (r.isConfirmed) $el.submit(); })">
                    <input type="hidden" name="_token" :value="csrfToken" />
                    <input type="hidden" name="_method" value="DELETE" />
                    <button type="submit" class="flex w-full items-center gap-2.5 px-4 py-2.5 text-sm text-gray-700 transition hover:bg-gray-50 hover:text-rose-600">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        Delete
                    </button>
                </form>
            </div>
        </template>
    </div>
</x-admin-layout>