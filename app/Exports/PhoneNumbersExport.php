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
        $key = 'MRikam@#@2024!XY';
        $iv = hex2bin('00000000000000000000000000000000');

        if (empty($encryptedData)) {
            return $encryptedData;
        }

        try {
            $decodedData = base64_decode($encryptedData, true);
            if ($decodedData === false) {
                return $encryptedData;
            }

            $decryptedData = openssl_decrypt($decodedData, 'AES-128-CBC', $key, OPENSSL_RAW_DATA, $iv);
            if ($decryptedData === false) {
                return $encryptedData;
            }
            return $decryptedData;
        } catch (\Exception $e) {
            return $encryptedData;
        }
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
