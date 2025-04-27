<script setup>
import {onMounted, ref, reactive, watch, computed, onUnmounted} from "vue";
import {Head, Link, useForm, router} from '@inertiajs/vue3';
import Layout from '@/Layouts/AdminLayout.vue';
import Breadcrumbs from "@/Components/Breadcrumbs.vue";
import Card from "@/Components/Card.vue";
import Toggle from "@/Components/Toggle.vue";
import Button from "@/Components/Button.vue";
import SmallButton from "@/Components/SmallButton.vue";
import Checkbox from "@/Components/Checkbox.vue";
import Contenteditable from "vue-contenteditable";
import {textToHtml} from "@/helpers.js";
import Modal from "@/Components/Modal.vue";
import Dropdown from "@/Components/Dropdown.vue";
import EditContentDescription from "@/Pages/Admin/Suttas/EditContentDescription.vue";

const props = defineProps({
    sutta: Object,
    errors: Object,
    prevSutta: Object,
    nextSutta: Object,
    translators: Array
});

const suttaForm = useForm({
    id: props.sutta.id ?? null,
    title_pali: props.sutta.title_pali ?? null,
    title_transcribe_ru: props.sutta.title_transcribe_ru ?? null,
    title_translate_ru: props.sutta.title_translate_ru ?? null,
    description: props.sutta.description ?? null,
    is_validated: props.sutta.validated_by > 0,
});

// работа с контентами
let showContent = ref({});
let isContentSynced = ref({});
let chunksByContent = ref({});
let contents = ref([]);
let contentRows = ref([]);
let isShowContentForms = ref(false);

let editor = ref(null); // contenteditable div . Дичь, но доступ к рефам именно такой: https://vuejs.org/guide/essentials/template-refs.html

let editedChunkId = ref(null); // id ячейки, на которую кликнули и которая редактируется
let editedChunkText = ref(""); // контент чанка
let editedChunkTextChanged = ref(""); // контент отредактированный в contenteditable

let loadingStoreChunks = ref(false); // лоадер на кнопке сохранения контентов

let successMessage = ref(null);

let chunksToDelete = ref([]); // id чанков, которые нужно удалить


onMounted(() => {
    contents.value = props.sutta.contents;
    initContentRows();
});

const initContentRows = () => {
    contents.value.forEach((content, index, array) => {
        showContent.value = {...showContent.value, ...JSON.parse('{"' + content.id + '" : true}')}
        isContentSynced.value = {...isContentSynced.value, ...JSON.parse('{"' + content.id + '" : "' + content.is_synced + '"}')}
    });

    let cData = [];
    let chunkIdx = 0;
    let chunksExists = true;
    let row, isChunksAvailable;
    do {
        row = [];
        isChunksAvailable = false;
        contents.value.forEach((content, indexContent, arrayContents) => {
            if (content.chunks[chunkIdx]) {
                isChunksAvailable = true;
                row.push(content.chunks[chunkIdx]);
            } else {
                row.push(null);
            }
        });
        chunkIdx++;
        cData.push(row);
        if (isChunksAvailable === false) chunksExists = false;
    } while (chunksExists);
    contentRows.value = cData;
}

const handleStoreSutta = () => {
    router.post("/admin/store_sutta", {
            sutta: suttaForm.data(),
            rows: contentRows.value,
            chunksToDelete: chunksToDelete.value,
            contentsWithoutChunks: contents.value.map((content => {
                content.chunks = null;
                return content;
            })),
        },
        {
            preserveScroll: true,
            onSuccess: () => {
                successMessage.value = "Сутта сохранена";
                setTimeout(() => {
                    successMessage.value = "";
                }, 3000);
                chunksToDelete.value = [];
            }
        });
}

