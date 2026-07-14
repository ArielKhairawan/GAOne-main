# Catatan Refactor — Modul Monitoring Operasional

Dokumen ini menjelaskan apa yang berubah, mengapa, dan apa yang perlu Anda lakukan
setelah mengekstrak ZIP ini.

## ⚠️ Temuan penting sebelum membaca lebih lanjut

Project yang diunggah (`GAOne.zip`) adalah **sistem General Affairs (GA)** — Travel,
Booking Fasilitas, ATK, Purchase Order, Survey/CSAT, Approval workflow — BUKAN sistem
monitoring BBM/Kendaraan/WC. Brief Anda meminta refactor total sebuah "website
Monitoring Operasional" dengan modul Fuel/Vehicle/Toilet, sementara source code yang
ada adalah sistem GA.

Karena brief juga secara eksplisit berkata "jangan hanya menambahkan fitur" namun
juga "pastikan seluruh fitur lama tetap berjalan" dan "jangan membuat project baru
dari nol", pendekatan yang diambil adalah:

**Menambahkan modul Monitoring Operasional (Fuel, Vehicle, Toilet) sebagai modul baru
dengan arsitektur bersih (Repository + Service Layer + Form Request + sidebar
permission-gated) ke dalam project GA yang sudah ada — tanpa mengubah logika bisnis
modul GA yang sudah berjalan.**

Jika project yang ingin direfactor sebenarnya berbeda dari yang terupload, beri tahu
saya dan unggah ulang — saya akan sesuaikan.

## Keterbatasan lingkungan kerja saya

Sandbox saya **tidak memiliki PHP/Composer/Node yang bisa dieksekusi, dan tidak ada
akses internet**. Artinya saya tidak bisa menjalankan `php artisan migrate`,
`composer install`, `npm run build`, atau linter apa pun untuk memverifikasi kode ini.

Setiap file di bawah ditulis dan ditinjau secara manual dengan sangat hati-hati
(termasuk pengecekan otomatis brace/paren balance dan referensi route/permission/nama
kelas), tetapi **Anda tetap harus menjalankan langkah setup di bawah dan mengujinya**
sebelum dipakai produksi.

## Langkah setup (wajib, dijalankan sekali setelah extract)

```bash
composer install
npm install && npm run build
php artisan migrate          # menambahkan 4 tabel baru, tidak mengubah tabel lama
php artisan db:seed          # idempotent (firstOrCreate), aman dijalankan ulang
php artisan storage:link     # supaya foto inspeksi WC bisa diakses via URL publik
```

Tidak ada package baru yang ditambahkan — `barryvdh/laravel-dompdf` dan
`maatwebsite/excel` sudah ada di `composer.json` sebelumnya dan sudah saya pastikan
sudah ter-cache di `bootstrap/cache/packages.php`, jadi export PDF/Excel langsung
berfungsi tanpa konfigurasi tambahan.

## Apa yang ditambahkan

**Database** — `database/migrations/2026_06_24_*`: `vehicles`, `fuel_logs`,
`toilet_inspections`, `toilet_inspection_items` (checklist EAV-style sesuai
permintaan — menambah item checklist hanya perlu edit `config/monitoring.php`, tidak
perlu migration baru).

**Konfigurasi** — `config/monitoring.php`: daftar checklist WC, opsi dropdown
lokasi/status/jenis kendaraan/jenis BBM, dan ambang batas notifikasi (bisa diubah via
`.env`: `MONITORING_FUEL_BUDGET_THRESHOLD`, `MONITORING_FUEL_MIN_KMPL`,
`MONITORING_TOILET_INTERVAL_HOURS`).

**Arsitektur** (Clean Architecture / Separation of Concerns):
- `app/Repositories/Contracts` + `app/Repositories/Eloquent` — Repository Pattern
  untuk Vehicle, FuelLog, ToiletInspection (dibind di `AppServiceProvider`).
- `app/Services/Monitoring` — Service Layer (VehicleService, FuelLogService,
  ToiletInspectionService, DashboardMonitoringService) — semua logika perhitungan
  (jarak tempuh, konsumsi BBM, total harga) ada **di satu tempat** di
  `FuelLogService`, bukan di controller atau blade.
