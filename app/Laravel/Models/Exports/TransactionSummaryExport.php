<?php

namespace App\Laravel\Models\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\Fill;
use Maatwebsite\Excel\Events\AfterSheet;

use Helper,Str,Carbon,DB;

class TransactionSummaryExport implements WithEvents,FromCollection,WithMapping,WithHeadings,ShouldAutoSize
{
    use Exportable;

    public function __construct(Collection $transactions,$transaction_count)
    {
        $this->transactions = $transactions;
        $this->transaction_count = $transaction_count;

    }

    public function headings(): array
        {
            return [
                'Date',
                'Pre-Processing Reference Number',
                'Post-Processing Reference Number',
                'Payor',
                'Tax',
                "Total",
               
                
            ];
        }

    public function map($value): array
    {
        return [
            Helper::date_format($value->created_at),
            $value->processing_fee_code,
            $value->transaction_code,
            $value->company_name,
            " ",
            Str::upper($value->total_amount),
            
        ];
    }



    public function collection()
    {
        return $this->transactions;
    }

   public function registerEvents(): array
    {
        $styleTitulos = [
        'font' => [
            'bold' => true,
            'size' => 12
        ]
        ];
        return [
            BeforeExport::class => function(BeforeExport $event) {
                $event->writer->getProperties()->setCreator('Sistema de alquileres');
            },
            AfterSheet::class => function(AfterSheet $event) use ($styleTitulos){
                $event->sheet->getStyle("A1:F1")->applyFromArray($styleTitulos);
                $this->filas = [];
                $this->limites = [];
                foreach ($this->transaction_count as $key => $value) {

                     array_push($this->limites, $value->count);
                    if ($key > 1) {
                        array_push($this->filas, $value->count + $this->filas[$key-1] + 1 );
                    }else{
                        array_push($this->filas, $value->count + array_sum($this->filas) + 1 );
                    }
                }
                foreach ($this->filas as $index => $fila){
                    $fila++;
                    $event->sheet->insertNewRowBefore($fila, 1);
                    
                   
                    $event->sheet->setCellValue("F{$fila}", "=SUM(E".($fila - $this->limites[$index]).":F".($fila - 1).")");
                    $event->sheet->setCellValue("A{$fila}","SUBTOTAL");
                    
                    
                }
                $event->sheet->setCellValue('A'. ($event->sheet->getHighestRow() + 2),"GRAND TOTAL");
                $event->sheet->setCellValue('F'. ($event->sheet->getHighestRow()), $this->transactions->sum('total_amount'));
                $event->sheet->getDelegate()->getStyle('A'.$event->sheet->getHighestRow().':'.$event->sheet->getDelegate()->getHighestColumn().$event->sheet->getHighestRow())
                    ->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('92D050');
            }
        ];
    }
}