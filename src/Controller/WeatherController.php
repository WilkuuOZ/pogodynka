<?php



namespace App\Controller;

use App\Entity\Location;
use App\Repository\LocationRepository;
use App\Repository\MeasurementRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class WeatherController extends AbstractController
{
    #[Route('/weather/{city}/{countryCode?}', name: 'app_weather')]
    public function cityWeather(string $city, ?string $countryCode, MeasurementRepository $repository, LocationRepository $locationRepository): Response
    {

        $location = $locationRepository->findOneBy(['city' => $city, 'country' => $countryCode]);

        if (!$location) {
            throw $this->createNotFoundException('No location found for city: ' . $city . (isset($countryCode) ? ' in country: ' . $countryCode : ''));
        }


        $measurements = $repository->findBy(['location' => $location]);


        return $this->render('weather/city.html.twig', [
            'location' => $location,
            'measurements' => $measurements,
        ]);
    }
}