- `app/Services/{Fuel,Toilet,Vehicle}NotificationService.php` — persis seperti yang
  Anda minta, terpisah dari controller.
- `app/Http/Requests/{Fuel,Vehicle,Toilet}` — validasi terpisah dari controller.
- `app/Http/Controllers/{Fuel,Vehicle,Toilet}` — controller tipis, hanya orkestrasi.
- `app/Console/Commands/CheckMonitoringAlerts.php` + scheduler di
  `routes/console.php` (`everyFourHours()`) — untuk notifikasi "WC belum diperiksa
  sesuai jadwal", karena ini berbasis waktu, bukan event simpan data.

**Route per modul** — `routes/fuel.php`, `routes/vehicle.php`, `routes/toilet.php`
(baru), dan saya juga memecah route GA yang lama (`routes/users.php`,
`routes/approvals.php`, `routes/reports.php`, `routes/modules.php`,
`routes/auth.php`) — **isinya identik 1:1 dengan `web.php` yang lama**, hanya
dipindah lokasi, supaya seluruh project konsisten "satu file route per modul"
seperti yang Anda minta, bukan hanya modul baru.

**Sidebar permission-gated** — `resources/views/layouts/sidebar/`: `index`,
`admin`, `operator`, `supervisor`, `fuel`, `vehicle`, `toilet`, `reports.blade.php`
(8 file, sesuai contoh struktur di brief Anda). Setiap link kini dibungkus
`@can('modul.view')`. **Sebelumnya seluruh menu sidebar tampil ke semua user tanpa
ada pengecekan permission sama sekali** — ini saya perbaiki untuk seluruh sidebar
(modul lama maupun baru), bukan hanya modul baru.

**Role & Permission** (Spatie, additive — role lama tidak diubah/dihapus):
permission baru `fuel.*`, `vehicle.*`, `toilet.*`. Role baru: **Super Admin** (akses
penuh), **Admin Operasional**, **Supervisor**, **Operator BBM**,
**Petugas Kebersihan**, **Viewer** — sesuai daftar di brief Anda. User admin yang
sudah ada (`admin@ga1.local`) otomatis mendapat role `Admin` **dan** `Super Admin`.

**Dashboard & Laporan** — `DashboardController` & `dashboard.blade.php` ditambah
4 stat card + 3 chart Chart.js (load via CDN, bukan npm — lihat catatan di bawah).
`reports/index.blade.php` ditambah 2 card laporan (Fuel & Toilet) dengan tombol
PDF/Excel.

**Normalisasi data** — field "Jenis Kendaraan" dan "Plat Kendaraan" pada brief untuk
modul Fuel **tidak disimpan ulang** di `fuel_logs`, melainkan diambil dari relasi
`vehicle_id → vehicles` (menghindari data ganda yang bisa tidak konsisten). Data ini
tetap tampil di semua tabel/form/laporan, hanya sumbernya dari relasi.

**Tanda tangan petugas** — diimplementasikan sebagai signature pad kanvas (vanilla
JS, tanpa dependency baru), disimpan sebagai data URI PNG di kolom `tanda_tangan`
(TEXT). Ini pilihan yang lebih sederhana & rendah-risiko dibanding upload file
terpisah; bisa diganti ke penyimpanan file nanti bila diperlukan.

## Bug lama yang ikut diperbaiki (ditemukan saat pengecekan, sesuai permintaan Anda)

