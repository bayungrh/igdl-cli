<?php
namespace IGdl;

abstract class DownloadAbstract implements DownloadInterface
{
    const USER_AGENT = 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/64.0.3282.167 Safari/537.36';
    private $client;
    private $domParser;
    protected $sUrl;
    protected $sFileName;
    protected $sFileType;
    protected $sDownloadUrl;
    public function getClient($aOptions = [])
    {
        return $this->client = $this->client ?: new \GuzzleHttp\Client($aOptions);
    }
    protected function getDomParser()
    {
        return $this->domParser = $this->domParser ?: new \PHPHtmlParser\Dom;
    }
    public function setUrl($sUrl)
    {
        $this->sUrl = (string)$sUrl;
        return $this;
    }
    public function setFileName($sFileName)
    {
        $this->sFileName = (string)$sFileName;
        return $this;
    }
    public function setFileType($sFileType)
    {
        $this->sFileType = (string)$sFileType;
        return $this;
    }
    public function setDownloadUrl($sDownloadUrl)
    {
        $this->sDownloadUrl = (string)$sDownloadUrl;
        return $this;
    }
}