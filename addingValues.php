<?php

namespace App\Command;

use App\Entity\Location;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(
    name: 'app:add-locations',
    description: 'Dodaj przykładowe lokalizacje do bazy danych',
)]
class AddLocationCommand extends Command
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct();
        $this->entityManager = $entityManager;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $locations = [
            ['city' => 'Szczecin', 'country' => 'PL', 'latitude' => '53.4289', 'longitude' => '14.5530'],
            ['city' => 'Police', 'country' => 'PL', 'latitude' => '53.5521', 'longitude' => '14.5718'],
        ];

        foreach ($locations as $locationData) {
            $location = new Location();
            $location->setCity($locationData['city'])
                ->setCountry($locationData['country'])
                ->setLatitude($locationData['latitude'])
                ->setLongitude($locationData['longitude']);

            $this->entityManager->persist($location);
        }

        $this->entityManager->flush();

        $output->writeln('Przykładowe lokalizacje zostały dodane do bazy danych.');

        return Command::SUCCESS;
    }
}
