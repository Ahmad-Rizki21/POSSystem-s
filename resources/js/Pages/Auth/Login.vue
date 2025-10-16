<script setup>
import Checkbox from '@/Components/Checkbox.vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const form = useForm({
    email: 'admin@example.com', // Default email untuk testing
    password: 'password',     // Default password untuk testing
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Log in" />

        <!-- Header -->
        <div class="form-header">
            <h1 class="form-title">Welcome Back</h1>
            <p class="form-subtitle">Please login to your account</p>
        </div>

        <div v-if="status" class="status-message">
            {{ status }}
        </div>

        <form @submit.prevent="submit" class="login-form">
            <!-- Email Field -->
            <div class="form-group">
                <InputLabel for="email" value="Email" class="form-label" />
                <div class="input-wrapper">
                    <svg class="input-icon" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M4 4H20C21.1 4 22 4.9 22 6V18C22 19.1 21.1 20 20 20H4C2.9 20 2 19.1 2 18V6C2 4.9 2.9 4 4 4Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M22 6L12 13L2 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    <TextInput
                        id="email"
                        type="email"
                        class="form-input"
                        v-model="form.email"
                        required
                        autofocus
                        autocomplete="username"
                        placeholder="Enter your email"
                    />
                </div>
                <InputError class="error-message" :message="form.errors.email" />
            </div>

            <!-- Password Field -->
            <div class="form-group">
                <InputLabel for="password" value="Password" class="form-label" />
                <div class="input-wrapper">
                    <svg class="input-icon" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect x="3" y="11" width="18" height="11" rx="2" ry="2" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M7 11V7C7 5.67392 7.52678 4.40215 8.46447 3.46447C9.40215 2.52678 10.6739 2 12 2C13.3261 2 14.5979 2.52678 15.5355 3.46447C16.4732 4.40215 17 5.67392 17 7V11" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    <TextInput
                        id="password"
                        type="password"
                        class="form-input"
                        v-model="form.password"
                        required
                        autocomplete="current-password"
                        placeholder="Enter your password"
                    />
                </div>
                <InputError class="error-message" :message="form.errors.password" />
            </div>

            <!-- Remember Me & Forgot Password -->
            <div class="form-options">
                <label class="remember-me">
                    <Checkbox name="remember" v-model:checked="form.remember" />
                    <span class="remember-text">Remember me</span>
                </label>

                <Link
                    v-if="canResetPassword"
                    :href="route('password.request')"
                    class="forgot-password"
                >
                    Forgot password?
                </Link>
            </div>

            <!-- Submit Button -->
            <div class="form-actions">
                <PrimaryButton
                    class="submit-button"
                    :class="{ 'button-loading': form.processing }"
                    :disabled="form.processing"
                >
                    <span v-if="!form.processing">Log in</span>
                    <span v-else class="loading-content">
                        <svg class="spinner" viewBox="0 0 24 24">
                            <circle class="spinner-circle" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"/>
                        </svg>
                        Logging in...
                    </span>
                </PrimaryButton>
            </div>

        </form>
    </GuestLayout>
</template>

<style scoped>
/* Form Header */
.form-header {
    text-align: center;
    margin-bottom: 2rem;
}

.form-title {
    font-size: 2rem;
    font-weight: 700;
    color: #1a202c;
    margin-bottom: 0.5rem;
    animation: slideDown 0.6s ease;
}

.form-subtitle {
    font-size: 0.95rem;
    color: #718096;
    animation: slideDown 0.6s ease 0.1s backwards;
}

