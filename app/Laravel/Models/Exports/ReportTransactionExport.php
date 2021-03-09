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

class ReportTransactionExport implements FromCollection,WithMapping,WithHeadings,ShouldAutoSize
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
                'Submitted By',
                'Application Code',
                "Application Type",
                "Peza Unit",
                "Account Code",
                "Description",
                "Processing Fee",
                "Processing Fee Status",
                "Application Fee",
                "Application Fee Status",
                "Processed By",
                "Status",
                
            ];
        }

    public function map($value): array
    {
        return [
           
            
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