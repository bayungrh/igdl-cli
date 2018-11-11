<?php
namespace IGdl;

interface DownloadInterface
{
    /**
     * Defines URL to be parsed
     * @param string $sUrl
     * @return $this
     */
    public function setUrl($sUrl);
    public function setFileName($sFileName);
    public function setFileType($sFileType);
    /**
     * Returns URLs that can be downloaded (indexed by optional file name) for specified URL (with setUrl)
     * @uses self::setUrl
     * @return string[]
     */
    public function getDownloadables();
    /**
     * Returns Guzzle client that will be used to download resources
     * @return \GuzzleHttp\Client
     */
    public function getClient();
}