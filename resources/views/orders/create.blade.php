@extends('layouts.app')

@section('title', 'POS Kasir')

@section('content')
<div x-data="posApp()" class="grid grid-cols-1 lg:grid-cols-4 gap-6" x-init="init()">
    <div class="lg:col-span-3 space-y-4">
        <div class="bg-white rounded-2xl shadow-sm border border-cream-200 p-4">
            <div class="flex flex-wrap items-center gap-3">
                <div class="flex-1 min-w-[200px]">
                    <input type="text" x-model="search" @@input="filterMenus()" placeholder="Cari menu..." class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:border-maroon-400 focus:ring-2 focus:ring-maroon-100 outline-none">
                </div>
                <div class="flex gap-1 overflow-x-auto">
                    <button @@click="activeCategory = ''; filterMenus()" class="rounded-lg px-4 py-2 text-sm font-medium transition-all" :class="activeCategory === '' ? 'bg-maroon-700 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'">Semua</button>
                    @foreach($categories as $category)
                    <button @@click="activeCategory = '{{ $category->id }}'; filterMenus()" class="rounded-lg px-4 py-2 text-sm font-medium whitespace-nowrap transition-all" :class="activeCategory === '{{ $category->id }}' ? 'bg-maroon-700 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'">{{ $category->name }}</button>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-cream-200 p-4">
            <div class="flex flex-wrap items-center gap-3 mb-4">
                <span class="text-sm font-semibold text-gray-700">Pilih Meja:</span>
                @foreach($tables as $table)
                <button @@click="selectTable('{{ $table->id }}')" class="rounded-lg px-3 py-2 text-sm font-medium transition-all border-2"
                    :class="selectedTable === '{{ $table->id }}' ? 'border-maroon-600 bg-maroon-50 text-maroon-700' : 'border-gray-200 text-gray-600 hover:border-maroon-300'">
                    Meja {{ $table->table_number }}
                </button>
                @endforeach
            </div>

            <div x-show="!selectedTable" class="text-center py-6 text-gray-400 text-sm">
                Silakan pilih nomor meja untuk memulai pesanan
            </div>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3" x-show="selectedTable">
            <template x-for="menu in filteredMenus" :key="menu.id">
                <div class="bg-white rounded-xl border border-cream-200 overflow-hidden hover:shadow-md transition-all cursor-pointer" @@click="addToCart(menu)">
                    <div class="h-24 bg-gray-100 flex items-center justify-center overflow-hidden">
                        <template x-if="menu.photo">
                            <img :src="'/storage/' + menu.photo" :alt="menu.name" class="h-full w-full object-cover">
                        </template>
                        <template x-if="!menu.photo">
                            <svg class="h-8 w-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        </template>
                    </div>
                    <div class="p-2.5">
                        <p class="text-xs font-medium text-gray-900 truncate" x-text="menu.name"></p>
                        <p class="text-sm font-bold text-maroon-600 mt-0.5" x-text="'Rp ' + formatPrice(menu.selling_price)"></p>
                    </div>
                </div>
            </template>
        </div>
    </div>

    <div class="lg:col-span-1" x-show="selectedTable">
        <div class="bg-white rounded-2xl shadow-sm border border-cream-200 overflow-hidden sticky top-6">
            <div class="p-4 bg-maroon-700 text-white">
                <div class="flex items-center justify-between">
                    <h3 class="font-semibold text-sm">Keranjang Pesanan</h3>
                    <span class="text-xs text-cream-200" x-text="'Meja ' + (selectedTable ? tables.find(t => t.id == selectedTable)?.table_number : '')"></span>
                </div>
            </div>

            <div class="p-4 max-h-[300px] overflow-y-auto space-y-2" x-show="cart.length > 0">
                <template x-for="(item, index) in cart" :key="item.id">
                    <div class="flex items-start gap-2 p-2 rounded-lg bg-gray-50">
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900 truncate" x-text="item.name"></p>
                            <p class="text-xs text-gray-500" x-text="'Rp ' + formatPrice(item.price) + ' x ' + item.quantity"></p>
                            <template x-if="item.notes">
                                <p class="text-[10px] text-amber-600 mt-0.5 italic" x-text="'Catatan: ' + item.notes"></p>
                            </template>
                            <div class="flex items-center gap-1 mt-1">
                                <button @@click="updateQty(item.id, item.quantity - 1)" class="h-6 w-6 rounded bg-gray-200 text-gray-600 text-xs font-bold hover:bg-gray-300">-</button>
                                <span class="text-sm font-semibold w-6 text-center" x-text="item.quantity"></span>
                                <button @@click="updateQty(item.id, item.quantity + 1)" class="h-6 w-6 rounded bg-gray-200 text-gray-600 text-xs font-bold hover:bg-gray-300">+</button>
                            </div>
                        </div>
                        <div class="text-right shrink-0">
                            <p class="text-sm font-bold text-maroon-600" x-text="'Rp ' + formatPrice(item.subtotal)"></p>
                            <button @@click="removeItem(item.id)" class="text-[10px] text-rose-500 hover:text-rose-700 mt-1">Hapus</button>
                        </div>
                    </div>
                </template>
            </div>

            <div class="p-4" x-show="cart.length > 0">
                <div class="space-y-1.5 text-sm">
                    <div class="flex justify-between text-gray-600">
                        <span>Subtotal</span>
                        <span x-text="'Rp ' + formatPrice(totals.subtotal)"></span>
                    </div>
                    <div class="flex justify-between text-gray-600">
                        <span>Pajak (10%)</span>
                        <span x-text="'Rp ' + formatPrice(totals.tax)"></span>
                    </div>
                    <div class="flex justify-between text-base font-bold text-gray-900 pt-2 border-t border-gray-200">
                        <span>Grand Total</span>
                        <span class="text-maroon-700" x-text="'Rp ' + formatPrice(totals.grand_total)"></span>
                    </div>
                </div>

                <form method="POST" action="{{ route('orders.checkout') }}" class="mt-4 space-y-3" @@submit.prevent="checkout($event)">
                    @csrf
                    <div>
                        <input type="text" name="customer_name" placeholder="Nama Pelanggan (opsional)" class="w-full rounded-xl border border-gray-200 px-3 py-2 text-sm focus:border-maroon-400 focus:ring-2 focus:ring-maroon-100 outline-none">
                    </div>
                    <div>
                        <select name="payment_method" x-model="paymentMethod" class="w-full rounded-xl border border-gray-200 px-3 py-2 text-sm focus:border-maroon-400 focus:ring-2 focus:ring-maroon-100 outline-none" required>
                            <option value="cash">Tunai</option>
                            <option value="transfer">Transfer</option>
                            <option value="qris">QRIS</option>
                        </select>
                    </div>
                    <div>
                        <input type="number" name="payment_amount" x-model="paymentAmount" placeholder="Jumlah Pembayaran" class="w-full rounded-xl border border-gray-200 px-3 py-2 text-sm focus:border-maroon-400 focus:ring-2 focus:ring-maroon-100 outline-none" required min="0">
                    </div>
                    <div x-show="paymentMethod === 'cash'" class="flex flex-wrap gap-1.5">
                        <template x-for="nominal in [50000, 100000, 150000, 200000, 500000]">
                            <button type="button" @@click="setPayment(nominal)" class="rounded-lg border border-gray-200 px-3 py-1.5 text-xs font-medium text-gray-600 hover:border-maroon-300 hover:text-maroon-700 transition-all" :class="paymentAmount == nominal ? 'border-maroon-600 bg-maroon-50 text-maroon-700' : ''" x-text="'Rp ' + formatPrice(nominal)"></button>
                        </template>
                        <button type="button" @@click="setPayment(totals.grand_total)" class="rounded-lg border border-gray-200 px-3 py-1.5 text-xs font-medium text-gray-600 hover:border-maroon-300 hover:text-maroon-700 transition-all" :class="paymentAmount == totals.grand_total ? 'border-maroon-600 bg-maroon-50 text-maroon-700' : ''">Bayar Pas</button>
                    </div>
                    <input type="hidden" name="table_id" x-model="selectedTable">
                    <button type="submit" class="w-full rounded-xl bg-maroon-700 px-4 py-3 text-sm font-bold text-white hover:bg-maroon-800 shadow-sm transition-all">Buat Pesanan</button>
                </form>
            </div>

            <div class="p-4 text-center" x-show="cart.length === 0">
                <svg class="h-10 w-10 text-gray-300 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z"/></svg>
                <p class="text-sm text-gray-400">Keranjang kosong</p>
                <p class="text-xs text-gray-300 mt-1">Pilih menu untuk ditambahkan</p>
            </div>
    </div>
