<!--<template>-->
<!--    <div>-->
<!--        <Head>-->
<!--            <title v-if="term.id">Редактирование термина</title>-->
<!--            <title v-else="term.id">Создание термина</title>-->
<!--        </Head>-->

<!--        <div class="mb-2"><Breadcrumb :home="{icon: 'pi pi-home', url: '/admin'}" :model="[{label:'Термины', url: '/admin/terms'}]" /></div>-->

<!--        <h1 v-if="term.id">Редактирование термина</h1>-->
<!--        <h1 v-else="term.id">Создание термина</h1>-->

<!--        <form @submit.prevent="handleStoreTerm">-->
<!--            <input v-if="term.id" type="hidden" name="id" :value="form.id" />-->
<!--            <div class="mt-4 mb-2 flex flex-row justify-content-start align-items-center">-->
<!--                <Button class="p-button-success mr-2" type="submit" >Сохранить</Button>-->
<!--                <div v-if="isDirty" class="text-400">есть изменения в форме, необходимо сохранение</div>-->
<!--                <div v-if="!isDirty && $page.props.flash.success_edit_term"><InlineMessage severity="success">{{$page.props.flash.success_edit_term}}</InlineMessage></div>-->
<!--            </div>-->

<!--        <div class="flex mt-4">-->
<!--            <div class="w-8 pr-4">-->
<!--                <Card>-->
<!--                    <template #content>-->
<!--                        <div class="field">-->
<!--                            <label for="title">Базовое название термина</label>-->
<!--                            <InputText id="title" type="text" class="inputfield w-full" :class="errors.title ? 'p-invalid':''" v-model="form.title"/>-->
<!--                            <small v-if="errors.title" class="p-invalid">{{errors.title}}</small>-->
<!--                        </div>-->
<!--                        <div class="field">-->
<!--                            <label for="slug">Slug</label>-->
<!--                            <InputText id="slug" type="text" class="inputfield w-full" :class="errors.slug ? 'p-invalid':''" v-model="form.slug"/>-->
<!--                            <small v-if="errors.slug" class="p-invalid">{{errors.slug}}</small>-->
<!--                        </div>-->
<!--                        <div class="field">-->
<!--                            <label for="short_text">Краткое описание</label>-->
<!--                            <Textarea v-model="form.short_text" :autoResize="true" rows="3" class="inputfield w-full" :class="errors.short_text ? 'p-invalid':''" />-->
<!--                            <small v-if="errors.short_text" class="p-invalid">{{errors.short_text}}</small>-->
<!--                        </div>-->
<!--                        <div class="field">-->
<!--                            <label for="text">Полное описание</label>-->
<!--                            <Textarea v-model="form.text" :autoResize="true" rows="6" class="inputfield w-full" :class="errors.text ? 'p-invalid':''" />-->
<!--                            <small v-if="errors.text" class="p-invalid">{{errors.text}}</small>-->
<!--                        </div>-->
<!--                    </template>-->
<!--                </Card>-->
<!--            </div>-->
<!--            <div class="w-4">-->
<!--                <Card>-->
<!--                    <template #content>-->
<!--                        <div class="field">-->
<!--                            <label for="parts_text">Состав из других терминов</label>-->
<!--                            <Textarea v-model="form.parts_text" :autoResize="true" rows="5" class="inputfield w-full" :class="errors.parts_text ? 'p-invalid':''" />-->
<!--                            <small v-if="errors.parts_text" class="p-invalid">{{errors.parts_text}}</small>-->
<!--                        </div>-->
<!--                        <div class="field">-->
<!--                            <label for="list_variants">Синонимы</label>-->
<!--                            <Textarea v-model="form.list_variants" :autoResize="true" rows="5" class="inputfield w-full" :class="errors.list_variants ? 'p-invalid':''" />-->
<!--                            <small v-if="errors.list_variants" class="p-invalid">{{errors.list_variants}}</small>-->
<!--                        </div>-->
<!--                    </template>-->
<!--                </Card>-->
<!--            </div>-->
<!--        </div>-->
<!--        </form>-->
<!--    </div>-->
<!--</template>-->

<!--<script>-->
<!--import DataTable from 'primevue/datatable';-->
<!--import Column from 'primevue/column';-->
<!--import Listbox from 'primevue/listbox';-->
<!--import Card from 'primevue/card';-->
<!--import InputText from 'primevue/inputtext';-->
<!--import Button from 'primevue/button';-->
<!--import ProgressSpinner from 'primevue/progressspinner';-->
<!--import Message from "primevue/message";-->
<!--import InlineMessage from "primevue/inlinemessage";-->
<!--import Breadcrumb from 'primevue/breadcrumb';-->

<!--export default {-->
<!--    name: "AdminEditTermPage",-->
<!--    props:{-->
<!--        term: Object,-->
<!--        errors: Object-->
<!--    },-->
<!--    components:{-->
<!--        Card, InputText, InlineMessage, Breadcrumb-->
<!--    },-->
<!--    data(){-->
<!--        return {-->
<!--            form: this.$inertia.form({-->
<!--                id: this.term.id ?? null,-->
<!--                title: this.term.title ?? null,-->
<!--                list_variants: this.term.variants ? this.term.variants.map(variant=>variant.title).join("\n") : null,-->
<!--                short_text: this.term.short_text ?? null,-->
<!--                text: this.term.text ?? null,-->
<!--                parts_text: this.term.parts_text ?? null,-->
<!--                slug: this.term.slug ?? null-->
<!--            })-->
<!--        }-->
<!--    },-->
<!--    computed:{-->
<!--        isDirty(){-->
<!--            return this.form.isDirty;-->
<!--        }-->
<!--    },-->
<!--    methods:{-->
<!--        handleStoreTerm(){-->
<!--            this.form.post("/admin/store_term/");-->
<!--        }-->
<!--    }-->
<!--}-->
<!--</script>-->
