<script setup>
import {onMounted, reactive, ref} from "vue";
import {useForm} from '@inertiajs/vue3'
import Layout from "@/Layouts/AdminLayout.vue";

const props = defineProps({
    terms: Array,
    termProposals: Array,
    errors: Object
});

let selectedTermProposals = ref([]);
let selectedTermForAddVariant = ref(null);
let filtersTerms = ref({});
let filtersTermProposals = ref({});
let loadingJoinTermProposal = ref(false);
let formAddVariant = useForm({
    term_proposal_ids: [],
    term_id: null
});

onMounted(() => {
    formAddVariant.term_proposal_ids = [];
    formAddVariant.term_id = null;
});

const handleJoinTermProposal = () => {
    formAddVariant.clearErrors();

    if (!selectedTermForAddVariant) formAddVariant.term_id = null;
    else formAddVariant.term_id = selectedTermForAddVariant.id;

    if (!selectedTermProposals) formAddVariant.term_proposal_ids = [];
    else formAddVariant.term_proposal_ids = selectedTermProposals.map(item => item.id);

    formAddVariant
        .post("/admin/terms/add_variant", {preserveScroll: true});
}

</script>


<template>
    <div>
        <Layout title="Термины">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

                <div class="px-4 sm:px-6 lg:px-8">
                    <div class="sm:flex sm:items-center">
                        <div class="sm:flex-auto">
                            <h1 class="text-2xl font-semibold leading-6 text-gray-700">Термины</h1>
                            <p class="mt-2 text-sm text-gray-700">Термины, найденные в текстах и требующие пояснения</p>
                        </div>
                        <div class="mt-4 sm:ml-16 sm:mt-0 sm:flex-none">
                            <button type="button"
                                    class="block rounded-md bg-blue-500 px-3 py-2 text-center text-sm font-semibold text-white shadow-sm hover:bg-blue-400 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-500">
                                Создать
                            </button>
                        </div>
                    </div>
                    <div class="mt-8 flow-root">
                        <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                            <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                                <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 sm:rounded-lg">
                                    <table class="min-w-full divide-y divide-gray-300">
                                        <thead class="bg-gray-50">
                                        <tr>
                                            <th scope="col"
                                                class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">
                                                Термин
                                            </th>
                                            <th scope="col"
                                                class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">
                                                Синонимы
                                            </th>
                                            <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-6">
                                                <span class="sr-only">Edit</span>
                                            </th>
                                        </tr>
                                        </thead>
                                        <tbody class="divide-y divide-gray-200 bg-white">
                                        <tr v-for="term in terms" :key="term.id">
                                            <td class="whitespace-nowrap py-5 pr-3 text-sm sm:pl-0">
                                                <div class="pl-4">
                                                    <div class="font-medium text-gray-900">{{ term.title }}</div>
                                                </div>
                                            </td>
                                            <td class="whitespace-nowrap px-3 py-5 text-sm text-gray-500">
                                                <span v-for="variant in term.variants" :key="variant.id" class="mr-4">
                                                    {{ variant.title }}
                                                </span>
                                            </td>
                                            <td class="relative whitespace-nowrap py-5 pl-3 pr-4 text-right text-sm font-medium sm:pr-0">
                                                <div class="mr-4"><a :href="'/admin/edit_term/'+term.id"
                                                                     class="text-blue-500 hover:text-blue-700"
                                                >Edit<span class="sr-only">, {{ term.title }}</span></a
                                                ></div>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


            </div>
        </Layout>

        <!--        <h1>Термины</h1>-->


        <!--        <div class="mt-2">-->
        <!--            <Link href="/admin/create_term" class="p-button" >Создать термин</Link>-->
        <!--        </div>-->

        <!--        <div class="flex mt-4">-->
        <!--            <div class="w-8 pr-4">-->
        <!--                <Card>-->
        <!--                    <template #title>Термины</template>-->
        <!--                    <template #content>-->
        <!--                    <DataTable :value="terms" :selection.sync="selectedTermForAddVariant" :rows="10" :filters="filtersTerms" :paginator="true" class="p-datatable-sm">-->
        <!--                        <template #header>-->
        <!--                            <div class="flex justify-content-between">-->
        <!--                                <span class="p-input-icon-left">-->
        <!--                                    <i class="pi pi-search" />-->
        <!--                                    <InputText v-model="filtersTerms['global']" placeholder="Фильтр.." />-->
        <!--                                </span>-->
        <!--                            </div>-->
        <!--                        </template>-->
        <!--                        <template #empty>-->
        <!--                            No items found.-->
        <!--                        </template>-->
        <!--                        <template #loading>-->
        <!--                            Loading data. Please wait.-->
        <!--                        </template>-->
        <!--                        <Column selectionMode="single" headerStyle="width: 3em"></Column>-->
        <!--                        <Column header="Термин" field="title" headerStyle="width: 20em" ></Column>-->
        <!--                        <Column header="Синонимы">-->
        <!--                            <template #body="slotProps">-->
        <!--                                <template v-for="variant of slotProps.data.variants">{{variant.title}}, </template>-->
        <!--                            </template>-->
        <!--                        </Column>-->
        <!--                        <Column headerStyle="width: 5em">-->
        <!--                            <template #body="slotProps">-->
        <!--                                <Link :href="'/admin/edit_term/'+slotProps.data.id" class="p-button p-button-secondary p-button-sm">Edit</Link>-->
        <!--                            </template>-->
        <!--                        </Column>-->
        <!--                    </DataTable>-->
        <!--                    </template>-->
        <!--                </Card>-->
        <!--            </div>-->
        <!--            <div class="w-4">-->
        <!--                <Card>-->
        <!--                    <template #title>Заявки на термины</template>-->
        <!--                    <template #content>-->
        <!--                        <DataTable :value="termProposals" :selection.sync="selectedTermProposals" :rows="10" :filters="filtersTermProposals" :paginator="true" class="p-datatable-sm">-->
        <!--                            <template #header>-->
        <!--                                <div class="flex justify-content-between">-->
        <!--                                    <span class="p-input-icon-left">-->
        <!--                                        <i class="pi pi-search" />-->
        <!--                                        <InputText v-model="filtersTermProposals['global']" placeholder="Фильтр.." />-->
        <!--                                    </span>-->
        <!--                                </div>-->
        <!--                            </template>-->
        <!--                            <template #empty>-->
        <!--                                No items found.-->
        <!--                            </template>-->
        <!--                            <template #loading>-->
        <!--                                Loading data. Please wait.-->
        <!--                            </template>-->
        <!--                            <Column selectionMode="multiple" headerStyle="width: 3em"></Column>-->
        <!--                            <Column header="Термин" field="title" ></Column>-->
        <!--                        </DataTable>-->

        <!--                        <p class="mt-4 mb-2">Присоединить выделенные синонимы к выбранному термину:</p>-->

        <!--                        <form @submit.prevent="handleJoinTermProposal">-->
        <!--                            <Button type="submit" >-->
        <!--                                <i class="pi pi-spin pi-slack mr-3" v-if="loadingJoinTermProposal"></i>-->
        <!--                                Присоединить-->
        <!--                            </Button>-->
        <!--                        </form>-->

        <!--                        <div v-if="$page.props.flash.success_add_term_variant" class="mt-2">-->
        <!--                            <Message severity="success">{{$page.props.flash.success_add_term_variant}}</Message>-->
        <!--                        </div>-->
        <!--                        <div v-if="errors.term_id" class="mt-2"><InlineMessage severity="error">{{ errors.term_id }}</InlineMessage></div>-->
        <!--                        <div v-if="errors.term_proposal_ids" class="mt-2"><InlineMessage severity="error">{{ errors.term_proposal_ids }}</InlineMessage></div>-->

        <!--                    </template>-->
        <!--                </Card>-->
        <!--            </div>-->
        <!--        </div>-->


    </div>
</template>

