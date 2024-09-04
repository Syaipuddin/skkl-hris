<?php
require('fpdf.php');
require('htmlparser.inc');


//protection function exist start
if(function_exists('mcrypt_encrypt'))
{
	function RC4($key, $data)
	{
		return mcrypt_encrypt(MCRYPT_ARCFOUR, $key, $data, MCRYPT_MODE_STREAM, '');
	}
}
else
{
	function RC4($key, $data)
	{
		static $last_key, $last_state;

		if($key != $last_key)
		{
			$k = str_repeat($key, 256/strlen($key)+1);
			$state = range(0, 255);
			$j = 0;
			for ($i=0; $i<256; $i++){
				$t = $state[$i];
				$j = ($j + $t + ord($k[$i])) % 256;
				$state[$i] = $state[$j];
				$state[$j] = $t;
			}
			$last_key = $key;
			$last_state = $state;
		}
		else
			$state = $last_state;

		$len = strlen($data);
		$a = 0;
		$b = 0;
		$out = '';
		for ($i=0; $i<$len; $i++){
			$a = ($a+1) % 256;
			$t = $state[$a];
			$b = ($b+$t) % 256;
			$state[$a] = $state[$b];
			$state[$b] = $t;
			$k = $state[($state[$a]+$state[$b]) % 256];
			$out .= chr(ord($data[$i]) ^ $k);
		}
		return $out;
	}
}

//protection function exist end

