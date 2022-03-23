<?php

declare(strict_types=1);

namespace App\SharedKernel\Tasks;

final class RunMigrationTask extends \Phalcon\Cli\Task
{
    public function mainAction()
    {
        $migrationsDirectory = __DIR__ . '/../../../migrations';
        $this->createMigrationsTableIfNotExist();

        $candidates = array_map(
            static function (string $file): int {
                return (int) explode('.', $file)[0];
            },
            array_diff(scandir($migrationsDirectory), ['..', '.'])
        );

        sort($candidates);

        $last = $this->getLastExecuted();
        $counter = 0;

        foreach ($candidates as $candidate) {
            if ($candidate > $last) {
                $filename = $migrationsDirectory . '/' . $candidate . '.sql';
                $this->db->execute(file_get_contents($filename));
                $this->setLastExecuted($candidate);
                $counter++;
            }
        }

        echo sprintf('Migrated %d', $counter);
        echo PHP_EOL;
    }

    private function createMigrationsTableIfNotExist()
    {
        $this->db->execute('CREATE TABLE IF NOT EXISTS migrations (version BIGINT NOT NULL, PRIMARY KEY(version))');
    }

    private function getLastExecuted(): int
    {
        return (int) $this->db->fetchColumn('SELECT version FROM migrations ORDER BY version DESC LIMIT 1');
    }

    private function setLastExecuted(int $version): void
    {
        $this->db->execute(sprintf('INSERT INTO migrations VALUES (%d)', $version));
    }
}
