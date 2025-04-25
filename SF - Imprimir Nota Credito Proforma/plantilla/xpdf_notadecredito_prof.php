<?php
include('fpdf/fpdf.php');
require('../includes/arch_cfg.php');
require('../includes/dbmssql_cfg.php');

class PDF_Code128 extends FPDF
{
	protected $T128;                                         // Tableau des codes 128
	protected $ABCset = "";                                  // jeu des caract�res �ligibles au C128
	protected $Aset = "";                                    // Set A du jeu des caract�res �ligibles
	protected $Bset = "";                                    // Set B du jeu des caract�res �ligibles
	protected $Cset = "";                                    // Set C du jeu des caract�res �ligibles
	protected $SetFrom;                                      // Convertisseur source des jeux vers le tableau
	protected $SetTo;                                        // Convertisseur destination des jeux vers le tableau
	protected $JStart = array("A" => 103, "B" => 104, "C" => 105); // Caract�res de s�lection de jeu au d�but du C128
	protected $JSwap = array("A" => 101, "B" => 100, "C" => 99);   // Caract�res de changement de jeu

	//____________________________ Extension du constructeur _______________________
	function __construct($orientation = 'P', $unit = 'mm', $format = 'A4')
	{

		parent::__construct($orientation, $unit, $format);

		$this->T128[] = array(2, 1, 2, 2, 2, 2);           //0 : [ ]               // composition des caract�res
		$this->T128[] = array(2, 2, 2, 1, 2, 2);           //1 : [!]
		$this->T128[] = array(2, 2, 2, 2, 2, 1);           //2 : ["]
		$this->T128[] = array(1, 2, 1, 2, 2, 3);           //3 : [#]
		$this->T128[] = array(1, 2, 1, 3, 2, 2);           //4 : [$]
		$this->T128[] = array(1, 3, 1, 2, 2, 2);           //5 : [%]
		$this->T128[] = array(1, 2, 2, 2, 1, 3);           //6 : [&]
		$this->T128[] = array(1, 2, 2, 3, 1, 2);           //7 : [']
		$this->T128[] = array(1, 3, 2, 2, 1, 2);           //8 : [(]
		$this->T128[] = array(2, 2, 1, 2, 1, 3);           //9 : [)]
		$this->T128[] = array(2, 2, 1, 3, 1, 2);           //10 : [*]
		$this->T128[] = array(2, 3, 1, 2, 1, 2);           //11 : [+]
		$this->T128[] = array(1, 1, 2, 2, 3, 2);           //12 : [,]
		$this->T128[] = array(1, 2, 2, 1, 3, 2);           //13 : [-]
		$this->T128[] = array(1, 2, 2, 2, 3, 1);           //14 : [.]
		$this->T128[] = array(1, 1, 3, 2, 2, 2);           //15 : [/]
		$this->T128[] = array(1, 2, 3, 1, 2, 2);           //16 : [0]
		$this->T128[] = array(1, 2, 3, 2, 2, 1);           //17 : [1]
		$this->T128[] = array(2, 2, 3, 2, 1, 1);           //18 : [2]
		$this->T128[] = array(2, 2, 1, 1, 3, 2);           //19 : [3]
		$this->T128[] = array(2, 2, 1, 2, 3, 1);           //20 : [4]
		$this->T128[] = array(2, 1, 3, 2, 1, 2);           //21 : [5]
		$this->T128[] = array(2, 2, 3, 1, 1, 2);           //22 : [6]
		$this->T128[] = array(3, 1, 2, 1, 3, 1);           //23 : [7]
		$this->T128[] = array(3, 1, 1, 2, 2, 2);           //24 : [8]
		$this->T128[] = array(3, 2, 1, 1, 2, 2);           //25 : [9]
		$this->T128[] = array(3, 2, 1, 2, 2, 1);           //26 : [:]
		$this->T128[] = array(3, 1, 2, 2, 1, 2);           //27 : [;]
		$this->T128[] = array(3, 2, 2, 1, 1, 2);           //28 : [<]
		$this->T128[] = array(3, 2, 2, 2, 1, 1);           //29 : [=]
		$this->T128[] = array(2, 1, 2, 1, 2, 3);           //30 : [>]
		$this->T128[] = array(2, 1, 2, 3, 2, 1);           //31 : [?]
		$this->T128[] = array(2, 3, 2, 1, 2, 1);           //32 : [@]
		$this->T128[] = array(1, 1, 1, 3, 2, 3);           //33 : [A]
		$this->T128[] = array(1, 3, 1, 1, 2, 3);           //34 : [B]
		$this->T128[] = array(1, 3, 1, 3, 2, 1);           //35 : [C]
		$this->T128[] = array(1, 1, 2, 3, 1, 3);           //36 : [D]
		$this->T128[] = array(1, 3, 2, 1, 1, 3);           //37 : [E]
		$this->T128[] = array(1, 3, 2, 3, 1, 1);           //38 : [F]
		$this->T128[] = array(2, 1, 1, 3, 1, 3);           //39 : [G]
		$this->T128[] = array(2, 3, 1, 1, 1, 3);           //40 : [H]
		$this->T128[] = array(2, 3, 1, 3, 1, 1);           //41 : [I]
		$this->T128[] = array(1, 1, 2, 1, 3, 3);           //42 : [J]
		$this->T128[] = array(1, 1, 2, 3, 3, 1);           //43 : [K]
		$this->T128[] = array(1, 3, 2, 1, 3, 1);           //44 : [L]
		$this->T128[] = array(1, 1, 3, 1, 2, 3);           //45 : [M]
		$this->T128[] = array(1, 1, 3, 3, 2, 1);           //46 : [N]
		$this->T128[] = array(1, 3, 3, 1, 2, 1);           //47 : [O]
		$this->T128[] = array(3, 1, 3, 1, 2, 1);           //48 : [P]
		$this->T128[] = array(2, 1, 1, 3, 3, 1);           //49 : [Q]
		$this->T128[] = array(2, 3, 1, 1, 3, 1);           //50 : [R]
		$this->T128[] = array(2, 1, 3, 1, 1, 3);           //51 : [S]
		$this->T128[] = array(2, 1, 3, 3, 1, 1);           //52 : [T]
		$this->T128[] = array(2, 1, 3, 1, 3, 1);           //53 : [U]
		$this->T128[] = array(3, 1, 1, 1, 2, 3);           //54 : [V]
		$this->T128[] = array(3, 1, 1, 3, 2, 1);           //55 : [W]
		$this->T128[] = array(3, 3, 1, 1, 2, 1);           //56 : [X]
		$this->T128[] = array(3, 1, 2, 1, 1, 3);           //57 : [Y]
		$this->T128[] = array(3, 1, 2, 3, 1, 1);           //58 : [Z]
		$this->T128[] = array(3, 3, 2, 1, 1, 1);           //59 : [[]
		$this->T128[] = array(3, 1, 4, 1, 1, 1);           //60 : [\]
		$this->T128[] = array(2, 2, 1, 4, 1, 1);           //61 : []]
		$this->T128[] = array(4, 3, 1, 1, 1, 1);           //62 : [^]
		$this->T128[] = array(1, 1, 1, 2, 2, 4);           //63 : [_]
		$this->T128[] = array(1, 1, 1, 4, 2, 2);           //64 : [`]
		$this->T128[] = array(1, 2, 1, 1, 2, 4);           //65 : [a]
		$this->T128[] = array(1, 2, 1, 4, 2, 1);           //66 : [b]
		$this->T128[] = array(1, 4, 1, 1, 2, 2);           //67 : [c]
		$this->T128[] = array(1, 4, 1, 2, 2, 1);           //68 : [d]
		$this->T128[] = array(1, 1, 2, 2, 1, 4);           //69 : [e]
		$this->T128[] = array(1, 1, 2, 4, 1, 2);           //70 : [f]
		$this->T128[] = array(1, 2, 2, 1, 1, 4);           //71 : [g]
		$this->T128[] = array(1, 2, 2, 4, 1, 1);           //72 : [h]
		$this->T128[] = array(1, 4, 2, 1, 1, 2);           //73 : [i]
		$this->T128[] = array(1, 4, 2, 2, 1, 1);           //74 : [j]
		$this->T128[] = array(2, 4, 1, 2, 1, 1);           //75 : [k]
		$this->T128[] = array(2, 2, 1, 1, 1, 4);           //76 : [l]
		$this->T128[] = array(4, 1, 3, 1, 1, 1);           //77 : [m]
		$this->T128[] = array(2, 4, 1, 1, 1, 2);           //78 : [n]
		$this->T128[] = array(1, 3, 4, 1, 1, 1);           //79 : [o]
		$this->T128[] = array(1, 1, 1, 2, 4, 2);           //80 : [p]
		$this->T128[] = array(1, 2, 1, 1, 4, 2);           //81 : [q]
		$this->T128[] = array(1, 2, 1, 2, 4, 1);           //82 : [r]
		$this->T128[] = array(1, 1, 4, 2, 1, 2);           //83 : [s]
		$this->T128[] = array(1, 2, 4, 1, 1, 2);           //84 : [t]
		$this->T128[] = array(1, 2, 4, 2, 1, 1);           //85 : [u]
		$this->T128[] = array(4, 1, 1, 2, 1, 2);           //86 : [v]
		$this->T128[] = array(4, 2, 1, 1, 1, 2);           //87 : [w]
		$this->T128[] = array(4, 2, 1, 2, 1, 1);           //88 : [x]
		$this->T128[] = array(2, 1, 2, 1, 4, 1);           //89 : [y]
		$this->T128[] = array(2, 1, 4, 1, 2, 1);           //90 : [z]
		$this->T128[] = array(4, 1, 2, 1, 2, 1);           //91 : [{]
		$this->T128[] = array(1, 1, 1, 1, 4, 3);           //92 : [|]
		$this->T128[] = array(1, 1, 1, 3, 4, 1);           //93 : [}]
		$this->T128[] = array(1, 3, 1, 1, 4, 1);           //94 : [~]
		$this->T128[] = array(1, 1, 4, 1, 1, 3);           //95 : [DEL]
		$this->T128[] = array(1, 1, 4, 3, 1, 1);           //96 : [FNC3]
		$this->T128[] = array(4, 1, 1, 1, 1, 3);           //97 : [FNC2]
		$this->T128[] = array(4, 1, 1, 3, 1, 1);           //98 : [SHIFT]
		$this->T128[] = array(1, 1, 3, 1, 4, 1);           //99 : [Cswap]
		$this->T128[] = array(1, 1, 4, 1, 3, 1);           //100 : [Bswap]                
		$this->T128[] = array(3, 1, 1, 1, 4, 1);           //101 : [Aswap]
		$this->T128[] = array(4, 1, 1, 1, 3, 1);           //102 : [FNC1]
		$this->T128[] = array(2, 1, 1, 4, 1, 2);           //103 : [Astart]
		$this->T128[] = array(2, 1, 1, 2, 1, 4);           //104 : [Bstart]
		$this->T128[] = array(2, 1, 1, 2, 3, 2);           //105 : [Cstart]
		$this->T128[] = array(2, 3, 3, 1, 1, 1);           //106 : [STOP]
		$this->T128[] = array(2, 1);                       //107 : [END BAR]

		for ($i = 32; $i <= 95; $i++) {                                            // jeux de caract�res
			$this->ABCset .= chr($i);
		}
		$this->Aset = $this->ABCset;
		$this->Bset = $this->ABCset;

		for ($i = 0; $i <= 31; $i++) {
			$this->ABCset .= chr($i);
			$this->Aset .= chr($i);
		}
		for ($i = 96; $i <= 127; $i++) {
			$this->ABCset .= chr($i);
			$this->Bset .= chr($i);
		}
		for ($i = 200; $i <= 210; $i++) {                                           // controle 128
			$this->ABCset .= chr($i);
			$this->Aset .= chr($i);
			$this->Bset .= chr($i);
		}
		$this->Cset = "0123456789" . chr(206);

		for ($i = 0; $i < 96; $i++) {                                                   // convertisseurs des jeux A & B
			@$this->SetFrom["A"] .= chr($i);
			@$this->SetFrom["B"] .= chr($i + 32);
			@$this->SetTo["A"] .= chr(($i < 32) ? $i + 64 : $i - 32);
			@$this->SetTo["B"] .= chr($i);
		}
		for ($i = 96; $i < 107; $i++) {                                                 // contr�le des jeux A & B
			@$this->SetFrom["A"] .= chr($i + 104);
			@$this->SetFrom["B"] .= chr($i + 104);
			@$this->SetTo["A"] .= chr($i);
			@$this->SetTo["B"] .= chr($i);
		}
	}

