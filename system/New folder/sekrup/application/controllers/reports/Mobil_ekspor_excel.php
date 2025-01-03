<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class Mobil_ekspor_excel extends CI_Controller {

    public function __construct() {
        parent::__construct();
		
		$this->load->model('pinjam/Mmobil');
		
		//date_default_timezone_set("Asia/Jakarta");

	}

    public function exmobil() {
        $datt = $this->Mmobil->get_data_mobil()->result();


        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set title
        $sheet->mergeCells('A1:Q2');
        $sheet->setCellValue('A1', 'REKAP DAFTAR KENDARAAN DINAS KOMINFO');

        // Set headers
        $sheet->setCellValue('A3', 'No');
        $sheet->setCellValue('B3', 'Merk');
        $sheet->setCellValue('C3', 'Type');
        $sheet->setCellValue('D3', 'Ukuran/CC');

        // Merge cells
        $sheet->mergeCells('E3:F3');
        $sheet->setCellValue('E3', 'Tahun');

            $sheet->setCellValue('E4', 'Rakit');
            $sheet->setCellValue('F4', 'Beli');
        
        // Merge cells
        $sheet->mergeCells('G3:J3');
        $sheet->setCellValue('G3', 'Nomor');

            // Isi nomor
            $sheet->setCellValue('G4', 'Rangka');
            $sheet->setCellValue('H4', 'Mesin');
            $sheet->setCellValue('I4', 'Nopol');
            $sheet->setCellValue('J4', 'BPKB');

        $sheet->setCellValue('K3', 'Asal Usul');
        $sheet->setCellValue('L3', 'Pajak');
        $sheet->setCellValue('M3', 'Status Kendaraan');
        $sheet->setCellValue('N3', 'Keperluan Kendaraan');
        $sheet->setCellValue('O3', 'Tujuan Kendaraan');
        $sheet->setCellValue('P3', 'Hak Kendaraan');
        $sheet->setCellValue('Q3', 'Keterangan');

        // Mengatur formatting header
        $sheet->getStyle('A1:Q4')->getFont()->setBold(true);
        $sheet->getStyle('A1:Q4')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A1:Q4')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

        //mengatur margecells
        $sheet->mergeCells('A3:A4');
        $sheet->mergeCells('B3:B4');
        $sheet->mergeCells('C3:C4');
        $sheet->mergeCells('D3:D4');
        //$sheet->mergeCells('E3:E4');
        //$sheet->mergeCells('J3:J4');
        $sheet->mergeCells('K3:K4');
        $sheet->mergeCells('L3:L4');
        $sheet->mergeCells('M3:M4');
        $sheet->mergeCells('N3:N4');
        $sheet->mergeCells('O3:O4');
        $sheet->mergeCells('P3:P4');
        $sheet->mergeCells('Q3:Q4');
        
        //mengatur wrap text
        //$sheet->getStyle('E3')->getAlignment()->setWrapText(true); 

        // Set sheet title
        $sheet->setTitle('REKAP DAFTAR KENDARAAN');

        $counter2 = 5;  // Starting row for data
        $counter1 = 0;  // Counter for numbering rows
        $currentMonth = date('m');
        $currentDay = date('d');

        // Fill data
        foreach ($datt as $row) {
            $counter1++;
             // Memisahkan data tanggal dan bulan dari kolom 'pjk'
             list($paymentMonth, $paymentDay) = explode('/', $row->pjk);
             $paymentMonthName = date('F', mktime(0, 0, 0, $paymentMonth, 10)); // Mengubah angka bulan menjadi nama bulan 

            $sheet->setCellValue('A' . $counter2, $counter1);
            $sheet->setCellValue("B$counter2", $row->nmerk ?? '');
            $sheet->setCellValue("C$counter2", $row->jenis ?? '');
            $sheet->setCellValue("D$counter2", $row->ukuran ?? '');
            $sheet->setCellValue("E$counter2", $row->th_rakit ?? '');
            $sheet->setCellValue("F$counter2", $row->thbeli ?? '');
                $sheet->setCellValue("G$counter2", $row->nrangka ?? '');
                $sheet->setCellValue("H$counter2", $row->nmesin ?? '');
                $sheet->setCellValue("I$counter2", $row->nopol ?? '');
                $sheet->setCellValue("J$counter2", $row->nbpkb ?? '');
            $sheet->setCellValue("K$counter2", $row->asal ?? '');
            $sheet->setCellValue("L$counter2", $paymentDay . '-' . $paymentMonthName ?? '');
            
            $sheet->setCellValue('M' . $counter2, $this->getStatus($row->status ?? ''));
            $sheet->setCellValue('N' . $counter2, $this->getKeperluan($row->r_perlu ?? ''));
            $sheet->setCellValue('O' . $counter2, $this->getTujuan($row->r_tujuan ?? ''));
            $sheet->setCellValue('P' . $counter2, $this->getHak($row->hak ?? ''));
            $sheet->setCellValue('Q' . $counter2, $row->ket ?? '');
           
           // Pengecekan apakah tanggal dan bulan pajak sama dengan hari ini
           if ($paymentDay == $currentDay && $paymentMonth == $currentMonth) {
            // Tanggal dan bulan pajak sama dengan hari ini, warna merah
                $sheet->getStyle('A5' . $counter1 . ':Q' . $counter1)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFFF0000');
            } elseif ($paymentMonth == $currentMonth) {
                // Hanya bulan pajak sama dengan bulan ini, warna kuning
                $sheet->getStyle('A5' . $counter1 . ':Q' . $counter1)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFFFE699');
            } else {
                // Tanggal dan bulan pajak tidak sama dengan hari ini, warna putih
                $sheet->getStyle('A5' . $counter1 . ':Q' . $counter1)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFF');
            }

            $counter2++;

        }

         // Mengatur border mengikuti banyaknya data
         $lastRow = $counter2- 1; // Baris terakhir yang berisi data
         $sheet->getStyle('A1:Q' . $lastRow)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

        // Auto-size columns
        foreach(range('A1','Q') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        // Mengatur alignment untuk data
        $sheet->getStyle('A5:A' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('B5:C' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
        $sheet->getStyle('D5:D' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('E5:F' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('G5:H' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
        $sheet->getStyle('I5:I' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('J5:Q' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
         
        // Save the file
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="Rekap_Daftar_Kendaraan_Dinas_Kominfo.xlsx"');
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

        private function getKeperluan($keperluan) {
        $keperluanArray = [
            '1' => 'Dinas Luar Biasa',
            '2' => 'Fasilitasi Kunjungan Tamu',
            '3' => 'Rangkaian Kegiatan Pimpinan Daerah',
            '4' => 'Kegiatan Lainnya'
        ];
        $keys = explode("-", trim($keperluan, "-"));
        return implode(", ", array_map(fn($key) => $keperluanArray[$key] ?? 'Unknown', $keys));
        }

        private function getTujuan($tujuan) {
        $tujuanArray = [
            '1' => 'Dalam Kota',
            '2' => 'Dalam Daerah',
            '3' => 'Luar Daerah'
        ];
        $keys = explode("-", trim($tujuan, "-"));
        return implode(", ", array_map(fn($key) => $tujuanArray[$key] ?? 'Unknown', $keys));
        }

        private function getHak($hak) {
        $hakArray = [
            '0' => 'Kepala Dinas',
            '1' => 'Sekretaris',
            '2' => 'Kabid TIK',
            '3' => 'Kabid PDKI',
            '4' => 'Kabid IKP',
            '5' => 'Kabid e-Gov',
            '6' => 'Kabid Statistik',
            '7' => 'POOL Kantor',
            '8' => 'Sekretariat',
            '9' => 'TIK',
            '10' => 'PDKI',
            '11' => 'IKP',
            '12' => 'e-Gov',
            '13' => 'Statistik',
            '14' => 'Komisi Informasi'
        ];
        return $hakArray[$hak] ?? 'Unknown';
        }
}
?>