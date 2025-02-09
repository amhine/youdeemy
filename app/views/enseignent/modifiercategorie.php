<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier la catégorie</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,typography,aspect-ratio"></script>
</head>
<body class="bg-gray-100">
    <div class="min-h-screen py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-3xl mx-auto bg-white rounded-lg shadow-md p-8">
            <h1 class="text-2xl font-bold text-gray-900 mb-8">Modifier la catégorie</h1>
            
            <?php if(isset($_SESSION['error'])): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                    <?= $_SESSION['error'] ?>
                    <?php unset($_SESSION['error']); ?>
                </div>
            <?php endif; ?>

            <form action="/modifierCategorie/<?= $categorie->getIdCategorie() ?>" method="POST" class="space-y-6">
    <input type="hidden" name="id_categorie" value="<?= htmlspecialchars($categorie->getIdCategorie()) ?>">
    
    <div>
        <label for="nom" class="block text-sm font-medium text-gray-700">Nom de la catégorie</label>
        <input type="text" 
               name="nom" 
               id="nom" 
               value="<?= htmlspecialchars($categorie->getNom()) ?>"
               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
               required>
    </div>
    
    <div>
        <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
        <textarea name="description" 
                  id="description" 
                  rows="4"
                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                  required><?= htmlspecialchars($categorie->getDescription()) ?></textarea>
    </div>

    <div class="flex justify-end space-x-4">
        <a href="/encategorie"
           class="inline-flex justify-center py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
            Annuler
        </a>
        <button type="submit"
                class="inline-flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
            Enregistrer les modifications
        </button>
    </div>
</form>

        </div>
    </div>
</body>
</html>