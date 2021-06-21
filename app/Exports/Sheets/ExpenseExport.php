<?php

namespace App\Exports\Sheets;

use DB;
use App\Repositories\ReportRepository;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithHeadings;

use Maatwebsite\Excel\Concerns\RegistersEventListeners;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class ExpenseExport implements FromQuery, WithTitle, WithHeadings, WithEvents
{
  use RegistersEventListeners;
  private $input = [];

  public function __construct(array $input)
  {
    $this->input = $input;
  }

  public function headings(): array
  {
    return [
      'Nama Pengeluaran',
      'Detail Pengeluaran',
      'Jumlah Pengeluaran',
      'Tgl. Pengeluaran',
      'Status Pengeluaran',
      'Dibuat Oleh',
      'Tanggal Disetujui',
      'Disetujui Oleh',
    ];
  }

  /**
   * @return Builder
   */
  public function query()
  {
    $q =  ReportRepository::getExport($this->input);
    return $q->select(
        'expensename', 
        'expensedetail', 
        'expenseprice',
        DB::raw("to_char(expensedate, 'DD-MM-YYYY') as tanggal"),
        DB::raw("CASE WHEN expenses.expenseexecutedby = '0' THEN 'Draft' ELSE 'Selesai' END as status"),
        'cr.username as create',
        DB::raw("to_char(expenseexecutedat, 'DD-MM-YYYY') as tanggalend"),
        'er.username as execute',
      )->orderBy('expensedate', 'asc')
    ->getQuery();
  }

  /**
   * @return string
   */
  public function title(): string
  {
    return 'Pengeluaran';
  }

  public static function afterSheet(AfterSheet $event){
    $event->sheet->appendRows(array(
        array('test1', 'test2'),
        array('test3', 'test4'),
        //....
    ), $event);
  }

/**
 * @return array
 */
  public function registerEvents(): array
  {
    $styleTitulos = [
      'font' => [
        'bold' => true,
        'size' => 12
      ]
    ];
    return [
      AfterSheet::class => function(AfterSheet $event) use ($styleTitulos){
        $event->sheet->getStyle("A1:H1")->applyFromArray($styleTitulos);
        $event->sheet->setCellValue('A'. ($event->sheet->getHighestRow()+1),"");
        $tyes = ReportRepository::get($this->input);
        foreach ($tyes as $item){
          if(isset($item->totalex)){
            $event->sheet->appendRows(array(
              array('Total', $item->totalex),
            ), $event);
          }
        }
      }
    ];
  }
}