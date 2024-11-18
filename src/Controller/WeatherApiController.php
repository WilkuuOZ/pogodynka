<?php

namespace App\Controller;

use App\Service\WeatherUtil;
use App\Entity\Measurement;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\Routing\Annotation\Route;

class WeatherApiController extends AbstractController
{
    private WeatherUtil $weatherUtil;

    // Konstruktor wstrzykujący serwis WeatherUtil
    public function __construct(WeatherUtil $weatherUtil)
    {
        $this->weatherUtil = $weatherUtil;
    }

    #[Route('/api/v1/weather', name: 'app_weather_api', methods: ['GET'])]
    public function index(
        #[MapQueryParameter('country')] string $country,
        #[MapQueryParameter('city')] string $city,
        #[MapQueryParameter('format')] ?string $format = 'json',  // Parametr format z domyślną wartością 'json'
        #[MapQueryParameter('twig')] bool $twig = false  // Parametr twig
    ): Response {
        // Pobieramy prognozę pogody dla podanej miejscowości i kraju
        $measurements = $this->weatherUtil->getWeatherForCountryAndCity($country, $city);

        // Mapujemy wyniki na tablicę danych do zwrócenia
        $measurementsData = array_map(fn(Measurement $m) => [
            'date' => $m->getDate()->format('Y-m-d'),
            'celsius' => $m->getCelsius(),
            'fahrenheit' => $m->getFahrenheit(),
        ], $measurements);

        // Sprawdzamy, czy parametr 'twig' jest ustawiony na true
        if ($twig) {
            if ($format === 'csv') {
                // Renderujemy szablon CSV z użyciem TWIG
                return $this->render('weather_api/index.csv.twig', [
                    'city' => $city,
                    'country' => $country,
                    'measurements' => $measurementsData,
                ]);
            }

            // Renderujemy szablon JSON z użyciem TWIG
            return $this->render('weather_api/index.json.twig', [
                'city' => $city,
                'country' => $country,
                'measurements' => $measurementsData,
            ]);
        }

        // Jeśli format to CSV (bez użycia TWIG)
        if ($format === 'csv') {
            $csvData = [];
            $csvData[] = implode(',', ['city', 'country', 'date', 'celsius', 'fahrenheit']);

            foreach ($measurementsData as $data) {
                $csvData[] = implode(',', [
                    $city,
                    $country,
                    $data['date'],
                    $data['celsius'],
                    $data['fahrenheit'],
                ]);
            }

            $csvContent = implode("\n", $csvData);

            return new Response(
                $csvContent,
                Response::HTTP_OK,
                [
                    'Content-Type' => 'text/csv',
                    'Content-Disposition' => 'attachment; filename="weather_data.csv"',
                ]
            );
        }

        // Domyślnie zwracamy JSON (bez użycia TWIG)
        return $this->json([
            'country' => $country,
            'city' => $city,
            'measurements' => $measurementsData,
        ]);
    }
}
