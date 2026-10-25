# Optik SaaS — Tahap 1 Fondasi (Selesai)

Stack: PHP 8.3.4, Laravel 13.37, Livewire 4.4.7, Tailwind 4, MySQL 8.0.30 Laragon, Node 24.

## Database
- Utama `optik_saas`: fresh-migrate OK, 29 tabel + 2 paket (basic 99rb, pro 199rb).
- Testing `optik_saas_testing`: dipakai phpunit karena pdo_sqlite tidak tersedia di PHP ini.
- Kredensial lokal root tanpa password, lihat `.env`.

## Cara jalan
```
composer install
npm install
php artisan migrate:fresh --seed
npm run build
php artisan serve  -> http://localhost:8000
```
Alur: /register (akun+toko+trial 14 hari) -> /dashboard -> /stores/select bila multi-toko.

## Keamanan tenant
- ActiveStore: toko aktif hanya dari session + cek store_user aktif; tolak toko asing; regenerate session.
- BelongsToStore: forActiveStore() abort 403 + filter store_id; auto-isi store_id.
- Middleware store + store.role; Policy Product + Sale + ChecksStoreMembership.
- Tes TenantIsolationTest 4/4 passed: query lintas toko null, pindah toko ditolak, policy blokir, dashboard tidak tercampur.

## Selesai
Migrasi stores/store_user/plans/subscriptions/katalog/pelanggan/resep/beli/stok/jual/bayar/biaya/pesanan-optik/retur/garansi; Auth Livewire Login/Register/Select/Create; Dashboard toko aktif; Layout Tailwind responsif; Route aman.

## Lanjut Tahap 2 (Selesai)
Produk & Stok (/products) -> Pelanggan & Resep (/customers) -> Kasir (/sales/create) -> Pesanan Kacamata (/optical-orders) -> Laporan (/reports, owner saja). Harga dihitung ulang di server via SaleService; stok dicatat via StockService; semua query forStore + policy. Tes ModuleIsolationTest 3/3 + TenantIsolationTest 4/4 = 7/7 passed.

## Tahap 3 Operasional Lengkap (Selesai)
Kategori (/categories) -> Supplier (/suppliers) -> Pembelian (/purchases, stok otomatis + via PurchaseService) -> Pengeluaran (/expenses) -> Retur (/returns, stok kembali via ReturnService) -> Garansi (/warranties) -> Nota cetak thermal (/print/sales/{id}) -> Ekspor CSV penjualan & stok (/export/sales, /export/stock). Semua forStore + policy + role (pembelian/stok: owner,stok; retur: owner,kasir; laporan/pengeluaran/ekspor-penjualan: owner). Tes ExtendedModuleTest 2/2. Total 10/10 passed, build sukses.

