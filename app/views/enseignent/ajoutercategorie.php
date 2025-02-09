
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un cours</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,typography,aspect-ratio"></script>
</head>
<body>
<div class="bg-gray-100 flex justify-center items-center min-h-screen m-0 ">
    <form action="/ajouterNouvelleCategorie" method="POST" id="addCategoryForm">
        <div class="max-w-[900px] w-full bg-white rounded-lg shadow-lg overflow-y-scroll ">
            <div class="px-8 py-4 bg-blue-400 text-white">
                <h1 class="flex justify-center font-bold text-white text-3xl">Categorie</h1>
            </div>
            <div class="px-8 py-6">
                <div class="mb-6">
                    <label class="block text-gray-700 font-semibold mb-2" for="nom">Nom :</label>
                    <input type="text" id="nom" name="nom" class="appearance-none rounded-lg relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-gray-800 focus:border-gray-800 focus:z-10 sm:text-sm" required>
                </div>
               
                <div class="mb-6">
                    <label class="block text-gray-700 font-semibold mb-2" for="description">Description:</label>
                    <input type="text" id="description" name="description" class="appearance-none rounded-lg relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-gray-800 focus:border-gray-800 focus:z-10 sm:text-sm" required>
                </div>
                <div class="flex justify-between mt-8">
                    <a href="/encategorie" class="text-white bg-red-600 w-40 rounded-lg py-3 hover:bg-red-800 cursor-pointer flex justify-center">
                        Annuler
                    </a>
                    <button type="submit" class="text-white bg-blue-600 w-40 rounded-lg py-3 hover:bg-blue-800 cursor-pointer">
                        Ajouter
                    </button>
                </div>
            </div>
        </div>
        
    </form>
    
</div>

</body>

</html>