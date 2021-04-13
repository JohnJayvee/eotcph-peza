<?php

namespace App\Laravel\Models\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use App\Schedule;

use Helper,Str,Carbon;

class  ReportTransactionExport implements FromCollection,WithMapping,WithHeadings,ShouldAutoSize
{
    use Exportable;

    public function __construct(Collection $transactions)
    {
        $this->transactions = $transactions;
    }

    public function headings(): array
        {
            return [
                'Transaction Date',
                'Submitted By/Company Name',
                'Peza Unit',
                'Application Type',
                'Pre Processing Code',
                'Pre Processing Cost',
                'Pre Processing Code',
                'Post Processing Cost',
                'Processor',
                'Status',
            ];
        }

    public function map($value): array
    {
        return [
            Helper::date_format($value->created_at),
            $value->company_name,
            $value->department ? $value->department->name : "N/A",
            $value->type ? Strtoupper($value->type->name) : "N/A",
            $value->type ? $value->type->pre_process->code : "---",
            Helper::money_format($value->processing_fee) ?: 0 ,
            $value->type ? $value->type->post_process->code : "---",
            Helper::money_format($value->amount) ?: '---',
            str::title($value->admin ? $value->admin->full_name : '---'),
            $value->status == 'APPROVED' ? $value->status : ($value->is_resent == 1 ? "RESENT" : $value->status),

        ];
    }



    public function collection()
    {
        return $this->transactions;
    }

    // public function registerEvents(): array
    // {
    //     return [
    //         AfterSheet::class    => function(AfterSheet $event) {
    //             $cellRange = 'A1:I100';
    //             $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setSize(14);
    //             $event->sheet->getDelegate()->getRowDimension(1)->setRowHeight(30);
    //         },
    //     ];
    // }
}