class PDF_HTML_Table extends FPDF
{
var $B;
var $I;
var $U;
var $HREF;

function PDF($orientation='P', $unit='mm', $format='A4')
{
	//Call parent constructor
	$this->FPDF($orientation,$unit,$format);
	//Initialization
	$this->B=0;
	$this->I=0;
	$this->U=0;
	$this->HREF='';
}

function WriteHTML2($html)
{
	//HTML parser
	$html=str_replace("\n",' ',$html);
	$a=preg_split('/<(.*)>/U',$html,-1,PREG_SPLIT_DELIM_CAPTURE);
	foreach($a as $i=>$e)
	{
		if($i%2==0)
		{
			//Text
			if($this->HREF)
				$this->PutLink($this->HREF,$e);
			else
				$this->Write(5,$e);
		}
		else
		{
			//Tag
			if($e[0]=='/')
				$this->CloseTag(strtoupper(substr($e,1)));
			else
			{
				//Extract attributes
				$a2=explode(' ',$e);
				$tag=strtoupper(array_shift($a2));
				$attr=array();
				foreach($a2 as $v)
				{
					if(preg_match('/([^=]*)=["\']?([^"\']*)/',$v,$a3))
						$attr[strtoupper($a3[1])]=$a3[2];
				}
				$this->OpenTag($tag,$attr);
			}
		}
	}
}

function OpenTag($tag, $attr)
{
	//Opening tag
	if($tag=='B' || $tag=='I' || $tag=='U')
		$this->SetStyle($tag,true);
	if($tag=='A')
		$this->HREF=$attr['HREF'];
	if($tag=='BR')
		$this->Ln(5);
	if($tag=='P')
		$this->Ln(10);
}

function CloseTag($tag)
{
	//Closing tag
	if($tag=='B' || $tag=='I' || $tag=='U')
		$this->SetStyle($tag,false);
	if($tag=='A')
		$this->HREF='';
	if($tag=='P')
		$this->Ln(10);
}

function SetStyle($tag, $enable)
{
	//Modify style and select corresponding font
	$this->$tag+=($enable ? 1 : -1);
	$style='';
	foreach(array('B','I','U') as $s)
		if($this->$s>0)
			$style.=$s;
	$this->SetFont('',$style);
}

function PutLink($URL, $txt)
{
	//Put a hyperlink
	$this->SetTextColor(0,0,255);
	$this->SetStyle('U',true);
	$this->Write(5,$txt,$URL);
	$this->SetStyle('U',false);
	$this->SetTextColor(0);
}

function WriteTable($data, $w)
{
	$this->SetLineWidth(.3);
	$this->SetFillColor(255,255,255);
	$this->SetTextColor(0);
	$this->SetFont('');
	foreach($data as $row)
	{
		$nb=0;
		for($i=0;$i<count($row);$i++)
			$nb=max($nb,$this->NbLines($w[$i],trim($row[$i])));
		$h=5*$nb;
		$this->CheckPageBreak($h);
		for($i=0;$i<count($row);$i++)
		{
			$x=$this->GetX();
			$y=$this->GetY();
			$this->Rect($x,$y,$w[$i],$h);
			$this->MultiCell($w[$i],5,trim($row[$i]),0,'C');
			//Put the position to the right of the cell
			$this->SetXY($x+$w[$i],$y);					
		}
		$this->Ln($h);

	}
}

function NbLines($w, $txt)
{
	//Computes the number of lines a MultiCell of width w will take
	$cw=&$this->CurrentFont['cw'];
	if($w==0)
		$w=$this->w-$this->rMargin-$this->x;
	$wmax=($w-2*$this->cMargin)*1000/$this->FontSize;
	$s=str_replace("\r",'',$txt);
	$nb=strlen($s);
	if($nb>0 && $s[$nb-1]=="\n")
		$nb--;
	$sep=-1;
	$i=0;
	$j=0;
	$l=0;
	$nl=1;
	while($i<$nb)
	{
		$c=$s[$i];
		if($c=="\n")
		{
			$i++;
			$sep=-1;
			$j=$i;
			$l=0;
			$nl++;
			continue;
		}
		if($c==' ')
			$sep=$i;
		$l+=$cw[$c];
		if($l>$wmax)
		{
			if($sep==-1)
			{
				if($i==$j)
					$i++;
			}
			else
				$i=$sep+1;
			$sep=-1;
			$j=$i;
			$l=0;
			$nl++;
		}
		else
			$i++;
	}
	return $nl;
}

function CheckPageBreak($h)
{
	//If the height h would cause an overflow, add a new page immediately
	if($this->GetY()+$h>$this->PageBreakTrigger)
		$this->AddPage($this->CurOrientation);
}

function ReplaceHTML($html)
{
	$html = str_replace( '<li>', "\n<br> - " , $html );
	$html = str_replace( '<LI>', "\n - " , $html );
	$html = str_replace( '</ul>', "\n\n" , $html );
	$html = str_replace( '<strong>', "<b>" , $html );
	$html = str_replace( '</strong>', "</b>" , $html );
	$html = str_replace( '&#160;', "\n" , $html );
	$html = str_replace( '&nbsp;', " " , $html );
	$html = str_replace( '&quot;', "\"" , $html ); 
	$html = str_replace( '&#39;', "'" , $html );
	return $html;
}

function ParseTable($Table)
{
	$_var='';
	$htmlText = $Table;
	$parser = new HtmlParser ($htmlText);
	while ($parser->parse())
	{
		if(strtolower($parser->iNodeName)=='table')
		{
			if($parser->iNodeType == NODE_TYPE_ENDELEMENT)
				$_var .='/::';
			else
				$_var .='::';
		}

		if(strtolower($parser->iNodeName)=='tr')
		{
			if($parser->iNodeType == NODE_TYPE_ENDELEMENT)
				$_var .='!-:'; //opening row
			else
				$_var .=':-!'; //closing row
		}
		if(strtolower($parser->iNodeName)=='td' && $parser->iNodeType == NODE_TYPE_ENDELEMENT)
		{
			$_var .='#,#';
		}
		if ($parser->iNodeName=='Text' && isset($parser->iNodeValue))
		{
			$_var .= $parser->iNodeValue;
		}
	}
	$elems = explode(':-!',str_replace('/','',str_replace('::','',str_replace('!-:','',$_var)))); //opening row
	foreach($elems as $key=>$value)
	{
		if(trim($value)!='')
		{
			$elems2 = explode('#,#',$value);
			array_pop($elems2);
			$data[] = $elems2;
		}
	}
	return $data;
}

function WriteHTML($html)
{
	$html = $this->ReplaceHTML($html);
	//Search for a table
	$start = strpos(strtolower($html),'<table');
	$end = strpos(strtolower($html),'</table');
	if($start!==false && $end!==false)
	{
		$this->WriteHTML2(substr($html,0,$start).'<BR>');

		$tableVar = substr($html,$start,$end-$start);
		$tableData = $this->ParseTable($tableVar);
		for($i=1;$i<=count($tableData[0]);$i++)
		{
			if($this->CurOrientation=='L')
				$w[] = abs(120/(count($tableData[0])-1))+24;
			else
				$w[] = abs(120/(count($tableData[0])-1))+5;
		}
		$this->WriteTable($tableData,$w);

		$this->WriteHTML2(substr($html,$end+8,strlen($html)-1).'<BR>');
	}
	else
	{
		$this->WriteHTML2($html);
	}
}


        // ----------- PROTECTION PLUGIN STARTED ------------------////////

        var $encrypted = false;  //whether document is protected
	var $Uvalue;             //U entry in pdf document
	var $Ovalue;             //O entry in pdf document
	var $Pvalue;             //P entry in pdf document
	var $enc_obj_id;         //encryption object id