</div>
<div x-show="showModal" class="fixed inset-0 z-50 flex items-center justify-center" x-cloak>
    <div class="fixed inset-0 bg-black/40" @@click="showModal = false"></div>
    <div class="relative bg-white rounded-2xl shadow-xl w-full max-w-sm mx-4 overflow-hidden">
        <div class="p-4 bg-maroon-700 text-white flex items-center justify-between">
            <h3 class="font-semibold" x-text="modalMenu?.name"></h3>
            <button @@click="showModal = false" class="text-cream-200 hover:text-white">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <div class="p-4 space-y-4">
            <p class="text-lg font-bold text-maroon-700" x-text="'Rp ' + formatPrice(modalMenu?.selling_price)"></p>

            <div>
                <label class="text-sm font-medium text-gray-700">Jumlah</label>
                <div class="flex items-center gap-3 mt-2">
                    <button @@click="modalQty = Math.max(1, modalQty - 1)" class="h-9 w-9 rounded-lg bg-gray-100 text-gray-600 hover:bg-gray-200 text-lg font-bold flex items-center justify-center">-</button>
                    <span class="text-lg font-bold w-8 text-center" x-text="modalQty"></span>
                    <button @@click="modalQty++" class="h-9 w-9 rounded-lg bg-gray-100 text-gray-600 hover:bg-gray-200 text-lg font-bold flex items-center justify-center">+</button>
                </div>
            </div>

            <div>
                <label class="text-sm font-medium text-gray-700">Catatan untuk koki</label>
                <textarea x-model="modalNotes" placeholder="Contoh: Pedas, tanpa es, tidak pakai bawang..." class="w-full rounded-xl border border-gray-200 px-3 py-2.5 text-sm focus:border-maroon-400 focus:ring-2 focus:ring-maroon-100 outline-none mt-1" rows="3"></textarea>
            </div>

            <div class="flex gap-2 pt-1">
                <button @@click="showModal = false" class="flex-1 rounded-xl border border-gray-200 px-4 py-2.5 text-sm font-medium text-gray-600 hover:bg-gray-50 transition-all">Batal</button>
                <button @@click="confirmAddToCart()" class="flex-1 rounded-xl bg-maroon-700 px-4 py-2.5 text-sm font-bold text-white hover:bg-maroon-800 transition-all shadow-sm">Tambah ke Pesanan</button>
            </div>
        </div>
    </div>
