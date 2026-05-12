<?php

namespace App\Exports;

use App\Models\Complaint;
use App\Models\DataEntry;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithMapping;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Carbon\Carbon;

class ComplaintExport implements FromQuery, WithHeadings, WithStyles, WithColumnWidths, WithMapping
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
        ->where('task_name', 'complaint')
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

    public function map($complaint): array
    {
        return [
            $complaint->id,
            $this->decryptData($complaint->exchange_name),
            $this->decryptData($complaint->user_name),
            $this->decryptData($complaint->name),
            $this->decryptData($complaint->phone->phone_number),
            $this->decryptData($complaint->feedback),
            $this->decryptData($complaint->amount),
            $complaint->created_at,
            $complaint->updated_at,
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
            'C' => 20,
            'D' => 25,
            'E' => 20,
            'F' => 30,
            'G' => 15,
            'H' => 20,
            'I' => 20,
        ];
    }
}
