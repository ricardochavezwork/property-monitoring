<?php

class Crawler_Ad_Sell extends DataClass {

  const TABLE_NAME = "AnnunciVendita";

  public $Id = 0;
  public $Indirizzo = "";
  public $Link = "";
  public $LinkMappa = "";
  public $LinkPlanimetria = "";
  public $SuperficieM2 = 0;
  public $Locali = 0;
  public $Bagni = 0;
  public $Piano = "";
  public $Ascensore = 0;
  public $Prezzo = 0;
  public $SpeseCondominio = 0 ;
  public $SpeseRiscaldamento = 0;
  public $Agenzia = "";
  public $Telefono = "";
  public $TipoRiscaldamento = 0;//1 : Cenrtralizzato, 2 : Autonomo
  public $Contratto = "";
  public $Zona = 0;
  public $DataRegistrazione = "";
  public $DataAggiornamento = "";

  public function __construct($src = null, $stripSlashes = false) {
    if($src){
      if (is_array($src)) {
          // Load by array
          $this->_loadByRow($src, $stripSlashes);
      } else if (is_numeric($src) && intval($src) > 0) {
          // Load by Id
          $this->_loadFilter(self::TABLE_NAME, "Id = " . intval($src));
          //$this->setParent($src);
      }
    }
  }

  public function Save(){
    global $Database;

    $query = sprintf("CALL SaveAnnunciVendita(%d, %s, %s, %s, %s, %d, %d, %d, %s, %s, %d, %d, %d, %s, %s, %d, %s, %d);",
      $this->Id,
      $this->EscapeNulls($this->Indirizzo),
      $this->EscapeNulls($this->Link),
      $this->EscapeNulls($this->LinkMappa),
      $this->EscapeNulls($this->LinkPlanimetria),
      $this->SuperficieM2,
      $this->Locali,
      $this->Bagni,
      $this->EscapeNulls($this->Piano),
      $this->Ascensore,
      $this->Prezzo,
      $this->SpeseCondominio,
      $this->SpeseRiscaldamento,
      $this->EscapeNulls($this->Agenzia),
      $this->EscapeNulls($this->Telefono),
      $this->TipoRiscaldamento,
      $this->EscapeNulls($this->Contratto),
      $this->Zona
    );

    if ($Database->Query($query))
        {
            if ($this->Id <= 0)
                $this->Id = $Database->InsertedId();
            return TRUE;
        }
      return FALSE;

  }

  public static function SearchByLink($string){
    global $Database;

    $exists = false;
    $found_id = 0;

    if($string){
      $sql = sprintf("SELECT COUNT(*) as Total, Id FROM AnnunciVendita WHERE Link = '%s'", $Database->Escape($string));
      if ($res = $Database->Query($sql)){
        if ($row = $Database->Fetch($res)){
            $tot = $row["Total"];
            $found_id = intval($row["Id"]);
            if(intval($tot) > 0){
                $exists = true;
            }
        }
      }

      /*if($exists){
        $update_sql = sprintf("UPDATE AnnunciVendita SET TettoMassimo = %d, DataAggiornamento = CURRENT_TIMESTAMP WHERE Id = '%d';", $tettoMassimo, $found_id);
        $Database->Query($update_sql);
      }*/

    }

    return $found_id;

  } 

}