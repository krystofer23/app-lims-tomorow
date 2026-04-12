<?php

namespace App\Imports;

use App\Models\tenant\Essays;
use App\Models\tenant\ItemsMatriz;
use App\Models\tenant\Matriz;
use App\Models\tenant\Methodologies;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToCollection;

class ImportMatriz implements ToCollection
{
    public function collection(Collection $rows)
    {
        $matriz = [
            "Suelo" => [
                "essays" => [
                    "PCB 28-2,2',3,4,4',5'-Hexachlorobiphenyl",
                    "PCB 52-2,2',3,4,4',5,5'-Heptachlorobiphenyl",
                    "PCB 101-2,2',4,4',5,5'-Hexachlorobiphenyl",
                    "PCB 118-2,2',5,5'-Tetrachlorobiphenyl",
                    "PCB 138-2,2',5-Trichlorobiphenyl",
                    "PCB 153-2,3',4,4',5-Pentachlorobiphenyl",
                    "PCB 180-2,4,4'-Trichlorobiphenyl",
                ],
                "methodology" => "Semi-volatile Organic Compounds 
                    by Gas Chromatography/Mass
                    Spectrometry // Ultrasonic
                    Extraction.
                    EPA Method 8270E Rev 6 Jun
                    2018 // EPA Method 3550C Rev 3
                    February 2007",
                "unit_price" => 250,
                "other_company" => "ENVIROTEST"
            ],
            "Suelo" => [
                "essays" => [
                    "Benzene,",
                    "Toluene,",
                    "Ethylbenzene,",
                    "Xileno,",
                    "Naphtalene,"
                ],
                "methodology" => "EPA Method 8260D Rev 4 June 2018. // EPA Method 5021A Rev 2 July 2014
                    Volatile Organic Compounds by Gas Chromatography/ Mass Spectrometry (GC/MS). // Volatile Organic Compounds in Various Sample Matrices using Equilibrium Headspace Analysis",
                "unit_price" => 250,
            ],
            "Suelo" => [
                "essays" => [
                    "2-Chlorobiphenyl; ",
                    "2,3-Dichlorobiphenyl; ",
                    "2,2,5'-Trichlorobiphenyl; ",
                    "2,4',5 -Trichlorobiphenyl; ",
                    "2,2',3,5'-Tetrachlorobiphenyl; ",
                    "2,2',5,5'-Tetrachlorobiphenyl; ",
                    "2,3',4,4'-Tetrachlorobiphenyl; ",
                    "2,2',3,4,5-Pentachlorobiphenyl;",
                    "2,2',4,5,5'-Pentachlorobiphenyl;",
                    "2,3,3',4',6-Pentachlorobiphenyl;",
                    "2,2',3,4,4',5'-Hexachlorobiphenyl;",
                    "2,2',3,4,5,5'-Hexachlorobiphenyl;",
                    "2,2',3,5,5',6-Hexachlorobiphenyl; ",
                    "2,2',4,4',5,5'-Hexachlorobiphenyl; ",
                    "2,2',3,3',4,4',5-Heptachlorobiphenyl; ",
                    "2,2',3,4,4',5,5'-Heptachlorobiphenyl; ",
                    "2,2',3,4,4',5',6-Heptachlorobiphenyl;",
                    "2,2',3,4',5,5',6-Heptachlorobiphenyl; ",
                    "2,2',3,3',4,4',5,5',6-Nonachlorobiphenyl; ",
                    "2,4,4-Trichlorobiphenyl",
                    "2,3,4,4,5-Pentachlorobiphenyl",
                    "Total PCB's.",
                ],
                "methodology" =>  "EPA Method 8270E Rev 6 Jun 2018 // EPA Method 3550C Rev 3 February 2007
                    Semi-volatile Organic Compounds by Gas Chromatography/Mass Spectrometry // Ultrasonic Extraction",
                "unit_price" => 250,
            ],
            "Suelo" => [
                "essays" => [
                    "Aldrin",
                    "Endrin",
                    "4,4'-DDD;",
                    "Heptachloro",
                ],
                "methodology" => "EPA Method 8270E Rev 6 Jun 2018. // EPA Method 3550C Rev 3 February 2007
                    Semi-volatile Organic Compounds by Gas Chromatography/Mass Spectrometry // Ultrasonic Extraction",
                "unit_price" => 250,
            ],
            "EPA Method 8270E Rev 6 Jun 2018. // EPA Method 3550C Rev 3 February 2007
                Semi-volatile Organic Compounds by Gas Chromatography/Mass Spectrometry // Ultrasonic Extraction" => [
                "essays" => [
                    "- Monóxido de Carbono (CO).",
                    "- Monóxido de Nitrógeno (NO).",
                    "- Dioxido de Nitrogeno (NO2).",
                    "- Oxígeno (O2).",
                    "- Oxido de Nitrógeno (NOx).",
                ],
                "methodology" => "Determination of Nitrogen Oxides, Carbon Monoxide, and Oxygen Emissions from Natural Gas-Fired Engines, Boilers and Process Heaters Using Portable Analyzers  CTM 034 (1997)",
                "unit_price" => 350,
            ],
            "Metales totales en emisiones gaseosas" => [
                "essays" => [
                    "- Antimony (Sb) ²",
                    "- Arsenic (As) ²",
                    "- Barium (Ba) ²",
                    "- Beryllium (Be) ²",
                    "- Cadmium (Cd) ²",
                    "- Chromium (Cr) ²",
                    "- Cobalt (Co) ²",
                    "- Copper (Cu) ²",
                    "- Lead (Pb) ²",
                    "- Manganese (Mn) ²",
                    "- Mercury (Hg) ²",
                    "- Nickel (Ni) ²",
                    "- Phosphorus (P) ²",
                    "- Selenium (Se) ²",
                    "- Silver (Ag) ²",
                    "- Thallium (Ti) ²",
                    "- Zinc (Zn) ²",
                ],
                "methodology" => "NTP 712.110:2022
                    MONITOREO DE EMISIONES ATMOSFÉRICAS. 
                    Determination of Metals Emissions from Stationary Sources. 1ª Edition
                    EPA Method 29 (7-1-23)
                    Determination of Metals Emissions from Stationary Sources - by Inductively Coupled Plasma-Atomic Emission Spectrometry",
                "unit_price" => 500
            ],
            "Metales totales en emisiones gaseosas" => [
                "essays" => [
                    "- Vanadium (V) ²",
                    "- Iron (Fe) ²",
                    "- Tin (Sn) ²",
                    "- Titanium (Ti) ²",
                ],
                "methodology" =>
                "NTP 712.110:2022
                    (Validated Method out of scope)
                    MONITOREO DE EMISIONES ATMOSFÉRICAS. Determination of Metals Emissions from Stationary Sources. 1ª Edition
                    EPA Method 29 (7-1-23)
                    (Validated Method out of scope)
                    Determination of Metals Emissions from Stationary Sources - by Inductively Coupled Plasma-Atomic Emission Spectrometry",
                "unit_price" => 500,
            ],
            "Equipo:Isocinetico pequeño" => [
                "essays" => [
                    "- Benzene ²",
                    "- Trichloroethene ²",
                    "- Toluene ²",
                    "- Tetrachloroethene ²",
                    "- Chlorobenzene ²",
                    "- Ethylbenzene ²",
                    "- m-Xylene ²",
                    "- P-Xylene ²",
                    "- o-Xylene ²",
                    "- m,p-Xylene ²",
                    "- Styrene ²",
                    "- Isopropylbenzene ²",
                    "- Bromobenzene ²",
                    "- n-Propylbenzene ²",
                    "- 2- Chlorotoluene ²",
                    "- 4-Chlorotoluene ²",
                    "- 1,3,5- Trimethylbenzene ²",
                    "- Tert- Butylbenzene ²",
                    "- 1,2,4-Trimethylbenzene ²",
                    "- Sec-Butylbenzene ²",
                    "- 1,3- Dichlorobenzene ²",
                    "- 1,4- Dichlorobenzene ²",
                    "- p- Isopropyltoluene ²",
                    "- 1,2-Dichlorobenzene ²",
                    "- nButhylbenzene ²",
                    "- 1,2,4- Trichlorobenzene ²",
                    "- Naphthalene ²",
                    "- 1,2,3-Trichlorobenzene ²",
                ],
                "methodology" => "EPA Method 18 (7-1-23)
                    Measurement of Gaseous Organic Compound Emissions by Gas Chromatography
                    NTP 900.018:2021 
                    ATMOSPHERIC EMISSIONS MONITORING.Measurement of Gaseous Organic Compound Emissions by Gas Chromatography",
                "unit_price" => 600,
            ],
            "Es un método de la EPA (Environmental Protection Agency) utilizado para calcular el peso molecular seco (dry molecular weight) de una muestra de gas, generalmente en emisiones de fuentes estacionarias como chimeneas o ductos industriales." => [
                "essays" => [
                    "- Carbon Monoxide (CO) ²",
                    "- Oxygen (O2) ²",
                    "- Carbon Dioxide (CO2) ²",
                ],
                "methodology" => "METHOD 3 (7-1-23)
                    GAS ANALYSIS FOR THE DETERMINATION OF DRY MOLECULAR WEIGHT",
                "unit_price" => 500,
            ],
            "Todo tipo de combustible equipo:testo" => [
                "essays" => [
                    "- Nitrogen Oxides (NOx) ²",
                    "- Nitric Oxide (NO) ²",
                    "- Nitrogen Dioxide (NO2) ²",
                ],
                "methodology" => "EPA CTM-022
                        Determination of Nitric Oxide, Nitrogen Dioxide
                        and NOx Emissions from Stationary Combustion Sources by electrochemical analyzer. 1995",
                "unit_price" => 350,
            ],
            "EPA CTM-022
            Determination of Nitric Oxide, Nitrogen Dioxide
            and NOx Emissions from Stationary Combustion Sources by electrochemical analyzer. 1995" => [
                "essays" => [
                    "- Nitrogen Oxides (NOx) ²",
                    "- Nitric Oxide (NO) ²",
                    "- Nitrogen Dioxide (NO2) ²",
                ],
                "methodology" => "EPA CTM-030
                    Determination of Nitrogen Oxides, Carbon Monoxide, and Oxygen Emissions from Natural Gas-Fired Engines, Boilers and Process Heaters Using Portable Analyzers. 1997",
                "unit_price" => 350,
            ],
            "tipo de combustible: solo gas natural equipo:testo" => [
                "essays" => [
                    "Sulfuro de hidrógeno: H2S",
                    "Hidrocarburos Totales:HT",
                    "Dióxido de carbono:CO2",
                ],
                "methodology" => "CTM 022
                    (Validated Method out of scope)
                    Nitric Oxide, Nitrogen Dioxide, & NOx emissions by Electrochemical Analyzer",
                "unit_price" => 350,
            ],
            "tipo de combustible: solo gas natural equipo:testo" => [
                "essays" => [
                    "Sulfuro de hidrógeno: H2S",
                    "Hidrocarburos Totales:HT",
                    "Dióxido de carbono:CO2",
                ],
                "methodology" => "CTM 030
                    (Validated Method out of scope)
                    Determination of Nitrogen Oxides, Carbon Monoxide, and Oxygen Emissions from Natural Gas-Fired Engines, Boilers and Process Heaters Using Portable Analyzers",
                "unit_price" => 350,
            ],
            "tipo de combustible: solo gas natural(propano,butano y combustibles líquidos) equipo:testo" => [
                "essays" => [
                    "Sulfuro de hidrógeno: H2S",
                    "Hidrocarburos Totales:HT",
                    "Dióxido de carbono:CO2",
                ],
                "methodology" => "CTM-034 
                    (Validated Method out of scope)
                    Test Method -Determination of Oxygen, Carbon Monoxide and Oxides of Nitrogen For Periodic Monitoring. Determination of
                    Nitrogen Oxides, Carbon Monoxide, and Oxygen Emissions from Natural Gas Fired Engines, Boilers and Process Heaters Using
                    Portable Analyzers",
                "unit_price" => 350,
            ],
            "Emisiones Gas Natural y disel (butano y propano)" => [
                "essays" => [
                    "- Monóxido de Carbono (CO).",
                    "- Monóxido de Nitrógeno (NO).",
                    "- Dioxido de Nitrogeno (NO2).",
                    "- Oxígeno (O2).",
                    "- Oxido de Nitrógeno (NOx).",
                ],
                "methodology" => "Determination of Nitrogen Oxides, Carbon Monoxide, and Oxygen Emissions from Natural Gas-Fired Engines, Boilers and Process Heaters Using Portable Analyzers  CTM 034 (1997)",
                "unit_price" => 350,
            ],
            "Metales totales en emisiones gaseosas" => [
                "essays" => [
                    "- Antimony (Sb) ²",
                    "- Arsenic (As) ²",
                    "- Barium (Ba) ²",
                    "- Beryllium (Be) ²",
                    "- Cadmium (Cd) ²",
                    "- Chromium (Cr) ²",
                    "- Cobalt (Co) ²",
                    "- Copper (Cu) ²",
                    "- Lead (Pb) ²",
                    "- Manganese (Mn) ²",
                    "- Mercury (Hg) ²",
                    "- Nickel (Ni) ²",
                    "- Phosphorus (P) ²",
                    "- Selenium (Se) ²",
                    "- Silver (Ag) ²",
                    "- Thallium (Ti) ²",
                    "- Zinc (Zn) ²",
                ],
                "methodology" => "NTP 712.110:2022
                    MONITOREO DE EMISIONES ATMOSFÉRICAS. 
                    Determination of Metals Emissions from Stationary Sources. 1ª Edition
                    EPA Method 29 (7-1-23)
                    Determination of Metals Emissions from Stationary Sources - by Inductively Coupled Plasma-Atomic Emission Spectrometry",
                "unit_price" => 500,
            ],
            "Metales totales en emisiones gaseosas" => [
                "essays" => [
                    "- Vanadium (V) ²",
                    "- Iron (Fe) ²",
                    "- Tin (Sn) ²",
                    "- Titanium (Ti) ²",
                ],
                "methodology" => "NTP 712.110:2022
                    (Validated Method out of scope)
                    MONITOREO DE EMISIONES ATMOSFÉRICAS. Determination of Metals Emissions from Stationary Sources. 1ª Edition
                    EPA Method 29 (7-1-23)
                    (Validated Method out of scope)
                    Determination of Metals Emissions from Stationary Sources - by Inductively Coupled Plasma-Atomic Emission Spectrometry",
                "unit_price" => 500,
            ],
            "VOCS Equipo:Isocinetico pequeño" => [
                "essays" => [
                    "- Benzene ²",
                    "- Trichloroethene ²",
                    "- Toluene ²",
                    "- Tetrachloroethene ²",
                    "- Chlorobenzene ²",
                    "- Ethylbenzene ²",
                    "- m-Xylene ²",
                    "- P-Xylene ²",
                    "- o-Xylene ²",
                    "- m,p-Xylene ²",
                    "- Styrene ²",
                    "- Isopropylbenzene ²",
                    "- Bromobenzene ²",
                    "- n-Propylbenzene ²",
                    "- 2- Chlorotoluene ²",
                    "- 4-Chlorotoluene ²",
                    "- 1,3,5- Trimethylbenzene ²",
                    "- Tert- Butylbenzene ²",
                    "- 1,2,4-Trimethylbenzene ²",
                    "- Sec-Butylbenzene ²",
                    "- 1,3- Dichlorobenzene ²",
                    "- 1,4- Dichlorobenzene ²",
                    "- p- Isopropyltoluene ²",
                    "- 1,2-Dichlorobenzene ²",
                    "- nButhylbenzene ²",
                    "- 1,2,4- Trichlorobenzene ²",
                    "- Naphthalene ²",
                    "- 1,2,3-Trichlorobenzene ²",
                ],
                "methodology" => "EPA Method 18 (7-1-23)
                    Measurement of Gaseous Organic Compound Emissions by Gas Chromatography
                    NTP 900.018:2021 
                    ATMOSPHERIC EMISSIONS MONITORING.Measurement of Gaseous Organic Compound Emissions by Gas Chromatography",
                "unit_price" => 600,
            ],
            "Es un método de la EPA (Environmental Protection Agency) utilizado para calcular el peso molecular seco (dry molecular weight) de una muestra de gas, generalmente en emisiones de fuentes estacionarias como chimeneas o ductos industriales." => [
                "essays" => [
                    "- Carbon Monoxide (CO) ²",
                    "- Oxygen (O2) ²",
                    "- Carbon Dioxide (CO2) ²",
                ],
                "methodology" => "METHOD 3 (7-1-23)
                    GAS ANALYSIS FOR THE DETERMINATION OF DRY MOLECULAR WEIGHT",
                "unit_price" => 500,
            ],
            "todo tipo de combustible equipo:testo" => [
                "essays" => [
                    "- Nitrogen Oxides (NOx) ²",
                    "- Nitric Oxide (NO) ²",
                    "- Nitrogen Dioxide (NO2) ²",
                ],
                "methodology" => "EPA CTM-022
                    Determination of Nitric Oxide, Nitrogen Dioxide
                    and NOx Emissions from Stationary Combustion Sources by electrochemical analyzer. 1995",
                "unit_price" => 350,
            ],
            "tipo de combustible: solo gas natural
            equipo:testo
            selección de sitio de muestreo:motores reciprocantes,calderos y hornos de proceso" => [
                "essays" => [
                    "- Nitrogen Oxides (NOx) ²",
                    "- Nitric Oxide (NO) ²",
                    "- Nitrogen Dioxide (NO2) ²",
                ],
                "methodology" => "EPA CTM-030
                    Determination of Nitrogen Oxides, Carbon Monoxide, and Oxygen Emissions from Natural Gas-Fired Engines, Boilers and Process Heaters Using Portable Analyzers. 1997",
                "unit_price" => 350,
            ],
            "tipo de combustible: solo gas natural equipo:testo" => [
                "essays" => [
                    "Sulfuro de hidrógeno: H2S",
                    "Hidrocarburos Totales:HT",
                    "Dióxido de carbono:CO2",
                ],
                "methodology" => "CTM 022
                    (Validated Method out of scope)
                    Nitric Oxide, Nitrogen Dioxide, & NOx emissions by Electrochemical Analyzer",
                "unit_price" => 350,
            ],
            "tipo de combustible: solo gas natural equipo:testo" => [
                "essays" => [
                    "Sulfuro de hidrógeno: H2S",
                    "Hidrocarburos Totales:HT",
                    "Dióxido de carbono:CO2",
                ],
                "methodology" => "CTM 030
                    (Validated Method out of scope)
                    Determination of Nitrogen Oxides, Carbon Monoxide, and Oxygen Emissions from Natural Gas-Fired Engines, Boilers and Process Heaters Using Portable Analyzers",
                "unit_price" => 350,
            ],
            "tipo de combustible: solo gas natural(propano,butano y combustibles líquidos) equipo:testo" => [
                "essays" => [
                    "Sulfuro de hidrógeno: H2S",
                    "Hidrocarburos Totales:HT",
                    "Dióxido de carbono:CO2",
                ],
                "methodology" => "CTM-034 
                    (Validated Method out of scope)
                    Test Method -Determination of Oxygen, Carbon Monoxide and Oxides of Nitrogen For Periodic Monitoring. Determination of
                    Nitrogen Oxides, Carbon Monoxide, and Oxygen Emissions from Natural Gas Fired Engines, Boilers and Process Heaters Using
                    Portable Analyzers",
                "unit_price" => 350,
            ]
        ];

        foreach ($matriz as $key => $value) {
            $findMethodology = Methodologies::firstOrCreate([
                'description' => $value['methodology'],
            ]);

            $mat = Matriz::create([
                'description' => $key,
                'methodologie_id' => $findMethodology?->id,
                'number_samples' => 1,
                'unit_price' => $value['unit_price'],
                'other_company' => ($value['other_company'] ?? false) ? true : false,
                'other_company_name' => $value['other_company'] ?? null
            ]);

            foreach ($value['essays'] as $i => $row) {
                $findEssay = Essays::firstOrCreate([
                    'description' => $row
                ]);

                ItemsMatriz::create([
                    'matriz_id' => $mat?->id,
                    'essays_id' => $findEssay?->id
                ]);
            }
        }

        // foreach ($rows as $key => $row) {
        //     if ($key === 0) continue;

        //     $matriz = trim($row[0] ?? '');
        //     $essay = trim($row[1] ?? '');
        //     $methodology  = trim($row[2] ?? '');
        //     $other = trim($row[10] ?? null);

        //     $findEssay = Essays::firstOrCreate([
        //         'description' => $essay
        //     ]);

        //     $findMethodology = Methodologies::firstOrCreate([
        //         'description' => $methodology
        //     ]);

        //     if ($matriz === '' || $matriz === '-') {
        //         continue;
        //     }

        //     $matriz = Matriz::create([
        //         'description' => $matriz,
        //         'methodologie_id' => $findMethodology?->id, 
        //         'number_samples' => $row[6] ?? null, 
        //         'unit_price' => $row[8] ? floatval($row[8]) ?? null : null,
        //         'other_company' => $other ? true : false,
        //         'other_company_name' => $other ?? null
        //     ]);

        //     ItemsMatriz::create([
        //         'matriz_id' => $matriz?->id,
        //         'essays_id' => $findEssay?->id
        //     ]);
        // }
    }
}
