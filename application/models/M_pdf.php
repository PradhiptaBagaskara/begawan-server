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

		$pdf = new FPDF('p','mm','A4');
        // membuat halaman baru
        $pdf->AddPage();
        $pdf->AliasNbPages();
        // setting jenis font yang akan digunakan
        // mencetak string 
          // Line break
    	$proyek = $this->api2->get("proyek", ['id' => $id]);
        $pdf->SetFooterInfo("Laporan Transaksi - ".$proyek->nama_proyek);

        $pdf->SetFont('Arial','',6);
        $pdf->Cell(20,0,'Nama Pekerjaan ',0);
        $pdf->Cell(3,0,":",0);
        $pdf->Cell(0,0,$proyek->nama_proyek,0);
        $pdf->Ln(4);

        $pdf->Cell(20,0,'Tanggal Mulai',0,'c');
        $pdf->Cell(3,0,":",0,'c');      
        $pdf->Cell(0,0,$proyek->tgl_mulai,0,'c');

        $pdf->Ln(4);
        $pdf->Cell(20,0,'Tanggal Selesai ',0,'c');
        $pdf->Cell(3,0,":",0,'c');
        $pdf->Cell(0,0,$proyek->tgl_selesai,0,'c');

        $pdf->Ln(4);
        $pdf->Cell(20,0,'Nilai Pekerjaan ',0,'c');
        $pdf->Cell(3,0,":",0,'c');
        $pdf->Cell(0,0,$this->api->rupiah($proyek->modal),0,'c');

        $pdf->Ln(4);
        $pdf->Cell(20,0,'Nama Laporan ',0,'c');
        $pdf->Cell(3,0,":",0,'c');
        $pdf->Cell(0,0,"Laporan Transaksi Pekerjaan",0,'c');

        
        $pdf->Ln(6);

        $pdf->SetFont('Arial','B','6');
        $pdf->Cell(5,5,"NO",1,0,'C');
        $pdf->Cell(25,5,"NAMA KARYAWAN",1,0,'C');
        // $pdf->Cell(15,5,"KREDIT",1,0,'C');
        $pdf->Cell(20,5,"TANGGAL",1,0,'C');
        $pdf->Cell(60,5,"NAMA TRANSAKSI",1,0,'C');
        $pdf->Cell(30,5,"DANA",1,0,'C');

        // $set = "semen 20 rit 3000000, \nbata ringan1 rit 5000000, \nsemen 20 rit 3000000, \nbata ringan1 rit 5000000";
        // $width = 90;
        // $heigh =ceil(($pdf->GetStringWidth($set) / $width)) * 10;
        $this->db->select('user.nama, user.role, user.saldo, transaksi.dana, transaksi.created_date, transaksi.jenis, transaksi.nama_transaksi, transaksi.keterangan, transaksi.id, proyek.nama_proyek');
        $this->db->from('transaksi');
        $this->db->join('user', 'transaksi.id_user = user.id', 'left');
        $this->db->join('proyek', 'transaksi.id_proyek = proyek.id', 'left');
        $this->db->where('proyek.id', $id);
        $tx = $this->db->get()->result();
        $pdf->SetFont('Arial','','6');

        $no = 1;
        if ($tx == null) {
        	$pdf->Ln();
	        $pdf->SetFont('Arial','','10');
	        $pdf->Cell(5,5,$no++,1,0,'C');
	        $pdf->Cell(25,5,"Kosong",1,0,'C');
	        // $pdf->Cell(15,5,"Kosong",1,0,'C');
	        $pdf->Cell(20,5,"Kosong",1,0,'C');
	        $pdf->Cell(60,5,"Kosong",1,0,'C');
	        $pdf->Cell(30,5,"Kosong",1,0,'C');
        }else{
        setlocale (LC_TIME, 'id_ID');

        foreach ($tx as $key) {
        	$text = $key->nama_transaksi;
        	$width = 60;
        	$heigh =5;
            setlocale (LC_TIME, 'id_ID');
			$tgl = strftime("%e %B %Y",strtotime(($key->created_date)));


	        $pdf->Ln();
	        $pdf->Cell(5,$heigh,$no++,1,0,'C');
	        $pdf->Cell(25,$heigh,strtoupper($key->nama),1,0);
	        // $pdf->Cell(15,$heigh,$key->jenis,1,0,'C');
	        $pdf->Cell(20,$heigh,$tgl,1,0,'C');
	        $pdf->Cell($width, 5, $text,1,0);
	        $pdf->Cell(30,$heigh,$this->api->rupiah(($key->dana)),1,0,'c');

        }
    }
        $this->db->select('sum(dana) as dana');
        $this->db->from('transaksi');
        $total = $this->db->get()->row("dana");
        $pdf->Ln();
        $pdf->SetFont('Arial','B','6');
	    $pdf->Cell(110, 5, "TOTAL",1,0,'C');
	    $pdf->Cell(30, 5, $this->api->rupiah($total),1,0);
        $pdf->Ln();


	    $pdf->Cell(110, 5, "LABA",1,0,'C');
	    $pdf->Cell(30, 5, $this->api->rupiah($proyek->modal-$total),1,0);

