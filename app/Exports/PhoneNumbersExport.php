<?php

namespace App\Exports;

use App\Models\PhoneNumber;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
class PhoneNumbersExport implements FromQuery, WithHeadings, WithMapping, WithStyles, WithColumnWidths
{
    public function query()
    {
        return PhoneNumber::where('status', 'active')->select('phone_number', 'status', 'created_at');
    }

    public function headings(): array
    {
        return [
            'Phone Number',
            'Status',
            'Created At',
        ];
    }

    public function map($phoneNumber): array
    {
        return [
            $this->decryptData($phoneNumber->phone_number),
            $phoneNumber->status,
            $phoneNumber->created_at,
        ];
    }

    private function decryptData($encryptedData)
    {
        return $encryptedData;
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1:C1')->getFont()->setBold(true);
        $sheet->getStyle('A1:C1')->getFont()->setSize(12);
        $sheet->getStyle('A:A')->getAlignment()->setHorizontal('left');
        return $sheet;
    }

    public function columnWidths(): array
    {
        return [
            'A' => 25,
            'B' => 15,
            'C' => 25,
        ];
    }
}
