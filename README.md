# Bargny Diaspora Platform

Plateforme web de contribution de la diaspora destinée au financement, à la sélection
et au suivi de projets communautaires dans la commune de Bargny.

Ce projet s’inscrit dans un cadre académique et vise à mettre en pratique
les bonnes pratiques de conception logicielle, d’architecture web
et de travail collaboratif avec Git et GitHub.

---

## 🎯 Objectifs du projet
- Permettre aux porteurs de projets de soumettre leurs initiatives
- Permettre aux membres de la diaspora de contribuer financièrement
- Assurer la transparence du processus de sélection et de financement
- Offrir un suivi clair de l’évolution des projets financés

---

## 👥 Acteurs du système
- Porteur de projet
- Membre de la diaspora
- Administrateur

---

## 🛠️ Technologies utilisées
- **Backend** : Laravel (PHP)
- **Base de données** : SQLite / MySQL
- **Gestion des versions** : Git & GitHub
- **Authentification** : Laravel Sanctum

---

## ⚙️ Installation du projet (local)

### Prérequis
- PHP 8.2+
- Composer
- Git

### Étapes
```bash
git clone https://github.com/TON_USERNAME/bargny-diaspora-platform.git
cd bargny-diaspora-platform
composer install
php artisan key:generate
php artisan migrate
php artisan serve
