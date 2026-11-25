<?php
namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class RiwayatExport implements FromCollection, WithHeadings, WithMapping
{
    private int $no = 0;

    public function __construct(private Collection $data) {}

    public function collection() { return $this->data; }

    public function headings(): array
    {
        return ['No', 'Tanggal', 'Hasil', 'Akurasi (%)', 'Sumber'];
    }

    public function map($row): array
    {
        return [++$this->no, $row->created_at->format('d-m-Y H:i'), $row->hasil, $row->akurasi, ucfirst($row->sumber)];
    }
}