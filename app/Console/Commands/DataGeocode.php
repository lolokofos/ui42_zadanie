<?php

namespace App\Console\Commands;

use App\Models\City;
use App\Services\GeocodingService;
use Illuminate\Console\Command;

class DataGeocode extends Command
{
    protected $signature = 'data:geocode {--limit= : Limit number of records}';
    protected $description = 'Geocode cities using Google Geocoding API';

    public function handle(): int
    {
        $geocoder = new GeocodingService();

        $total = 0;
        $updated = 0;
        $failed = 0;

        $limit = (int) $this->option('limit');

        $query = City::query()
            ->whereNull('latitude')
            ->whereNull('longitude');

        if ($limit > 0) {
            $query->limit($limit);
        }

        foreach ($query->cursor() as $city) {
            $total++;

            $base = $city->address !== null && $city->address !== ''
                ? $city->address
                : $city->name;

            $q = trim($base . ', Slovakia');

            $result = $geocoder->geocode($q);

            if ($result === null) {
                $status = $geocoder->lastStatus() ?? 'UNKNOWN';
                $error = $geocoder->lastErrorMessage();
                $suffix = $error !== null ? (' | ' . $error) : '';
                $this->warn('Geocode zlyhalo: ' . $city->id . ' | ' . $city->name . ' | ' . $status . $suffix);
                $failed++;
                usleep(200000);
                continue;
            }

            $city->latitude = $result['lat'];
            $city->longitude = $result['lng'];
            $city->save();

            $updated++;
            $this->info('Geocode OK: ' . $city->id . ' | ' . $city->name);

            usleep(200000);
        }

        $this->info('Total: ' . $total);
        $this->info('Updated: ' . $updated);
        $this->info('Failed: ' . $failed);

        return self::SUCCESS;
    }
}