	//________________ Fonction encodage et dessin du code 128 _____________________
	function Code128($x, $y, $code, $w, $h)
	{
		$Aguid = "";                                                                      // Cr�ation des guides de choix ABC
		$Bguid = "";
		$Cguid = "";
		for ($i = 0; $i < strlen($code); $i++) {
			$needle = substr($code, $i, 1);
			$Aguid .= ((strpos($this->Aset, $needle) === false) ? "N" : "O");
			$Bguid .= ((strpos($this->Bset, $needle) === false) ? "N" : "O");
			$Cguid .= ((strpos($this->Cset, $needle) === false) ? "N" : "O");
		}

		$SminiC = "OOOO";
		$IminiC = 4;

		$crypt = "";
		while ($code > "") {
			// BOUCLE PRINCIPALE DE CODAGE
			$i = strpos($Cguid, $SminiC);                                                // for�age du jeu C, si possible
			if ($i !== false) {
				$Aguid[$i] = "N";
				$Bguid[$i] = "N";
			}

			if (substr($Cguid, 0, $IminiC) == $SminiC) {                                  // jeu C
				$crypt .= chr(($crypt > "") ? $this->JSwap["C"] : $this->JStart["C"]);  // d�but Cstart, sinon Cswap
				$made = strpos($Cguid, "N");                                             // �tendu du set C
				if ($made === false) {
					$made = strlen($Cguid);
				}
				if (fmod($made, 2) == 1) {
					$made--;                                                            // seulement un nombre pair
				}
				for ($i = 0; $i < $made; $i += 2) {
					$crypt .= chr(strval(substr($code, $i, 2)));                          // conversion 2 par 2
				}
				$jeu = "C";
			} else {
				$madeA = strpos($Aguid, "N");                                            // �tendu du set A
				if ($madeA === false) {
					$madeA = strlen($Aguid);
				}
				$madeB = strpos($Bguid, "N");                                            // �tendu du set B
				if ($madeB === false) {
					$madeB = strlen($Bguid);
				}
				$made = (($madeA < $madeB) ? $madeB : $madeA);                         // �tendu trait�e
				$jeu = (($madeA < $madeB) ? "B" : "A");                                // Jeu en cours

				$crypt .= chr(($crypt > "") ? $this->JSwap[$jeu] : $this->JStart[$jeu]); // d�but start, sinon swap

				$crypt .= strtr(substr($code, 0, $made), $this->SetFrom[$jeu], $this->SetTo[$jeu]); // conversion selon jeu

			}
			$code = substr($code, $made);                                           // raccourcir l�gende et guides de la zone trait�e
			$Aguid = substr($Aguid, $made);
			$Bguid = substr($Bguid, $made);
			$Cguid = substr($Cguid, $made);
		}                                                                          // FIN BOUCLE PRINCIPALE

		$check = ord($crypt[0]);                                                   // calcul de la somme de contr�le
		for ($i = 0; $i < strlen($crypt); $i++) {
			$check += (ord($crypt[$i]) * $i);
		}
		$check %= 103;

		$crypt .= chr($check) . chr(106) . chr(107);                               // Chaine crypt�e compl�te

		$i = (strlen($crypt) * 11) - 8;                                            // calcul de la largeur du module
		$modul = $w / $i;

		for ($i = 0; $i < strlen($crypt); $i++) {                                      // BOUCLE D'IMPRESSION
			$c = $this->T128[ord($crypt[$i])];
			for ($j = 0; $j < count($c); $j++) {
				$this->Rect($x, $y, $c[$j] * $modul, $h, "F");
				$x += ($c[$j++] + $c[$j]) * $modul;
			}
		}
	}

