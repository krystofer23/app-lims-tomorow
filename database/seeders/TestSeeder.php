<?php

namespace Database\Seeders;

use App\Models\tenant\Parameters;
use Exception;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class TestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        try {
            $groupParameters = Parameters::query()
                ->where('description', 'REGEXP', 'METALES|Metales|METALS|Metals')
                ->get();

            $groupParameterIds = $groupParameters->pluck('id')->toArray();

            $allParameters = Parameters::query()
                ->whereNotIn('id', $groupParameterIds)
                ->get();

            foreach ($groupParameters as $groupParameter) {
                $description = $groupParameter->description ?? '';

                $metalNames = $this->extractMetalNames($description);

                if ($metalNames->isEmpty()) {
                    continue;
                }

                $ids = [];

                foreach ($metalNames as $metalName) {
                    $normalizedMetalName = $this->normalizeText($metalName);

                    $parameter = $allParameters->first(function ($param) use ($normalizedMetalName) {
                        $description = $this->normalizeText($param->description ?? '');
                        $name = $this->normalizeText($param->name ?? '');

                        return $description === $normalizedMetalName
                            || $name === $normalizedMetalName
                            || str_contains($description, $normalizedMetalName)
                            || str_contains($name, $normalizedMetalName);
                    });

                    if ($parameter) {
                        $ids[] = [
                            'id' => $parameter->id,
                            'description' => $parameter->description,
                        ];
                    }
                }

                if (count($ids) !== 0) {
                    $groupParameter->update([
                        'ids_connections_parameters' => collect($ids)
                            ->pluck('id')
                            ->unique()
                            ->values()
                            ->toArray(),
                        'is_metal' => true,
                    ]);
                }
            }
        } catch (Exception $e) {
            Log::error($e->getMessage());
        }
    }

    private function extractMetalNames(string $description)
    {
        $normalizedDescription = $this->normalizeText($description);

        /**
         * Caso especial:
         * - Metals in Filter Mercury
         */
        if (str_contains($normalizedDescription, 'metals in filter mercury')) {
            return collect(['Mercurio']);
        }

        /**
         * Caso inglés:
         * - Metals Low Volume - (PM10, PM2.5)_Al, As, B, Ba...
         * - Metals High Volume - (PTS, PM10, PM2.5)_Al, As, B, Ba...
         */
        if (
            str_contains($normalizedDescription, 'metals') &&
            str_contains($description, '_')
        ) {
            $text = Str::after($description, '_');

            return collect(explode(',', $text))
                ->map(fn ($symbol) => trim($symbol))
                ->map(fn ($symbol) => rtrim($symbol, '.'))
                ->map(fn ($symbol) => $this->metalSymbolToSpanishName($symbol))
                ->filter()
                ->values();
        }

        /**
         * Caso inglés:
         * Metals: Antimony(Sb), Arsenic(As), Barium(Ba)
         * Metals: Vanadium (V), Iron (Fe), Tin (Sn) and Titanium (Ti)
         */
        if (
            str_contains($normalizedDescription, 'metals') &&
            preg_match_all('/\(([A-Za-z0-9]+)\)/', $description, $matches)
        ) {
            return collect($matches[1])
                ->map(fn ($symbol) => trim($symbol))
                ->map(fn ($symbol) => $this->metalSymbolToSpanishName($symbol))
                ->filter()
                ->values();
        }

        /**
         * Caso español:
         * - Metales Totales: Plata, Aluminio, Arsénico...
         * - Metales Disueltos: Plata, Aluminio, Arsénico...
         * METALES:_Aluminio, Arsénico...
         */
        if (str_contains($description, ':')) {
            $text = Str::after($description, ':');

            $text = trim($text);
            $text = ltrim($text, '_');
            $text = rtrim($text, '.');

            return collect(explode(',', $text))
                ->map(fn ($name) => trim($name))
                ->filter()
                ->values();
        }

        return collect();
    }

    private function metalSymbolToSpanishName(string $symbol): ?string
    {
        $symbol = trim($symbol);
        $symbol = rtrim($symbol, '.');

        $map = [
            'Ag' => 'Plata',
            'Al' => 'Aluminio',
            'As' => 'Arsénico',
            'B' => 'Boro',
            'Ba' => 'Bario',
            'Be' => 'Berilio',
            'Ca' => 'Calcio',
            'Cd' => 'Cadmio',
            'Ce' => 'Cerio',
            'Co' => 'Cobalto',
            'Cr' => 'Cromo',
            'Cu' => 'Cobre',
            'Fe' => 'Hierro',
            'Hg' => 'Mercurio',
            'K' => 'Potasio',
            'Li' => 'Litio',
            'Mg' => 'Magnesio',
            'Mn' => 'Manganeso',
            'Mo' => 'Molibdeno',
            'Na' => 'Sodio',
            'Ni' => 'Níquel',
            'P' => 'Fosforo',
            'Pb' => 'Plomo',
            'Sb' => 'Antimonio',
            'Se' => 'Selenio',
            'SiO2' => 'Sílice',
            'Sn' => 'Estaño',
            'Sr' => 'Estroncio',
            'Th' => 'Torio',
            'Ti' => 'Titanio',
            'Tl' => 'Talio',
            'U' => 'Uranio',
            'V' => 'Vanadio',
            'Zn' => 'Zinc',
        ];

        return $map[$symbol] ?? null;
    }

    private function normalizeText(?string $text): string
    {
        $text = $text ?? '';

        $text = trim($text);
        $text = mb_strtolower($text, 'UTF-8');

        $text = str_replace(
            ['á', 'é', 'í', 'ó', 'ú', 'ñ', 'ü'],
            ['a', 'e', 'i', 'o', 'u', 'n', 'u'],
            $text
        );

        $text = str_replace('sílice', 'silice', $text);
        $text = str_replace('sio₂', 'sio2', $text);
        $text = str_replace('si02', 'sio2', $text);

        $text = preg_replace('/\s+/', ' ', $text);

        return trim($text);
    }
}
