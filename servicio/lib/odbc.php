<?php
class odbc
{
	private $dsn;
	private $usuario;
	private $clave;
	private $enlace;
	private $rs;
	private $rw;
	public $resultado = array();
	
	public function configurar($vDsn, $vUser, $vClave)
	{
		$this->dsn 		= $vDsn;
		$this->usuario 	= $vUser;
		$this->clave 	= $vClave;
	}
	
	private function conectar()
	{
		$this->enlace = odbc_connect($this->dsn, $this->usuario, $this->clave);
		if (!$this->enlace)
		{
			exit("<strong>Error tratando de conectarse con el origen de datos.</strong>");
		}
	}
	
	private function desconectar()
	{
		odbc_close($this->enlace);
	}
	
	public function ejecutar_cmd($cmd)
	{	
		$this->conectar();
		$this->rs = odbc_exec($this->enlace, $cmd) or die(exit("Error en odbc_exec<br />".odbc_errormsg()));
		$this->desconectar();
		
		if (!$this->rs)		return false;
		else				return true;
	}
	
	public function ejecutar_sql($sql)
	{	
		$this->conectar();
		$this->rs = odbc_exec($this->enlace, $sql) or die(exit("Error en odbc_exec<br />".odbc_errormsg()));
		$N=0;
		$this->resultado = array();
		if ($this->rs != false)
		{
			while($this->rw = odbc_fetch_array($this->rs) )
			{
				$this->resultado[$N] = $this->rw;
				$N++;
			}
		}
		@odbc_free_result($this->rs);
		$this->desconectar();
		return $this->resultado;
	}
}
?>