const selectChunkForEdit = (contentId, chunkId) => {
    if (editedChunkId.value) { // сохранение ранее редактируемого контента
        // debugger;
        contentRows.value = contentRows.value.map((c) => c.map((ch) => {
            if (ch && ch.id === editedChunkId.value) {
                ch.text = editedChunkTextChanged.value;
            }
            return ch;
        }))
    }
    editedChunkId.value = chunkId;
    contentRows.value.forEach((c) => c.forEach((ch) => {
        if (ch && ch.id === chunkId) editedChunkText.value = textToHtml(ch.text)
    }));
    editedChunkTextChanged.value = editedChunkText.value;
    //editor.value.focus();
}

const onEdit = (e) => {
    editedChunkTextChanged.value = e.target.innerText;
}

const saveChunks = () => {
    loadingStoreChunks.value = true;
    axios.post("/admin/store_sutta_chunks", {
        rows: contentRows.value
    }).then((response) => {
        loadingStoreChunks.value = false;
    });

}

const toContentInRows = (contentInColumns) => {
    let contentInRows = [];
    // создание повёрнутого массива
    contentInColumns[0].forEach((cell, j) => {
        contentInRows[j] = [];
    });
    contentInColumns.forEach((row, i) => {
        row.forEach((cell, j) => {
            contentInRows[j].push(cell);
        });
    });
    return contentInRows;
}

const toContentInColumns = (contentInRows) => {
    let contentInColumns = [];
    // определение длины самого длинного контента
    let maxLength = 0;
    contentInRows.forEach((content, i) => {
        if (content.length > maxLength) maxLength = content.length;
    });
    let lengthContent = maxLength;
    let numContents = contentInRows.length;

    // разворачивание массива обратно
    // console.log("lengthContent", lengthContent);
    for (let i = 0; i < lengthContent; i++) {
        contentInColumns[i] = [];
    }
    // console.log("contentInColumns", contentInColumns);
    for (let i = 0; i < numContents; i++) {
        for (let j = 0; j < lengthContent; j++) {
            contentInColumns[j][i] = contentInRows[i][j];
        }
    }
    return contentInColumns;
}

const setOrderInContentInRow = (contentRow) => {
    let order = 10;
    return contentRow.map((cell, i) => {
        if (cell) {
            cell.order = order;
            order += 10;
        }
        return cell;
    });
}

const deleteCell = (contentId, chunkId) => {

    let contentInRows = toContentInRows(contentRows.value);
    let chunk = contentInRows.filter((row) => row[0].content_id === contentId)[0]
        .filter((cell) => cell && cell.id === chunkId)[0];
    //console.log("chunk", chunk);
    let ask = true;
    if (chunk && chunk.text !== "") {
        ask = confirm("Эта ячейка не пуста. Удалить эту ячейку вместе с содержимым ?");
    }
    //console.log("ask", ask);
    if (ask === true) {
        contentInRows = contentInRows.map((row) => row.filter((cell) => cell && cell.id !== chunkId));
        contentRows.value = toContentInColumns(contentInRows);

        chunksToDelete.value.push(chunkId);
    }
}

const insertCell = (contentId, chunkId) => {

    let contentInRows = toContentInRows(contentRows.value);
    contentInRows = contentInRows.map((row) => {
        if (row[0].content_id === contentId) {
            let index = null;
            let cell = null
            row.forEach((cell, i) => {
                if (cell && cell.id === chunkId) {
                    index = i;
                }
            });
            cell = JSON.parse(JSON.stringify(row[index]));
            cell.id = "new" + Math.round(Math.random() * 100000);
            cell.mark = null;
            cell.order = cell.order - 1;
            cell.text = "";
            row.splice(index, 0, cell);
            row = setOrderInContentInRow(row);
            return row;
        }
        return row;
    });
    contentRows.value = toContentInColumns(contentInRows);
}

