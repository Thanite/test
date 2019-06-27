<?php

class Quote
{
    public $id;
    public $siteId;
    public $destinationId;
    public $dateQuoted;

    public function __construct($id, $siteId, $destinationId, $dateQuoted)
    {
        $this->id = $id;
        $this->siteId = $siteId;
        $this->destinationId = $destinationId;
        $this->dateQuoted = $dateQuoted;
    }

    public static function renderHtml(Quote $quote)
    {
        return '<p>' . $quote->id . '</p>';
    }

    public static function renderText(Quote $quote)
    {
        return (string) $quote->id;
    }

    public function destinationLink()
    {
        $siteRepository = new SiteRepository();
        return $siteRepository->getById(
            $this->siteId
        )->url;
    }

    public function destinationName()
    {
        $destinationRepository = new destinationRepository();
        return $destinationRepository->getById(
            $this->destinationId
        )->countryName;
    }

    public function summary($id)
    {
        return Quote::renderText($this->getById($this->id));
    }

    public function summaryHtml($id)
    {
        return Quote::renderText($this->getById($this->id));
    }
}