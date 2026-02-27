<?php

namespace Database\Seeders;

use App\Models\Property;
use App\Models\PropertyImage;
use Illuminate\Database\Seeder;

class PropertySeeder extends Seeder
{
    public function run(): void
    {
        $properties = [
            [
                'title' => 'Villa mit Seeblick',
                'description' => "Luxuriöse Villa mit einzigartigem Seeblick und großem Garten. Dieses Haus wurde mit moderner Architektur entworfen und bietet ein offenes Küchenkonzept, ein geräumiges Wohnzimmer und Seeblick aus jedem Zimmer.\n\nDie Villa ist mit einem privaten Pool, einem Grillplatz und Parkplätzen ausgestattet. Das Stadtzentrum ist in 15 Minuten erreichbar.",
                'price' => 850000,
                'location' => 'Starnberg, Bayern',
                'bedrooms' => 5,
                'bathrooms' => 3,
                'area' => 320,
                'images' => [
                    'https://images.unsplash.com/photo-1613490493576-7fde63acd811?w=800&h=500&fit=crop',
                    'https://images.unsplash.com/photo-1600596542815-ffad4c1539a9?w=800&h=500&fit=crop',
                    'https://images.unsplash.com/photo-1600607687939-ce8a6c25118c?w=800&h=500&fit=crop',
                ],
            ],
            [
                'title' => 'Stadtwohnung im Zentrum',
                'description' => "Moderne Wohnung in Berlin-Mitte, nur wenige Gehminuten von der U-Bahn entfernt. Neubau mit Aufzug und Tiefgarage.\n\nDiese Wohnung mit offener Küche, großem Balkon und Abstellraum bietet eine ausgezeichnete Verkehrsanbindung.",
                'price' => 420000,
                'location' => 'Mitte, Berlin',
                'bedrooms' => 3,
                'bathrooms' => 1,
                'area' => 120,
                'images' => [
                    'https://images.unsplash.com/photo-1545324418-cc1a3fa10c00?w=800&h=500&fit=crop',
                    'https://images.unsplash.com/photo-1502672260266-1c1ef2d93688?w=800&h=500&fit=crop',
                ],
            ],
            [
                'title' => 'Einfamilienhaus im Grünen',
                'description' => "Freistehendes Einfamilienhaus inmitten der grünen Natur des Schwarzwalds. Mit 500 m² Garten bietet es ein Leben in Harmonie mit der Natur.\n\nIdeal für gemütliche Kaminabende im Winter und Gartenaktivitäten im Sommer.",
                'price' => 380000,
                'location' => 'Freiburg, Baden-Württemberg',
                'bedrooms' => 4,
                'bathrooms' => 2,
                'area' => 200,
                'images' => [
                    'https://images.unsplash.com/photo-1518780664697-55e3ad937233?w=800&h=500&fit=crop',
                    'https://images.unsplash.com/photo-1564013799919-ab600027ffc6?w=800&h=500&fit=crop',
                    'https://images.unsplash.com/photo-1576941089067-2de3c901e126?w=800&h=500&fit=crop',
                ],
            ],
            [
                'title' => 'Luxus-Penthouse',
                'description' => "Penthouse-Wohnung mit Dachterrasse in der prestigeträchtigsten Lage Münchens. 360-Grad-Stadtblick.\n\nAusgestattet mit Smart-Home-System, Whirlpool, privatem Aufzug und Tiefgaragenplatz für 2 Fahrzeuge.",
                'price' => 1200000,
                'location' => 'Maxvorstadt, München',
                'bedrooms' => 4,
                'bathrooms' => 3,
                'area' => 280,
                'images' => [
                    'https://images.unsplash.com/photo-1512917774080-9991f1c4c750?w=800&h=500&fit=crop',
                    'https://images.unsplash.com/photo-1600607687644-c7171b42498f?w=800&h=500&fit=crop',
                    'https://images.unsplash.com/photo-1600566753190-17f0baa2a6c3?w=800&h=500&fit=crop',
                ],
            ],
            [
                'title' => 'Studio-Apartment als Kapitalanlage',
                'description' => "Ideales Studio-Apartment in Universitätsnähe als Kapitalanlage. Möbliert übergeben, hohe Mietrendite möglich.\n\nDie Wohnanlage bietet Sicherheitsdienst, Parkplätze und Gemeinschaftseinrichtungen.",
                'price' => 145000,
                'location' => 'Heidelberg, Baden-Württemberg',
                'bedrooms' => 1,
                'bathrooms' => 1,
                'area' => 55,
                'images' => [
                    'https://images.unsplash.com/photo-1502672260266-1c1ef2d93688?w=800&h=500&fit=crop',
                    'https://images.unsplash.com/photo-1560448204-e02f11c3d0e2?w=800&h=500&fit=crop',
                ],
            ],
            [
                'title' => 'Historisches Fachwerkhaus',
                'description' => "Liebevoll restauriertes authentisches Fachwerkhaus. Ein einzigartiger Wohnraum, der historischen Charme mit modernem Komfort verbindet.\n\nMit Obstbäumen im Garten ist dieses Haus ideal für alle, die ein ruhiges Leben suchen.",
                'price' => 480000,
                'location' => 'Rothenburg ob der Tauber, Bayern',
                'bedrooms' => 3,
                'bathrooms' => 2,
                'area' => 180,
                'images' => [
                    'https://images.unsplash.com/photo-1600596542815-ffad4c1539a9?w=800&h=500&fit=crop',
                    'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?w=800&h=500&fit=crop',
                    'https://images.unsplash.com/photo-1600573472592-401b489a3cdc?w=800&h=500&fit=crop',
                ],
            ],
            [
                'title' => 'Moderne Maisonette-Wohnung',
                'description' => "Stilvolle Maisonette-Wohnung über zwei Etagen mit großzügiger Raumaufteilung. Bodentiefe Fenster sorgen für viel Tageslicht.\n\nInklusive Stellplatz in der Tiefgarage und Kellerabteil. Energieeffiziente Bauweise.",
                'price' => 520000,
                'location' => 'Düsseldorf, Nordrhein-Westfalen',
                'bedrooms' => 4,
                'bathrooms' => 2,
                'area' => 165,
                'images' => [
                    'https://images.unsplash.com/photo-1600607687939-ce8a6c25118c?w=800&h=500&fit=crop',
                    'https://images.unsplash.com/photo-1600566753086-00f18fb6b3ea?w=800&h=500&fit=crop',
                    'https://images.unsplash.com/photo-1600585154526-990dced4db0d?w=800&h=500&fit=crop',
                ],
            ],
            [
                'title' => 'Reihenhaus mit Garten',
                'description' => "Gepflegtes Reihenhaus in familienfreundlicher Wohngegend. Ruhige Lage mit guter Anbindung an Schulen und Einkaufsmöglichkeiten.\n\nDer sonnige Garten bietet Platz für Kinder zum Spielen und entspannte Grillabende.",
                'price' => 295000,
                'location' => 'Hamburg-Rahlstedt',
                'bedrooms' => 3,
                'bathrooms' => 1,
                'area' => 110,
                'images' => [
                    'https://images.unsplash.com/photo-1576941089067-2de3c901e126?w=800&h=500&fit=crop',
                    'https://images.unsplash.com/photo-1600047509807-ba8f99d2cdde?w=800&h=500&fit=crop',
                ],
            ],
            [
                'title' => 'Exklusives Loft im Industriestil',
                'description' => "Einzigartiges Loft in einer umgebauten Fabrikhalle. Hohe Decken, originale Backsteinwände und große Industriefenster.\n\nOffener Wohn- und Essbereich mit Designer-Küche. Perfekt für Kunstliebhaber und kreative Köpfe.",
                'price' => 680000,
                'location' => 'Leipzig, Sachsen',
                'bedrooms' => 2,
                'bathrooms' => 2,
                'area' => 195,
                'images' => [
                    'https://images.unsplash.com/photo-1560448204-e02f11c3d0e2?w=800&h=500&fit=crop',
                    'https://images.unsplash.com/photo-1600585154526-990dced4db0d?w=800&h=500&fit=crop',
                    'https://images.unsplash.com/photo-1600566752355-35792bedcfea?w=800&h=500&fit=crop',
                ],
            ],
            [
                'title' => 'Alpenblick-Chalet',
                'description' => "Traumhaftes Chalet mit atemberaubendem Panoramablick auf die Alpen. Hochwertige Holzausstattung und gemütlicher Kachelofen.\n\nIdeal als Feriendomizil oder dauerhafter Wohnsitz für Naturliebhaber. Skigebiet in 10 Minuten erreichbar.",
                'price' => 750000,
                'location' => 'Garmisch-Partenkirchen, Bayern',
                'bedrooms' => 4,
                'bathrooms' => 2,
                'area' => 185,
                'images' => [
                    'https://images.unsplash.com/photo-1564013799919-ab600027ffc6?w=800&h=500&fit=crop',
                    'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?w=800&h=500&fit=crop',
                    'https://images.unsplash.com/photo-1600047509807-ba8f99d2cdde?w=800&h=500&fit=crop',
                ],
            ],
            [
                'title' => 'Neubau-Apartment mit Balkon',
                'description' => "Modernes Apartment im Neubau mit großzügigem Südbalkon. Hochwertige Ausstattung mit Fußbodenheizung und Einbauküche.\n\nRuhige Lage im Grünen, dennoch nur 15 Minuten zur Innenstadt. Ideal für Berufstätige.",
                'price' => 265000,
                'location' => 'Frankfurt-Niederrad',
                'bedrooms' => 2,
                'bathrooms' => 1,
                'area' => 75,
                'images' => [
                    'https://images.unsplash.com/photo-1600566753190-17f0baa2a6c3?w=800&h=500&fit=crop',
                    'https://images.unsplash.com/photo-1600573472592-401b489a3cdc?w=800&h=500&fit=crop',
                ],
            ],
            [
                'title' => 'Herrschaftliche Altbauwohnung',
                'description' => "Repräsentative Altbauwohnung mit Stuck, Parkettböden und hohen Decken. Klassische Eleganz trifft auf moderne Annehmlichkeiten.\n\nZentrale Lage im beliebten Viertel. Balkon zum ruhigen Innenhof.",
                'price' => 590000,
                'location' => 'Stuttgart-West',
                'bedrooms' => 4,
                'bathrooms' => 2,
                'area' => 145,
                'images' => [
                    'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?w=800&h=500&fit=crop',
                    'https://images.unsplash.com/photo-1600566752355-35792bedcfea?w=800&h=500&fit=crop',
                    'https://images.unsplash.com/photo-1600210492493-0946911123ea?w=800&h=500&fit=crop',
                ],
            ],
        ];

        foreach ($properties as $data) {
            $images = $data['images'];
            unset($data['images']);

            $property = Property::create($data);

            foreach ($images as $index => $url) {
                PropertyImage::create([
                    'property_id' => $property->id,
                    'url' => $url,
                    'order' => $index,
                ]);
            }
        }
    }
}
