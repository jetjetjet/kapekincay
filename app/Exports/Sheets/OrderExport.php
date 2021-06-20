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

class OrderExport implements FromQuery, WithTitle, WithHeadings, WithEvents
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
      'Tgl. Pesanan',
      'Tipe Pesanan',
      'Nomor Pesanan',
      'Harga Pesanan',
      'Diskon',
      'Status',
      'Dibuat Oleh'
    ];
  }

  /**
   * @return Builder
   */
  public function query()
  {
    return ReportRepository::getOrder($this->input)->select(
      DB::raw("to_char(orderdate, 'DD-MM-YYYY') as tanggal"),   
      DB::raw("CASE WHEN orders.ordertype = 'DINEIN' THEN 'Makan ditempat' ELSE 'Bungkus' END as ordertypetext"),
      'orderinvoice',
      DB::raw("orderprice - coalesce(orderdiscountprice,0) as price"),
      'orderdiscountprice',
      DB::raw("CASE WHEN orders.orderstatus = 'PAID' THEN 'Lunas' WHEN orders.orderstatus = 'VOIDED' THEN 'Dibatalkan' ELSE 'Diproses' END as orderstatuscase"),
      'username',
      )->orderBy('orderdate', 'asc')->getQuery();
  }

  /**
   * @return string
   */
  public function title(): string
  {
    return 'Pesanan';
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
        $event->sheet->getStyle("A1:G1")->applyFromArray($styleTitulos);
        $event->sheet->setCellValue('A'. ($event->sheet->getHighestRow()+1),"");
        $tyes = ReportRepository::get($this->input);
        foreach ($tyes as $item){
          if(isset($item->total)){
            $event->sheet->appendRows(array(
              array('Total', $item->total),
            ), $event);
          }
        }
      }
    ];
  }
}