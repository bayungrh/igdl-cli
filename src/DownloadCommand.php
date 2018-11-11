<?php
namespace IGdl;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\ProgressBar;
class DownloadCommand extends \Symfony\Component\Console\Command\Command
{
	const URL = 'url';
    const ERRORS = 'errors';
    protected function configure()
    {
        $this->setName('download')
        ->setDescription("Download an URL resources")
        ->addOption(self::URL, 'u', InputOption::VALUE_OPTIONAL, 'Instagram Post Url');
    }

    /** 
     * @return \Self::download()
     */
	protected function execute(InputInterface $input, OutputInterface $output)
    {
        ini_set('display_errors', $output->isDebug() ? '1' : '0');
        $aUrl = [];
		if ($input->getOption(self::URL)) {
			$sUrl = $input->getOption(self::URL);
			$sUrl = trim($sUrl);
			$downloadable = new Downloadable($sUrl);
			$output->writeln("<comment>Parsing ". $downloadable->getUrl()."</comment>".PHP_EOL);

			$this->download($downloadable, $output);
		}
	}

	/** 
     * @param $oResult
     * @param $output
     * @return \IGdl\Downloadable::getDownloadables()
     */
	private function download($oResult, OutputInterface $output)
    {
		//if ($output->isVerbose()) {
    		$downloadable = $oResult->getDownloadables();
    		if (!empty($downloadable->error)) return $output->writeln("<error>Error: $downloadable->msg</error>");
			$output->writeln("<comment>Type: ".$oResult->getFileType()."</comment>");
			$output->writeln("<comment>Result: ".$oResult->getFileName() ."</comment>");
    		$output->writeln(
                PHP_EOL . "<question>Downloading >> ". $downloadable->getFileName()."</question>"
            );
			$progressBar = new ProgressBar($output, 1);
			$progressBar->start();

            $oResult->download();
    		$progressBar->setFormat('verbose');
    		if($downloadable->getFileName()) $progressBar->finish();
			$output->isVerbose() && $progress->advance();
			// $progress->finish();
			$output->writeln(PHP_EOL.PHP_EOL."<info>Finish!</info>");
        //}
	}
}