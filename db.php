<?php 
class cParadox 
{ 
    var $m_pxdoc = NULL; 
    var $m_fp     = NULL; 
    var $m_rs     = NULL; 
    var $m_default_field_value = ""; 
    var $m_use_field_slashes = false;    
    var $m_use_field_trim      = false;    
     
    function Open($filename) 
    { 
        $this->m_pxdoc = px_new(); 
        if( !$this->m_pxdoc) 
        { 
            die ("cParadox Error: px_new() failed."); 
        } 
    
        $this->m_fp = fopen($filename, "r"); 
    
        if(!$this->m_fp) 
        { 
            px_delete($this->m_pxdoc); 
            die ("cParadox Error: fopen failed.Filename:$filename"); 
        } 
    
        if(!px_open_fp($this->m_pxdoc ,$this->m_fp) ) 
        { 
            px_delete($this->m_pxdoc); 
            fclose( $this->m_fp ); 
            die ("cParadox Erro: px_open_fp failed."); 
        } 
    
        return true;    
    } 
    
    function Close() 
    { 
        if ( $this->m_pxdoc ) 
        { 
            px_close($this->m_pxdoc); 
            px_delete($this->m_pxdoc); 
        } 
        
        if( $this->m_fp ) 
        { 
            fclose( $this->m_fp ); 
        } 
    } 
    
    function GetNumRecords() 
    { 
        return px_numrecords($this->m_pxdoc); 
    } 
    
        function GetRecord($rec) 
    { 
        $this->m_rs = px_get_record($this->m_pxdoc ,$rec ,PX_KEYTOUPPER); 
        return $this->m_rs; 
    } 
        
    function GetField($field ,$type=0, $format=0) 
    { 
        if ( !$this->m_rs ) 
        { 
            return false;      
        } 
        
        $value = isset($this->m_rs[$field])? $this->m_rs[$field] : ""; 
        
        if ( $this->m_use_field_slashes ) 
        { 
            $value = addslashes($value); 
        } 

        if ( $this->m_use_field_trim ) 
        { 
            $value = trim($value); 
        } 
        

        return $value; 
    } 
}; 

error_reporting(E_ERROR); 

$pdx = new cParadox(); 
$pdx->m_default_field_value = "?";//" "; 

if ( $pdx->Open("/var/www/html/stowell/_data/prod/ORD_FAB01.DB") ) 
{ 
     $tot_rec = $pdx->GetNumRecords(); 
    if ( $tot_rec ) 
     { 
        echo "<table border=1>\n"; 
        for($rec=0; $rec<$tot_rec; $rec++) 
        { 
             $pdx->GetRecord($rec); 
    
            echo "<tr>"; 

            echo "<td>" . $rec; 
            echo "<td>" . $pdx->GetField(CODE); 
            echo "<td>" . $pdx->GetField(NAME); 
        } 
    } 

    $pdx->Close();  
} 