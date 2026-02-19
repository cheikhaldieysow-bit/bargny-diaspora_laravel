<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::whereHas('role', function ($query) {
            $query->where('name', 'User');
        })->get();

        if ($users->isEmpty()) {
            $this->command->warn('No users found. Please run UserSeeder first.');
            return;
        }

        $projects = [
            [
                'titre' => 'Développement d\'une application mobile de gestion agricole',
                'description' => 'Application mobile permettant aux agriculteurs de suivre leurs cultures, gérer leurs stocks et accéder aux informations météorologiques.',
                'problem' => 'Les agriculteurs manquent d\'outils numériques adaptés pour optimiser leur production et gérer efficacement leurs exploitations.',
                'objectif' => 'Créer une application mobile intuitive et accessible qui améliore la productivité agricole de 30% dans les 12 premiers mois.',
                'budget' => 15000000,
                'duration' => 180,
                'status' => 'approved',
                'submitted_at' => now()->subDays(30),
                'funded_at' => now()->subDays(15),
            ],
            [
                'titre' => 'Plateforme e-learning pour l\'éducation primaire',
                'description' => 'Plateforme en ligne offrant des cours interactifs et des exercices pour les élèves du primaire.',
                'problem' => 'Manque d\'accès à des ressources éducatives de qualité dans les zones rurales.',
                'objectif' => 'Améliorer l\'accès à l\'éducation pour 5000 élèves dans les régions éloignées.',
                'budget' => 25000000,
                'duration' => 240,
                'status' => 'in_progress',
                'submitted_at' => now()->subDays(45),
                'funded_at' => now()->subDays(20),
            ],
            [
                'titre' => 'Système de gestion des déchets intelligents',
                'description' => 'Solution IoT pour optimiser la collecte et le tri des déchets en milieu urbain.',
                'problem' => 'Gestion inefficace des déchets entraînant pollution et problèmes sanitaires.',
                'objectif' => 'Réduire les déchets non triés de 40% et améliorer l\'efficacité de la collecte.',
                'budget' => 35000000,
                'duration' => 300,
                'status' => 'pending',
                'submitted_at' => now()->subDays(10),
            ],
            [
                'titre' => 'Marketplace pour produits locaux',
                'description' => 'Plateforme de commerce électronique connectant producteurs locaux et consommateurs.',
                'problem' => 'Difficulté pour les producteurs locaux de commercialiser leurs produits à grande échelle.',
                'objectif' => 'Créer un canal de distribution numérique pour 200 producteurs locaux.',
                'budget' => 12000000,
                'duration' => 150,
                'status' => 'approved',
                'submitted_at' => now()->subDays(60),
                'funded_at' => now()->subDays(40),
            ],
            [
                'titre' => 'Application de télémédecine rurale',
                'description' => 'Service de consultation médicale à distance pour les populations rurales.',
                'problem' => 'Accès limité aux services de santé dans les zones éloignées.',
                'objectif' => 'Fournir des consultations médicales à distance à 10000 patients par an.',
                'budget' => 20000000,
                'duration' => 200,
                'status' => 'draft',
            ],
            [
                'titre' => 'Système d\'irrigation intelligent',
                'description' => 'Solution automatisée d\'irrigation basée sur l\'analyse des données météo et du sol.',
                'problem' => 'Gaspillage d\'eau et irrigation inefficace dans l\'agriculture.',
                'objectif' => 'Réduire la consommation d\'eau de 50% tout en augmentant les rendements.',
                'budget' => 18000000,
                'duration' => 220,
                'status' => 'rejected',
                'submitted_at' => now()->subDays(70),
            ],
        ];

        foreach ($projects as $index => $projectData) {
            $user = $users[$index % $users->count()];
            
            Project::firstOrCreate(
                ['titre' => $projectData['titre']],
                array_merge($projectData, ['user_id' => $user->id])
            );
        }

        $this->command->info('Projects created successfully!');
    }
}
