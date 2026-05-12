<?php

namespace App\Exports;

use App\Models\User;
use App\Models\DataEntry;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithMapping;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class QuaterlyReportExport implements FromQuery, WithHeadings, WithStyles, WithColumnWidths, WithMapping
{
    use Exportable;

    protected $fourMonthsAgo;

    public function __construct()
    {
    }

    public function query()
    {
        $key = config('app.aes_encrypt_key');
        $fourMonthsAgo = Carbon::now()->subMonths(4);
        $query = User::select(
            'users.name as user_name',
            'exchanges.name as exchange_name',
            \DB::raw('COUNT(data_entries.id) as TotalNewIdCount'),
            \DB::raw("SUM(CAST(data_entries.amount AS DECIMAL(10, 2))) as TotalAmountFourMonths")
            )
            ->join('exchanges', 'users.exchange_id', '=', 'exchanges.id')
            ->leftJoin('data_entries', function ($join) use ($fourMonthsAgo) {
                $join->on('users.id', '=', 'data_entries.user_id')
                    ->where('data_entries.task_name', '=', 'newid')
                    ->where('data_entries.created_at', '>=', $fourMonthsAgo);
            })
            ->whereNotNull('users.exchange_id')
            ->groupBy('users.id');

            $count = $query->count();

        return $query;
    }
    private function decryptData($encryptedData)
    {
        return $encryptedData;
    }

    public function map($record): array
    {
        return [
            $this->decryptData($record->user_name),    // Decrypt user_name
            $this->decryptData($record->exchange_name), // Decrypt exchange_name
            $record->TotalNewIdCount,
            number_format($record->TotalAmountFourMonths, 2),
        ];
    }

    public function headings(): array
    {
        return [
            'User Name',
            'Exchange Name',
            'Total New ID',
            'Total Amount',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1:D1')->getFont()->setBold(true);
        $sheet->getStyle('A1:D1')->getFont()->setSize(12);
        return $sheet;
    }

    public function columnWidths(): array
    {
        return [
            'A' => 25,
            'B' => 25,
            'C' => 20,
            'D' => 30,
        ];
    }
}
