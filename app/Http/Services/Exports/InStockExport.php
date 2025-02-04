<?php

namespace App\Http\Services\Exports;

use Illuminate\Support\Collection;
use App\Models\Company;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class InStockExport implements 
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
    protected $propertyCounts;
    public function __construct(Collection $collection, string $model, string $titleHeader = '')
    {
        $this->titleHeader = $titleHeader;
        $this->collection = $collection;
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
            'PRODUCT NAME',
            'CATEGORY',
            'UNIT',
            'QUANTITIES'
        ];
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return 'In Stock Products';
    }
    public function registerEvents(): array
    {
        $store = Company::first();
        return [

            AfterSheet::class => function (AfterSheet $event) use ($store){
                $paperWidthMm = 210;
                $paperHeightMm = 297;

                $marginMm = 19.1 * 2;

                $availableWidthMm = $paperWidthMm - $marginMm;

                $columnCount = 4;
                $columnWidthMm = $availableWidthMm / $columnCount;

                $columnWidthPoints = $columnWidthMm * 2.835;

                foreach (range('A', 'D') as $column) {
                    $sheet = $event->sheet->getDelegate()->getColumnDimension($column)->setWidth($columnWidthPoints);
                }
                $sheet = $event->sheet->getDelegate();
                $sheet->getColumnDimension($column)->setWidth($columnWidthPoints);
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
                    $sheet->mergeCells('B1:E1');
                    $sheet->mergeCells('B2:E2');
                    $sheet->mergeCells('B3:E3');
                    $sheet->mergeCells('B4:E4');
                    $sheet->setCellValue('B1','Store: ' . $store->name );
                    $sheet->setCellValue('B2','Address: ' . $store->address );
                    $sheet->setCellValue('B3','Email: ' . $store->email );
                    $sheet->setCellValue('B4','Contact No.: ' . $store->mobile_number );
                    $sheet->getStyle('B1:E4')->applyFromArray([
                        'font' => [
                            'bold' => true,
                            'name' => 'Calibri'
                        ],
                        'alignment' => [
                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
                            'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                            'wrapText' => true,
                            'indent' => 3
                        ],
                    ]);
                    $sheet->getStyle('A4:E4')->applyFromArray([
                        
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
                    $sheet->mergeCells('A1:E1');
                }
                $sheet->insertNewRowBefore(5, 1);
                $sheet->mergeCells('A5:E5');
                $sheet->setCellValue('A5',"\n");
                $sheet->getStyle('A5:E5')->applyFromArray([
                    'borders' => [
                        'bottom' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_NONE,
                        ]
                    ],
                ]);

                $sheet->insertNewRowBefore(6, 1);
                $sheet->mergeCells('A6:E6');
                $sheet->setCellValue('A6','IN STOCK PRODUCTS');
                $sheet->getStyle('A6')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'name' => 'Calibri',
                        'size' => 16
                    ],
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,

                    ],
                    
                ]);
                $sheet->insertNewRowBefore(7, 1);
                $sheet->mergeCells('A7:E7');
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
                $sheet->mergeCells('A8:E8');
                $sheet->setCellValue('A8',"\n");
                $sheet->getStyle('A8')->applyFromArray([
                    'alignment' => [
                        'indent' => 5
                    ],
                ]);
               
                $sheet->getStyle('A9:E9')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'name' => 'Calibri',
                        'size' => 14
                    ],
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                        'wrapText' => true,
                        'intent' => 3
                    ],

                ]);
                
                $rowCount = $this->collection->count() + 10;
                $sheet->getStyle('A9:E'.($rowCount - 1))->applyFromArray([
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
    public function map($product): array
    {
        return [
            $product['id'],
            $product['product_name'],
            $product['categories']['name'],
            $product['units']['name'],
            $product['quantities']['value'],
        ];
    }
    public function styles(Worksheet $sheet)
    {
        return [
            'A' => [
                'font' => ['name' => 'Calibri'],
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                ],
            ],
            'B' => [
                'font' => ['name' => 'Calibri'],
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                ],
            ],
            
            'C' => [
                'font' => ['name' => 'Calibri'],
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                ],
            ],
            'D' => [
                'font' => ['name' => 'Calibri'],
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                ],
            ],
            'E' => [
                'font' => ['name' => 'Calibri'],
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                ],
            ],
            
        ];
    }
    public function collection()
    {
       return $this->collection;
    }
}