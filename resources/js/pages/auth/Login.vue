<script setup lang="ts">
import { Form, Head } from '@inertiajs/vue3';
import InputError from '@/components/InputError.vue';
import PasskeyVerify from '@/components/PasskeyVerify.vue';
import PasswordInput from '@/components/PasswordInput.vue';
import TextLink from '@/components/TextLink.vue';
import { Button } from '@/components/ui/button';
import { Checkbox } from '@/components/ui/checkbox';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Spinner } from '@/components/ui/spinner';
import { register } from '@/routes';
import { store } from '@/routes/login';
import { request } from '@/routes/password';

defineOptions({
    layout: {
        title: 'Log in to your account',
        description: 'Enter your email and password below to log in',
    },
});

defineProps<{
    status?: string;
    canResetPassword: boolean;
}>();
</script>

<template>
    <Head title="Log in" />

    <div
        v-if="status"
        class="mb-4 text-center text-sm font-medium text-green-600"
    >
        {{ status }}
    </div>

    <!-- <PasskeyVerify /> -->

    <Form
        v-bind="store.form()"
        :reset-on-success="['password']"
        v-slot="{ errors, processing }"
        class="flex flex-col gap-6"
    >
        <div class="grid gap-6">
            <div class="grid gap-2">
                <Label
                    for="email"
                    class="text-sm font-medium text-slate-700 dark:text-slate-300"
                    >Email address</Label
                >
                <Input
                    id="email"
                    type="email"
                    name="email"
                    required
                    autofocus
                    :tabindex="1"
                    autocomplete="email"
                    placeholder="email@example.com"
                    class="rounded-lg transition-all focus-visible:border-[#0d1b4b] focus-visible:ring-[#0d1b4b]/20"
                />
                <InputError :message="errors.email" />
            </div>

            <div class="grid gap-2">
                <div class="flex items-center justify-between">
                    <Label
                        for="password"
                        class="text-sm font-medium text-slate-700 dark:text-slate-300"
                        >Password</Label
                    >
                    <TextLink
                        v-if="canResetPassword"
                        :href="request()"
                        class="text-xs font-medium text-[#0d1b4b] decoration-transparent transition-colors hover:text-[#c9a84c] hover:decoration-current dark:text-amber-400 dark:hover:text-amber-300"
                        :tabindex="5"
                    >
                        Forgot your password?
                    </TextLink>
                </div>
                <PasswordInput
                    id="password"
                    name="password"
                    required
                    :tabindex="2"
                    autocomplete="current-password"
                    placeholder="Password"
                    class="rounded-lg transition-all focus-visible:border-[#0d1b4b] focus-visible:ring-[#0d1b4b]/20"
                />
                <InputError :message="errors.password" />
            </div>

            <div class="flex items-center justify-between">
                <Label
                    for="remember"
                    class="flex cursor-pointer items-center space-x-2.5"
                >
                    <Checkbox
                        id="remember"
                        name="remember"
                        :tabindex="3"
                        class="rounded-md transition-colors data-[state=checked]:border-[#0d1b4b] data-[state=checked]:bg-[#0d1b4b]"
                    />
                    <span
                        class="text-sm text-slate-600 select-none dark:text-slate-400"
                        >Remember me</span
                    >
                </Label>
            </div>

            <Button
                type="submit"
                class="mt-2 h-10 w-full cursor-pointer rounded-lg border-0 bg-[#0d1b4b] font-bold text-white shadow-md transition-all duration-200 hover:bg-[#0d1b4b]/90 focus-visible:ring-[#c9a84c]/50"
                :tabindex="4"
                :disabled="processing"
                data-test="login-button"
            >
                <Spinner v-if="processing" class="mr-2" />
                Log in
            </Button>
        </div>

        <div class="text-center text-sm text-muted-foreground">
            Don't have an account?
            <TextLink
                :href="register()"
                :tabindex="5"
                class="font-semibold text-[#0d1b4b] decoration-transparent hover:text-[#c9a84c] hover:decoration-current dark:text-amber-400 dark:hover:text-amber-300"
                >Sign up</TextLink
            >
        </div>
    </Form>
</template>
