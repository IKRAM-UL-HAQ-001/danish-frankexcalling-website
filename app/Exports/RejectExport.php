<?php

namespace App\Exports;

use App\Models\Reject;
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

class RejectExport implements FromQuery, WithHeadings, WithStyles, WithColumnWidths, WithMapping
{
    use Exportable;

    protected $exchange_id;
    protected $user_id;
    protected $start_date;
    protected $end_date;

    public function __construct($exchange_id, $user_id, $start_date, $end_date)
    {
        $this->exchange_id = $exchange_id;
        $this->user_id = $user_id;
        $this->start_date = $start_date;
        $this->end_date = $end_date;
    }

    public function query()
    {
        $startDate = Carbon::parse($this->start_date)->startOfDay();
        $endDate = Carbon::parse($this->end_date)->endOfDay();

        return DataEntry::select(
                'data_entries.id',
                'exchanges.name as exchange_name',
                'users.name as user_name',
                'data_entries.name',
                'data_entries.phone_id',
                'data_entries.feedback',
                'data_entries.amount',
                'data_entries.created_at',
                'data_entries.updated_at'
            )
            ->where('task_name', 'reject')
            ->join('exchanges', 'data_entries.exchange_id', '=', 'exchanges.id')
            ->join('users', 'data_entries.user_id', '=', 'users.id')
            ->whereBetween('data_entries.created_at', [$startDate, $endDate])
            ->when($this->exchange_id, function ($query) {
                $query->where('data_entries.exchange_id', (int) $this->exchange_id);
            })
            ->when($this->user_id, function ($query) {
                $query->where('data_entries.user_id', (int) $this->user_id);
            })
            ->distinct();
    }

    public function map($reject): array
    {
        \Log::info('Map Method Called in RejectExport');

        return [
            $reject->id,
            $this->decryptData($reject->exchange_name),
            $this->decryptData($reject->user_name),
            $this->decryptData($reject->name),
            $this->decryptData($reject->phone->phone_number),
            $this->decryptData($reject->feedback),
            $this->decryptData($reject->amount),
            $reject->created_at,
            $reject->updated_at,
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

    public function headings(): array
    {
        return [
            'ID',
            'Exchange Name',
            'User Name',
            'Customer Name',
            'Customer Number',
            'Feedback',
            'Amount',
            'Created At',
            'Updated At',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1:I1')->getFont()->setBold(true);
        $sheet->getStyle('A1:I1')->getFont()->setSize(12);
        return $sheet;
    }

    public function columnWidths(): array
    {
        return [
            'A' => 10,
            'B' => 20,
            'C' => 15,
            'D' => 20,
            'E' => 20,
            'F' => 20,
            'G' => 20,
            'H' => 30,
            'I' => 30,
        ];
    }
}
