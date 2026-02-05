<?php

namespace App\Services;

use GuzzleHttp\Client;

class GeocodingService
{
    private Client $client;
    private ?string $lastStatus = null;
    private ?string $lastErrorMessage = null;

    public function __construct(?Client $client = null)
    {
        $this->client = $client ?? new Client([
            'timeout' => 15,
            'headers' => [
                'User-Agent' => 'LaravelGeocoder/1.0 (student-project)',
            ],
        ]);
    }

    public function geocode(string $query): ?array
    {
        try {
            $key = (string) env('GOOGLE_GEOCODING_KEY', '');
            if ($key === '') {
                $this->lastStatus = 'MISSING_KEY';
                $this->lastErrorMessage = 'GOOGLE_GEOCODING_KEY is empty';
                return null;
            }

            $response = $this->client->get('https://maps.googleapis.com/maps/api/geocode/json', [
                'query' => [
                    'address' => $query,
                    'key' => $key,
                ],
            ]);

            $body = $response->getBody();
            $body->rewind();
            $json = $body->getContents();
            $data = json_decode($json, true);

            if (!is_array($data) || $data === []) {
                $this->lastStatus = 'EMPTY_RESPONSE';
                $this->lastErrorMessage = 'Empty or invalid JSON';
                return null;
            }

            $status = $data['status'] ?? null;
            if ($status !== 'OK') {
                $this->lastStatus = is_string($status) ? $status : 'UNKNOWN_STATUS';
                $this->lastErrorMessage = is_string($data['error_message'] ?? null) ? $data['error_message'] : null;
                return null;
            }

            $item = $data['results'][0] ?? null;
            $location = $item['geometry']['location'] ?? null;

            if (!is_array($location) || !isset($location['lat'], $location['lng'])) {
                $this->lastStatus = 'MISSING_LOCATION';
                $this->lastErrorMessage = 'Missing geometry.location';
                return null;
            }

            $this->lastStatus = 'OK';
            $this->lastErrorMessage = null;

            return [
                'lat' => (float) $location['lat'],
                'lng' => (float) $location['lng'],
            ];
        } catch (\Throwable) {
            $this->lastStatus = 'EXCEPTION';
            $this->lastErrorMessage = null;
            return null;
        }
    }

    public function lastStatus(): ?string
    {
        return $this->lastStatus;
    }

    public function lastErrorMessage(): ?string
    {
        return $this->lastErrorMessage;
    }
}
