<?php

namespace App\Console\Commands;

use App\Models\City;
use App\Services\CityDetailParser;
use DOMDocument;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Uri;
use GuzzleHttp\Psr7\UriResolver;
use Illuminate\Console\Command;
use Symfony\Component\DomCrawler\Crawler;

class DataImport extends Command
{
    protected $signature = 'data:import {--url= : URL okresu alebo zoznamu obci}';
    protected $description = 'Import obci z e-obce.sk';

    public function handle(): int
    {
        libxml_use_internal_errors(true);

        $url = (string) $this->option('url');
        if ($url === '') {
            $url = 'https://www.e-obce.sk/kraj/NR.html';
        }

        $client = new Client([
            'timeout' => 20,
            'headers' => [
                'User-Agent' => 'Mozilla/5.0 (compatible; LaravelImport/1.0)',
            ],
        ]);

        $districtUrls = [];

        if (str_contains($url, '/kraj/NR.html')) {
            try {
                $krajHtml = $this->fetchHtml($client, $url);
            } catch (\Throwable $e) {
                $this->error('Nepodarilo sa stiahnut kraj: ' . $e->getMessage());
                return self::FAILURE;
            }

            $krajCrawler = $this->crawlerFromHtml($krajHtml, $url);
            $krajLinks = $this->extractAbsoluteLinks($krajCrawler, $url);

            $districtUrls = array_values(array_filter($krajLinks, function ($link) {
                return is_string($link)
                    && preg_match('#^https://www\.e-obce\.sk/okres/[a-z0-9\-_]+\.html$#i', $link);
            }));

            $this->info('Najdene okresy: ' . count($districtUrls));

            if ($districtUrls === []) {
                $this->error('Nenasiel som ziadne pouzitelne okres URL na stranke.');
                return self::FAILURE;
            }
        } else {
            $districtUrls = [$url];
        }

        $parser = new CityDetailParser();

        foreach ($districtUrls as $districtUrl) {
            $districtSlug = pathinfo(parse_url($districtUrl, PHP_URL_PATH) ?? '', PATHINFO_FILENAME);
            $districtName = $districtSlug !== '' ? str_replace(['_', '-'], ' ', $districtSlug) : $districtUrl;

            try {
                $html = $this->fetchHtml($client, $districtUrl);
            } catch (\Throwable $e) {
                $this->error('Okres zlyhal: ' . $districtName . ' | ' . $e->getMessage());
                continue;
            }

            $crawler = $this->crawlerFromHtml($html, $districtUrl);
            $links = $this->extractAbsoluteLinks($crawler, $districtUrl);

            $cityLinks = array_values(array_filter($links, function ($link) {
                if (!is_string($link) || $link === '') {
                    return false;
                }

                if ($link === 'https://www.e-obce.sk/obec/lukacovce_nitra/lukacovce.html') {
                    return true;
                }

                return (bool) preg_match(
                    '#^https://www\.e-obce\.sk/obec/[a-z0-9\-]+/[a-z0-9\-]+\.html$#i',
                    $link
                );
            }));

            $this->info('Okres ' . $districtName . ' | obce: ' . count($cityLinks));

            if ($cityLinks === []) {
                $this->error('Okres bez obci: ' . $districtName);
                continue;
            }

            foreach ($cityLinks as $detailUrl) {
                try {
                    $detailHtml = $this->fetchHtml($client, $detailUrl);
                    $detailCrawler = $this->crawlerFromHtml($detailHtml, $detailUrl);

                    $data = $parser->parse($detailCrawler);

                    $coatPath = null;
                    if (!empty($data['coat_of_arms_url'])) {
                        $coatPath = $this->downloadCoat($client, $data['coat_of_arms_url']);
                    }

                    City::updateOrCreate(
                        ['source_url' => $detailUrl],
                        [
                            'name' => $data['name'],
                            'mayor_name' => $data['mayor_name'],
                            'mayor_title' => $data['mayor_title'],
                            'address' => $data['address'],
                            'phone' => $data['phone'],
                            'fax' => $data['fax'],
                            'email' => $data['email'],
                            'website' => $data['website'],
                            'coat_of_arms_path' => $coatPath,
                        ]
                    );

                    $this->info('Ulozene do DB: ' . ($data['name'] ?? 'Neznama obec'));
                } catch (\Throwable $e) {
                    $this->error('Obec zlyhala: ' . $detailUrl . ' | ' . $e->getMessage());
                    continue;
                }
            }
        }

        return self::SUCCESS;
    }

    private function fetchHtml(Client $client, string $url): string
    {
        $response = $client->get($url);
        $body = $response->getBody();
        $body->rewind();
        return $body->getContents();
    }

    private function crawlerFromHtml(string $html, string $baseUrl): Crawler
    {
        $html = $this->normalizeToUtf8($html);

        $html = mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8');

        $dom = new DOMDocument();

        $wrapped = '<?xml encoding="UTF-8">' . $html;

        $dom->loadHTML($wrapped, LIBXML_NOWARNING | LIBXML_NOERROR);

        return new Crawler($dom, $baseUrl);
    }

    private function normalizeToUtf8(string $html): string
    {
        if (preg_match('//u', $html) === 1) {
            return $html;
        }
        foreach (['Windows-1250', 'ISO-8859-2'] as $from) {
            $converted = @iconv($from, 'UTF-8//IGNORE', $html);
            if ($converted !== false && preg_match('//u', $converted) === 1) {
                return $converted;
            }
        }

        return $html;
    }

    private function extractAbsoluteLinks(Crawler $crawler, string $baseUrl): array
    {
        $baseUri = new Uri($baseUrl);

        $links = $crawler->filter('a')->each(function (Crawler $node) use ($baseUri) {
            $href = $node->attr('href');
            if (!$href) {
                return null;
            }

            $abs = UriResolver::resolve($baseUri, new Uri($href));
            return (string) $abs;
        });

        $links = array_values(array_filter(array_unique($links)));

        return $links;
    }

    private function downloadCoat(Client $client, string $imageUrl): ?string
    {
        try {
            $response = $client->get($imageUrl);
            $body = $response->getBody();
            $body->rewind();
            $bytes = $body->getContents();
        } catch (\Throwable) {
            return null;
        }

        if ($bytes === '') {
            return null;
        }

        $dir = public_path('coats');
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }

        $ext = pathinfo(parse_url($imageUrl, PHP_URL_PATH) ?? '', PATHINFO_EXTENSION);
        $ext = $ext !== '' ? $ext : 'png';

        $file = sha1($imageUrl) . '.' . $ext;
        file_put_contents($dir . DIRECTORY_SEPARATOR . $file, $bytes);

        return 'coats/' . $file;
    }
}
