<script setup>
import Layout from "@/Layouts/AdminLayout.vue";
import {Head} from "@inertiajs/vue3";
import Pagination from "@/Components/Pagination.vue";
import {reactive, ref} from "vue";

const props = defineProps({
    usersPage: Array,
    roles: Array,
});

const users = reactive({
    data: props.usersPage.data
});

const editUserRolesId = ref(-1);
const formDisabled = ref(false);

const formIsSuperadmin = ref(0);
const formRoles = ref([]);

function onSubmit(e) {
    e.preventDefault();

    const existingUser = users.data.find(user => user.id === editUserRolesId.value);

    const url = `/admin/users/${existingUser.id}/roles`;
    formDisabled.value = true;
    fetch(url, {
        method: 'PUT',
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify({
            is_superadmin: formIsSuperadmin.value,
            role_ids: formRoles.value || [],
        }),
    }).then((result) => {
        if (!result.ok) {
            console.error(result);
            throw new Error('Cannot save, error!');
        }
        existingUser.is_superadmin = parseInt(formIsSuperadmin.value);
        existingUser.roles = props.roles.filter(role => formRoles.value.includes(role.id)).sort();
        editUserRolesId.value = -1;
    }).catch((error) => {
        console.error(error);
        alert('Cannot save, error!');
    }).finally(() => {
        formDisabled.value = false;
    });
}

function editUser(userId) {
    const user = props.usersPage.data.find(user => user.id === userId);
    editUserRolesId.value = userId;
    formIsSuperadmin.value = user.is_superadmin ? 1 : 0;
    formRoles.value = user.roles.map(role => role.id);
}

function cancelEdit() {
    editUserRolesId.value = -1;
}

</script>

<template>
    <Layout title="Users">
        <Head>
            <title>Пользователи</title>
        </Head>

        <div class="mx-auto w-full px-4 sm:px-6 lg:px-8">

            <div class="text-2xl font-semibold leading-6 text-gray-700 mb-4">
                <h1>Пользователи</h1>
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
                                        e-mail
                                    </th>
                                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">
                                        Роли
                                    </th>
                                </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 bg-white">
                                <tr v-for="user in users.data" :key="user.id">
                                    <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm text-gray-800 sm:pl-6">
                                        {{ user.email }}
                                    </td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-800">
                                        <div v-if="editUserRolesId == user.id" class="w-fit">
                                            <form @submit="onSubmit">
                                                <div class="flex flex-col" id="roles-form">
                                                    <div>Суперадмин?<br />
                                                        <select v-model="formIsSuperadmin" name="is_superadmin" class="w-full" :disabled="formDisabled">
                                                            <option value="0">Нет</option>
                                                            <option value="1">Да</option>
                                                        </select>
                                                    </div>
                                                    <div class="align-text-bottom">Назначить роли:<br />
                                                        <select v-model="formRoles" name="roles" multiple class="w-full" :disabled="formDisabled">
                                                            <option v-for="role in $props.roles" :value="role.id" :class="role.name">{{ role.name }}</option>
                                                        </select>
                                                    </div>
                                                    <div class="flex flex-row">
                                                        <input type="submit" value="Сохранить" class="mb-1 mr-1 px-4 py-3 focus:text-indigo-500 text-sm leading-4 hover:bg-white border focus:border-indigo-500 rounded" :disabled="formDisabled" />
                                                        <button @click="cancelEdit" class="mb-1 mr-1 px-4 py-3 focus:text-indigo-500 text-sm leading-4 hover:bg-white border focus:border-indigo-500 rounded" :disabled="formDisabled">
                                                            Отмена
                                                        </button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                        <div v-else class="flex flex-col md:flex-row">
                                            <div class="content-center pr-2" :id="'roles-user-' + user.id">
                                                {{ user.is_superadmin ? 'Суперадмин' : '' }}
                                                {{ user.roles.sort((a, b) => a.name.localeCompare(b.name)).map(role => role.name).join(', ') }}
                                            </div>
                                            <button @click="editUser(user.id)" class="px-4 py-3 focus:text-indigo-500 text-sm leading-4 hover:bg-white border focus:border-indigo-500 rounded">
                                                Редактировать
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                </tbody>
                            </table>

                            <Pagination class="pt-6 px-4 pb-4 bg-white" :links="$props.usersPage.links"/>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </Layout>
</template>