	/**
	* Function to set permissions as well as user and owner passwords
	*
	* - permissions is an array with values taken from the following list:
	*   copy, print, modify, annot-forms
	*   If a value is present it means that the permission is granted
	* - If a user password is set, user will be prompted before document is opened
	* - If an owner password is set, document can be opened in privilege mode with no
	*   restriction if that password is entered
	*/
	function SetProtection($permissions=array(), $user_pass='', $owner_pass=null)
	{
		$options = array('print' => 4, 'modify' => 8, 'copy' => 16, 'annot-forms' => 32 );
		$protection = 192;
		foreach($permissions as $permission)
		{
			if (!isset($options[$permission]))
				$this->Error('Incorrect permission: '.$permission);
			$protection += $options[$permission];
		}
		if ($owner_pass === null)
			$owner_pass = uniqid(rand());
		$this->encrypted = true;
		$this->padding = "\x28\xBF\x4E\x5E\x4E\x75\x8A\x41\x64\x00\x4E\x56\xFF\xFA\x01\x08".
						"\x2E\x2E\x00\xB6\xD0\x68\x3E\x80\x2F\x0C\xA9\xFE\x64\x53\x69\x7A";
		$this->_generateencryptionkey($user_pass, $owner_pass, $protection);
	}

/****************************************************************************
*                                                                           *
*                              Private methods                              *
*                                                                           *
****************************************************************************/

	function _putstream($s)
	{
		if ($this->encrypted) {
			$s = RC4($this->_objectkey($this->n), $s);
		}
		parent::_putstream($s);
	}

	function _textstring($s)
	{
		if ($this->encrypted) {
			$s = RC4($this->_objectkey($this->n), $s);
		}
		return parent::_textstring($s);
	}

	/**
	* Compute key depending on object number where the encrypted data is stored
	*/
	function _objectkey($n)
	{
		return substr($this->_md5_16($this->encryption_key.pack('VXxx',$n)),0,10);
	}

	function _putresources()
	{
		parent::_putresources();
		if ($this->encrypted) {
			$this->_newobj();
			$this->enc_obj_id = $this->n;
			$this->_out('<<');
			$this->_putencryption();
			$this->_out('>>');
			$this->_out('endobj');
		}
	}

	function _putencryption()
	{
		$this->_out('/Filter /Standard');
		$this->_out('/V 1');
		$this->_out('/R 2');
		$this->_out('/O ('.$this->_escape($this->Ovalue).')');
		$this->_out('/U ('.$this->_escape($this->Uvalue).')');
		$this->_out('/P '.$this->Pvalue);
	}

	function _puttrailer()
	{
		parent::_puttrailer();
		if ($this->encrypted) {
			$this->_out('/Encrypt '.$this->enc_obj_id.' 0 R');
			$this->_out('/ID [()()]');
		}
	}

	/**
	* Get MD5 as binary string
	*/
	function _md5_16($string)
	{
		return pack('H*',md5($string));
	}

	/**
	* Compute O value
	*/
	function _Ovalue($user_pass, $owner_pass)
	{
		$tmp = $this->_md5_16($owner_pass);
		$owner_RC4_key = substr($tmp,0,5);
		return RC4($owner_RC4_key, $user_pass);
	}

	/**
	* Compute U value
	*/
	function _Uvalue()
	{
		return RC4($this->encryption_key, $this->padding);
	}

	/**
	* Compute encryption key
	*/
	function _generateencryptionkey($user_pass, $owner_pass, $protection)
	{
		// Pad passwords
		$user_pass = substr($user_pass.$this->padding,0,32);
		$owner_pass = substr($owner_pass.$this->padding,0,32);
		// Compute O value
		$this->Ovalue = $this->_Ovalue($user_pass,$owner_pass);
		// Compute encyption key
		$tmp = $this->_md5_16($user_pass.$this->Ovalue.chr($protection)."\xFF\xFF\xFF");
		$this->encryption_key = substr($tmp,0,5);
		// Compute U value
		$this->Uvalue = $this->_Uvalue();
		// Compute P value
		$this->Pvalue = -(($protection^255)+1);
	}

        // ----------- PROTECTION PLUGIN ENDED ------------------////////
        
        
        //////// ------------ PLUGIN buatan sendiri STARTED -------- //////
        function Header()
        {
            //Logo
            $this->Image('img/logo/logo_kg_ori.png',10,8,33);
            //Arial bold 15
            $this->SetFont('Arial','B',15);
            //Move to the right
            $this->Cell(80);
            //Title
            $this->Cell(40,10,'Slip Gaji KG',1,0,'C');
            //Line break
            $this->Ln(20);
        }

        function Footer()
        {
            //Position at 1.5 cm from bottom
            $this->SetY(-15);
            //Arial italic 8
            $this->SetFont('Arial','I',8);
            //Page number
            $this->Cell(0,10,'Page '.$this->PageNo().' / Slip Gaji KG',0,0,'C');            
        }
        
        //////// ------------ PLUGIN buatan sendiri ENDED -------- //////
}
?>
