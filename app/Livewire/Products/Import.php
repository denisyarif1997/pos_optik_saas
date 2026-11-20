<?php

namespace App\Livewire\Products;

use App\Models\Category;
use App\Models\Product;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('components.layouts.app')]
class Import extends Component
{
    use WithFileUploads;

    public $file;

    public array $fileContent = [];

    public int $validationStage = 1;

    public array $validationErrors = [];

    public array $validationWarnings = [];

    public array $validTypes = ['FRAME', 'STOCK_LENS', 'RX_LENS', 'CONTACT_LENS', 'SERVICE'];

    public array $storeCategories = [];

    public int $importedCount = 0;

    public bool $showImportResult = false;

    public function downloadTemplate()
    {
        $headers = ['name', 'type', 'category', 'sku', 'barcode', 'sell_price', 'cost_price', 'stock', 'min_stock'];
        $example = ['Frame Titanium Aviator', 'FRAME', 'Frame', 'FRM-001', '', '350000', '180000', '10', '2'];

        return response()->streamDownload(function () use ($headers, $example) {
            $out = fopen('php://output', 'w');
            fputcsv($out, $headers);
            fputcsv($out, $example);
            fclose($out);
        }, 'template-produk.csv', ['Content-Type' => 'text/csv']);
    }

    public function updatedFile(): void
    {
        // Dipanggil otomatis Livewire setelah file berhasil diupload via wire:model
        if ($this->file) {
            $this->upload();
        }
    }

    public function upload(): void
    {
        if (! $this->file) {
            $this->addError('file', 'Pilih file terlebih dahulu sebelum menekan Muat File.');

            return;
        }

        $this->validate([
            'file' => 'required|file|max:1024|mimes:csv,txt',
        ]);

        $storeId = app('activeStore')->id();
        abort_unless($storeId, 403);

        $path = $this->file->getRealPath();
        $rows = [];
        $handle = fopen($path, 'r');
        $header = fgetcsv($handle);

        if ($header === false) {
            $this->addError('file', 'File kosong atau tidak dapat dibaca.');

            return;
        }

        $expected = ['name', 'type', 'category', 'sku', 'barcode', 'sell_price', 'cost_price', 'stock', 'min_stock'];
        $missing = array_diff($expected, $header);
        if (! empty($missing)) {
            $this->addError('file', 'Header kolom tidak lengkap. Kolom yang hilang: '.implode(', ', $missing));

            return;
        }

        while (($row = fgetcsv($handle)) !== false) {
            if (empty(array_filter($row))) {
                continue;
            }
            $rows[] = array_combine($header, $row);
        }
        fclose($handle);

        if (empty($rows)) {
            $this->addError('file', 'Tidak ada data baris yang valid di file.');

            return;
        }

        $this->fileContent = $rows;
        $this->validationStage = 1;
        $this->validationErrors = [];
        $this->validationWarnings = [];
        $this->importedCount = 0;
        $this->showImportResult = false;
    }

    public function validateMasterData(): void
    {
        $storeId = app('activeStore')->id();
        abort_unless($storeId, 403);

        $this->storeCategories = Category::forStore($storeId)
            ->pluck('name')
            ->toArray();

        $errors = [];
        $warnings = [];
        $existingSkus = Product::forStore($storeId)
            ->whereNotNull('sku')
            ->pluck('sku')
            ->filter()
            ->toArray();
        $skusInFile = [];

        foreach ($this->fileContent as $index => $row) {
            $rowNum = $index + 2;

            if (empty(trim((string) ($row['name'] ?? '')))) {
                $errors[] = "Baris {$rowNum}: Nama produk wajib diisi.";

                continue;
            }

            $type = strtoupper(trim((string) ($row['type'] ?? '')));
            if ($type !== '' && ! in_array($type, $this->validTypes)) {
                $errors[] = "Baris {$rowNum}: Tipe '{$row['type']}' tidak valid. Pilihan: ".implode(', ', $this->validTypes);
            }

            $category = trim((string) ($row['category'] ?? ''));
            if ($category !== '' && ! in_array($category, $this->storeCategories)) {
                $warnings[] = "Baris {$rowNum}: Kategori '{$category}' belum ada di toko. Tambahkan data master kategori terlebih dahulu.";
            }

            $sku = trim((string) ($row['sku'] ?? ''));
            if ($sku !== '') {
                if (in_array($sku, $existingSkus)) {
                    $warnings[] = "Baris {$rowNum}: SKU '{$sku}' sudah ada di toko (data akan diupdate).";
                }
                if (in_array($sku, $skusInFile)) {
                    $errors[] = "Baris {$rowNum}: SKU '{$sku}' duplikat dalam file.";
                }
                $skusInFile[] = $sku;
            }
        }

        $this->validationErrors = $errors;
        $this->validationWarnings = $warnings;
    }

    public function continueImport(): void
    {
        // Tahap 2: Validasi ulang sebelum impor aktual
        $this->validateMasterData();

        if (! empty($this->validationErrors)) {
            $this->validationStage = 1;
            $this->addError('import', 'Ada kesalahan validasi yang harus diperbaiki sebelum mengimpor.');

            return;
        }

        $storeId = app('activeStore')->id();
        $count = 0;

        foreach ($this->fileContent as $row) {
            $name = trim((string) ($row['name'] ?? ''));
            $type = strtoupper(trim((string) ($row['type'] ?? 'FRAME')));
            $categoryName = trim((string) ($row['category'] ?? ''));
            $sku = trim((string) ($row['sku'] ?? '')) ?: null;
            $barcode = trim((string) ($row['barcode'] ?? '')) ?: null;

            $categoryId = null;
            if ($categoryName !== '') {
                $cat = Category::forStore($storeId)->where('name', $categoryName)->first();
                if ($cat) {
                    $categoryId = $cat->id;
                }
            }

            $data = [
                'store_id' => $storeId,
                'name' => $name,
                'type' => $type,
                'category_id' => $categoryId,
                'sku' => $sku,
                'barcode' => $barcode,
                'sell_price' => (float) ((string) ($row['sell_price'] ?? 0)),
                'cost_price' => (float) ((string) ($row['cost_price'] ?? 0)),
                'stock' => (int) ((string) ($row['stock'] ?? 0)),
                'min_stock' => (int) ((string) ($row['min_stock'] ?? 0)),
                'is_active' => true,
            ];

            if ($sku !== null && Product::forStore($storeId)->where('sku', $sku)->exists()) {
                Product::forStore($storeId)->where('sku', $sku)->update($data);
            } else {
                Product::create($data);
            }

            $count++;
        }

        $this->importedCount = $count;
        $this->fileContent = [];
        $this->validationStage = 3;
        $this->showImportResult = true;
        $this->file = null;

        session()->flash('success', "Berhasil mengimpor {$count} produk.");
    }

    public function resetImport(): void
    {
        $this->file = null;
        $this->fileContent = [];
        $this->validationStage = 1;
        $this->validationErrors = [];
        $this->validationWarnings = [];
        $this->importedCount = 0;
        $this->showImportResult = false;
    }

    public function render()
    {
        return view('livewire.products.import')->title('Impor Produk');
    }
}
