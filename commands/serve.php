<?php

	namespace Commands;

	use App\Console\Command;

	class Serve extends Command
	{
		protected string $signature = 'serve';
		protected string $description = 'Serve the application out of maintenance mode';

		public function handle($className = ''): void
		{
			$this->info('Starting the application server...');

			$port = 8000;
			$host = 'localhost';

			while (!$this->isPortAvailable($host, $port))
				$port++;

			$this->success("Server running at http://{$host}:{$port}");

			$root = $this->findProjectRoot() ."/public";
			passthru("php -S {$host}:{$port} -t {$root}");
		}

		private function isPortAvailable(string $host, int $port): bool
		{
			$connection = @fsockopen($host, $port);
			if (is_resource($connection)) {
				fclose($connection);
				return false;
			}
			return true;
		}

		private function findProjectRoot(): string
		{
			$dir = __DIR__;

			while (!file_exists($dir . '/vendor') && dirname($dir) !== $dir) {
				$dir = dirname($dir);
			}

			return $dir;
		}
	}
