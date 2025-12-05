<?php

namespace Database\Seeders;

use App\Models\Speech;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class SpeechSeeder extends Seeder
{
    public function run(): void
    {
        $speeches = [
            [
                'author' => 'Madame Marieme Soda NDIAYE',
                'function' => 'Directrice Générale du CSAR',
                'title' => 'Discours d\'installation - Engagement pour la sécurité alimentaire',
                'excerpt' => 'En tant que Directrice Générale du CSAR, je m\'engage à diriger cette institution avec transparence et efficacité pour assurer la sécurité alimentaire de nos concitoyens.',
                'content' => 'Mesdames et Messieurs,

C\'est avec un profond sentiment de responsabilité et d\'engagement que j\'assume aujourd\'hui les fonctions de Directrice Générale du Commissariat à la Sécurité Alimentaire et à la Résilience (CSAR).

Le CSAR, créé par décret n° 2024-1234 du 15 juillet 2024, représente une étape majeure dans la stratégie nationale de sécurité alimentaire du Sénégal. Notre mission est claire : assurer la sécurité alimentaire et renforcer la résilience des populations face aux défis climatiques et aux crises alimentaires.

Avec nos 137 agents déployés sur l\'ensemble du territoire national, nos 70 magasins de stockage d\'une capacité de 86 000 tonnes, et plus de 50 ans d\'expérience dans la gestion des crises alimentaires, le CSAR dispose d\'une expertise reconnue et d\'une infrastructure solide.

Notre approche s\'articule autour de trois axes prioritaires :

1. **La prévention** : Anticiper les crises alimentaires par une veille permanente et des systèmes d\'alerte précoce
2. **L\'intervention** : Répondre rapidement et efficacement aux situations d\'urgence alimentaire
3. **La résilience** : Renforcer les capacités des populations à faire face aux chocs alimentaires

Je m\'engage personnellement à :
- Maintenir la transparence dans toutes nos actions
- Renforcer la collaboration avec nos partenaires nationaux et internationaux
- Innover dans nos méthodes d\'intervention
- Placer l\'humain au cœur de toutes nos décisions

Ensemble, nous construirons un Sénégal plus résilient, où chaque citoyen aura accès à une alimentation suffisante et nutritive.

Je vous remercie de votre confiance et de votre soutien dans cette mission cruciale pour notre nation.',
                'date' => Carbon::parse('2024-07-15'),
                'location' => 'Dakar, Sénégal',
                'summary' => 'Discours d\'installation de la nouvelle Directrice Générale du CSAR, marquant le début d\'une nouvelle ère pour la sécurité alimentaire au Sénégal.',
                'portrait' => 'images/dg.jpg'
            ],
            [
                'author' => 'Madame Maimouna DIEYE',
                'function' => 'Ministre de la Famille et des Solidarités',
                'title' => 'Lancement du CSAR - Une nouvelle ère pour la sécurité alimentaire',
                'excerpt' => 'Le CSAR représente une avancée majeure dans notre stratégie nationale de sécurité alimentaire et de résilience.',
                'content' => 'Excellences,
Mesdames et Messieurs,

C\'est avec une grande satisfaction que je procède aujourd\'hui au lancement officiel du Commissariat à la Sécurité Alimentaire et à la Résilience (CSAR).

Cette nouvelle institution, placée sous la tutelle du Ministère de la Famille et des Solidarités, incarne la vision du Président de la République, Son Excellence Macky SALL, de garantir la sécurité alimentaire pour tous les Sénégalais.

Le CSAR n\'est pas simplement une nouvelle structure administrative. Il représente une approche innovante et intégrée pour répondre aux défis alimentaires de notre époque. Dans un contexte marqué par les changements climatiques, la croissance démographique et les crises internationales, la sécurité alimentaire devient plus que jamais une priorité nationale.

Notre approche s\'appuie sur quatre piliers fondamentaux :

**1. La coordination nationale**
Le CSAR centralise et coordonne toutes les actions liées à la sécurité alimentaire, évitant ainsi la dispersion des efforts et optimisant l\'utilisation des ressources.

**2. La prévention et l\'anticipation**
Grâce à nos systèmes de veille et d\'alerte précoce, nous pouvons anticiper les crises et mettre en place des mesures préventives.

**3. L\'intervention rapide**
Nos 70 magasins répartis sur l\'ensemble du territoire nous permettent d\'intervenir rapidement en cas de crise alimentaire.

**4. Le renforcement de la résilience**
Nous travaillons avec les communautés pour renforcer leur capacité à faire face aux chocs alimentaires.

Le CSAR bénéficie du soutien de partenaires de premier plan :
- Le Programme Alimentaire Mondial (PAM)
- L\'Organisation des Nations Unies pour l\'alimentation et l\'agriculture (FAO)
- L\'Union Européenne
- La Banque Mondiale

Je tiens à remercier tous nos partenaires pour leur confiance et leur soutien continu.

Le CSAR est également doté d\'un budget de 50 milliards de FCFA, témoignant de l\'engagement ferme du gouvernement sénégalais pour la sécurité alimentaire.

Mesdames et Messieurs, la sécurité alimentaire n\'est pas un luxe, c\'est un droit fondamental. Le CSAR sera le garant de ce droit pour tous les Sénégalais, sans distinction.

Je confie cette mission cruciale à Madame Marieme Soda NDIAYE, Directrice Générale du CSAR, dont je connais le professionnalisme et l\'engagement.

Ensemble, nous construirons un Sénégal plus fort, plus résilient, où la faim ne sera plus qu\'un mauvais souvenir.

Je vous remercie.',
                'date' => Carbon::parse('2024-07-15'),
                'location' => 'Palais de la République, Dakar',
                'summary' => 'Discours de lancement officiel du CSAR par la Ministre de la Famille et des Solidarités, marquant un tournant dans la politique de sécurité alimentaire du Sénégal.',
                'portrait' => 'images/ministre.JPG'
            ],
            [
                'author' => 'Madame Marieme Soda NDIAYE',
                'function' => 'Directrice Générale du CSAR',
                'title' => 'Bilan 2024 - Réalisations et perspectives',
                'excerpt' => 'L\'année 2024 a été marquée par des réalisations significatives dans notre mission de sécurité alimentaire.',
                'content' => 'Mesdames et Messieurs,

Alors que nous clôturons cette année 2024, première année d\'existence du CSAR, je souhaite partager avec vous le bilan de nos actions et nos perspectives pour l\'avenir.

**Bilan 2024 - Des réalisations significatives**

En cette première année d\'activité, le CSAR a démontré sa capacité à répondre efficacement aux défis alimentaires :

**Interventions d\'urgence :**
- Distribution de 15 000 tonnes de riz aux populations touchées par les inondations
- Assistance alimentaire à 500 000 personnes dans les régions de Saint-Louis, Matam et Tambacounda
- Mise en place de cantines scolaires dans 200 écoles des zones rurales

**Renforcement des capacités :**
- Formation de 1 000 agents communautaires en techniques de stockage et de conservation
- Équipement de 50 magasins en systèmes de contrôle de température
- Mise en place de 15 centres de formation en techniques agricoles

**Innovation et modernisation :**
- Développement d\'une plateforme numérique de suivi des stocks
- Installation de systèmes d\'alerte précoce dans les 14 régions
- Création d\'une base de données nationale sur la sécurité alimentaire

**Partenariats renforcés :**
- Signature d\'accords de coopération avec 10 ONG nationales
- Renforcement de la collaboration avec le PAM et la FAO
- Lancement de projets pilotes avec l\'Union Européenne

**Perspectives 2025 - Vers une sécurité alimentaire durable**

Pour l\'année 2025, le CSAR se fixe des objectifs ambitieux :

1. **Extension du réseau de magasins** : Construction de 20 nouveaux magasins pour atteindre une capacité de 100 000 tonnes

2. **Diversification des interventions** : Développement de programmes de nutrition et de fortification alimentaire

3. **Innovation technologique** : Déploiement de l\'intelligence artificielle pour la prévision des crises alimentaires

4. **Renforcement de la résilience** : Formation de 2 000 agriculteurs aux techniques d\'adaptation climatique

5. **Coopération régionale** : Extension de nos actions aux pays de la CEDEAO

**Chiffres clés 2024 :**
- 137 agents mobilisés
- 70 magasins opérationnels
- 86 000 tonnes de capacité de stockage
- 50+ années d\'expérience cumulée
- 500 000 bénéficiaires directs
- 15 000 tonnes de riz distribuées

Je tiens à remercier tous les agents du CSAR pour leur dévouement, nos partenaires pour leur soutien, et le gouvernement sénégalais pour sa confiance.

La sécurité alimentaire est un défi permanent, mais avec la détermination de notre équipe et le soutien de tous, nous continuerons à progresser vers un Sénégal où la faim ne sera plus qu\'un souvenir.

Je vous remercie.',
                'date' => Carbon::parse('2024-12-15'),
                'location' => 'Siège du CSAR, Dakar',
                'summary' => 'Bilan de la première année d\'activité du CSAR, présentant les réalisations et les perspectives pour 2025.',
                'portrait' => 'images/dg.jpg'
            ],
            [
                'author' => 'Madame Maimouna DIEYE',
                'function' => 'Ministre de la Famille et des Solidarités',
                'title' => 'Politique nationale de sécurité alimentaire - Vision 2030',
                'excerpt' => 'La vision 2030 du Sénégal en matière de sécurité alimentaire s\'articule autour de la durabilité et de l\'autonomie alimentaire.',
                'content' => 'Excellences,
Mesdames et Messieurs,

C\'est avec une vision claire de l\'avenir que je vous présente aujourd\'hui la politique nationale de sécurité alimentaire du Sénégal à l\'horizon 2030.

**Contexte et enjeux**

Dans un monde en pleine mutation, marqué par les changements climatiques, la croissance démographique et les crises géopolitiques, la sécurité alimentaire devient un enjeu stratégique majeur.

Le Sénégal, avec sa population de plus de 17 millions d\'habitants, doit relever le défi de garantir l\'accès à une alimentation suffisante, nutritive et durable pour tous ses citoyens.

**Vision 2030 - Les objectifs stratégiques**

Notre vision s\'articule autour de quatre objectifs majeurs :

**1. Autonomie alimentaire**
- Atteindre 80% d\'autosuffisance en riz d\'ici 2030
- Développer la production locale de céréales, légumineuses et tubercules
- Renforcer les filières de transformation agroalimentaire

**2. Résilience climatique**
- Adapter l\'agriculture aux changements climatiques
- Développer des variétés résistantes à la sécheresse
- Mettre en place des systèmes d\'irrigation durables

**3. Accès universel**
- Garantir l\'accès à une alimentation nutritive pour tous
- Éliminer la malnutrition chronique chez les enfants
- Réduire de 50% le taux de pauvreté alimentaire

**4. Gouvernance efficace**
- Renforcer les capacités institutionnelles
- Améliorer la coordination entre les acteurs
- Développer des systèmes d\'information fiables

**Le rôle central du CSAR**

Le CSAR sera l\'institution pivot de cette stratégie, avec des missions élargies :

- **Coordination nationale** : Centraliser et coordonner toutes les actions de sécurité alimentaire
- **Veille stratégique** : Anticiper les crises et proposer des solutions
- **Intervention d\'urgence** : Répondre rapidement aux situations de crise
- **Développement durable** : Promouvoir des pratiques agricoles durables

**Moyens et ressources**

Pour atteindre ces objectifs, le gouvernement sénégalais s\'engage à :

- Allouer 5% du budget national à la sécurité alimentaire
- Mobiliser 200 milliards de FCFA d\'investissements privés
- Former 10 000 techniciens agricoles
- Équiper 500 000 agriculteurs en technologies modernes

**Partenariats stratégiques**

Nous renforcerons nos partenariats avec :
- Les organisations internationales (FAO, PAM, FIDA)
- Les institutions financières (Banque Mondiale, BAD)
- Les pays amis et partenaires
- Le secteur privé national et international

**Indicateurs de suivi**

Nous mettrons en place un système de suivi rigoureux avec des indicateurs clés :
- Taux d\'autosuffisance alimentaire
- Prévalence de la malnutrition
- Accès à l\'eau potable
- Résilience des systèmes agricoles

**Engagement politique**

Le Président de la République, Son Excellence Macky SALL, a fait de la sécurité alimentaire une priorité absolue de son mandat. Cet engagement se traduit par :

- La création du CSAR
- L\'augmentation significative du budget agricole
- La mise en place de programmes sociaux ciblés
- Le renforcement de la coopération internationale

Mesdames et Messieurs, la sécurité alimentaire n\'est pas seulement une question de production agricole. C\'est un enjeu de développement durable, de justice sociale et de stabilité politique.

Avec la vision 2030, le Sénégal s\'engage sur la voie d\'une transformation profonde de son système alimentaire, pour le bien-être de tous ses citoyens et la prospérité de notre nation.

Je vous remercie de votre attention et de votre engagement dans cette mission cruciale.',
                'date' => Carbon::parse('2024-10-16'),
                'location' => 'Assemblée Nationale, Dakar',
                'summary' => 'Présentation de la politique nationale de sécurité alimentaire du Sénégal à l\'horizon 2030, marquant l\'engagement du gouvernement pour une sécurité alimentaire durable.',
                'portrait' => 'images/ministre.JPG'
            ]
        ];

        foreach ($speeches as $speech) {
            Speech::create($speech);
        }
    }
} 