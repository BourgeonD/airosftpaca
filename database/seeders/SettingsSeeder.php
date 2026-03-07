<?php
namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    public function run(): void
    {
        $sections = [
            [
                'id'    => 'general',
                'num'   => '01',
                'title' => 'RÈGLES GÉNÉRALES',
                'rules' => [
                    ['title' => 'Respect mutuel',       'text' => "Tout membre doit faire preuve de respect envers les autres utilisateurs, qu'il s'agisse de joueurs, de chefs d'escouade ou de modérateurs. Les insultes, provocations et comportements toxiques sont strictement interdits."],
                    ['title' => 'Honnêteté',            'text' => "Les informations renseignées sur votre profil doivent être sincères. Usurper l'identité d'un autre joueur ou d'une escouade est interdit."],
                    ['title' => 'Sécurité',             'text' => "AirsoftPACA est une communauté dédiée à l'airsoft. Tout contenu faisant la promotion de la violence réelle, d'activités illégales ou de comportements dangereux sera supprimé immédiatement."],
                    ['title' => 'Âge minimum',          'text' => "L'utilisation de la plateforme est ouverte à partir de 14 ans. Certains événements peuvent imposer un âge minimum propre à leur organisation."],
                    ['title' => 'Conformité légale',    'text' => "Tout contenu publié sur AirsoftPACA doit être conforme à la législation française en vigueur. Toute publication contraire à la loi entraîne une suppression immédiate et un signalement aux autorités compétentes."],
                ],
            ],
            [
                'id'    => 'comptes',
                'num'   => '02',
                'title' => 'COMPTES & INSCRIPTIONS',
                'rules' => [
                    ['title' => 'Un compte par personne',     'text' => "Chaque joueur ne peut posséder qu'un seul compte. Les comptes multiples sont détectés et bannis sans avertissement préalable."],
                    ['title' => 'Pseudonyme adapté',          'text' => "Votre pseudo doit être correct et ne pas contenir de termes offensants, de références à des idéologies extrémistes ou de contenu inapproprié."],
                    ['title' => 'Sécurité du compte',         'text' => "Vous êtes responsable de la sécurité de votre compte. Ne partagez jamais vos identifiants. En cas de suspicion de compromission, contactez immédiatement l'administration."],
                    ['title' => 'Suppression de compte',      'text' => "Vous pouvez demander la suppression de votre compte en contactant l'administration. Les données liées à des parties et interactions publiques peuvent être conservées de manière anonymisée."],
                    ['title' => 'Avatars & photos de profil', 'text' => "Les avatars doivent être appropriés. Sont interdits : photos de personnalités réelles sans leur consentement, images à caractère sexuel ou violent, logos de marques déposées."],
                ],
            ],
            [
                'id'    => 'escouades',
                'num'   => '03',
                'title' => 'ESCOUADES',
                'rules' => [
                    ["title" => "Création d'une escouade",     "text" => "La création d'une escouade nécessite une demande validée par l'administration. Le chef d'escouade est responsable de sa structure et du comportement de ses membres sur la plateforme."],
                    ["title" => "Rôles au sein d'une escouade","text" => "Chaque escouade dispose d'une hiérarchie : Chef d'escouade, Modérateur(s) et Membres. Le chef peut déléguer des droits de gestion aux modérateurs mais reste responsable final."],
                    ["title" => "Représentation",              "text" => "Le nom, le TAG et la description d'une escouade doivent correspondre à une structure réelle de joueurs. Créer une escouade fictive dans le seul but d'obtenir le statut de chef est interdit."],
                    ["title" => "Recrutement",                 "text" => "Le recrutement doit se faire dans le respect des joueurs. Toute forme de démarchage abusif, de spam d'invitations ou de pression sur les membres est prohibée."],
                    ["title" => "Dissolution",                 "text" => "En cas de dissolution, tous les membres sont notifiés et leurs rôles remis à zéro. Un chef qui dissout son escouade peut soumettre une nouvelle demande de création ultérieurement."],
                ],
            ],
            [
                'id'    => 'parties',
                'num'   => '04',
                'title' => 'PARTIES & ÉVÉNEMENTS',
                'rules' => [
                    ['title' => 'Informations exactes',        'text' => "Les informations d'une partie (lieu, date, prix, règles) doivent être exactes et tenues à jour. Toute modification majeure doit être communiquée aux participants inscrits dans les meilleurs délais."],
                    ['title' => 'Engagement des participants', 'text' => "En s'inscrivant à une partie, un joueur s'engage à y participer. En cas d'empêchement, il est de sa responsabilité de se désinscrire le plus tôt possible pour libérer sa place."],
                    ['title' => 'Respect des règles du terrain','text' => "AirsoftPACA facilite la mise en relation mais n'est pas responsable de l'organisation physique des parties. Les règles de sécurité airsoft doivent être respectées sur le terrain."],
                    ['title' => 'Contenu des parties',         'text' => "Les parties organisées via AirsoftPACA doivent être des activités légales de loisir airsoft. Toute partie simulant des actes criminels réels ou à caractère idéologique extrémiste est interdite."],
                    ['title' => 'PAF & paiements',             'text' => "AirsoftPACA n'est pas une plateforme de paiement. Les éventuels frais de participation (PAF) sont gérés directement entre les organisateurs et les joueurs."],
                ],
            ],
            [
                'id'    => 'forum',
                'num'   => '05',
                'title' => 'FORUM',
                'rules' => [
                    ['title' => 'Sujets appropriés',          'text' => "Chaque message doit être publié dans la catégorie correspondant à son contenu. Les sujets hors-sujet répétés peuvent être déplacés ou supprimés par la modération."],
                    ['title' => 'Contenu interdit sur le forum','text' => "Sont strictement interdits : insultes et attaques personnelles, spam et publicités non sollicitées, contenus à caractère sexuel, politique extrémiste ou discriminatoire, informations personnelles d'autrui sans consentement, liens vers des sites malveillants."],
                    ['title' => 'Doublon de sujets',          'text' => "Avant de créer un nouveau sujet, vérifiez qu'il n'existe pas déjà. Les doublons seront fermés ou fusionnés par la modération."],
                    ['title' => 'Respect de la modération',   'text' => "Les décisions de la modération concernant la suppression ou la fermeture d'un sujet sont finales. Les contestations doivent être adressées en message privé à l'administration, jamais en public sur le forum."],
                    ['title' => 'Langue',                     'text' => "AirsoftPACA étant une plateforme régionale PACA, le français est la langue de communication par défaut. Des discussions en d'autres langues sont tolérées dans les catégories adaptées."],
                    ['title' => 'Citations & réponses',       'text' => "Citez uniquement les passages pertinents auxquels vous répondez. Les citations intégrales répétées alourdissent les fils de discussion et sont à éviter."],
                ],
            ],
            [
                'id'    => 'sanctions',
                'num'   => '07',
                'title' => 'SANCTIONS',
                'rules' => [
                    ['title' => 'Avertissement',           'text' => "Pour les infractions mineures ou les premiers manquements. Le membre est notifié et invité à corriger son comportement."],
                    ['title' => 'Restriction temporaire',  'text' => "Limitation d'accès à certaines fonctionnalités (forum, invitations) pour une durée déterminée."],
                    ['title' => 'Suspension de compte',    'text' => "Suspension temporaire du compte (7 à 30 jours) pour les infractions répétées ou graves."],
                    ['title' => 'Ban définitif',           'text' => "Suppression permanente du compte pour les infractions très graves ou les récidivistes. Sans possibilité d'appel."],
                ],
            ],
            [
                'id'    => 'contact',
                'num'   => '08',
                'title' => 'CONTACT & MODÉRATION',
                'rules' => [
                    ['title' => 'Signaler un contenu',         'text' => "Utilisez le bouton de signalement disponible sur les profils utilisateurs. Chaque signalement est examiné par l'équipe de modération dans les meilleurs délais."],
                    ['title' => "Contacter l'administration",  'text' => "Pour toute question relative à votre compte, une sanction ou un litige, contactez l'administration via le formulaire de contact disponible sur la page d'accueil ou directement par email."],
                    ['title' => "Appel d'une sanction",        'text' => "En cas de désaccord avec une décision de modération, vous disposez de 7 jours pour soumettre un appel motivé à l'administration. L'appel doit être factuel et respectueux, sans quoi il sera rejeté sans examen."],
                    ['title' => 'Mise à jour du règlement',    'text' => "Ce règlement est susceptible d'évoluer. Toute modification majeure sera annoncée sur le forum. La poursuite de l'utilisation de la plateforme après une mise à jour vaut acceptation du nouveau règlement."],
                ],
            ],
        ];

        Setting::set('rules_sections', json_encode($sections));
        Setting::set('rules_updated_at', now()->toDateString());
    }
}
