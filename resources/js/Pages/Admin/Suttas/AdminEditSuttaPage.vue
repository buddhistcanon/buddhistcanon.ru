<script setup>
import {onMounted, ref, reactive, watch, computed} from "vue";
import { Head, Link, useForm } from '@inertiajs/inertia-vue3';
import { Inertia } from '@inertiajs/inertia';
import Layout from '@/Layouts/AdminLayout.vue';
import Breadcrumbs from "@/Components/Breadcrumbs.vue";
import Card from "@/Components/Card.vue";
import Toggle from "@/Components/Toggle.vue";
import Button from "@/Components/Button.vue";
import Checkbox from "@/Components/Checkbox.vue";
import Contenteditable from "vue-contenteditable";
import {textToHtml} from "@/helpers.js";
import { usePage } from '@inertiajs/inertia-vue3';

const props = defineProps({
    sutta: Object,
    errors: Object,
});

const suttaForm = useForm({
    id: props.sutta.id ?? null,
    title_pali: props.sutta.title_pali ?? null,
    title_transcribe_ru: props.sutta.title_transcribe_ru ?? null,
    title_translate_ru: props.sutta.title_translate_ru ?? null,
    description: props.sutta.description ?? null,
});

// работа с контентами
let showContent = ref({});
let isContentLinked = ref({});
let chunksByContent = ref({});
let contentRows = ref([]);
let isShowContentForms = ref(false);

let editor = ref(null); // contenteditable div . Дичь, но доступ к рефам именно такой: https://vuejs.org/guide/essentials/template-refs.html

let editedChunkId = ref(null); // id ячейки, на которую кликнули и которая редактируется
let editedChunkText = ref(""); // контент чанка
let editedChunkTextChanged = ref(""); // контент отредактированный в contenteditable

let loadingStoreChunks = ref(false); // лоадер на кнопке сохранения контентов

let successMessage = ref(null);



onMounted(() => {
    // let obj = {};
    // props.sutta.contents.forEach((item)=>{
    //     //obj = Object.assign(obj, JSON.parse('{"'+item.id+'" : true}'));
    //     showContent[item.id] = true;
    // });

    props.sutta.contents.forEach((content, index, array)=>{
        showContent.value = {...showContent.value, ...JSON.parse('{"'+content.id+'" : true}')}
        isContentLinked.value = {...isContentLinked.value, ...JSON.parse('{"'+content.id+'" : "'+content.is_synced+'"}')}
    });

    let cData = [];
    let i = 0;
    let chunksExists = true;
    let row, isChunksAvailable;
    do{
        row = [];
        isChunksAvailable = false;
        props.sutta.contents.forEach((content, indexContent, arrayContents)=>{
            if(content.chunks[i]){
                isChunksAvailable = true;
                row.push(content.chunks[i]);
            }else{
                row.push(null);
            }
        });
        i++;
        cData.push(row);
        if(isChunksAvailable === false) chunksExists = false;
    }while(chunksExists);
    contentRows.value = cData;
});

const handleStoreSutta = () => {
    Inertia.post("/admin/store_sutta",{
            sutta: suttaForm.data(),
            rows: contentRows.value
        },
        {
            preserveScroll: true,
            onSuccess: () => {
                successMessage.value = "Сутта сохранена";
                setTimeout(()=>{
                    successMessage.value = "";
                }, 3000);
            }
        });
}

const selectChunkForEdit = (contentId, chunkId)=>{
    if(editedChunkId.value){ // сохранение ранее редактируемого контента
        // debugger;
        contentRows.value = contentRows.value.map((c)=> c.map((ch)=>{
            if(ch && ch.id === editedChunkId.value){
                ch.text = editedChunkTextChanged.value;
            }
            return ch;
        }))
    }
    editedChunkId.value = chunkId;
    contentRows.value.forEach((c)=>c.forEach((ch)=>{ if(ch && ch.id === chunkId) editedChunkText.value = textToHtml(ch.text) }));
    editedChunkTextChanged.value = editedChunkText.value;
    //editor.value.focus();
}

const onEdit = (e)=>{
    editedChunkTextChanged.value = e.target.innerText;
}

const saveChunks = ()=>{
    loadingStoreChunks.value = true;
    axios.post("/admin/store_sutta_chunks", {
        rows: contentRows.value
    }).then((response)=>{
        loadingStoreChunks.value = false;
    });

}


</script>

<template>
    <Layout title="Edit sutta">
        <Head>
            <title v-if="sutta.id">Редактирование {{sutta.name}}</title>
            <title v-else>Создание сутты</title>
        </Head>