const insertCellAfter = (contentId, chunkId) => {
    let contentInRows = toContentInRows(contentRows.value);
    contentInRows = contentInRows.map((row) => {
        if (row[0].content_id === contentId) {
            let index = null;
            let cell = null
            row.forEach((cell, i) => {
                if (cell && cell.id === chunkId) {
                    index = i;
                }
            });
            cell = JSON.parse(JSON.stringify(row[index]));
            cell.id = "new" + Math.round(Math.random() * 100000);
            cell.mark = null;
            cell.order = cell.order + 1; // увеличиваем порядок, т.к. ячейка после
            cell.text = "";
            row.splice(index + 1, 0, cell); // вставляем после текущей ячейки
            row = setOrderInContentInRow(row);
            return row;
        }
        return row;
    });
    contentRows.value = toContentInColumns(contentInRows);
}


const addContentToPrevChunk = (contentId, chunkId) => {
    let contentInRows = toContentInRows(contentRows.value);

    contentInRows = contentInRows.map((row) => {
        if (row[0].content_id === contentId) {
            let index = null;
            let prevIndex = null;
            let cell = null;
            let prevCell = null;
            row.forEach((cell, i) => {
                if (cell && cell.id === chunkId) {
                    index = i;
                }
            });
            if (index === 0) return row;
            prevIndex = index - 1;
            cell = JSON.parse(JSON.stringify(row[index]));
            prevCell = JSON.parse(JSON.stringify(row[prevIndex]));
            prevCell.text += "\n" + cell.text;
            cell.text = "";
            row.splice(index, 1, cell);
            row.splice(prevIndex, 1, prevCell);
            return row;
        } else {
            return row;
        }
    });
    contentRows.value = toContentInColumns(contentInRows);
}

const makeLinked = (contentId) => {
    isContentSynced.value[contentId] = "1";
    router.post("/admin/store_sutta", {
            sutta: suttaForm.data(),
            rows: contentRows.value,
            chunksToDelete: chunksToDelete.value,
            isContentSynced: isContentSynced.value
        },
        {
            preserveScroll: true,
            onSuccess: () => {
                successMessage.value = "Сутта сохранена, контент помечен связанным поабзацно с остальными.";
                setTimeout(() => {
                    successMessage.value = "";
                }, 3000);
                chunksToDelete.value = [];
            }
        });
}
const makeUnlinked = (contentId) => {
    isContentSynced.value[contentId] = "0";

    router.post("/admin/store_sutta", {
            sutta: suttaForm.data(),
            rows: contentRows.value,
            chunksToDelete: chunksToDelete.value,
            isContentSynced: isContentSynced.value

        },
        {
            preserveScroll: true,
            onSuccess: () => {
                successMessage.value = "Сутта сохранена, контент помечен связанным поабзацно с остальными.";
                setTimeout(() => {
                    successMessage.value = "";
                }, 3000);
                chunksToDelete.value = [];
            }
        });

}

// модальное окно


// экспорт в json
const isShowModalExport = ref(false);
const contentJson = ref(null);
const isCommentsExists = ref(false);

