<script setup>
import BreezeButton from '@/Components/Button.vue';
import BreezeGuestLayout from '@/Layouts/AuthLayout.vue';
import BreezeInput from '@/Components/Input.vue';
import BreezeInputError from '@/Components/InputError.vue';
import BreezeLabel from '@/Components/Label.vue';
import { Head, Link, useForm } from '@inertiajs/inertia-vue3';

const form = useForm({
    nickname: '',
    email: '',
    password: '',
    password_confirmation: '',
    invite: '',
    first_name: '',
    last_name: ''
});

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <BreezeGuestLayout>
        <Head title="Register" />

        <form @submit.prevent="submit">

            <div class="mt-4">
                <BreezeLabel for="nickname" value="Никнейм или другой идентификатор (ФИО)" />
                <BreezeInput id="nickname" type="text" class="mt-1 block w-full" v-model="form.nickname" required autofocus />
                <BreezeInputError class="mt-2" :message="form.errors.nickname" />
            </div>

            <div class="mt-4">
                <BreezeLabel for="email" value="Email" />
                <BreezeInput id="email" type="email" class="mt-1 block w-full" v-model="form.email" required  />
                <BreezeInputError class="mt-2" :message="form.errors.email" />
            </div>

            <div class="mt-4">
                <BreezeLabel for="password" value="Пароль" />
                <BreezeInput id="password" type="password" class="mt-1 block w-full" v-model="form.password" required  />
                <BreezeInputError class="mt-2" :message="form.errors.password" />
            </div>

            <div class="mt-4">
                <BreezeLabel for="password_confirmation" value="Подвердите пароль" />
                <BreezeInput id="password_confirmation" type="password" class="mt-1 block w-full" v-model="form.password_confirmation" required  />
                <BreezeInputError class="mt-2" :message="form.errors.password_confirmation" />
            </div>

            <div class="mt-4">
                <BreezeLabel for="invite" value="Инвайт" />
                <BreezeInput id="invite" type="text" class="mt-1 block w-full" v-model="form.invite" required />
                <BreezeInputError class="mt-2" :message="form.errors.invite" />
            </div>

            <div class="mt-4 text-gray-800">
                Опциональные данные:
            </div>

            <div class="mt-4">
                <BreezeLabel for="first_name" value="Имя" />
                <BreezeInput id="first_name" type="text" class="mt-1 block w-full" v-model="form.first_name" />
                <BreezeInputError class="mt-2" :message="form.errors.first_name" />
            </div>

            <div class="mt-4">
                <BreezeLabel for="last_name" value="Фамилия" />
                <BreezeInput id="last_name" type="text" class="mt-1 block w-full" v-model="form.last_name" />
                <BreezeInputError class="mt-2" :message="form.errors.last_name" />
            </div>

            <div class="flex items-center justify-end mt-4">
                <Link :href="route('login')" class="underline text-sm text-gray-600 hover:text-gray-900">
                    Уже зарегистрированы?
                </Link>

                <BreezeButton class="ml-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    Регистрация
                </BreezeButton>
            </div>
        </form>
    </BreezeGuestLayout>
</template>
