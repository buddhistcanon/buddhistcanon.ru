<script setup>
import BreezeButton from '@/Components/Button.vue';
import BreezeCheckbox from '@/Components/Checkbox.vue';
import BreezeGuestLayout from '@/Layouts/AuthLayout.vue';
import BreezeInput from '@/Components/Input.vue';
import BreezeInputError from '@/Components/InputError.vue';
import BreezeLabel from '@/Components/Label.vue';
import {Head, Link, useForm} from '@inertiajs/vue3';

defineProps({
    canResetPassword: Boolean,
    status: String,
});

const form = useForm({
    email: '',
    password: '',
    remember: false
});

const submit = () => {
    form.post('/login', {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <BreezeGuestLayout>
        <Head title="Log in"/>

        <div v-if="status" class="mb-4 font-medium text-sm text-green-600">
            {{ status }}
        </div>

        <form @submit.prevent="submit">
            <div>
                <BreezeLabel for="email" value="Email"/>
                <BreezeInput id="email" type="email" class="mt-1 block w-full" v-model="form.email" required autofocus
                             autocomplete="username"/>
                <BreezeInputError class="mt-2" :message="form.errors.email">
                    <a href="/forgot-password" class="underline">Забыли пароль?</a>
                </BreezeInputError>
            </div>

            <div class="mt-4">
                <BreezeLabel for="password" value="Пароль"/>
                <BreezeInput id="password" type="password" class="mt-1 block w-full" v-model="form.password" required
                             autocomplete="current-password"/>
                <BreezeInputError class="mt-2" :message="form.errors.password"/>
            </div>

            <div class="block mt-4">
                <label class="flex items-center">
                    <BreezeCheckbox name="remember" v-model:checked="form.remember"/>
                    <span class="ml-2 text-sm text-gray-600">запомнить меня</span>
                </label>
            </div>

            <div class="flex items-center justify-end mt-4">
                <Link v-if="canResetPassword" href="/register"
                      class="underline text-sm text-gray-600 hover:text-gray-900">
                    Регистрация
                </Link>

                <BreezeButton class="ml-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">Вход</BreezeButton>
            </div>
        </form>
    </BreezeGuestLayout>
</template>
