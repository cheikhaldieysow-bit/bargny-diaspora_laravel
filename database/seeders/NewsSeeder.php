<?php

namespace Database\Seeders;

use App\Models\News;
use App\Models\User;
use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class NewsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get admin and manager users
        $admins = User::whereHas('role', function ($query) {
            $query->whereIn('name', ['Admin', 'Manager']);
        })->get();

        if ($admins->isEmpty()) {
            $this->command->warn('No admin/manager users found. Please run UserSeeder first.');
            return;
        }

        $newsData = [
            [
                'title' => 'Lancement réussi de notre plateforme de financement participatif',
                'content' => 'Nous sommes heureux d\'annoncer le lancement officiel de notre plateforme de financement participatif. Cette initiative vise à soutenir les entrepreneurs locaux et les projets innovants dans notre communauté. Grâce à votre soutien, nous pourrons transformer des idées en réalités et contribuer au développement économique de notre région.',
                'is_published' => true,
                'published_at' => now()->subDays(30),
            ],
            [
                'title' => 'Premier projet financé avec succès : Application agricole',
                'content' => 'Le projet "Développement d\'une application mobile de gestion agricole" vient d\'atteindre son objectif de financement ! Avec plus de 15 millions de FCFA collectés, ce projet pourra démarrer dès le mois prochain. Félicitations à tous les contributeurs qui ont rendu cela possible.',
                'is_published' => true,
                'published_at' => now()->subDays(15),
            ],
            [
                'title' => 'Webinaire : Comment réussir sa campagne de financement',
                'content' => 'Rejoignez-nous le 15 février pour un webinaire gratuit sur les meilleures pratiques pour réussir votre campagne de financement participatif. Nos experts partageront leurs conseils et astuces pour maximiser vos chances de succès. Inscriptions ouvertes dès maintenant !',
                'is_published' => true,
                'published_at' => now()->subDays(5),
            ],
            [
                'title' => 'Nouvelle fonctionnalité : Suivi en temps réel des projets',
                'content' => 'Nous avons ajouté une nouvelle fonctionnalité permettant aux contributeurs de suivre l\'avancement des projets qu\'ils soutiennent en temps réel. Vous recevrez désormais des notifications régulières sur les étapes importantes de développement.',
                'is_published' => true,
                'published_at' => now()->subDays(2),
            ],
            [
                'title' => 'Partenariat stratégique avec la Banque de Développement',
                'content' => 'Nous sommes ravis d\'annoncer un partenariat stratégique avec la Banque de Développement. Ce partenariat permettra de doubler les contributions pour certains projets à fort impact social. Plus de détails seront communiqués prochainement.',
                'is_published' => false,
            ],
            [
                'title' => 'Témoignage : L\'histoire de succès de Marie Kouassi',
                'content' => 'Marie Kouassi, fondatrice du projet "Plateforme e-learning", partage son expérience avec notre plateforme. Découvrez comment elle a réussi à lever 25 millions de FCFA et comment son projet transforme l\'éducation dans les zones rurales.',
                'is_published' => true,
                'published_at' => now()->subDays(10),
            ],
            [
                'title' => 'Bilan annuel : 50 projets financés, 500 millions collectés',
                'content' => 'Cette année a été exceptionnelle ! Nous avons financé 50 projets innovants pour un montant total de 500 millions de FCFA. Merci à notre communauté de contributeurs pour leur confiance et leur soutien constant.',
                'is_published' => false,
            ],
            [
                'title' => 'Nouveaux critères de sélection des projets pour 2024',
                'content' => 'À partir de ce mois, nous mettons en place de nouveaux critères de sélection pour garantir la qualité et la viabilité des projets présentés sur notre plateforme. Ces critères incluent une évaluation renforcée de l\'impact social et environnemental.',
                'is_published' => true,
                'published_at' => now()->subDay(),
            ],
        ];

        foreach ($newsData as $news) {
            News::firstOrCreate(
                ['title' => $news['title']],
                array_merge($news, [
                    'user_id' => $admins->random()->id,
                ])
            );
        }

        $this->command->info('News created successfully!');
    }
}
