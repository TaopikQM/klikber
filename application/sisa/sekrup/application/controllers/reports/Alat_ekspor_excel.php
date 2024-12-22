<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class Alat_ekspor_excel extends CI_Controller {

    public function __construct() {
        parent::__construct();
		
		$this->load->model('pinjam/Malat');
		
		//date_default_timezone_set("Asia/Jakarta");

	}

    public function exalat() {
        $datt = $this->Malat->get_data_alat()->result();


        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set title
        $sheet->mergeCells('A1:D2');
        $sheet->setCellValue('A1', 'REKAP DAFTAR ALAT DINAS KOMINFO');

        // Set headers
        $sheet->setCellValue('A3', 'No');
        $sheet->setCellValue('B3', 'Nama Barang');
        $sheet->setCellValue('C3', 'Status');
        $sheet->setCellValue('D3', 'Keterangan');
        
       
        // Mengatur formatting header
        $sheet->getStyle('A1:D3')->getFont()->setBold(true);
        $sheet->getStyle('A1:D3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A1:D3')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

        // Set sheet title
        $sheet->setTitle('REKAP DAFTAR ALAT');

        $counter2 = 4;  // Starting row for data
        $counter1 = 0;  // Counter for numbering rows

        // Fill data
        foreach ($datt as $row) {
            $counter1++;
             
            $sheet->setCellValue('A' . $counter2, $counter1);
            $sheet->setCellValue("B$counter2", $row->nmbarang ?? '');
            $sheet->setCellValue('C' . $counter2, $this->getStatus($row->status ?? ''));
            $sheet->setCellValue("D$counter2", $row->ket ?? '');
           

            $counter2++;

        }

         // Mengatur border mengikuti banyaknya data
         $lastRow = $counter2- 1; // Baris terakhir yang berisi data
         $sheet->getStyle('A1:D' . $lastRow)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

        // Auto-size columns
        foreach(range('A1','D') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        // Mengatur alignment untuk data
        $sheet->getStyle('A4:A' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('B4:B' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
        $sheet->getStyle('C4:C' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('D4:D' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
         
        // Save the file
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="Rekap_Daftar_Alat_Dinas_Kominfo.xlsx"');
        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        }

        private function getStatus($status) {
        $statuses = [
            '0' => 'Bisa Dipinjam',
            '1' => 'Perawatan',
            '2' => 'Penggunaan Khusus'
        ];
        return $statuses[$status] ?? 'Unknown';
        }

}
?>