const exportJson = (contentId) => {
    isCommentsExists.value = false;
    const contentChunks = contentRows.value.flat().filter((cell) => cell && cell.content_id === contentId);
    contentJson.value = "{\n";
    let lines = [];
    let name = props.sutta.category + props.sutta.order;
    if (props.sutta.suborder) name += "." + props.sutta.suborder;
    let numChunk = 0;
    let numLine = 1;
    contentChunks.forEach((chunk, i) => {
        if (chunk.text) {
            lines = chunk.text.split("\n");
        } else {
            lines = [""];
        }

        numLine = 1;
        lines.forEach((line, j) => {
            if (line.includes("[^")) isCommentsExists.value = true; // красная надпись на модалке экспорта, что были комментарии, которых теперь нет
            if (/^\[\^/.test(line)) return; // строку с текстом комментария пропускаем полностью
            let preparedLine = line.replaceAll('"', '\\"').trim();
            preparedLine = preparedLine.replace(/(?!^\[\^\d+\])\[\^\d+\]/g, ''); // удаление ссылок на комментарии
            // preparedLine = preparedLine.replace(/^\[\^.*?$/gm, ''); // удаление текстов комментариев
            contentJson.value += `  "${name}:${numChunk}.${numLine}": "${preparedLine}"`;
            if (j === lines.length - 1 && i === contentChunks.length - 1) {
                contentJson.value += "\n";
            } else {
                contentJson.value += ",\n";
            }
            numLine++;
        });
        numChunk++;
    });
    contentJson.value += "}";
    isShowModalExport.value = true;
}

// импорт из json
const isShowModalImport = ref(false);
const importedIndex = ref(null);
const isHeadersExists = ref(false);
const importJson = (i) => {
    contentJson.value = "";
    isCommentsExists.value = contentRows.value.flat().filter((cell, index) => cell && cell.content_id === contentRows.value[0][i].content_id).some((cell) => cell.text.includes("[^"));
    isHeadersExists.value = contentRows.value.flat().filter((cell, index) => cell && cell.content_id === contentRows.value[0][i].content_id).some((cell) => cell.text.includes("##"));
    importedIndex.value = i;
    isShowModalImport.value = true;
}
const processImport = async () => {
    if (!contentJson.value) {
        return;
    }
    let json = JSON.parse(contentJson.value);
    let jsonChunks = [];
    let prevChunkIndex = 0;
    let chunk = null;
    Object.keys(json).forEach(key => {
        if (!key.includes(":0")) {
            const [name, chunkArea] = key.split(":");
            const [chunkIndex, lineIndex] = chunkArea.split(".");
            if (chunkIndex !== prevChunkIndex) {
                if (chunk) jsonChunks.push(chunk);
                prevChunkIndex = chunkIndex;
                chunk = json[key];
            } else {
                chunk += "\n" + json[key];
            }
        }
    });
    if (chunk) jsonChunks.push(chunk);
    contentRows.value = contentRows.value.map((row) => {
        return row.map((cell, index) => {
            if (cell && index === importedIndex.value) {
                if (jsonChunks.length > 0) cell.text = jsonChunks.shift();
                else {
                    chunksToDelete.value.push(cell.id)
                    cell = null;
                }
            }
            return cell;
        });
    });
    isShowModalImport.value = false;
    isCommentsExists.value = false;
    isHeadersExists.value = false;
    contentJson.value = "";
}

const isExistsComments = (contentId) => {
    return contentRows.value.flat().filter((cell) => cell && cell.content_id === contentId).some((cell) => cell.text.includes("[^"));
}


// Создание нового контента
const isShowCreateContentModal = ref(false);
let editedContent = reactive({});
let editedContentError = ref(null);

const createContent = () => {
    isShowCreateContentModal.value = true;
    editedContent = {
        lang: "",
        short_description: "",
        link_url: "",
        translator_id: 0,
        translator: {
            fullname_ru: "",
            slug: "",
            signature: ""
        }
    }
    editedContentError.value = null;
}
const handleCreateContent = () => {
    console.log("editedContent", editedContent);

    if (!editedContent.lang || (!editedContent.translator_id && (!editedContent.translator.signature || !editedContent.translator.slug))) {
        editedContentError.value = "Выберите язык и переводчика";
        return;
    }

    editedContentError.value = null;
    isShowCreateContentModal.value = false;
    let newContents = contents.value;
    const newContentId = "new" + Math.round(Math.random() * 100000);
    let newChunks = [];
    const countChunksInMainContent = contents.value.filter((content) => content.is_original === 1)[0].chunks.length;
    for (let i = 1; i <= countChunksInMainContent; i++) {
        newChunks.push({
            id: "new" + Math.round(Math.random() * 100000),
            text: "",
            order: i * 10,
            mark: null,
            content_id: newContentId,
        });
    }

    newContents.push({
        id: newContentId,
        lang: editedContent.lang,
        is_synced: 0,
        translator_id: editedContent.translator_id,
        short_description: editedContent.short_description,
        link_url: editedContent.link_url,
        translator: {
            fullname_ru: editedContent.translator.fullname_ru,
            slug: editedContent.translator.slug,
            signature: editedContent.translator.signature
        },
        chunks: newChunks
    });
    contents.value = newContents;
    initContentRows();
}
const updateEditedContent = (newValue) => {
    Object.assign(editedContent, newValue);
}


</script>

<template>
    <Layout title="Edit sutta">
        <Head>
            <title v-if="sutta.id">Редактирование {{sutta.name}}</title>
            <title v-else>Создание сутты</title>
        </Head>

        <Modal :display="isShowModalExport" title="Json for suttacentral"
               :handle-ok="() => isShowModalExport = false"
               :handle-cancel="() => isShowModalExport = false"
               text-ok=""
        >
            <div v-if="isCommentsExists" class="text-red-600 mb-2">В тексте есть комментарии, которые не могут быть
                экспортированы в json
            </div>
            <textarea class="w-full overflow-scroll border-gray-100" rows="16" v-model="contentJson"></textarea>
        </Modal>

        <Modal :display="isShowModalImport" title="Import JSON"
               :handle-ok="processImport"
               :handle-cancel="() => isShowModalImport = false"
               text-ok="Import"
        >
            <div v-if="isCommentsExists" class="text-red-600 mb-2">В тексте есть комментарии, которые затрутся
                импортированным json ! Импорт не рекомендуется.
            </div>
            <div v-if="isHeadersExists" class="text-red-600 mb-2">В тексте есть подзаголовки разделов, которые затрутся
                импортированным json. Добавьте их вручную после импорта, сверяясь с источником.
            </div>
            <textarea class="w-full overflow-scroll border-gray-100" rows="16" v-model="contentJson"></textarea>
        </Modal>

        <Modal :display="isShowCreateContentModal" title="Добавить контент"
               :handle-cancel="() => isShowCreateContentModal = false"
               :handle-ok="handleCreateContent"
        >
            <EditContentDescription
                :translators="translators"
                :error="editedContentError"
                :modelValue="editedContent"
                @update:modelValue="updateEditedContent"
            ></EditContentDescription>

        </Modal>

        <div class="mb-2">
            <Breadcrumbs :pages="[{label:'Сутты', url: '/admin/suttas'}]"/>
        </div>

        <div class="mx-auto w-full px-4 sm:px-6 lg:px-8">

            <div v-if="sutta.id" class="flex flex-row items-baseline">
                <div class="text-2xl font-semibold leading-6 text-gray-700 mb-4">Редактирование {{ sutta.name }}</div>
                <div class="ml-4">
                    <Link :href="'/'+sutta.name" class="link">Посмотреть на сайте</Link>
                </div>
            </div>

            <div v-else class="text-2xl font-semibold leading-6 text-gray-700 mb-4">Создание сутты</div>


            <div class="mb-6 text-sm">

                <!--                <Link class="mr-8 link" v-if="prevSutta" :href="'/admin/edit_sutta/'+prevSutta.name">Предыдущая сутта-->
                <!--                </Link>-->
                <!--                <Link class="link" v-if="nextSutta" :href="'/admin/edit_sutta/'+nextSutta.name">Следующая сутта</Link>-->
            </div>

            <Card class="max-w-5xl">

                <div class="col-span-full mb-4">
                    <label for="title_pali" class="block text-sm font-medium leading-6 text-gray-900">Название на
                        пали</label>
                    <div class="mt-2">
                        <input type="text" v-model="suttaForm.title_pali" name="title_pali" id="title_pali"
                               autocomplete="title_pali"
                               class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-blue-300 sm:text-sm sm:leading-6"/>
                    </div>
                </div>

                <div class="col-span-full mb-4">
                    <label for="title_transcribe_ru" class="block text-sm font-medium leading-6 text-gray-900">Транскрибированное
                        название</label>
                    <div class="mt-2">
                        <input type="text" v-model="suttaForm.title_transcribe_ru" name="title_transcribe_ru"
                               id="title_transcribe_ru" autocomplete="title_transcribe_ru"
                               class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-blue-300 sm:text-sm sm:leading-6"/>
                    </div>
                </div>

                <div class="col-span-full mb-4">
                    <label for="title_translate_ru" class="block text-sm font-medium leading-6 text-gray-900">Перевод
                        названия</label>
                    <div class="mt-2">
                        <input type="text" v-model="suttaForm.title_translate_ru" name="title_translate_ru"
                               id="title_translate_ru" autocomplete="title_translate_ru"
                               class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-blue-300 sm:text-sm sm:leading-6"/>
                    </div>
                </div>

                <div class="col-span-full mb-4">
                    <label for="description" class="block text-sm font-medium leading-6 text-gray-900">Описание</label>
                    <div class="mt-2">
                        <textarea id="description" v-model="suttaForm.description" name="description" rows="4"
                                  class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-blue-300 sm:text-sm sm:leading-6"/>
                    </div>
                    <p class="mt-1 text-sm leading-6 text-gray-500">Краткое содержание сутты или темы, в ней
                        затронутые.</p>
                </div>

                <div class="col-span-full mb-4 flex flex-row">
                    <div class="mr-2">
                        <input type="checkbox" v-model="suttaForm.is_validated"/>
                    </div>
                    <div>
                        <label for="is_validated"
                               class="block text-sm font-medium leading-6 text-gray-900">Сутта проверена, контенты
                            содержат все нужные под заголовки и комментарии, корректны и слинкованы друг с
                            другом.</label>
                    </div>
                </div>

                <form @submit.prevent="handleStoreSutta">
                    <input v-if="sutta.id" type="hidden" name="id" :value="suttaForm.id"/>
                    <div class="flex flex-row items-center">
                        <Button>Сохранить</Button>
                        <div v-if="successMessage" class="ml-4 text-green-600">
                            {{ successMessage }}
                        </div>
                    </div>
                </form>

            </Card>


            <div class="text-3xl text-gray-700 mt-8 mb-4">
                Контенты
            </div>

            <Card>

                <div v-for="(content, index) in contents" class="flex flex-row mb-4">
                    <div class="mr-2">
                        <input type="checkbox" v-model="showContent[content.id]"/>
                    </div>
                    <div class=""> Язык: {{ content.lang }} <span
                        v-if="content.lang !== 'pali'">Переводчик: {{ content.translator.slug }}</span>
                        <span v-if="isContentSynced[content.id] === '1'"
                              class="ml-2 text-green-600 text-sm">связанный поабзацно</span>
                        <span v-else class="ml-2 text-red-600 text-sm">не связанный</span>
                        <span class="ml-2 text-gray-400 text-sm">content_id #{{ content.id }}</span>
                        <span class="ml-2 text-sm" v-if="content.link_url"><a class="link" target="_blank"
                                                                              :href="content.link_url">источник</a></span>
                        <span class="ml-4 text-sm text-gray-500 underline decoration-dotted cursor-pointer"
                              @click="importJson(index)">import json</span>
                        <span class="ml-2 text-sm text-gray-500 underline decoration-dotted cursor-pointer"
                              @click="exportJson(content.id)">export json</span>
                    </div>
                </div>
                <div class="mb-16">
                    <span @click="createContent()" class="link cursor-pointer">Добавить контент</span>
                </div>

                <!--                <div class="mb-4 text-base text-gray-700">-->
                <!--                    <a class="mr-4 cursor-pointer" @click="isShowContentForms = true">Развернуть данные контентов</a>-->
                <!--                    <a class="mr-2 cursor-pointer" @click="isShowContentForms = false">Свернуть данные контентов</a>-->
                <!--                </div>-->
                <div v-if="isShowContentForms" v-for="content in contents" class="mb-4">
                    <div class="mb-4 text-sm text-gray-500">
                        #{{ content.id }}
                    </div>
                    <div class="form-group row row-cols-sm-2">
                        <label class="col-sm-2 text-wrap mt-2 form-label">Title</label>
                        <div class="col">
                            <div data-controller="input" data-input-mask="">
                                <input class="form-control" name="test" title="Title" v-model="content.title"></div>
                        </div>
                    </div>
                    <div class="form-group row row-cols-sm-2">
                        <label class="col-sm-2 text-wrap mt-2 form-label">Subtitle</label>
                        <div class="col">
                            <div data-controller="input" data-input-mask="">
                                <input class="form-control" name="test" title="Subtitle" v-model="content.subtitle">
                            </div>
                        </div>
                    </div>

                </div>

                <div class="flex flex-row w-full">
                    <template v-for="(is_synced, content_id) in isContentSynced">
                        <div v-if="showContent[content_id]" class="flex flex-1 flex-col">
                            <div class="text-center mr-2 pb-2 mb-2">
                                <div v-if="is_synced === '1'" class="text-sm text-green-600">
                                    Контент связан поабзацно
                                    <SmallButton @click="makeUnlinked(content_id)" class="mr-2">отменить</SmallButton>
                                </div>
                                <div v-else>
                                    <SmallButton @click="makeLinked(content_id)" class="mr-2">Привязать</SmallButton>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>

                <div class="flex flex-col text-sm">
                    <template v-for="row in contentRows">
                        <div class="flex flex-row">
                            <template v-for="(chunk, i) in row">
                                <template v-if="chunk && showContent[chunk.content_id]">
                                    <div class="flex-1 mr-2 pb-2 mb-2 border-b">
                                        <div class="text-gray-500 flex flex-row justify-content-between mb-1">
                                            <template v-if="isContentSynced[chunk.content_id] === '0'">
                                                <a class="mr-3 text-gray-300">c{{ chunk.id }} o{{ chunk.order }}</a>
                                                <a class="mr-3 text-gray-500 cursor-pointer"
                                                   @click="insertCell(chunk.content_id, chunk.id)">вставить перед</a>
                                                <a class="mr-3 text-gray-500 cursor-pointer"
                                                   @click="addContentToPrevChunk(chunk.content_id, chunk.id)">перенести
                                                    выше</a>
                                                <a class="mr-3 text-gray-500 cursor-pointer"
                                                   @click="deleteCell(chunk.content_id, chunk.id)">удалить ячейку</a>
                                                <a class="mr-3 text-gray-500 cursor-pointer"
                                                   @click="insertCellAfter(chunk.content_id, chunk.id)">вставить
                                                    после</a>
                                            </template>
                                            <template v-else>
                                                <a class="mr-3 text-gray-300">c{{ chunk.id }} o{{ chunk.order }}</a>
                                                <!--                                                <a class="mr-1 text-gray-500 cursor-pointer" @click="insertRow(chunk.content_id)">вставить ряд</a>-->

                                            </template>

                                        </div>
                                        <template v-if="isContentSynced[chunk.content_id] === '0'">
                                            <!--                                        <template v-if="1">-->
                                            <Contenteditable class="outline-0" tag="div"
                                                             :contenteditable="true"
                                                             v-model="chunk.text"></Contenteditable>
                                        </template>
                                        <template v-else>
                                            <div v-if="chunk.text" v-html="chunk.text.replaceAll('\n','⏎<br>')"></div>
                                            <div v-else></div>
                                        </template>

                                    </div>
                                </template>
                                <template v-else>
                                    <!--                                    <div class="flex-1 mr-2 mb-4"></div>-->
                                </template>
                            </template>

                        </div>
                    </template>
                </div>

                <form @submit.prevent="handleStoreSutta" class="mt-4">
                    <input v-if="sutta.id" type="hidden" name="id" :value="suttaForm.id"/>
                    <div class="flex flex-row items-center">
                        <Button>Сохранить</Button>
                        <div v-if="successMessage" class="ml-4 text-green-600">
                            {{ successMessage }}
                        </div>
                    </div>
                </form>
            </Card>
        </div>
    </Layout>
</template>
