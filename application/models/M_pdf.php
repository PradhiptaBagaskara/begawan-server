<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class M_pdf extends CI_Model {

	public function __construct()
	{
		parent::__construct();
		//Do your magic here
		$this->load->model('M_api','api');
		$this->load->library('pdf');
	}
	function laporan_proyek($idProyek)
	{

		$id = $idProyek;

		$pdf = new FPDF('l','mm','A4');
        // membuat halaman baru
        $pdf->AddPage();
        $pdf->AliasNbPages();
        // setting jenis font yang akan digunakan
        $pdf->SetFont('Arial','B',14);
        // mencetak string 
          // Line break
    	$proyek = $this->api2->get("proyek", ['id' => $id]);
        $pdf->SetFooterInfo("Laporan Transaksi - ".$proyek->nama_proyek);

        $pdf->SetFont('Arial','',12);
        $pdf->Cell(40,6,'Nama Pekerjaan ',0,'c');
        $pdf->Cell(3,6,":",0,'c');
        $pdf->Cell(0,6,$proyek->nama_proyek,0,'c');
        $pdf->Ln();

        $pdf->Cell(40,6,'Tanggal dibuat',0,'c');
        setlocale (LC_TIME, 'id_ID');
		$date = strftime("%A, %e %B %Y",strtotime(($proyek->created_date)));
        $pdf->Cell(3,6,":",0,'c');      
        $pdf->Cell(0,6,strftime($date),0,'c');

        $pdf->Ln();
        $pdf->Cell(40,6,'Nilai Pekerjaan ',0,'c');
        $pdf->Cell(3,6,":",0,'c');
        $pdf->Cell(0,6,$this->api->rupiah($proyek->modal),0,'c');
        
        $pdf->Ln(15);

        $pdf->SetFont('Arial','B','12');
        $pdf->Cell(10,10,"NO",1,0,'C');
        $pdf->Cell(50,10,"NAMA KARYAWAN",1,0,'C');
        $pdf->Cell(25,10,"KREDIT",1,0,'C');
        $pdf->Cell(40,10,"TANGGAL",1,0,'C');
        $pdf->Cell(100,10,"NAMA TRANSAKSI",1,0,'C');
        $pdf->Cell(40,10,"DANA",1,0,'C');

        // $set = "semen 20 rit 3000000, \nbata ringan1 rit 5000000, \nsemen 20 rit 3000000, \nbata ringan1 rit 5000000";
        // $width = 90;
        // $heigh =ceil(($pdf->GetStringWidth($set) / $width)) * 10;
        $this->db->select('user.nama, user.role role, user.saldo, transaksi.dana, transaksi.created_date, transaksi.jenis, transaksi.nama_transaksi, transaksi.keterangan, transaksi.id, proyek.nama_proyek');
        $this->db->from('transaksi');
        $this->db->join('user', 'transaksi.id_user = user.id', 'left');
        $this->db->join('proyek', 'transaksi.id_proyek = proyek.id', 'left');
        $this->db->where('proyek.id', $id);
        $tx = $this->db->get()->result();
        $no = 1;
            if ($tx == null) {
        	$pdf->Ln();
	        $pdf->SetFont('Arial','','10');
	        $pdf->Cell(10,10,$no++,1,0,'C');
	        $pdf->Cell(50,10,"Kosong",1,0,'C');
	        $pdf->Cell(25,10,"Kosong",1,0,'C');
	        $pdf->Cell(40,10,"Kosong",1,0,'C');
	        $pdf->Cell(100,10,"Kosong",1,0,'C');
	        $pdf->Cell(40,10,"Kosong",1,0,'C');
        }else{
        foreach ($tx as $key) {
        	$text = $key->nama_transaksi;
        	$width = 100;
        	$heigh =10;
			$tgl = strftime("%e %B %Y",strtotime(($key->created_date)));


	        $pdf->Ln();
	        $pdf->SetFont('Arial','','10');
	        $pdf->Cell(10,$heigh,$no++,1,0,'C');
	        $pdf->Cell(50,$heigh,strtoupper($key->nama),1,0,'c');
	        $pdf->Cell(25,$heigh,$key->jenis,1,0,'C');
	        $pdf->Cell(40,$heigh,$tgl,1,0,'C');
	        $pdf->Cell($width, 10, $text,1,0);
	        $pdf->Cell(40,$heigh,$this->api->rupiah(($key->dana)),1,0,'c');

        }
    }
        $this->db->select('sum(dana) as dana');
        $this->db->from('transaksi');
        $total = $this->db->get()->row("dana");
        $pdf->Ln();
        $pdf->SetFont('Arial','B','11');
	    $pdf->Cell(225, 10, "TOTAL",1,0,'C');
	    $pdf->Cell(40, 10, $this->api->rupiah($total),1,0);
        $pdf->Ln();


	    $pdf->Cell(225, 10, "SELISIH (nilai - total)",1,0,'C');
	    $pdf->Cell(40, 10, $this->api->rupiah($proyek->modal-$total),1,0);


	    $fname =  "laporan - ".$proyek->nama_proyek." - ".strftime('%d%m%Y', time()).".pdf";
	    $fileloc = "./uploads/pdf/".$fname;
		// $pdf->Output('F',$fileloc);
		$pdf->Output();
		// return $fname;



		
		        
	}
	public function laporan_user($id_user)
	{

		$id = $id_user;

		$pdf = new FPDF('l','mm','A4');
        setlocale (LC_TIME, 'id_ID');

        // membuat halaman baru
        $pdf->AddPage();
        $pdf->AliasNbPages();
        // setting jenis font yang akan digunakan

    	$user = $this->api2->get("user", ['id' => $id]);
    	$proyek = $this->api2->get("proyek", ['id' => "1"]);
        $pdf->SetFooterInfo("Laporan Pembelian - ".$user->nama);

        $pdf->SetFont('Arial','',12);
        $pdf->Cell(40,6,'Nama user ',0,'c');
        $pdf->Cell(3,6,":",0,'c');
        $pdf->Cell(0,6,strtoupper($user->nama),0,'c');
        $pdf->Ln();

        $pdf->Cell(40,6,'Tanggal dibuat',0,'c');
        setlocale (LC_TIME, 'id_ID');
		$date = strftime("%A, %e %B %Y",time());
        $pdf->Cell(3,6,":",0,'c');      
        $pdf->Cell(0,6,strftime($date),0,'c');

        $pdf->Ln();
        $pdf->Cell(40,6,'Saldo akhir ',0,'c');
        $pdf->Cell(3,6,":",0,'c');
        $pdf->Cell(0,6,$this->api->rupiah($user->saldo),0,'c');
        $pdf->Ln();
        $pdf->Cell(40,6,'Laporan ',0,'c');
        $pdf->Cell(3,6,":",0,'c');
        $pdf->Cell(0,6,"Pembelian",0,'c');
        
        
        $pdf->Ln(15);

        $pdf->SetFont('Arial','B','12');
        $pdf->Cell(10,10,"NO",1,0,'C');
        $pdf->Cell(80,10,"NAMA TRANSAKSI",1,0,'C');
        $pdf->Cell(80,10,"NAMA PEKERJAAN",1,0,'C');
        $pdf->Cell(20,10,"KREDIT",1,0,'C');
        $pdf->Cell(32,10,"TANGGAL",1,0,'C');
        $pdf->Cell(40,10,"DANA",1,0,'C');

        // $set = "semen 20 rit 3000000, \nbata ringan1 rit 5000000, \nsemen 20 rit 3000000, \nbata ringan1 rit 5000000";
        // $width = 90;
        // $heigh =ceil(($pdf->GetStringWidth($set) / $width)) * 10;
        $this->db->select('user.nama, user.role role, user.saldo, transaksi.dana, transaksi.created_date, transaksi.jenis, transaksi.nama_transaksi, transaksi.keterangan, transaksi.id, proyek.nama_proyek');
        $this->db->from('transaksi');
        $this->db->join('user', 'transaksi.id_user = user.id', 'left');
        $this->db->join('proyek', 'transaksi.id_proyek = proyek.id', 'left');
        $this->db->where('user.id', $id);
        $no = 1;

        $tx = $this->db->get()->result();
        if ($tx == null) {
        	$pdf->Ln();
	        $pdf->SetFont('Arial','','10');
	        $pdf->Cell(10,10,$no++,1,0,'C');
	        $pdf->Cell(80,10,"Kosong",1,0,'C');
	        $pdf->Cell(80,10,"Kosong",1,0,'C');
	        $pdf->Cell(20,10,"Kosong",1,0,'C');
	        $pdf->Cell(32,10,"Kosong",1,0,'C');
	        $pdf->Cell(40,10,"Kosong",1,0,'C');
        }else{

	        foreach ($tx as $key) {
	        	$text = $key->nama_transaksi;
	        	$width = 100;
	        	$heigh =10;
				$tgl = strftime("%e %B %Y",strtotime(($key->created_date)));


		        $pdf->Ln();
		        $pdf->SetFont('Arial','','10');
		        $pdf->Cell(10,$heigh,$no++,1,0,'C');
		        $pdf->Cell(80,$heigh,$key->nama_transaksi,1,0,'c');
		        $pdf->Cell(80,$heigh,$key->nama_proyek,1,0,'c');

		        $pdf->Cell(20,$heigh,$key->jenis,1,0,'C');
		        $pdf->Cell(32,$heigh,$tgl,1,0,'C');
		        $pdf->Cell(40,$heigh,$this->api->rupiah(($key->dana)),1,0,'c');

	        }
        }

        $this->db->select('sum(dana) as dana');
        $this->db->from('transaksi');
        $this->db->where('id_user', $id);
        $total = $this->db->get()->row("dana");
        $pdf->Ln();
        $pdf->SetFont('Arial','B','11');
	    $pdf->Cell(222, 10, "TOTAL",1,0,'C');
	    $pdf->Cell(40, 10, $this->api->rupiah($total),1,0);
        // END LAPORAN PEMBELIAN
##################################################################        
#	    				START KHAS LAPORAN                       #
##################################################################

	    $pdf->AddPage();
	    $pdf->AcceptPageBreak();
        $pdf->SetFooterInfo("Laporan Histori Khas - ".$user->nama);


        // setting jenis font yang akan digunakan
        $pdf->SetFont('Arial','B',14);
        // mencetak string 


        $pdf->SetFont('Arial','',12);
        $pdf->Cell(40,6,'Nama user ',0,'c');
        $pdf->Cell(3,6,":",0,'c');
        $pdf->Cell(0,6,strtoupper($user->nama),0,'c');
        $pdf->Ln();

        $pdf->Cell(40,6,'Tanggal dibuat',0,'c');
		$date = strftime("%e %B %Y",time());
        $pdf->Cell(3,6,":",0,'c');      
        $pdf->Cell(0,6,strftime($date),0,'c');

        $pdf->Ln();
        $pdf->Cell(40,6,'Saldo akhir ',0,'c');
        $pdf->Cell(3,6,":",0,'c');
        $pdf->Cell(0,6,$this->api->rupiah($user->saldo),0,'c');

        $pdf->Ln();
        $pdf->Cell(40,6,'Laporan',0,'c');
        $pdf->Cell(3,6,":",0,'c');
        $pdf->Cell(0,6,"Histori Khas",0,'c');
        
        $pdf->Ln(15);

        $pdf->SetFont('Arial','B','12');
        $pdf->Cell(10,10,"NO",1,0,'C');
        $pdf->Cell(80,10,"NAMA PENGIRIM",1,0,'C');
        $pdf->Cell(40,10,"TANGGAL",1,0,'C');
        $pdf->Cell(40,10,"SALDO AWAL",1,0,'C');
        $pdf->Cell(40,10,"SALDO MASUK",1,0,'C');
        $pdf->Cell(40,10,"SALDO AKHIR",1,0,'C');

        $this->db->select('id_pemodal');
		$this->db->where('id_user', $id);
		$p =$this->db->get("khas_history")->row('id_pemodal');
		$this->db->query("SET lc_time_names = 'id_ID'");
		$this->db->select("khas_history.id, 
			khas_history.saldo_awal,
			khas_history.saldo_total,
			khas_history.saldo_masuk, 
			khas_history.created_date as created_date, 
			user.username, 
			user.nama, 
			if(khas_history.id_pemodal = user.id, (select nama from user where id='".$p."'),(select nama from user where id='".$p."')) as nama_pengirim");
		$this->db->from('khas_history');
		$this->db->join('user', 'khas_history.id_user = user.id', 'left');
		$this->db->where('khas_history.id_user', $id);
        $histori = $this->db->get()->result();
        $pdf->SetFont('Arial','','10');

       	if ($histori == null) {
        	$pdf->Ln();
	        $pdf->SetFont('Arial','','10');
	        $pdf->Cell(10,10,$no++,1,0,'C');
	        $pdf->Cell(80,10,"Kosong",1,0,'C');
	        $pdf->Cell(40,10,"Kosong",1,0,'C');
	        $pdf->Cell(40,10,"Kosong",1,0,'C');
	        $pdf->Cell(40,10,"Kosong",1,0,'C');
	        $pdf->Cell(40,10,"Kosong",1,0,'C');
        }else{
        $no = 1;
        foreach ($histori as $his ) {
        $pdf->Ln();
        $pdf->Cell(10,10,$no++,1,0,'C');
        $pdf->Cell(80,10,$his->nama_pengirim,1,0,'C');
        $pdf->Cell(40,10,strftime('%e %B %Y', strtotime($his->created_date)),1,0,'C');        
        $pdf->Cell(40,10,$this->api->rupiah( $his->saldo_awal ),1,0,'C');
        $pdf->Cell(40,10,$this->api->rupiah( $his->saldo_masuk ),1,0,'C');
        $pdf->Cell(40,10,$this->api->rupiah( $his->saldo_total ),1,0,'C');
        }
    }

	    $fname =  "laporan - ".$user->nama." - ".strftime('%d%m%Y', time()).".pdf";
	    $fileloc = "./uploads/pdf/".$fname;
	    
		$pdf->Output('F',$fileloc);
		// $pdf->Output();
		return $fname;




        
	}
	
		

}

/* End of file M_pdf.php */
/* Location: ./application/models/M_pdf.php */




 ?>