	function depurar($texto)
	{
		$reemplazos = [
			'Ñ' => 'N',
			'ñ' => 'n',
			'á' => 'a',
			'é' => 'e',
			'í' => 'i',
			'ó' => 'o',
			'ú' => 'u',
			'Á' => 'A',
			'É' => 'E',
			'Í' => 'I',
			'Ó' => 'O',
			'Ú' => 'U',
			'Ü' => 'U',
			'ü' => 'u',
		];
		return strtr($texto, $reemplazos);
	}

	function CabeceraPrincipal()
	{
		$codigoNeg = base64_decode(trim($_REQUEST['cneg']));
		$codigoNCProf = base64_decode(trim($_REQUEST['codncprof']));
		// $codigoServ = base64_decode(trim($_REQUEST['cmbServicio']));

		///Datos de la Empresa segun la Orden.
		$sql_cabecera = "SELECT C.CodNotaProf, C.FecReg,
						P.TipMoneda, E.EmpRaz, CLI.CliRaz, CLI.CliRuc, CLI.CliTel1, CLI.CliDir, N.NegDes, N.NegCne, M.MotNom
						FROM CABNOTACREDITO_PROF C
						LEFT JOIN CABORDPROF P ON P.CodOrdProf = C.CodNotaProf and P.CodProfNeg = C.CodNotaNeg
						LEFT JOIN EMPRESA E ON E.EmpCod = C.CodNotaEmp and E.EmpEst = 'A'
						LEFT JOIN CLIENTE CLI ON CLI.CliCod = C.CodNotaCli
						LEFT JOIN NEGOCIO N ON N.NegCod = C.CodNotaNeg and N.NegEst = 'A'
						LEFT JOIN MOTIVO M ON M.MotCod = C.MotCod
						WHERE C.Estado not in ('C') and C.CodOrdNotaCre='$codigoNCProf' and CodNotaNeg='$codigoNeg'";
		// echo $sql_cabecera;
		$dsl_cabecera = $_SESSION['dbmssql']->getAll($sql_cabecera);
		foreach ($dsl_cabecera as $v => $reporte) {
			$proforma	=	trim($reporte['CodNotaProf']);
			$fecreg		=	trim($reporte['FecReg']);
			$tipmoneda	=	trim($reporte['TipMoneda']);
			$empraz		=	trim($reporte['EmpRaz']);
			$cliraz		=	$this->depurar(trim($reporte['CliRaz']));
			$cliruc		=	trim($reporte['CliRuc']);
			$clitel1	=	trim($reporte['CliTel1']);
			$clidir		=	$this->depurar(trim($reporte['CliDir']));
			$negdes		=	trim($reporte['NegDes']);
			$negcne		=	trim($reporte['NegCne']);
			$motnom		=	trim($reporte['MotNom']);
		}

		// DAR FORMATO FECHA
		function convertir_fecha($fecha_datetime)
		{
			// Convertir el mes de texto a su formato numérico (Apr -> 04)
			$meses = array(
				"Jan" => "01",
				"Feb" => "02",
				"Mar" => "03",
				"Apr" => "04",
				"May" => "05",
				"Jun" => "06",
				"Jul" => "07",
				"Aug" => "08",
				"Sep" => "09",
				"Oct" => "10",
				"Nov" => "11",
				"Dec" => "12"
			);

			// Dividir la fecha
			$fecha_partes = explode(" ", trim($fecha_datetime));

			// Obtener los valores del mes y día
			$mes_num = $meses[$fecha_partes[0]]; // Ejemplo: "Apr" -> "04"
			$dia = str_pad($fecha_partes[1], 2, "0", STR_PAD_LEFT); // Asegurar dos dígitos

			// Obtener el año, hora y minutos
			$anio = $fecha_partes[2];
			$hora_minuto_segundos = substr($fecha_partes[3], 0, 8); // "04:01:21"
			$periodo = substr($fecha_partes[3], 8); // "PM" o "AM"

			// Crear una fecha en formato "Y-m-d H:i:s" para ser compatible con DateTime
			$fecha_completa = "$anio-$mes_num-$dia $hora_minuto_segundos $periodo";

			// Crear el objeto DateTime
			$date = DateTime::createFromFormat('Y-m-d h:i:s A', $fecha_completa);

			// Verificar si la fecha es válida
			if ($date instanceof DateTime) {
				return $date->format('d/m/Y h:i A'); // Ejemplo: "22/04/2025 04:01 PM"
			} else {
				return 'Fecha inválida';
			}
		}
		$fecha_formateada = convertir_fecha($fecreg);

		// CORTAR SI ES MUY LARGO
		$cliraz = substr($cliraz, 0, 72);
		$clidir = substr($clidir, 0, 72);

		// MONEDA DE LA NC
		if ($tipmoneda == 'S') {
			$texto = 'SOLES (S/.)';
		}
		if ($tipmoneda == 'D') {
			$texto = 'DOLARES ($. )';
		}

		//haciendo que el query este accesible a las funciones de la clase
		// $this->query_principal = $sql_cabecera;

		$this->SetFont('Arial', '', 8);
		$this->Cell(150);
		$this->Cell(30, 5, '', 0, 0, 'R');
		$this->Ln(5);

		//NUEVO CODIGO DE BARRAS - INICIO
		$tipo_cb = "NCP";
		$code = $tipo_cb . '|' . $codigoNCProf;
		$this->Code128(138, 18, $code, 57, 8);
		$this->Ln(8);
		//NUEVO CODIGO DE BARRAS - FIN


		////$this->Image('bxz.jpg',18,15,23);
		$this->Image('blanco_bxz.jpg', 18, 15, 23);  ///pinta un dibujo en blanco

		$this->Ln(8);
		$this->SetFont('Arial', 'B', 12);
		$this->Cell(180, 7, 'NOTA DE CREDITO DE PROFORMA ', 0, 1, 'C');
		$this->Cell(180, 7, 'N.C. ' . (string)str_pad($codigoNCProf, 7, '0', STR_PAD_LEFT) . '-' . $negcne, 0, 0, 'C');
		$this->Cell(110);
		$this->SetFont('Arial', '', 7);
		$this->Ln(15);

		// $this->Cell(139);
		// $this->Cell(40, 3, 'PROFORMA       : ' . $proforma, 0, 1, 'L');
		$this->SetFont('Arial', 'B', 7);
		$this->Cell(30, 3, 'PROFORMA AFECTADA', 0, 0, 'L');
		$this->Cell(3, 3, ':', 0, 0, 'L');
		$this->Cell(96, 3, 'P. ' . (string)str_pad($proforma, 7, '0', STR_PAD_LEFT) . '-' . $negcne, 0, 0, 'L');
		$this->SetFont('Arial', '', 7);
		$this->Cell(18, 3, 'EMPRESA', 0, 0, 'L');
		$this->Cell(3, 3, ':', 0, 0, 'L');
		$this->Cell(24, 3, $empraz, 0, 1, 'L');

		$this->Cell(16, 3, 'CLIENTE', 0, 0, 'L');
		$this->Cell(3, 3, ':', 0, 0, 'L');
		$this->Cell(110, 3, $cliraz, 0, 0, 'L');
		$this->Cell(18, 3, 'RUC NRO', 0, 0, 'L');
		$this->Cell(3, 3, ':', 0, 0, 'L');
		$this->Cell(24, 3, $cliruc, 0, 1, 'L');

		$this->Cell(16, 3, 'DIRECCION', 0, 0, 'L');
		$this->Cell(3, 3, ':', 0, 0, 'L');
		$this->Cell(110, 3, $clidir, 0, 0, 'L');
		$this->Cell(18, 3, 'TELEFONO', 0, 0, 'L');
		$this->Cell(3, 3, ':', 0, 0, 'L');
		$this->Cell(24, 3, $clitel1, 0, 1, 'L');

		$this->Cell(16, 3, 'NEGOCIO', 0, 0, 'L');
		$this->Cell(3, 3, ':', 0, 0, 'L');
		$this->Cell(110, 3, $negdes, 0, 0, 'L');
		$this->Cell(18, 3, 'FEC EMISION', 0, 0, 'L');
		$this->Cell(3, 3, ':', 0, 0, 'L');
		$this->Cell(24, 3, $fecha_formateada, 0, 1, 'L');

		$this->Cell(16, 3, 'MOTIVO', 0, 0, 'L');
		$this->Cell(3, 3, ':', 0, 0, 'L');
		$this->Cell(110, 3, $motnom, 0, 0, 'L');
		$this->Cell(18, 3, 'MONEDA', 0, 0, 'L');
		$this->Cell(3, 3, ':', 0, 0, 'L');
		$this->Cell(24, 3, $texto, 0, 1, 'L');
		$this->Ln(8);
	}