############
#     HISTORI DANA
###########
        $pdf->AddPage();
        $pdf->SetFooterInfo("Laporan Histori Dana - ".$proyek->nama_proyek);
        $pdf->SetFont('Arial','','6');

        $pdf->Cell(20,0,'Nama Pekerjaan ',0,'c');
        $pdf->Cell(3,0,":",0,'c');
        $pdf->Cell(0,0,$proyek->nama_proyek,0,'c');
        $pdf->Ln(4); 

        $pdf->Cell(20,0,'Nama Laporan ',0,'c');
        $pdf->Cell(3,0,":",0,'c');
        $pdf->Cell(0,0,"Histori Pekerjaan",0,'c');
        $pdf->Ln(6);   


        $pdf->SetFont('Arial','B','6');
        $pdf->Cell(5,5,"NO",1,0,'C');
        $pdf->Cell(25,5,"NAMA PENGIRIM",1,0,'C');
        $pdf->Cell(20,5,"JENIS",1,0,'C');
        $pdf->Cell(20,5,"TANGGAL",1,0,'C');
        $pdf->Cell(30,5,"SALDO AWAL",1,0,'C');
        $pdf->Cell(30,5,"TRANSAKSI",1,0,'C');

        $pdf->Cell(30,5,"SALDO AKHIR",1,0,'C');
        $no=1;

        $this->db->select('user.nama, user.role , user.saldo, khas_proyek.*, proyek.nama_proyek');

        $this->db->from('khas_proyek');
        $this->db->join('user', 'khas_proyek.id_pemodal = user.id', 'left');
        $this->db->join('proyek', 'khas_proyek.id_proyek = proyek.id', 'left');
        $this->db->where('proyek.id', $id);
        $his = $this->db->get()->result();
        $pdf->SetFont('Arial','','6');
        setlocale (LC_TIME, 'id_ID');

        if ($his == null) {
             $pdf->Cell(5,5,"0",1,0,'C');
            $pdf->Cell(25,5,"kosong",1,0,'C');
            $pdf->Cell(20,5,"kosong",1,0,'C');
            $pdf->Cell(20,5,"kosong",1,0,'C');
            $pdf->Cell(30,5,"kosong",1,0,'C');
            $pdf->Cell(30,5,"kosong",1,0,'C');
            $pdf->Cell(30,5,"kosong",1,0,'C');
        }

        foreach ($his as $ke) {
            # code...
            $pdf->Ln();

        setlocale (LC_TIME, 'id_ID');
        $tagl = strftime("%e %B %Y",strtotime(($ke->created_date)));
        $pdf->Cell(5,5,$no++,1,0,'C');
        $pdf->Cell(25,5,$ke->nama,1,0,'C');
        $pdf->Cell(20,5,$ke->jenis,1,0,'C');
        $pdf->Cell(20,5,$tagl,1,0,'C');
        $pdf->Cell(30,5,$this->api->rupiah($ke->saldo_awal),1,0,'C');
        $pdf->Cell(30,5,$this->api->rupiah($ke->saldo_masuk),1,0,'C');
        $pdf->Cell(30,5,$this->api->rupiah($ke->saldo_akhir),1,0,'C');

        }

	    $fname =  "laporan - ".$proyek->nama_proyek." - ".strftime('%d%m%Y', time()).".pdf";
	    $fileloc = "./uploads/laporan/".$fname;
		// $pdf->Output('F',$fileloc);
		$pdf->Output();
		return $fname;



		
		        
	}
	public function laporan_user($id_user)
	{

		$id = $id_user;

		$pdf = new FPDF('p','mm','A4');
        setlocale (LC_TIME, 'id_ID');

        // membuat halaman baru
        $pdf->AddPage();
        $pdf->AliasNbPages();
        // setting jenis font yang akan digunakan

    	$user = $this->api2->get("user", ['id' => $id]);
    	$proyek = $this->api2->get("proyek", ['id' => "1"]);
        $pdf->SetFooterInfo("Laporan Pembelian - ".$user->nama);

        $pdf->SetFont('Arial','',6);
        $pdf->Cell(20,0,'Nama user ',0,'c');
        $pdf->Cell(3,0,":",0,'c');
        $pdf->Cell(0,0,strtoupper($user->nama),0,'c');
  //       $pdf->Ln(4);

  //       $pdf->Cell(20,0,'Tanggal laporan',0,'c');
  //       setlocale (LC_TIME, 'id_ID');
		// $date = strftime("%A, %e %B %Y",time());
  //       $pdf->Cell(3,0,":",0,'c');      
  //       $pdf->Cell(0,0,strftime($date),0,'c');

        $pdf->Ln(4);
        $pdf->Cell(20,0,'Saldo akhir ',0,'c');
        $pdf->Cell(3,0,":",0,'c');
        $pdf->Cell(0,0,$this->api->rupiah($user->saldo),0,'c');

        $pdf->Ln(4);
        $pdf->Cell(20,0,'Laporan ',0,'c');
        $pdf->Cell(3,0,":",0,'c');
        $pdf->Cell(0,0,"Transaksi Pembelian",0,'c');
        
        
        $pdf->Ln(6);

        $pdf->SetFont('Arial','B','6');
        $pdf->Cell(5,5,"NO",1,0,'C');
        $pdf->Cell(50,5,"NAMA TRANSAKSI",1,0,'C');
        $pdf->Cell(40,5,"NAMA PEKERJAAN",1,0,'C');
        $pdf->Cell(30,5,"TANGGAL",1,0,'C');
        $pdf->Cell(30,5,"DANA",1,0,'C');

        // $set = "semen 20 rit 3000000, \nbata ringan1 rit 5000000, \nsemen 20 rit 3000000, \nbata ringan1 rit 5000000";
        // $width = 90;
        // $heigh =ceil(($pdf->GetStringWidth($set) / $width)) * 5;
        $this->db->select('user.nama, user.role role, user.saldo, transaksi.dana, transaksi.created_date, transaksi.jenis, transaksi.nama_transaksi, transaksi.keterangan, transaksi.id, proyek.nama_proyek');
        $this->db->from('transaksi');
        $this->db->join('user', 'transaksi.id_user = user.id', 'left');
        $this->db->join('proyek', 'transaksi.id_proyek = proyek.id', 'left');
        $this->db->where('user.id', $id);
        $no = 1;

        $tx = $this->db->get()->result();
        if ($tx == null) {
        	$pdf->Ln();
	        $pdf->SetFont('Arial','','6');
	        $pdf->Cell(5,5,$no++,1,0,'C');
	        $pdf->Cell(50,5,"Kosong",1,0,'C');
	        $pdf->Cell(40,5,"Kosong",1,0,'C');
	        $pdf->Cell(30,5,"Kosong",1,0,'C');
	        $pdf->Cell(30,5,"Kosong",1,0,'C');
        }else{
            setlocale (LC_TIME, 'id_ID');

	        foreach ($tx as $key) {
	        	$text = $key->nama_transaksi;
	        	$width = 50;
	        	$heigh =5;
                setlocale (LC_TIME, 'id_ID');

				$tgl = strftime("%e %B %Y",strtotime(($key->created_date)));


		        $pdf->Ln();
		        $pdf->SetFont('Arial','','5');
		        $pdf->Cell(5,$heigh,$no++,1,0,'C');
		        $pdf->Cell(50,$heigh,$key->nama_transaksi,1,0,'c');
		        $pdf->Cell(40,$heigh,$key->nama_proyek,1,0,'c');

		        $pdf->Cell(30,$heigh,$tgl,1,0,'C');
		        $pdf->Cell(30,$heigh,$this->api->rupiah(($key->dana)),1,0,'c');

	        }
        }

        $this->db->select('sum(dana) as dana');
        $this->db->from('transaksi');
        $this->db->where('id_user', $id);
        $total = $this->db->get()->row("dana");
        $pdf->Ln();
        $pdf->SetFont('Arial','B','6');
	    $pdf->Cell(125, 5, "TOTAL",1,0,'C');
	    $pdf->Cell(30, 5, $this->api->rupiah($total),1,0);
        // END LAPORAN PEMBELIAN
##################################################################        
#	    				START KHAS LAPORAN                       #
##################################################################

	    $pdf->AddPage();
	    $pdf->AcceptPageBreak();
        $pdf->SetFooterInfo("Laporan Histori Khas - ".$user->nama);


        // setting jenis font yang akan digunakan
        // mencetak string 


        $pdf->SetFont('Arial','',6);
        $pdf->Cell(20,0,'Nama user ',0,'c');
        $pdf->Cell(3,0,":",0,'c');
        $pdf->Cell(0,0,strtoupper($user->nama),0,'c');
        $pdf->Ln(4);

  //       $pdf->Cell(20,0,'Tanggal laporan',0,'c');
		// $date = strftime("%e %B %Y",time());
  //       $pdf->Cell(3,0,":",0,'c');      
  //       $pdf->Cell(0,0,strftime($date),0,'c');

        // $pdf->Ln(4);
        $pdf->Cell(20,0,'Saldo akhir ',0,'c');
        $pdf->Cell(3,0,":",0,'c');
        $pdf->Cell(0,0,$this->api->rupiah($user->saldo),0,'c');

        $pdf->Ln(4);
        $pdf->Cell(20,0,'Laporan',0,'c');
        $pdf->Cell(3,0,":",0,'c');
        $pdf->Cell(0,0,"Histori Khas",0,'c');
        
        $pdf->Ln(6);

        $pdf->SetFont('Arial','B','6');
        $pdf->Cell(5,5,"NO",1,0,'C');
        $pdf->Cell(40,5,"NAMA PENGIRIM",1,0,'C');
        $pdf->Cell(30,5,"TANGGAL",1,0,'C');
        $pdf->Cell(20,5,"JENIS",1,0,'C');
        $pdf->Cell(30,5,"SALDO AWAL",1,0,'C');
        $pdf->Cell(30,5,"SALDO MASUK",1,0,'C');
        $pdf->Cell(30,5,"SALDO AKHIR",1,0,'C');

        $this->db->select('id_pemodal');
		$this->db->where('id_user', $id);
		$p =$this->db->get("khas_history")->row('id_pemodal');
		$this->db->query("SET lc_time_names = 'id_ID'");
		$this->db->select("khas_history.id, 
			khas_history.saldo_awal,
			khas_history.saldo_total,
			khas_history.saldo_masuk,
            khas_history.jenis ,
			khas_history.created_date as created_date, 
			user.username, 
			user.nama, 
			if(khas_history.id_pemodal = user.id, (select nama from user where id='".$p."'),(select nama from user where id='".$p."')) as nama_pengirim");
		$this->db->from('khas_history');
		$this->db->join('user', 'khas_history.id_user = user.id', 'left');
		$this->db->where('khas_history.id_user', $id);
        $histori = $this->db->get()->result();
        $pdf->SetFont('Arial','','5');

       	if ($histori == null) {
        	$pdf->Ln();
	        $pdf->Cell(5,5,$no++,1,0,'C');
	        $pdf->Cell(40,5,"Kosong",1,0,'C');
            $pdf->Cell(30,5,"Kosong",1,0,'C');
	        $pdf->Cell(20,5,"Kosong",1,0,'C');
	        $pdf->Cell(30,5,"Kosong",1,0,'C');
	        $pdf->Cell(30,5,"Kosong",1,0,'C');
	        $pdf->Cell(30,5,"Kosong",1,0,'C');
        }else{
        $no = 1;
        foreach ($histori as $his ) {
        $pdf->Ln();
        $pdf->Cell(5,5,$no++,1,0,'C');
        $pdf->Cell(40,5,$his->nama_pengirim,1,0,'C');
        $pdf->Cell(30,5,strftime('%e %B %Y', strtotime($his->created_date)),1,0,'C');        
        $pdf->Cell(20,5,$his->jenis,1,0,'C');
        $pdf->Cell(30,5,$this->api->rupiah( $his->saldo_awal ),1,0);
        $pdf->Cell(30,5,$this->api->rupiah( $his->saldo_masuk ),1,0);
        $pdf->Cell(30,5,$this->api->rupiah( $his->saldo_total ),1,0);
        }
    }

	    $fname =  "laporan - ".$user->nama." - ".strftime('%d%m%Y', time()).".pdf";
	    $fileloc = "./uploads/laporan/".$fname;
	    
		// $pdf->Output('F',$fileloc);
		$pdf->Output();
		// return $fname;




        
	}
	
		

}

/* End of file M_pdf.php */
/* Location: ./application/models/M_pdf.php */




 ?>