<!--        <div class="mb-2"><Breadcrumbs :pages="[{label:'Сутты', url: '/admin/suttas'}]" /></div>-->

        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

        <div class="text-2xl font-semibold leading-6 text-gray-700 mb-8">
            <h1 v-if="sutta.id">Редактирование {{sutta.name}}</h1>
            <h1 v-else>Создание сутты</h1>
        </div>



            <Card>

                <div class="col-span-full mb-4">
                    <label for="title_pali" class="block text-sm font-medium leading-6 text-gray-900">Название на пали</label>
                    <div class="mt-2">
                        <input type="text" v-model="suttaForm.title_pali" name="title_pali" id="title_pali" autocomplete="title_pali" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-blue-300 sm:text-sm sm:leading-6" />
                    </div>
                </div>

                <div class="col-span-full mb-4">
                    <label for="title_transcribe_ru" class="block text-sm font-medium leading-6 text-gray-900">Транскрибированное название</label>
                    <div class="mt-2">
                        <input type="text" v-model="suttaForm.title_transcribe_ru" name="title_transcribe_ru" id="title_transcribe_ru" autocomplete="title_transcribe_ru" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-blue-300 sm:text-sm sm:leading-6" />
                    </div>
                </div>

                <div class="col-span-full mb-4">
                    <label for="title_translate_ru" class="block text-sm font-medium leading-6 text-gray-900">Перевод названия</label>
                    <div class="mt-2">
                        <input type="text" v-model="suttaForm.title_translate_ru" name="title_translate_ru" id="title_translate_ru" autocomplete="title_translate_ru" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-blue-300 sm:text-sm sm:leading-6" />
                    </div>
                </div>

                <div class="col-span-full mb-4">
                    <label for="description" class="block text-sm font-medium leading-6 text-gray-900">Описание</label>
                    <div class="mt-2">
                        <textarea id="description" v-model="suttaForm.description" name="description" rows="4" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-blue-300 sm:text-sm sm:leading-6" />
                    </div>
                    <p class="mt-3 text-sm leading-6 text-gray-600">Краткое содержание сутты или темы, в ней затронутые.</p>
                </div>

                <form @submit.prevent="handleStoreSutta">
                    <input v-if="sutta.id" type="hidden" name="id" :value="suttaForm.id" />
                        <div class="flex flex-row items-center">
                            <Button>Сохранить</Button>
                            <div v-if="successMessage" class="ml-4 text-green-600">
                                {{successMessage}}
                            </div>
                        </div>
                </form>

            </Card>


            <div class="text-3xl text-gray-700 mt-8 mb-4">
                Контенты
            </div>

            <Card>

                <div v-for="content in sutta.contents" class="flex flex-row mb-4">
                    <div class="mr-2">
                        <input type="checkbox" v-model="showContent[content.id]" />
                    </div>
                    <div class=""> Язык: {{content.lang}} <span v-if="content.lang !== 'pali'">Переводчик: {{content.translator.slug}}</span>
                        <span v-if="content.is_synced === '1'" class="ml-2 text-green-600 text-sm">связанный поабзацно</span>
                        <span v-else class="ml-2 text-red-600 text-sm">не связанный</span>
                    </div>
                </div>

<!--                <div class="mb-4 text-base text-gray-700">-->
<!--                    <a class="mr-4 cursor-pointer" @click="isShowContentForms = true">Развернуть данные контентов</a>-->
<!--                    <a class="mr-2 cursor-pointer" @click="isShowContentForms = false">Свернуть данные контентов</a>-->
<!--                </div>-->
                <div v-if="isShowContentForms" v-for="content in sutta.contents" class="mb-4">
                    <div class="mb-4 text-sm text-gray-500">
                        #{{content.id}}
                    </div>
                    <div class="form-group row row-cols-sm-2">
                        <label class="col-sm-2 text-wrap mt-2 form-label">Title</label><div class="col"><div data-controller="input" data-input-mask="">
                        <input class="form-control" name="test" title="Title" v-model="content.title"></div>
                    </div>
                    </div>
                    <div class="form-group row row-cols-sm-2">
                        <label class="col-sm-2 text-wrap mt-2 form-label">Subtitle</label><div class="col"><div data-controller="input" data-input-mask="">
                        <input class="form-control" name="test" title="Subtitle" v-model="content.subtitle"></div>
                    </div>
                    </div>

                </div>


                <div class="flex flex-col text-sm">
                    <template v-for="row in contentRows">
                        <div class="flex flex-row">
                            <template v-for="chunk in row">
                                <template v-if="chunk && showContent[chunk.content_id]">
                                    <div class="flex-1 mr-2 mb-4">
                                        <div class="text-gray-500 flex flex-row justify-content-between mb-1">
                                            <template v-if="isContentLinked[chunk.content_id] === '0'">
<!--                                                <a class="mr-2 text-gray-500 cursor-pointer">вставить ячейку</a>-->
<!--                                                <a class="mr-2 text-gray-500 cursor-pointer">вставить ряд</a>-->
<!--                                                <a class="mr-2 text-gray-500 cursor-pointer">удалить</a>-->
                                            </template>
                                            <template v-else>
<!--                                                <a class="mr-1 text-gray-500 cursor-pointer">вставить ряд</a>-->

                                            </template>

                                        </div>
                                        <Contenteditable class="outline-0" tag="div" :contenteditable="true" v-model="chunk.text"></Contenteditable>
                                    </div>
                                </template>
                                <template v-else>
<!--                                    <div class="flex-1 mr-2 mb-4"></div>-->
                                </template>
                            </template>

                        </div>
                    </template>
                </div>
            </Card>
        </div>
    </Layout>
</template>
