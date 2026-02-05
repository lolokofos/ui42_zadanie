<?php

namespace App\Services;

use Symfony\Component\DomCrawler\Crawler;

class CityDetailParser
{
    public function parse(Crawler $crawler): array
    {
        $name = $this->textOrNull($crawler->filter('h1')->first());

        $phone = $this->valueAfterLabel($crawler, 'Tel:');
        $email = $this->valueAfterLabel($crawler, 'Email:');
        $website = $this->valueAfterLabel($crawler, 'Web:');
        $mayorTitle = null;
        $mayor = $this->valueAfterLabel($crawler, 'Starosta:');
        if ($mayor !== null) {
            $mayorTitle = 'starosta';
        } else {
            $mayor = $this->valueAfterLabel($crawler, 'Primátor:')
                ?? $this->valueAfterLabel($crawler, 'Primator:');
            if ($mayor !== null) {
                $mayorTitle = 'primator';
            }
        }
        $fax = $this->valueAfterLabel($crawler, 'Fax:');

        $address = $this->parseAddress($crawler);
        $coatUrl = $this->parseCoatOfArmsUrl($crawler);

        return [
            'name' => $name,
            'mayor_name' => $mayor,
            'mayor_title' => $mayorTitle,
            'address' => $address,
            'phone' => $phone,
            'fax' => $fax,
            'email' => $email,
            'website' => $website,
            'coat_of_arms_url' => $coatUrl,
        ];
    }

    private function parseCoatOfArmsUrl(Crawler $crawler): ?string
    {
        $imgs = $crawler->filter('img');

        for ($i = 0; $i < $imgs->count(); $i++) {
            $img = $imgs->eq($i);

            $src = trim((string) $img->attr('src'));
            if ($src === '') {
                continue;
            }

            $alt = trim((string) $img->attr('alt'));

            $srcLower = strtolower($src);

            if (str_contains($srcLower, '/erb/')) {
                return $src;
            }

            if ($alt !== '' && mb_stripos($alt, 'Erb') === 0) {
                return $src;
            }
        }

        return null;
    }

    private function parseAddress(Crawler $crawler): ?string
    {
        $coatImg = $crawler->filterXPath('//img[contains(@src, "/erb/") or starts-with(@alt, "Erb")]')->first();
        if ($coatImg->count() === 0) {
            return null;
        }

        $coatTr = $coatImg->ancestors()->filter('tr')->first();
        if ($coatTr->count() === 0) {
            return null;
        }

        $trs = $coatTr->nextAll()->filter('tr');
        if ($trs->count() === 0) {
            return null;
        }

        $lines = [];

        for ($i = 0; $i < $trs->count(); $i++) {
            $tr = $trs->eq($i);
            $td = $tr->filter('td')->first();

            if ($td->count() === 0) {
                continue;
            }

            $text = trim(preg_replace('/\s+/u', ' ', $td->text('')));
            if ($text === '') {
                continue;
            }

            $lines[] = $text;

            if (count($lines) === 2) {
                break;
            }
        }

        if (count($lines) === 0) {
            return null;
        }

        if (count($lines) === 1) {
            return $lines[0];
        }

        return $lines[0] . ', ' . $lines[1];
    }

    private function valueAfterLabel(Crawler $crawler, string $label): ?string
    {
        $labelNode = $crawler->filterXPath(sprintf(
            '//*[self::td or self::div][normalize-space()="%s"]',
            $label
        ));

        if ($labelNode->count() === 0) {
            return null;
        }

        $node = $labelNode->first();

        $nextTd = $node->nextAll()->filter('td')->first();
        if ($nextTd->count() > 0) {
            $val = trim(preg_replace('/\s+/u', ' ', $nextTd->text('')));
            return $val !== '' ? $val : null;
        }

        $parentText = $node->ancestors()->first()->text('');
        $parentText = trim(preg_replace('/\s+/u', ' ', $parentText));
        if ($parentText !== '') {
            $parentText = str_replace($label, '', $parentText);
            $parentText = trim($parentText);
            return $parentText !== '' ? $parentText : null;
        }

        return null;
    }

    private function textOrNull(Crawler $node): ?string
    {
        if ($node->count() === 0) {
            return null;
        }

        $t = trim($node->text(''));
        return $t !== '' ? $t : null;
    }
}
