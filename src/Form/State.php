<?php

namespace App\Form;


use App\Enum\StateWhen;
use DateTime;

class State implements \JsonSerializable
{
    protected StateWhen $when;

    protected ?DateTime $dt = null;

    protected ?DateTime $tm = null;

    /**
     * @return StateWhen
     */
    public function getWhen(): StateWhen
    {
        return $this->when;
    }

    /**
     * @param StateWhen $when
     */
    public function setWhen(StateWhen $when): void
    {
        $this->when = $when;
    }

    /**
     * @return DateTime|null
     */
    public function getDt(): ?DateTime
    {
        return $this->dt;
    }

    /**
     * @param DateTime|null $dt
     */
    public function setDt(?DateTime $dt): void
    {
        $this->dt = $dt;
    }

    /**
     * @return DateTime|null
     */
    public function getTm(): ?DateTime
    {
        return $this->tm;
    }

    /**
     * @param DateTime|null $tm
     */
    public function setTm(?DateTime $tm): void
    {
        $this->tm = $tm;
    }


    public function jsonSerialize(): mixed
    {
        return json_encode([
           $this->tm,
           $this->dt,
           $this->when
        ]);
    }
}