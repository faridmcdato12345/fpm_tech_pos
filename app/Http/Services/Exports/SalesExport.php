<?php

namespace App\Http\Services\Exports;

use Illuminate\Support\Collection;
use App\Http\Services\FormatNumber;
use App\Models\Company;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithEvents;
use PhpOffice\PhpSpreadsheet\Style\Border;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Events\BeforeSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class SalesExport implements 
    FromCollection, 
    WithStrictNullComparison,
    ShouldAutoSize,
    WithEvents,
    WithHeadings,
    WithTitle,
    WithMapping,
    WithStyles
{
    protected $collection;
    protected $model;
    protected $titleHeader;
    protected $title;
    public function __construct(Collection $collection, string $model, string $titleHeader, string $title = '')
    {
        $this->titleHeader = $titleHeader;
        $this->collection = $collection;
        $this->title = $title;
        if(is_string($model)){
            $this->model = new $model;
        }
    }
     /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'ID',
            'INVOICE NUMBER',
            'PRODUCT NAME',
            'QUANTITY',
            'PRICE',
            'SUB TOTAL',
        ];
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return 'Sales Data';
    }
    public function registerEvents(): array
    {
        $store = Company::first();
        return [

            AfterSheet::class => function (AfterSheet $event) use ($store){
                $sheet = $event->sheet->getDelegate();
                $event->sheet->autoSize(true);
                $sheet->insertNewRowBefore(1, 4);
                if($store->company_logo){
                    $drawing = new Drawing;
                    $drawing->setName('Logo');
                    $drawing->setDescription('This is my logo');
                    $drawing->setPath(public_path($store->company_logo));
                    $drawing->setHeight(50);
                    $sheet->mergeCells('A1:A4');
                    $drawing->setCoordinates('A1');
                    $drawing->setWorksheet($event->sheet->getDelegate());
                    $sheet->mergeCells('B1:F1');
                    $sheet->mergeCells('B2:F2');
                    $sheet->mergeCells('B3:F3');
                    $sheet->mergeCells('B4:F4');
                    $sheet->setCellValue('B1','Store: ' . $store->name );
                    $sheet->setCellValue('B2','Address: ' . $store->address );
                    $sheet->setCellValue('B3','Email: ' . $store->email );
                    $sheet->setCellValue('B4','Contact No.: ' . $store->mobile_number );
                    $sheet->getStyle('B1:F4')->applyFromArray([
                        'font' => [
                            'bold' => true,
                        ],
                        'alignment' => [
                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
                            'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                            'wrapText' => true,
                            'indent' => 3
                        ],
                    ]);
                    $sheet->getStyle('A4:F4')->applyFromArray([
                        
                        'borders' => [
                            'bottom' => [
                                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM,
                                'colors' => ['argb' => '0000000']
                            ]
                        ],
                    ]);
                    $sheet->getStyle('A1:A4')->applyFromArray([
                        'alignment' => [
                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
                            'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                            'wrapText' => true,
                            'indent' => 3
                        ],
                    ]);
                }else{
                    $sheet->setCellValue('A1','Store: ' . $store->name . "\n" . 'Address: ' . $store->address . "\n" . 'Email: ' . $store->email . "\n" . 'Contact No.: ' . $store->mobile_number);
                    $sheet->mergeCells('A1:F1');
                }
                $sheet->insertNewRowBefore(5, 1);
                $sheet->mergeCells('A5:F5');
                $sheet->setCellValue('A5',"\n");
                $sheet->getStyle('A5:F5')->applyFromArray([
                    'borders' => [
                        'bottom' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_NONE,
                        ]
                    ],
                ]);

                $sheet->insertNewRowBefore(6, 1);
                $sheet->mergeCells('A6:F6');
                $sheet->setCellValue('A6', $this->title);
                $sheet->getStyle('A6')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 16
                    ],
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,

                    ],
                    
                ]);
                $sheet->insertNewRowBefore(7, 1);
                $sheet->mergeCells('A7:F7');
                $sheet->setCellValue('A7', $this->titleHeader );
                $sheet->getStyle('A7')->applyFromArray([
                    'font' => [
                        'bold' => false,
                        'size' => 12,
                        'italic' => true,
                    ],
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,

                    ],
                    
                ]);
                $sheet->insertNewRowBefore(8, 1);
                $sheet->mergeCells('A8:F8');
                $sheet->setCellValue('A8',"\n");
                $sheet->getStyle('A8')->applyFromArray([
                    'alignment' => [
                        'indent' => 5
                    ],
                ]);
               
                $sheet->getStyle('A9:F9')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 14
                    ],
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                        'wrapText' => true,
                        'intent' => 3
                    ],

                ]);
                
                // Calculate total amount
                $totalAmount = $this->collection->sum('extended');
                $rowCount = $this->collection->count() + 10; // +3 for the title and headings rows
                // Insert the total amount row
                $sheet->setCellValue('A' . $rowCount, 'GRAND TOTAL');
                $sheet->setCellValue('C' . $rowCount, (new FormatNumber)->toCurrency($totalAmount));
                $sheet->mergeCells('A'. ($rowCount) . ':B' . ($rowCount));
                $sheet->mergeCells('C'. ($rowCount) . ':F' . ($rowCount));
                // Style the total amount row
               
                $sheet->getStyle('A' . $rowCount . ':' . 'B' . $rowCount)->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 16
                    ],
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
                        'indent' => 3
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'colors' => ['argb' => '0000000']
                        ]
                    ],
                ]);
                $sheet->getStyle('C' . $rowCount . ':' . 'F' . $rowCount)->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 16
                    ],
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
                        'indent' => 3
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'colors' => ['argb' => '0000000']
                        ]
                    ],
                ]);
                $sheet->getStyle('A9:F'.($rowCount - 1))->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'colors' => ['argb' => '0000000']
                        ]
                    ],
                    
                ]);
            },
        ];
    }
    public function map($sales): array
    {
        return [
            $sales['id'],
            $sales['invoice_number'],
            $sales['product_name'],
            $sales['quantity'],
            (new FormatNumber)->toCurrency($sales['price']),
            (new FormatNumber)->toCurrency($sales['extended']),
        ];
    }
    public function styles(Worksheet $sheet)
    {
        return [
            'A' => [
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                ],
            ],
            'B' => [
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                ],
            ],
            
            'C' => [
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                ],
            ],
            'D' => [
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                ],
            ],
            'E' => [
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
                    'indent' => 3
                ],
            ],
            'F' => [
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
                    'indent' => 3
                ],
            ],
            
        ];
    }
    public function collection()
    {
       return $this->collection;
    }
}