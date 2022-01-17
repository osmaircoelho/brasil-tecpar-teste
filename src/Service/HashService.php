<?php


namespace App\Service;

use Carbon\Carbon;
use App\Repository\HashRepository;

class HashService
{
    private int $attempts=0, $currentAttempt=0;
    private string $string;

    public function __construct( private HashRepository $hashRepository ) {
        $this->string = "";
    }

    /**
     * @return int
     */
    public function getAttempts(): int
    {
        return $this->attempts;
    }

    /**
     * @param int $attempts
     * @return HashService
     */
    public function setAttempts(int $attempts = 1000000): HashService
    {
        $this->attempts = $attempts;
        return $this;
    }

    /**
     * @return string
     */
    private function getString(): string
    {
        return $this->string;
    }


    public function setString(string $string): self
    {
        $this->string = $string;
        return $this;
    }

    private function generateHash(): array
    {
        try {
            $hashResult = [];
            while ($this->currentAttempt < $this->getAttempts()) {

                $hashKeyEightDigits = $this->HashKeyEightDigits();
                $hashMD5 = md5($this->getString() . $hashKeyEightDigits);

                $hashResult = [
                    'key' => $hashKeyEightDigits,
                    'hash' => $hashMD5
                ];

                if ($this->isCheckHash($hashMD5)) {
                    return $hashResult;
                }
                $this->currentAttempt++;
            }
        } catch (\Exception $e) {
            throw new \Exception(
                "ERROR!! Due to the large number of attempts there was an error generating the hash!"
            );
        }
    }

    public function setRowsHashes(array $hashes): array
    {
        $rows = [];

        foreach ($hashes as $hash) {
            $rows[] = [
                $hash->getBatch()->format('Y-d-m H:i:s'),
                $hash->getId(),
                $hash->getStringInput(),
                $hash->getKeyFound(),
                $hash->getHashGenerated(),
                $hash->getNumberAttempts()
            ];
        }
        return $rows;
    }

    public function createHash(): array
    {
        $calculateHash = $this->generateHash();

        $hashDatas = [
            'attempts' => $this->currentAttempt,
            'batch'    => Carbon::now(),
            'hash'     => $calculateHash['hash'],
            'string'   => $this->getString(),
            'key'      => $calculateHash['key']
        ];

        $hashFound = $this->hashRepository->tryFindHashByInput($this->getString());

        if ( !$hashFound ) {
            $this->hashRepository->save($hashDatas);
        }
        return $hashDatas;
    }

    private function isCheckHash(string $hashMD5): bool
    {
        return (substr($hashMD5, 0, 4) === '0000');
    }

    private function HashKeyEightDigits(): string
    {
        $str = '1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZabcefghijklmnopqrstuvwxyz';
        return substr(str_shuffle($str), 0, 8);
    }
}
