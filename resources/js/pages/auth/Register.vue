<script setup lang="ts">
import { Form, Head } from '@inertiajs/vue3';
import InputError from '@/components/InputError.vue';
import PasswordInput from '@/components/PasswordInput.vue';
import TextLink from '@/components/TextLink.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Spinner } from '@/components/ui/spinner';
import { login } from '@/routes';
import { store } from '@/routes/register';

defineProps<{
    passwordRules: string;
}>();

defineOptions({
    layout: {
        title: 'Create an account',
        description: 'Enter your details below to create your account',
    },
});
</script>

<template>
    <Head title="Register" />

    <Form
        v-bind="store.form()"
        :reset-on-success="['password', 'password_confirmation']"
        v-slot="{ errors, processing }"
        class="flex flex-col gap-6"
    >
        <div class="grid gap-6">
            <div class="grid gap-2">
                <Label
                    for="name"
                    class="text-sm font-medium text-slate-700 dark:text-slate-300"
                    >Name</Label
                >
                <Input
                    id="name"
                    type="text"
                    required
                    autofocus
                    :tabindex="1"
                    autocomplete="name"
                    name="name"
                    placeholder="Full name"
                    class="rounded-lg transition-all focus-visible:border-[#0d1b4b] focus-visible:ring-[#0d1b4b]/20"
                />
                <InputError :message="errors.name" />
            </div>

            <div class="grid gap-2">
                <Label
                    for="email"
                    class="text-sm font-medium text-slate-700 dark:text-slate-300"
                    >Email address</Label
                >
                <Input
                    id="email"
                    type="email"
                    required
                    :tabindex="2"
                    autocomplete="email"
                    name="email"
                    placeholder="email@example.com"
                    class="rounded-lg transition-all focus-visible:border-[#0d1b4b] focus-visible:ring-[#0d1b4b]/20"
                />
                <InputError :message="errors.email" />
            </div>

            <div class="grid gap-2">
                <Label
                    for="password"
                    class="text-sm font-medium text-slate-700 dark:text-slate-300"
                    >Password</Label
                >
                <PasswordInput
                    id="password"
                    required
                    :tabindex="3"
                    autocomplete="new-password"
                    name="password"
                    placeholder="Password"
                    :passwordrules="passwordRules"
                    class="rounded-lg transition-all focus-visible:border-[#0d1b4b] focus-visible:ring-[#0d1b4b]/20"
                />
                <InputError :message="errors.password" />
            </div>

            <div class="grid gap-2">
                <Label
                    for="password_confirmation"
                    class="text-sm font-medium text-slate-700 dark:text-slate-300"
                    >Confirm password</Label
                >
                <PasswordInput
                    id="password_confirmation"
                    required
                    :tabindex="4"
                    autocomplete="new-password"
                    name="password_confirmation"
                    placeholder="Confirm password"
                    :passwordrules="passwordRules"
                    class="rounded-lg transition-all focus-visible:border-[#0d1b4b] focus-visible:ring-[#0d1b4b]/20"
                />
                <InputError :message="errors.password_confirmation" />
            </div>

            <Button
                type="submit"
                class="mt-2 h-10 w-full cursor-pointer rounded-lg border-0 bg-[#0d1b4b] font-bold text-white shadow-md transition-all duration-200 hover:bg-[#0d1b4b]/90 focus-visible:ring-[#c9a84c]/50"
                tabindex="5"
                :disabled="processing"
                data-test="register-user-button"
            >
                <Spinner v-if="processing" class="mr-2" />
                Create account
            </Button>
        </div>

        <div class="text-center text-sm text-muted-foreground">
            Already have an account?
            <TextLink
                :href="login()"
                class="font-semibold text-[#0d1b4b] decoration-transparent hover:text-[#c9a84c] hover:decoration-current dark:text-amber-400 dark:hover:text-amber-300"
                :tabindex="6"
                >Log in</TextLink
            >
        </div>
    </Form>
</template>