@keyframes slideDown {
    from {
        opacity: 0;
        transform: translateY(-20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Status Message */
.status-message {
    padding: 1rem;
    margin-bottom: 1.5rem;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 12px;
    font-size: 0.9rem;
    text-align: center;
    animation: fadeIn 0.5s ease;
}

/* Form */
.login-form {
    animation: fadeIn 0.8s ease 0.2s backwards;
}

@keyframes fadeIn {
    from {
        opacity: 0;
    }
    to {
        opacity: 1;
    }
}

/* Form Group */
.form-group {
    margin-bottom: 1.5rem;
}

.form-label {
    display: block;
    font-size: 0.9rem;
    font-weight: 600;
    color: #2d3748;
    margin-bottom: 0.5rem;
}

/* Input Wrapper */
.input-wrapper {
    position: relative;
    display: flex;
    align-items: center;
}

.input-icon {
    position: absolute;
    left: 1rem;
    width: 20px;
    height: 20px;
    color: #a0aec0;
    pointer-events: none;
    transition: color 0.3s ease;
    z-index: 1;
}

.form-input {
    width: 100%;
    padding: 0.875rem 1rem 0.875rem 3rem;
    border: 2px solid #e2e8f0;
    border-radius: 12px;
    font-size: 0.95rem;
    transition: all 0.3s ease;
    background: #f7fafc;
    color: #2d3748;
}

.form-input:focus {
    outline: none;
    border-color: #667eea;
    background: white;
    color: #2d3748;
    box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
}

.form-input::placeholder {
    color: #a0aec0;
}

.form-input:-webkit-autofill,
.form-input:-webkit-autofill:hover,
.form-input:-webkit-autofill:focus {
    -webkit-text-fill-color: #2d3748;
    -webkit-box-shadow: 0 0 0px 1000px white inset;
    transition: background-color 5000s ease-in-out 0s;
}

.form-input:focus + .input-icon,
.input-wrapper:focus-within .input-icon {
    color: #667eea;
}

.error-message {
    margin-top: 0.5rem;
    font-size: 0.85rem;
    color: #f56565;
}

/* Form Options */
.form-options {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1.5rem;
    flex-wrap: wrap;
    gap: 1rem;
}

.remember-me {
    display: flex;
    align-items: center;
    cursor: pointer;
    user-select: none;
}

.remember-text {
    margin-left: 0.5rem;
    font-size: 0.9rem;
    color: #4a5568;
}

.forgot-password {
    font-size: 0.9rem;
    color: #667eea;
    text-decoration: none;
    font-weight: 600;
    transition: all 0.3s ease;
    position: relative;
}

.forgot-password::after {
    content: '';
    position: absolute;
    bottom: -2px;
    left: 0;
    width: 0;
    height: 2px;
    background: #667eea;
    transition: width 0.3s ease;
}

.forgot-password:hover {
    color: #764ba2;
}

.forgot-password:hover::after {
    width: 100%;
}

/* Submit Button */
.form-actions {
    margin-bottom: 1.5rem;
}

.submit-button {
    width: 100%;
    padding: 1rem;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border: none;
    border-radius: 12px;
    font-size: 1rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
    position: relative;
    overflow: hidden;
}

.submit-button::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
    transition: left 0.5s ease;
}

.submit-button:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(102, 126, 234, 0.6);
}

.submit-button:hover::before {
    left: 100%;
}

.submit-button:active {
    transform: translateY(0);
}

.submit-button.button-loading {
    opacity: 0.7;
    cursor: not-allowed;
}

.loading-content {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
}

.spinner {
    width: 20px;
    height: 20px;
    animation: spin 1s linear infinite;
}

.spinner-circle {
    stroke-dasharray: 60;
    stroke-dashoffset: 45;
    animation: spinCircle 1.5s ease-in-out infinite;
}

@keyframes spin {
    to {
        transform: rotate(360deg);
    }
}

@keyframes spinCircle {
    0% {
        stroke-dashoffset: 60;
    }
    50% {
        stroke-dashoffset: 15;
    }
    100% {
        stroke-dashoffset: 60;
    }
}

/* Form Footer */
.form-footer {
    text-align: center;
    padding-top: 1.5rem;
    border-top: 1px solid #e2e8f0;
}

.footer-text {
    font-size: 0.9rem;
    color: #718096;
}

.footer-link {
    color: #667eea;
    text-decoration: none;
    font-weight: 600;
    transition: color 0.3s ease;
}

.footer-link:hover {
    color: #764ba2;
}

/* Responsive */
@media (max-width: 480px) {
    .form-title {
        font-size: 1.75rem;
    }

    .form-subtitle {
        font-size: 0.875rem;
    }

    .form-input {
        padding: 0.75rem 0.875rem 0.75rem 2.75rem;
        font-size: 0.9rem;
    }

    .input-icon {
        width: 18px;
        height: 18px;
        left: 0.875rem;
    }

    .submit-button {
        padding: 0.875rem;
        font-size: 0.95rem;
    }

    .form-options {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.75rem;
    }
}

/* Dark mode */
@media (prefers-color-scheme: dark) {
    .form-title {
        color: white;
    }

    .form-label {
        color: #e2e8f0;
    }

    .form-input {
        background: rgba(255, 255, 255, 0.05);
        border-color: rgba(255, 255, 255, 0.1);
        color: white;
    }

    .form-input::placeholder {
        color: rgba(255, 255, 255, 0.4);
    }

    .remember-text {
        color: #cbd5e0;
    }

    .footer-text {
        color: #a0aec0;
    }
}
</style>