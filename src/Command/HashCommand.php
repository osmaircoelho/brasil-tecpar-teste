<?php

namespace App\Command;

use HttpRequest;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Helper\Table;;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use App\Entity\Hash;
use App\Service\HashService;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Console\Helper\ProgressBar;

#[AsCommand(
    name: 'avato:test',
    description: 'Generate a hash from a string',
)]
class HashCommand extends Command
{

    public function __construct(
        private HttpClientInterface  $httpClient,
        private ManagerRegistry $doctrine,
        private HashService $hashService

    )
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('string', InputArgument::OPTIONAL, 'String to be consulted')
            ->addOption('requests', null, InputOption::VALUE_OPTIONAL, 'number of requests')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $stringHash = (string) $input->getArgument('string');
        $requests   = (int)    $input->getOption('requests');

        $hashRepository = $this->doctrine->getRepository(Hash::class);
        $progressBar = new ProgressBar($output, $requests);

        echo PHP_EOL;

        $progressBar->start();
        $i = 0;
        while ($i++ < $requests) {
            for( $i = 1; $i <= $requests; $i++ ){

                $response =  $this->httpClient->request('POST', $_ENV['APP_URL'].'/hash/create', [
                    'query' => [
                        'string' => $stringHash
                    ]
                ]);

                if( '429' === $response->getStatusCode() ){
                    echo $response->getContent();
                    $progressBar->finish();
                    break;
                }

                $stringHash = $response->toArray()["data"]["hash"];
                $progressBar->advance();
            }
        }
        $progressBar->finish();

        echo PHP_EOL;
        echo PHP_EOL;

        $hashes = $hashRepository->findAll();
        $table = new Table($output);
        $table->setHeaders(['Batch', 'Num. bloco', 'String entrada', 'Chave encontrada', 'Hash gerado', 'Tentativas']);
        $table->setRows($this->hashService->setRowsHashes($hashes));
        $table->render();
        $io->success('Congrats Hashes generated successfully \(^o^)/*\(^o^)/');
        return Command::SUCCESS;
    }
}
