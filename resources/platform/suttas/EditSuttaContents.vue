<template>
    <div class="d-flex flex-row justify-content-between mb-4">
        <div class="d-flex flex-row">
            <div v-for="content in contents" class="me-4">
                <div class="form-check form-switch">
                    <input type="checkbox" class="form-check-input" v-model="showContent[content.id]">
                    <label class="form-check-label">{{ content.lang }} <span
                        v-if="content.lang !== 'pali'">({{ content.translator.slug }})</span> #{{ content.id }}</label>
                </div>
            </div>
        </div>
        <div class="form-group">
            <button class="btn btn-success" @click="saveChunks()">
                <span v-if="loadingStoreChunks" class="me-2">...</span>
                Сохранить контенты
            </button>
        </div>
    </div>

    <div class="mb-4">
        <a class="me-4 text-muted" @click="isShowContentForms = true">Развернуть данные контентов</a>
        <a class="me-2 text-muted" @click="isShowContentForms = false">Свернуть данные контентов</a>
    </div>
    <div v-if="isShowContentForms" v-for="content in contents" class="mb-4">
        <div class="mb-4 fs-5 text-muted">
            #{{ content.id }}
        </div>
        <div class="form-group row row-cols-sm-2">
            <label class="col-sm-2 text-wrap mt-2 form-label">Title</label>
            <div class="col">
                <div data-controller="input" data-input-mask="">
                    <input class="form-control" name="test" title="Title" v-model="content.title"></div>
                <!--                <small class="form-text text-muted">Basic single-line text fields.</small>-->
            </div>
        </div>
        <div class="form-group row row-cols-sm-2">
            <label class="col-sm-2 text-wrap mt-2 form-label">Subtitle</label>
            <div class="col">
                <div data-controller="input" data-input-mask="">
                    <input class="form-control" name="test" title="Subtitle" v-model="content.subtitle"></div>
                <!--            <small class="form-text text-muted">Basic single-line text fields.</small>-->
            </div>
        </div>

    </div>


    <div class="d-flex flex-column">
        <template v-for="row in contentRows">
            <div class="container-fluid small">
                <div class="row mb-4">
                    <template v-for="chunk in row">
                        <template v-if="chunk && showContent[chunk.content_id]">
                            <div class="col">
                                <div class="text-muted d-flex flex-row justify-content-between mb-1">
                                    <template v-if="isContentLinked[chunk.content_id] === '0'">
                                        <a class="me-1 text-muted">вставить ячейку</a>
                                        <a class="me-1 text-muted">вставить ряд</a>
                                        <a class="me-1 text-muted">удалить</a>
                                    </template>
                                    <template v-else>
                                        <a class="me-1 text-muted">вставить ряд</a>

                                    </template>

                                </div>
                                <!--                                <div v-show="editedChunkId === chunk.id" contenteditable="true" style="background-color: #f1f1f1" @input="onEdit" v-html="editedChunkText" ref="editor"></div>-->
                                <!--                                <div v-show="!editedChunkId || editedChunkId !== chunk.id"  @click="selectChunkForEdit(chunk.content_id, chunk.id)" v-html="textToHtml(chunk.text)"></div>-->
                                <contenteditable tag="div" :contenteditable="true"
                                                 v-model="chunk.text"></contenteditable>
                            </div>
                        </template>
                    </template>
                </div>
            </div>
        </template>
    </div>
</template>

<script setup>
import {onMounted, reactive, ref} from "vue";
import contenteditable from "vue-contenteditable";
import {textToHtml} from "../utils.js"

const props = defineProps({
    contents: Array
});

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

onMounted(() => {

    props.contents.forEach((content, index, array) => {
        showContent.value = {...showContent.value, ...JSON.parse('{"' + content.id + '" : true}')}
        isContentLinked.value = {...isContentLinked.value, ...JSON.parse('{"' + content.id + '" : "' + content.is_synced + '"}')}
    });

    let cData = [];
    let i = 0;
    let chunksExists = true;
    let row, isChunksAvailable;
    do {
        row = [];
        isChunksAvailable = false;
        props.contents.forEach((content, indexContent, arrayContents) => {
            if (content.chunks[i]) {
                isChunksAvailable = true;
                row.push(content.chunks[i]);
            } else {
                row.push(null);
            }
        });
        i++;
        cData.push(row);
        if (isChunksAvailable === false) chunksExists = false;
    } while (chunksExists);
    contentRows.value = cData;
});

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

</script>
