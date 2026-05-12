<?php

namespace App\Exports;

use App\Models\FollowUp;
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

class FollowUpExport implements FromQuery, WithHeadings, WithStyles, WithColumnWidths, WithMapping
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
            ->where('task_name', 'followup')
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

    public function map($followUp): array
    {
        \Log::info('Map Method Called in FollowUpExport');

        return [
            $followUp->id,
            $this->decryptData($followUp->exchange_name),
            $this->decryptData($followUp->user_name),
            $this->decryptData($followUp->name),
            $this->decryptData($followUp->phone->phone_number),
            $this->decryptData($followUp->feedback),
            $this->decryptData($followUp->amount),
            $followUp->created_at,
            $followUp->updated_at,
        ];
    }

    private function decryptData($encryptedData)
    {
        return $encryptedData;
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
