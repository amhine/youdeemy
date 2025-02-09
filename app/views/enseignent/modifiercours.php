<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier le cours</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,typography,aspect-ratio"></script>
</head>
<body class="bg-gray-100">
    <div class="min-h-screen py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-3xl mx-auto bg-white rounded-lg shadow-md p-8">
            <h1 class="text-2xl font-bold text-gray-900 mb-8">Modifier le cours</h1>

            <?php if (isset($_SESSION['error'])): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    <?= htmlspecialchars($_SESSION['error']) ?>
                </div>
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>

            <form action="/modifier/<?= $cours->getIdCours() ?>" method="POST"  class="space-y-6">
                <div>
                    <label for="titre" class="block text-sm font-medium text-gray-700">Nom du cours</label>
                    <input type="text" name="titre" id="titre" value="<?= htmlspecialchars($cours->getNom()) ?>" 
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>
                <div>
                    <label for="categorie" class="block text-sm font-medium text-gray-700">Catégorie</label>
                    <select name="categorie" id="categorie" 
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <?php foreach ($categories as $category): ?>
                            <option value="<?= $category->getIdCategorie() ?>" 
                                    <?= $category->getIdCategorie() == $cours->getCategorie() ? 'selected' : '' ?>>
                                <?= htmlspecialchars($category->getNom()) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                  <label for="fichier" class="block text-sm font-medium text-gray-700">Fichier du cours</label>
                  <input type="url" name="fichier" id="fichier" value="<?= htmlspecialchars($cours->getFichier()) ?>"
                      class="mt-1 block w-full text-sm text-gray-500">
                </div>

                <div>
                    <label for="statut" class="block text-sm font-medium text-gray-700">Statut</label>
                    <select name="statut" id="statut" 
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="actif" <?= $cours->getStatus() == 'actif' ? 'selected' : '' ?>>Actif</option>
                        <option value="inactif" <?= $cours->getStatus() == 'inactif' ? 'selected' : '' ?>>Inactif</option>
                    </select>
                </div>
                <div>
                    <label for="type_cours" class="block text-sm font-medium text-gray-700">Type de contenu</label>
                    <select name="type_cours" id="type_cours" 
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="document" <?= $cours->getcontenu() == 'document' ? 'selected' : '' ?>>Document</option>
                        <option value="video" <?= $cours->getcontenu() == 'video' ? 'selected' : '' ?>>Vidéo</option>
                    </select>
                </div>
                <div>
                   <label for="images" class="block text-sm font-medium text-gray-700">Image du cours</label>
                   <input type="url" name="images" id="images" value="<?= htmlspecialchars($cours->getImage()) ?>"
                     class="mt-1 block w-full text-sm text-gray-500">
                </div>
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                    <textarea name="description" id="description" rows="4" 
                              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"><?= htmlspecialchars($cours->getDescription()) ?></textarea>
                </div>
                <div class="flex justify-end space-x-4">
                    <a href="/encourses" 
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