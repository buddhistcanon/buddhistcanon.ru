<script setup>
import BreezeButton from '@/Components/Button.vue';
import BreezeGuestLayout from '@/Layouts/AuthLayout.vue';
import BreezeInput from '@/Components/Input.vue';
import BreezeInputError from '@/Components/InputError.vue';
import BreezeLabel from '@/Components/Label.vue';
import {Head, Link, useForm} from '@inertiajs/vue3';

const form = useForm({
    nickname: '',
    email: '',
    password: '',
});

const submit = () => {
    form.post('/register', {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <BreezeGuestLayout>
        <Head title="Register"/>

        <form @submit.prevent="submit">

            <div class="mt-4">
                <BreezeLabel for="email" value="Email"/>
                <BreezeInput id="email" type="email" class="mt-1 block w-full" v-model="form.email" required/>
                <BreezeInputError class="mt-2" :message="form.errors.email"/>
            </div>

            <div class="mt-4">
                <BreezeLabel for="password" value="Пароль"/>
                <BreezeInput id="password" type="password" class="mt-1 block w-full" v-model="form.password" required/>
                <BreezeInputError class="mt-2" :message="form.errors.password"/>
            </div>

            <div class="flex items-center justify-end mt-4">
                <Link href="/login" class="underline text-sm text-gray-600 hover:text-gray-900">
                    Уже зарегистрированы?
                </Link>

                <BreezeButton class="ml-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    Регистрация
                </BreezeButton>
            </div>
        </form>
    </BreezeGuestLayout>
</template>