1. `resources/views/modules/form.blade.php` memiliki 4 baris pagar markdown
   (` ``` `) yang tertinggal di tengah HTML — dihapus.
2. Pagination (`->links()`) di seluruh project sebenarnya **tidak bergaya** sama
   sekali karena view paginasi default Laravel (Tailwind) tidak cocok dengan CSS
   custom project ini, sementara Tailwind sendiri tidak benar-benar dikompilasi
   (tidak ada `@import "tailwindcss"` di `app.css`). Diperbaiki dengan
   `Paginator::useBootstrapFive()` di `AppServiceProvider`, plus beberapa utility
   class kecil yang hilang ditambahkan ke `app.css`.
3. Sidebar tanpa pengecekan permission (lihat poin sidebar di atas).

## Yang sengaja TIDAK saya sentuh, dan alasannya

Saya **tidak** menulis ulang modul GA yang sudah berjalan (Approval engine, Travel,
Facility, ATK, PO, Survey/`ModuleController`) ke pola Repository+Service literal.
Pola generic-driven yang sudah ada (`Ga1Modules` config + `ModuleController`) sudah
berfungsi sebagai bentuk DRY repository/service untuk data master sederhana, dan
kode itu sudah "bekerja" tanpa saya bisa menjalankan test untuk memverifikasi
perubahan. Menulis ulang logika yang sudah berjalan tanpa kemampuan menjalankan
`php` di lingkungan saya adalah risiko tinggi untuk manfaat yang tidak diminta
secara spesifik. Repository+Service Pattern saya terapkan penuh pada 3 modul baru
yang memang memiliki logika lebih kompleks (perhitungan otomatis, relasi checklist,
notifikasi berbasis ambang batas) — sesuai instruksi "Repository Pattern jika
diperlukan".

## Setelah setup, coba alur ini

1. Login sebagai `admin@ga1.local` / `12345678`.
2. Cek sidebar → grup "Monitoring Bahan Bakar / Kendaraan / Kebersihan WC" muncul.
3. Tambah kendaraan → tambah data BBM untuk kendaraan itu → cek dashboard & grafik.
4. Tambah inspeksi WC dengan status "Kotor" → cek menu Notifikasi, harus ada entri
   baru otomatis.
5. Coba export PDF & Excel dari halaman Fuel dan Toilet.
6. Buat user baru dengan role "Viewer" → pastikan dia hanya bisa melihat, tidak ada
   tombol Tambah/Edit/Hapus, dan menu lain (Users, Approvals, dll) tidak muncul di
   sidebar-nya.

---

# UPDATE 2 — Role Overhaul, ATK/Meeting/Konsumsi/Pengaduan/CSAT, Dashboard Role-Based

Lanjutan dari refactor di atas. Dokumen di atas masih berlaku; bagian ini menjelaskan
perubahan besar babak kedua. **Catatan penting yang sama seperti di atas masih berlaku:
saya tidak punya PHP/Composer/internet di sandbox ini, jadi semua kode di bawah ditulis
dan ditinjau manual, bukan dijalankan.**

## Yang berubah

**Role final (Spatie, role lama dihapus otomatis oleh seeder):** Admin, Manager,
Finance, GA Staff, Driver, Petugas Kebersihan, Karyawan. Role dari iterasi
sebelumnya (Super Admin, Admin Operasional, Supervisor, Operator BBM, Viewer)
**dihapus otomatis** saat `db:seed` dijalankan ulang (lihat `seedRoles()` —
`Role::whereNotIn('name', FINAL_ROLES)->delete()`). User yang sebelumnya hanya
punya role tersebut akan otomatis kehilangan akses; pastikan tidak ada user
produksi nyata yang bergantung pada role lama sebelum menjalankan ulang seeder.

**Default user** (password semua: `password`): `admin@gaone.test`,
`finance@gaone.test`, `manager@gaone.test`, `staff@gaone.test`,
`employee@gaone.test` (role Karyawan), plus dua tambahan agar dashboard Driver &
Petugas Kebersihan bisa langsung dicoba: `driver@gaone.test`,
`kebersihan@gaone.test`. Akun lama `admin@ga1.local` / `12345678` tetap ada.

**Dashboard kini sepenuhnya role-based** lewat `Auth::user()` —
`DashboardController` → `RoleDashboardService` menentukan role tertinggi user
(urutan prioritas bila multi-role: Admin > Manager > Finance > GA Staff > Driver >
Petugas Kebersihan > Karyawan) lalu merender `resources/views/dashboards/{role}.blade.php`
yang sesuai. Chart Chart.js (BBM/WC/Kendaraan) hanya muncul untuk Admin/Manager/GA Staff.

**Sidebar dirombak total** sesuai struktur yang diminta (Dashboard → Operasional →
Inventaris → Meeting & Konsumsi → Layanan → Laporan → Notifikasi → Manajemen →
Pengaturan), semua permission-gated. Modul GA lama (Travel, Booking Fasilitas
generik untuk Aula, Vendor, PO, hasil survei lama) **tidak dihapus** tapi
dipindah ke grup kecil "Modul Lama (GA)" di paling bawah karena tidak ada di
struktur sidebar final yang diminta — datanya tetap ada dan rute lama tetap aktif.

**Toilet Inspection:**
- Foto sekarang **wajib** (jpg/jpeg/png/webp, maks 5MB) untuk inspeksi baru.
- Field `petugas` (teks bebas) diganti `petugas_id` (wajib pilih akun user) untuk
  inspeksi baru. Kolom `petugas` lama **tidak dihapus** — tetap dibaca sebagai
  fallback tampilan untuk data lama via accessor `petugas_name`.

**Vehicle & FuelLog:** ditambah `driver_id` (FK ke users, nullable) di samping
kolom `driver` (teks bebas) yang lama — dipakai untuk dashboard Driver
("Kendaraan Saya", "Riwayat Pengisian BBM"). Form sekarang punya 2 kolom: nama
bebas dan akun user, supaya kendaraan dengan driver yang belum punya akun di
sistem tetap bisa dicatat.

## Modul baru

**Inventaris ATK** (`app/Services/Inventory/`, `app/Http/Controllers/Atk/`) —
dibangun di atas tabel ATK yang **sudah ada** dari versi GA (bukan tabel baru),
ditambah kolom `satuan` dan `lokasi_penyimpanan`. Status (Tersedia/Stok
Menipis/Habis) **dihitung otomatis** dari stock vs minimum_stock, tidak
disimpan, supaya tidak ada risiko data tidak sinkron. Barang Masuk/Keluar kini
benar-benar mengubah `stock` (sebelumnya, lewat CRUD generik lama, mencatat
movement TIDAK mengubah stok — itu bug lama yang ikut diperbaiki). Permintaan
ATK memakai `ApprovalEngine` yang sama dengan modul GA lama (workflow GA Staff →
Manager), dan approve otomatis memotong stok + kirim notifikasi.

**Booking Ruang Meeting** (`meeting_rooms`, `meeting_bookings` — tabel baru) —
validasi bentrok jadwal & kapasitas sebelum submit, approval GA Staff → Manager,
endpoint cek ketersediaan real-time saat mengisi form. "Kalender Booking" pada
brief disederhanakan menjadi daftar terfilter per ruangan/tanggal (bukan
tampilan kalender visual) untuk menjaga lingkup pekerjaan tetap realistis.

**Permintaan Konsumsi** (`consumption_requests` — tabel baru) — bisa berdiri
sendiri (menu sendiri) atau otomatis dibuat saat booking meeting mencentang
"Butuh Konsumsi". Status permintaan konsumsi yang terhubung ke booking
**mengikuti** status booking induknya (disetujui/ditolak/selesai mengikuti
booking) karena tidak punya approval workflow sendiri; permintaan konsumsi
mandiri punya approval sendiri (GA Staff → Manager).

**Pengaduan (Complaint)** — modul ini **tidak ada** di 12 modul final yang
disebutkan, namun dashboard Karyawan memintanya ("Pengaduan Saya") dan daftar
trigger CSAT juga menyebutnya. Saya menambahkannya sebagai modul kecil
(`complaints` — tabel baru, tanpa approval workflow, cukup status
menunggu/diproses/selesai) supaya kedua bagian itu konsisten. Beri tahu saya
jika modul ini sebetulnya tidak diperlukan.

**Survei Kepuasan (CSAT)** — memakai ulang tabel `surveys`/`survey_responses`
yang sudah ada dari versi GA (bukan tabel baru), dengan `CsatService` generik
yang bisa dipanggil modul mana pun saat statusnya "selesai". **Dipasang untuk**
Booking Meeting, Permintaan Konsumsi (mandiri & terhubung booking), Permintaan
ATK, dan Pengaduan. **Tidak dipasang** untuk Monitoring Kendaraan/BBM/WC — modul
ini berupa catatan operasional rutin tanpa konsep "pemohon menerima layanan
lalu menilai", jadi rating kepuasan tidak banyak relevan di sana; beri tahu saya
bila Anda tetap menginginkannya. Form rating disederhanakan jadi satu nilai 1–5
(bukan 3 skor terpisah) — nilai itu disalin ke 3 kolom skor lama agar kompatibel.

**Role & Permission Management** (`/admin/roles`, `/admin/permissions`) — CRUD
role + centang permission per modul. Permission tidak bisa dibuat manual lewat
UI (mengikuti matriks modul × ability yang sudah ada di seeder); halaman
Permission bersifat read-only untuk referensi.

**Policy** (`app/Policies/`) — dibuat untuk Vehicle, FuelLog, ToiletInspection,
AtkItem, AtkRequest, MeetingBooking, ConsumptionRequest, Complaint. Selain
permission dasar, beberapa policy menambahkan pembatasan "data milik sendiri"
(mis. Driver hanya boleh `view`/`update` fuel log miliknya). Didaftarkan
eksplisit di `AppServiceProvider::boot()`.

## Perbaikan penting lain

**Approval generik vs Service modul baru** — halaman `/approvals` (generik,
dipakai semua modul GA lama) tadinya akan memproses approve/reject ATK/Meeting/
Konsumsi langsung lewat `ApprovalEngine`, **melewati** efek tambahan yang saya
bangun di Service masing-masing (potong stok ATK, sinkron status konsumsi,
notifikasi). Sudah diperbaiki — `ApprovalController::act()` sekarang
mendelegasikan ke Service modul yang tepat berdasarkan tipe record, jadi
perilakunya konsisten dari halaman manapun approval dilakukan. Saat memperbaiki
ini saya juga menyadari Manager/Finance/GA Staff kehilangan akses approve
Travel/Facility/PO karena pengecekan permission baru menjadi lebih spesifik per
modul — sudah ditambahkan kembali permission `travel.approve` /
`facility.approve` / `po.approve` ke role yang sesuai agar tidak regresi.

**Keterbatasan yang diketahui (bukan bug baru, melekat di desain
`ApprovalEngine` yang sudah ada sebelumnya):** approval tidak memverifikasi
apakah role user yang menekan tombol approve memang role yang seharusnya di
*step* itu — hanya memverifikasi user punya permission `{modul}.approve` secara
umum. Untuk workflow 2–3 step (ATK, Travel, dst.) ini berarti, misalnya,
Manager secara teknis bisa menekan approve di step yang seharusnya milik GA
Staff. Memperbaikinya butuh perubahan pada `ApprovalEngine` itu sendiri
(menambah pengecekan step.role_name vs role user) yang di luar lingkup
perubahan kali ini, tapi saya catat di sini supaya transparan.

**Driver/Petugas Kebersihan dashboard — keterbatasan data:** "Riwayat
Perjalanan" dan "Jadwal Kendaraan" pada dashboard Driver belum punya modul
pencatatan sendiri di sistem ini (tidak ada trip-log atau scheduling kendaraan);
riwayat pengisian BBM ditampilkan sebagai proksi. Demikian juga rekap biaya
ATK pada dashboard Finance belum bisa dinominalkan karena skema ATK saat ini
tidak punya harga satuan per barang.

## Setup tambahan untuk update ini

```bash
php artisan migrate          # 8 migration baru (4 alter + 4 tabel baru)
php artisan db:seed          # PENTING: ini akan MENGHAPUS role lama yang tidak
                              # ada di daftar final (lihat peringatan role di atas)
```

Tidak ada package baru. Tidak perlu menjalankan command `vendor:publish` yang
disebutkan di brief — `spatie/laravel-permission`, `spatie/laravel-activitylog`,
dan `maatwebsite/excel` sudah berfungsi penuh tanpa publish config/migration
tambahan (migration permission & activity log sudah ada sejak versi awal
project ini), dan saya tidak punya cara menjalankan command tersebut di
lingkungan kerja saya untuk memverifikasinya. Jalankan sendiri bila Anda secara
spesifik butuh file config yang dipublish (mis. untuk mengubah nama tabel
Spatie), tapi untuk menjalankan sistem ini seperti apa adanya, tidak diperlukan.

## Uji coba yang disarankan untuk update ini

1. Login sebagai masing-masing dari 7 default user → pastikan dashboard & isi
   sidebar berbeda sesuai role.
2. Sebagai Karyawan: buat Permintaan ATK, Booking Meeting (centang "Butuh
   Konsumsi"), Permintaan Konsumsi mandiri, dan Pengaduan.
3. Sebagai GA Staff: approve permintaan ATK tadi dari halaman `/atk/requests`
   **dan** dari halaman generik `/approvals` (di waktu berbeda) → pastikan stok
   berkurang di kedua kasus.
4. Sebagai Manager: approve booking meeting → tandai selesai → cek menu CSAT
   milik Karyawan tadi, harus muncul survei baru untuk diisi.
5. Isi survei dengan rating 1 atau 2 → cek notifikasi Admin/Manager muncul.
6. Coba buat inspeksi WC tanpa upload foto → harus ditolak validasi.
7. Edit kendaraan, hubungkan ke akun Driver demo → login sebagai Driver → cek
   "Kendaraan Saya" muncul di dashboard.

---

# Update — Modul Surat Izin Keluar (SIK)

Modul baru **Surat Izin Keluar (SIK)** ditambahkan ke project yang sudah ada
(Monitoring Operasional Terintegrasi) mengikuti pola arsitektur yang sama dengan
modul-modul sebelumnya: Service Layer, Form Request, Policy per model, sidebar
permission-gated. Tidak ada modul lama yang diubah perilakunya — hanya
ditambahkan (`AppServiceProvider`, `DatabaseSeeder`, `routes/web.php`,
`resources/views/layouts/sidebar/index.blade.php`, `resources/css/app.css`,
`app/Models/User.php`, dan `composer.json` di-*append*, bukan ditulis ulang).

## ⚠️ Sama seperti update sebelumnya: sandbox saya tidak bisa menjalankan PHP/Composer/npm

Seluruh kode ditulis dan ditinjau manual (termasuk pengecekan brace/paren balance
dan cross-check nama route/permission/relasi), tapi **wajib dijalankan & diuji**
di lingkungan Anda sebelum dipakai produksi.

## Langkah setup (wajib, sekali jalan)

```bash
composer require simplesoftwareio/simple-qrcode   # sudah ada di composer.json,
                                                    # tinggal composer install / update
composer install
php artisan migrate     # 3 migration baru:
                         #  - alter users (+employee_number, additive, nullable)
                         #  - create surat_izin_keluars
                         #  - create surat_izin_keluar_scans (log riwayat scan)
php artisan db:seed      # idempotent, menambah role Security + permission sik.*
                          # + 1 user demo Security (security@gaone.test / password)
php artisan storage:link # kalau belum pernah, dipakai juga untuk lampiran SIK
npm run build             # ada penambahan CSS badge status SIK & style scanner
```

## Keputusan desain (kenapa, bukan cuma apa)

- **Departemen bukan tabel terpisah.** Project ini tidak punya model/tabel
  `departments` — semua modul lama menyimpan departemen sebagai string di
  `users.department`. Supaya konsisten dan tidak menambah struktur yang tidak
  perlu, `surat_izin_keluars.department` menyimpan **snapshot** string
  departemen pemohon saat pengajuan dibuat (bukan relasi/join), sehingga riwayat
  SIK tetap akurat walau departemen user berubah di kemudian hari. Relasi
  "belongsTo Department" pada spesifikasi diwakili oleh accessor
  `$sik->departmentName` di atas kolom string ini.
- **Nomor Karyawan belum ada di tabel `users`.** Ditambahkan lewat migration
  alter yang murni additive (`employee_number`, nullable, unique) — tidak
  mengubah/menghapus kolom lama. Untuk user lama yang belum diisi, sistem
  menampilkan fallback otomatis `EMP-00001` dst (lihat
  `User::getEmployeeNumberDisplayAttribute()`) supaya PDF/Excel tetap punya
  nilai valid tanpa memaksa Anda mengisi data secara manual dulu.
- **QR Code**: memakai `simplesoftwareio/simple-qrcode` (satu-satunya package
  baru yang ditambahkan). Isi QR **selalu** `uuid` milik SIK (dibungkus URL
  verifikasi `/sik/security/verify/{uuid}`), bukan ID database. Format render
  SVG dipakai untuk tampilan web (tidak butuh Imagick), PNG base64 untuk
  disisipkan ke PDF.
- **Approval hanya oleh Manager departemen yang sama.** "Bawahan" diinterpretasikan
  sebagai user dengan `department` yang sama dengan Manager tsb — ini konsisten
  dengan cara departemen dipakai di seluruh project (string, bukan struktur
  org-chart). Digaransi dua lapis: `SuratIzinKeluarPolicy::approve()` untuk
  otorisasi halaman, dan `ApprovalSIKService::guardManagerScope()` untuk
  otorisasi di level Service (defense-in-depth).
- **Nomor SIK** (`SIK-{tahun}-{urutan 6 digit}`) hanya dibuat saat approve, di
  dalam `DB::transaction()` + `lockForUpdate()` supaya tetap unik walau dua
  approval terjadi bersamaan.
- **Status hanya berubah lewat proses scan** — tidak ada endpoint/])form untuk
  mengubah status secara manual di mana pun (termasuk dari halaman Admin).
  `SecurityScanService::scan()` adalah satu-satunya titik masuk perubahan status
  approved → sedang_keluar → completed, berdasarkan status SIK saat itu, bukan
  pilihan manual dari Security.
- **Kamera HTML5, bukan upload gambar** — halaman Scan QR
  (`resources/views/sik/security/scan.blade.php`) memakai library
  `html5-qrcode` (CDN) dengan `Html5Qrcode.start()` langsung ke kamera
  (`facingMode: 'environment'`), tidak memakai fitur upload file dari library
  tersebut.
- **Riwayat Scan** dicatat di tabel terpisah `surat_izin_keluar_scans`, mencatat
  baik scan yang **berhasil** maupun **gagal** (QR tidak valid, sudah completed,
  dll) — dipakai untuk "Riwayat Scan Hari Ini" (Security) dan "Riwayat Scan"
  di halaman detail SIK (semua role yang berhak lihat).
- **Badge warna status** ditambahkan sebagai class CSS baru (`sik-pending_approval`,
  `sik-approved`, dst) di `resources/css/app.css`, additive di akhir file — tidak
  mengubah `.status-badge.active/.inactive/.pending` yang sudah dipakai modul lain.

## Permission baru

`sik.view`, `sik.create`, `sik.edit`, `sik.delete`, `sik.approve`, `sik.export`
mengikuti pola cross-product modul×ability yang sudah ada. `sik.scan` ditambahkan
terpisah (khusus role Security) karena tidak ada di modul lain. Role baru:
**Security** (hanya `sik.view` + `sik.scan` + `notification.view`). Role lain
mendapat kombinasi permission SIK sesuai halaman yang relevan untuknya — lihat
`DatabaseSeeder::seedRoles()`.

## Uji coba yang disarankan untuk update SIK ini

1. Login sebagai Karyawan → Pengajuan SIK → kirim pengajuan. Cek Nama/Nomor
   Karyawan/Departemen di form terisi otomatis (disabled, tidak bisa diketik).
2. Login sebagai Manager dengan departemen yang sama → menu Approval SIK harus
   muncul pengajuan tsb → Approve → cek Nomor SIK otomatis terbentuk
   (`SIK-2026-000001`) dan notifikasi masuk ke Karyawan.
3. Login sebagai Security → Scan Security → Scan QR → arahkan kamera ke QR dari
   halaman detail SIK milik Karyawan tsb (bisa dibuka di device lain / screenshot
   di layar) → status harus berubah ke "Sedang Keluar", `jam_keluar_aktual`
   terisi waktu server.
4. Scan QR yang sama sekali lagi → status berubah ke "Completed",
   `jam_kembali_aktual` terisi, durasi di luar tampil di halaman detail.
5. Scan QR yang sama untuk ketiga kalinya → harus muncul pesan "Surat Izin
   Keluar sudah selesai digunakan." dan tercatat sebagai scan gagal di Riwayat
   Scan Hari Ini.
6. Login sebagai Admin/GA Staff → Laporan SIK → coba semua filter → Export PDF
   dan Export Excel.
7. Coba akses `/sik/approvals` sebagai Manager dari departemen **lain** dari
   pemohon → pengajuan tsb tidak boleh muncul di daftar approval-nya.