</div>
</div>
@endsection

@push('scripts')
<script>
function posApp() {
    return {
        search: '',
        activeCategory: '',
        selectedTable: '{{ $selectedTable ?? '' }}',
        tables: @json($tables),
        menus: @json($categories->flatMap->products),
        filteredMenus: [],
        cart: @json(array_values($cart ?? [])),
        totals: @json($totals),
        showModal: false,
        modalMenu: null,
        modalQty: 1,
        modalNotes: '',
        paymentMethod: 'cash',
        paymentAmount: '',

        init() {
            this.filteredMenus = this.menus;
        },

        filterMenus() {
            let result = this.menus;
            if (this.search) {
                result = result.filter(m => m.name.toLowerCase().includes(this.search.toLowerCase()));
            }
            if (this.activeCategory) {
                result = result.filter(m => m.category_id == this.activeCategory);
            }
            this.filteredMenus = result;
        },

        selectTable(tableId) {
            this.selectedTable = tableId;
            fetch('{{ route('orders.select-table') }}', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: JSON.stringify({ table_id: tableId })
            }).catch(() => {});
        },

        addToCart(menu) {
            this.modalMenu = menu;
            this.modalQty = 1;
            this.modalNotes = '';
            this.showModal = true;
        },

        confirmAddToCart() {
            fetch('{{ route('orders.add-to-cart') }}', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: JSON.stringify({ product_id: this.modalMenu.id, quantity: this.modalQty, notes: this.modalNotes })
            })
            .then(r => r.json())
            .then(data => {
                if (data.error) { alert(data.error); return; }
                this.cart = data.cart;
                this.totals = data.totals;
                this.showModal = false;
            })
            .catch(() => {});
        },

        updateQty(productId, qty) {
            if (qty < 0) return;
            fetch('{{ route('orders.update-cart') }}', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: JSON.stringify({ product_id: productId, quantity: qty })
            })
            .then(r => r.json())
            .then(data => {
                if (data.error) { alert(data.error); return; }
                this.cart = data.cart;
                this.totals = data.totals;
            })
            .catch(() => {});
        },

        removeItem(productId) {
            fetch('{{ route('orders.remove-from-cart') }}', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: JSON.stringify({ product_id: productId })
            })
            .then(r => r.json())
            .then(data => {
                this.cart = data.cart;
                this.totals = data.totals;
            })
            .catch(() => {});
        },

        checkout(event) {
            const form = event.target;
            if (!this.selectedTable) { alert('Pilih meja terlebih dahulu'); return; }
            form.submit();
        },

        setPayment(amount) {
            this.paymentAmount = amount;
        },

        formatPrice(value) {
            return parseInt(value).toLocaleString('id-ID');
        }
    };
}
</script>
@endpush
