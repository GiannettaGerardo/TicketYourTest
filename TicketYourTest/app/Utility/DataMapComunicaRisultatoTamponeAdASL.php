<?php


namespace App\Utility;

/**
 * Class DataMapComunicaRisultatoTamponeAdASL
 * Questa classe permette il refactoring del code smell "long parameters"
 * per il metodo comunicaRisultatoTamponeAdASL della classe ASLapi
 * @package App\Utility
 */
class DataMapComunicaRisultatoTamponeAdASL
{
    private $cfPaziente;
    private $nome;
    private $cognome;
    private $cittaResidenza;
    private $provinciaResidenza;
    private $nomeLaboratorio;
    private $provinciaLaboratorio;

    /**
     * @return string
     */
    public function getCfPaziente(): string
    {
        return $this->cfPaziente;
    }

    /**
     * @param string $cfPaziente
     */
    public function setCfPaziente(string $cfPaziente): void
    {
        $this->cfPaziente = $cfPaziente;
    }

    /**
     * @return string
     */
    public function getNome(): string
    {
        return $this->nome;
    }

    /**
     * @param string $nome
     */
    public function setNome(string $nome): void
    {
        $this->nome = $nome;
    }

    /**
     * @return string
     */
    public function getCognome(): string
    {
        return $this->cognome;
    }

    /**
     * @param string $cognome
     */
    public function setCognome(string $cognome): void
    {
        $this->cognome = $cognome;
    }

    /**
     * @return string
     */
    public function getCittaResidenza(): string
    {
        return $this->cittaResidenza;
    }

    /**
     * @param string $cittaResidenza
     */
    public function setCittaResidenza(string $cittaResidenza): void
    {
        $this->cittaResidenza = $cittaResidenza;
    }

    /**
     * @return string
     */
    public function getProvinciaResidenza(): string
    {
        return $this->provinciaResidenza;
    }

    /**
     * @param string $provinciaResidenza
     */
    public function setProvinciaResidenza(string $provinciaResidenza): void
    {
        $this->provinciaResidenza = $provinciaResidenza;
    }

    /**
     * @return string
     */
    public function getNomeLaboratorio(): string
    {
        return $this->nomeLaboratorio;
    }

    /**
     * @param string $nomeLaboratorio
     */
    public function setNomeLaboratorio(string $nomeLaboratorio): void
    {
        $this->nomeLaboratorio = $nomeLaboratorio;
    }

    /**
     * @return string
     */
    public function getProvinciaLaboratorio(): string
    {
        return $this->provinciaLaboratorio;
    }

    /**
     * @param string $provinciaLaboratorio
     */
    public function setProvinciaLaboratorio(string $provinciaLaboratorio): void
    {
        $this->provinciaLaboratorio = $provinciaLaboratorio;
    }


}
