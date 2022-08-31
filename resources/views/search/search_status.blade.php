<x-app-layout>

    <div class="container">
        <div class="flex flex-row content-between">
            <div class="mr-4">
                <p>Статус индексов</p>
                @dump($service->stats())
            </div>
            <div class="mr-4">

            </div>
            <div class="mr-4">


            </div>
        </div>
        <div class="mt-8">
            <p>Пробный поиск</p>
            @dump($service->searchInBooks("а"))
        </div>
    </div>

</x-app-layout>
