<?php
namespace IGdl;
/**
 * 
 */
class Downloadable extends \IGdl\DownloadAbstract
{
	/** 
	 * Constructor.
	 *
	 * @param string $sUrl
     */
	function __construct($sUrl)
	{
		$this->setUrl($sUrl);
		$this->sPath = "downloads";
	}

	/** 
     * @return \IGdl\DownloadAbstract
     */
	public function getUrl()
    {
        return $this->sUrl;
    }

    /** 
     * @return \IGdl\DownloadAbstract
     */
	public function getFileName()
    {
        return $this->sFileName;
    }

    /** 
     * @return \IGdl\DownloadAbstract
     */
	public function getFileType()
    {
        return $this->sFileType;
    }

    /** 
     * @return \IGdl\DownloadAbstract
     */
	public function getDownloadUrl()
    {
        return $this->sDownloadUrl;
    }

    /** 
     * @return void
     */
    public function download() {
    	$path = implode(DIRECTORY_SEPARATOR, [__DIR__, '..', $this->sPath, $this->getFileName()]);
    	if (!file_exists(dirname($path))) {
    		mkdir(dirname($path), 0777, true);
    	}
    	$resource = fopen($path, 'w');
    	return $this->getClient()->request('GET', $this->getDownloadUrl(), [
    		'headers' => [
		        'Content-Type' => 'application/jpeg'
		    ],
    		'sink' => $resource
    	]);
    }

    /** 
     * @return void
     */
    public function getDownloadables() {
    	try {
    		$oRes = $this->getClient()->request('GET', $this->sUrl, ['headers' => [
	    		'accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8',
	    		'origin' => 'https://www.instagram.com'
	    	]]);
    		$status_code = $oRes->getStatusCode();
	    	$sBody = (string) $oRes->getBody();
	    	$html = $this->getDomParser()->load($sBody);
	    	$title = $html->find('title')[0]->text;
	    	if($video = $html->find("meta[property=og:video]")[0]) {
	    		$video = $video->getAttribute('content');
	    	} else {
	    		$photo = $html->find("meta[property=og:image]")[0]->getAttribute('content');
	    		$video = null;
	    	}
	    	if(!is_null($video)) {
	    		$is_type = 'Video';
	    		$dl_url = $video.'?dl=1';
	    		$filename = explode('/', $video);
	    	} else {
	    		$is_type = "Image";
	    		$dl_url = $photo.'?dl=1';
	    		$filename = explode('/', $photo);
	    	}
	    	$this->setFileName(end($filename));
	    	$this->setDownloadUrl($dl_url);
	    	$this->setFileType($is_type);
	    	return $this;
    	} catch (\GuzzleHttp\Exception\RequestException $e) {
    		return (object) ['error' => true, 'msg' => $e->getCode()];
    	}
	}
}