	function FancyTable($header)
	{

		//Colores, ancho de l�nea y fuente en negrita
		$this->SetFillColor(204, 204, 204);
		$this->SetTextColor(0);
		$this->SetDrawColor(10, 10, 10);
		$this->SetLineWidth(.2);
		//Cabecera
		$w = array(8, 130, 20, 15, 15);
		for ($i = 0; $i < count($header); $i++)
			$this->Cell($w[$i], 5, $header[$i], 1, 0, 'C', 1);
		$this->Ln();

		//Restauraci�n de colores y fuentes
		$this->SetFillColor(255, 255, 255);
		$this->SetTextColor(0);
		$this->SetFont('Arial', '', 7);
		//Datos
		$fill = 0;
		// maximo de Filas //
		$max = 41;
		//$max=35;
		$i = $j = 0;

		$codigoNeg = base64_decode(trim($_REQUEST['cneg']));
		$codigoNCProf = base64_decode(trim($_REQUEST['codncprof']));
		// $codigoServ = base64_decode(trim($_REQUEST['cmbServicio']));

		$sql  = "SELECT D.Glosa, D.Cantidad, D.Punitario, D.Monto, M.MedAbrev
				FROM CABNOTACREDITO_PROF C
				LEFT JOIN DETNOTACREDITO_PROF D ON C.CodOrdNotaCre = D.CodOrdNotaCre AND C.CodNotaNeg = D.CodNotaNeg
				LEFT JOIN MEDIDA M ON M.MedCod = D.MedCod
				WHERE C.Estado not in ('C') AND C.CodOrdNotaCre = '$codigoNCProf' AND C.CodNotaNeg = '$codigoNeg'";

		$dsl_detalle = $_SESSION['dbmssql']->getAll($sql);
		foreach ($dsl_detalle as $v => $value) {
			$Descrip  		=  $this->depurar($value['Glosa']);
			$cantrecep    	=  $value['Cantidad'];
			$preciounit 	=  $value['Punitario'];
			$preciototal 	=  $value['Monto'];
			$medida 		=  $value['MedAbrev'];

			$preciounit    = number_format((float)$preciounit, 2, '.', '');
			$preciototal   = number_format((float)$preciototal, 2, '.', '');
			// CORTAR SI ES MUY LARGO
			$Descrip = substr($Descrip, 0, 72);

			$this->SetFont('Arial', '', 7);
			if ($i == $max) {
				$this->SetFillColor(204, 204, 204);
				$this->SetTextColor(0);
				$this->SetDrawColor(10, 10, 10);
				$this->SetLineWidth(.3);

				$w = array(8, 130, 20, 15, 15);
				for ($i = 0; $i < count($header); $i++)
					$this->Cell($w[$i], 3, $header[$i], 1, 0, 'C', 1);
				$this->Ln();
				$i = 0;
			}

			$this->SetFont('Arial', '', 7);
			// $estado  	=$allper['Estado'];
			$this->Cell($w[0], 4, (string)str_pad($j + 1, 3, '0', STR_PAD_LEFT), 'TLR', 0, 'C', 2);
			$this->Cell($w[1], 4, $Descrip, 'TL', 0, 'L', 2);
			$this->Cell($w[2], 4, $cantrecep . ' ' . $medida, 'TLR', 0, 'C', 2);
			$this->Cell($w[3], 4, $preciounit, 'TLR', 0, 'C', 2);
			$this->Cell($w[4], 4, $preciototal, 'TLR', 0, 'C', 2);

			$this->Ln();
			$this->Cell($w[0], 1, '', 'LRB', 0, 'C', 2);
			$this->Cell($w[1], 1, '', 'LRB', 0, 'L', 2);
			$this->Cell($w[2], 1, '', 'LRB', 0, 'C', 2);
			$this->Cell($w[3], 1, '', 'LRB', 0, 'C', 2);
			$this->Cell($w[4], 1, '', 'LRB', 0, 'C', 2);

			$this->Ln();
			$fill = !$fill;
			$i++;
			$j++;
		}

		$this->Ln(4);

		// $sentencia_principal = $this->query_principal;
		///Datos de la Empresa segun la Orden.
		$sql_footer = "SELECT C.SubTotal, C.MontTotal, C.MontCambio, C.Comentario, 
						P.TipMoneda, P.TipCambio
						FROM CABNOTACREDITO_PROF C
						LEFT JOIN CABORDPROF P ON P.CodOrdProf = C.CodNotaProf and P.CodProfNeg = C.CodNotaNeg
						LEFT JOIN EMPRESA E ON E.EmpCod = C.CodNotaEmp and E.EmpEst = 'A'
						LEFT JOIN CLIENTE CLI ON CLI.CliCod = C.CodNotaCli
						LEFT JOIN NEGOCIO N ON N.NegCod = C.CodNotaNeg and N.NegEst = 'A'
						LEFT JOIN MOTIVO M ON M.MotCod = C.MotCod
						WHERE C.Estado not in ('C') and C.CodOrdNotaCre='$codigoNCProf' and CodNotaNeg='$codigoNeg'";
		// echo $sql_cabecera;
		$dsl_footer = $_SESSION['dbmssql']->getAll($sql_footer);
		foreach ($dsl_footer as $v => $reporte) {
			$subtotal	=	trim($reporte['SubTotal']);
			$monttotal	=	trim($reporte['MontTotal']);
			$montcambio	=	trim($reporte['MontCambio']);
			$comentario	=	$this->depurar(trim($reporte['Comentario']));
			$tipmoneda	=	trim($reporte['TipMoneda']);
			$tipcambio	=	trim($reporte['TipCambio']);
		}

		$subtotal    = number_format((float)$subtotal, 2, '.', '');
		$monttotal   = number_format((float)$monttotal, 2, '.', '');
		$montcambio  = number_format((float)$montcambio, 2, '.', '');

		if ($tipmoneda == 'S') {
			$texto1 = 'Monto Total (S/.)        : ' . $monttotal;
			$texto2 = 'Monto al Cambio ($.) : ' . $montcambio;
		}
		if ($tipmoneda == 'D') {
			$texto1 = 'Monto Total ($. )         : ' . $monttotal;
			$texto2 = 'Monto al Cambio (S/) : ' . $montcambio;
		}

		//'Comentario Fac. :'.$comentario 	
		$arr_fila_0 = array('', '', '', 'SubTotal                    : ' . $subtotal);
		$arr_fila_1 = array('', '', '', $texto1);
		$arr_fila_2 = array('', '', '', 'TC Proforma              : ' . $tipcambio);
		$arr_fila_3 = array('', '', '', $texto2);




		for ($f = 0; $f < 6; $f++) {
			for ($c = 0; $c < 4; $c++) {
				if ($c == 3) $width = 40;
				else $width = 50;
				if ($f == 0) $this->Cell($width, 3, $arr_fila_0[$c], '', 0, 'L', 1);
				if ($f == 1) $this->Cell($width, 3, $arr_fila_1[$c], '', 0, 'L', 1);
				if ($f == 2) $this->Cell($width, 3, $arr_fila_2[$c], '', 0, 'L', 1);
				if ($f == 3) $this->Cell($width, 3, $arr_fila_3[$c], '', 0, 'L', 1);
			}
			$this->ln();
		}

		$this->ln(-20);

		if (strlen($comentario) > 0) {
			$this->Ln(2);
			$this->SetFont('Arial', '', 6);
			$this->Cell(40, 4, 'COMENTARIOS NC:', 0, 1, 'L', 0);
			$this->SetFont('Arial', '', 6);
			$dato = stripslashes(trim($comentario));
			$this->MultiCell(130, 3, $dato, 0, 'L');
			$this->Ln(1);
			$this->Cell(122);
		}
	}
}

$sql = "select replace(replace(replace(LEFT(convert(varchar,getdate(),103),12)+''+right(getdate(),8),' ',''),':',''),'/','') as fecha  ";
$dsl = $_SESSION['dbmssql']->getAll($sql);
foreach ($dsl as $v => $fec) {
	$nombrefile = $fec['fecha'];
}


$header = array('ITEM', 'DESCRIPCION', 'CANT.', 'P. UNIT.', 'IMPORTE');
//$pdf=new PDF();
$pdf = new PDF_Code128();
$pdf->AliasNbPages();
$pdf->SetFont('Arial', '', 7);
$pdf->AddPage();
$pdf->CabeceraPrincipal();
$pdf->FancyTable($header);
$pdf->Output('DOC_NC_PROF' . $nombrefile . '.pdf', 'D');
