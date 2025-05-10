<script setup>
import Checkbox from "@/Components/Checkbox.vue";
import GuestLayout from "@/Layouts/GuestLayout.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";

defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const form = useForm({
    email: "",
    password: "",
    remember: false,
});

const submit = () => {
    form.post(route("login"), {
        onFinish: () => form.reset("password"),
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Log in" />

        <div v-if="status" class="mb-4 font-medium text-sm text-green-600">
            {{ status }}
        </div>

        <h2 class="text-2xl font-bold text-primary-900 mb-6 text-center">Log in</h2>

        <form @submit.prevent="submit">
            <div>
                <InputLabel for="email" value="Email" class="text-primary-700" />

                <TextInput
                    id="email"
                    type="email"
                    class="mt-1 block w-full input-field"
                    v-model="form.email"
                    required
                    autofocus
                    autocomplete="username"
                />

                <div v-if="$page.props.errors" class="text-red-600 text-sm mt-1">
                    {{ $page.props.errors.email }}
                </div>
            </div>

            <div class="mt-4">
                <InputLabel
                    for="password"
                    value="Password"
                    class="text-primary-700"
                />

                <TextInput
                    id="password"
                    type="password"
                    class="mt-1 block w-full input-field"
                    v-model="form.password"
                    required
                    autocomplete="current-password"
                />

                <InputError class="mt-2" :message="form.errors.password" />
            </div>

            <div class="block mt-4">
                <label class="flex items-center">
                    <Checkbox name="remember" v-model:checked="form.remember" />
                    <span class="ml-2 text-sm text-primary-700">Remember me</span>
                </label>
            </div>

            <div class="flex items-center justify-end mt-6">
                <Link
                    v-if="canResetPassword"
                    :href="route('password.request')"
                    class="text-sm text-primary-600 hover:text-primary-800 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500"
                >
                    Forgot your password?
                </Link>

                <Link
                    :href="route('register')"
                    class="text-sm text-primary-600 ml-4 hover:text-primary-800 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500"
                >
                    Don't have an account?
                </Link>

                <PrimaryButton
                    class="ml-4"
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                >
                    Log in
                </PrimaryButton>
            </div>
        </form>
    </GuestLayout>
</template>

<style>
.input-field {
    @apply border-primary-200 focus:border-primary-500 focus:ring focus:ring-primary-200 focus:ring-opacity-50 rounded-md shadow-sm;
}
</style>
