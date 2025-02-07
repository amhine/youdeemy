<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Categories - Udemy</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,typography,aspect-ratio"></script>
</head>
<body>
    <!-- Votre navbar reste la même, mais mettez à jour les liens -->
    <nav class="bg-white mb-8 rounded-xl shadow-lg overflow-hidden transition-all hover:shadow-2xl hover:-translate-y-1">
        <div class="container mx-auto px-4">
            <div class="flex items-center justify-between h-16">
                <div class="flex-shrink-0">
                    <img src="https://frontends.udemycdn.com/frontends-homepage/staticx/udemy/images/v7/logo-udemy.svg" alt="Udemy" width="75" height="28">
                </div>
                <div class="hidden md:flex md:items-center space-x-4">
                    <a href="index.php?action=dashboard" class="text-gray-800 cursor-pointer hover:purple-800 px-3 py-2 rounded-md text-sm font-medium">Home</a>
                    <a href="index.php?action=categories" class="text-gray-600 cursor-pointer hover:purple-800 px-3 py-2 rounded-md text-sm font-medium">Categories</a>
                    <a href="index.php?action=courses" class="text-gray-600 hover:text-purple-800 px-3 py-2 rounded-md text-sm font-medium">Courses</a>
                    <a href="index.php?action=statistics" class="text-gray-600 hover:text-purple-800 px-3 py-2 rounded-md text-sm font-medium">Statistiques</a>
                    <a href="index.php?action=logout" class="text-white hover:text-purple-500 px-4 py-2 rounded bg-purple-600 hover:bg-purple-700">Logout</a>
                </div>
            </div>
        </div>
    </nav>

    <div class="flex flex-wrap justify-center mt-8 mb-8 gap-10">
        <?php foreach ($categories as $categorie): ?>
            <a href="index.php?action=categorie-details&id_categorie=<?php echo $categorie->getIdCategorie(); ?>" 
                class="flex flex-col items-center text-center bg-white shadow-md rounded-lg p-4 transition-transform transform hover:scale-105 w-1 md:w-1/2 lg:w-1/3">
                <h3 class="text-lg font-bold text-purple-600 mb-2"><?php echo $categorie->getNom(); ?></h3>
                <p class="text-sm text-gray-600 mb-4"><?php echo $categorie->getDescription(); ?></p>
                <div class="mt-auto">
                    <span class="mt-4 block text-blue-600 font-semibold hover:underline">Voir plus</span>
                </div>
            </a>
        <?php endforeach; ?>
    </div>

    <!-- Votre footer reste le même -->
</body>
</html>