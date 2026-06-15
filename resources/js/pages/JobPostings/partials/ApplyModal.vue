<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import { apply } from '@/routes/job-postings';

type JobPosting = {
    id: number;
    title: string;
    description: string;
    cover_image: string | null;
    location: string | null;
    type: string;
};

const isOpen = defineModel<boolean>('open');

const props = defineProps<{
    job: JobPosting | null;
}>();

const currentStep = ref(1);

const form = useForm({
    applicant_name: '',
    applicant_email: '',
    applicant_phone: '',
    residential_address: '',
    education_level: 'college_graduate',
    years_of_experience: 0,
    has_license: false,
    license_number: '',
    license_expiry: '',
    height_cm: '',
    weight_kg: '',
    resume: null as File | null,
    cover_letter: '',
    references: '',
});

const fileInput = ref<HTMLInputElement | null>(null);

function handleFileChange(event: Event) {
    const file = (event.target as HTMLInputElement).files?.[0] ?? null;
    form.resume = file;
}

function nextStep() {
    form.clearErrors();

    if (currentStep.value === 1) {
        if (!form.applicant_name) {
            form.setError('applicant_name', 'Full name is required.');
        }

        if (!form.applicant_email) {
            form.setError('applicant_email', 'Email is required.');
        }

        if (!form.applicant_phone) {
            form.setError('applicant_phone', 'Phone number is required.');
        }

        if (!form.residential_address) {
            form.setError(
                'residential_address',
                'Residential address is required.',
            );
        }

        if (form.hasErrors) {
            return;
        }
    } else if (currentStep.value === 2) {
        if (!form.education_level) {
            form.setError('education_level', 'Education level is required.');
        }

        if (
            form.years_of_experience === null ||
            form.years_of_experience === undefined ||
            form.years_of_experience < 0
        ) {
            form.setError(
                'years_of_experience',
                'Valid experience years are required.',
            );
        }

        if (form.hasErrors) {
            return;
        }
    } else if (currentStep.value === 3) {
        if (!form.height_cm) {
            form.setError('height_cm', 'Height is required.');
        }

        if (!form.weight_kg) {
            form.setError('weight_kg', 'Weight is required.');
        }

        if (form.has_license) {
            if (!form.license_number) {
                form.setError('license_number', 'License number is required.');
            }

            if (!form.license_expiry) {
                form.setError(
                    'license_expiry',
                    'License expiry date is required.',
                );
            }
        }

        if (form.hasErrors) {
            return;
        }
    }

    currentStep.value++;
}

function prevStep() {
    currentStep.value--;
}

function handleSubmit() {
    if (!props.job) {
        return;
    }

    form.clearErrors();

    if (!form.resume) {
        form.setError('resume', 'Resume file is required.');

        return;
    }

    form.post(apply(props.job.id).url, {
        forceFormData: true,
        onSuccess: () => {
            isOpen.value = false;
            form.reset();
            currentStep.value = 1;

            if (fileInput.value) {
                fileInput.value.value = '';
            }
        },
        onError: (errors) => {
            if (
                errors.applicant_name ||
                errors.applicant_email ||
                errors.applicant_phone ||
                errors.residential_address
            ) {
                currentStep.value = 1;
            } else if (errors.education_level || errors.years_of_experience) {
                currentStep.value = 2;
            } else if (
                errors.height_cm ||
                errors.weight_kg ||
                errors.license_number ||
                errors.license_expiry
            ) {
                currentStep.value = 3;
            } else if (
                errors.resume ||
                errors.cover_letter ||
                errors.references
            ) {
                currentStep.value = 4;
            }
        },
    });
}

function handleClose() {
    isOpen.value = false;
    form.reset();
    form.clearErrors();
    currentStep.value = 1;

    if (fileInput.value) {
        fileInput.value.value = '';
    }
}
</script>

<template>
    <Transition name="fade">
        <div v-if="isOpen" class="modal-overlay" @click.self="handleClose">
            <div class="modal-content">
                <!-- Header -->
                <div class="modal-header">
                    <div>
                        <h3 class="modal-title">
                            {{
                                props.job
                                    ? `Apply for ${props.job.title}`
                                    : 'Apply Now'
                            }}
                        </h3>
                        <p class="modal-subtitle">
                            Progressive Application Form
                        </p>
                    </div>
                    <button class="close-btn" @click="handleClose">
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            width="20"
                            height="20"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                        >
                            <line x1="18" y1="6" x2="6" y2="18"></line>
                            <line x1="6" y1="6" x2="18" y2="18"></line>
                        </svg>
                    </button>
                </div>

                <!-- Stepper Progress Bar -->
                <div class="stepper-container">
                    <div class="stepper-progress">
                        <div
                            class="step-badge"
                            :class="{ active: currentStep >= 1 }"
                        >
                            1
                        </div>
                        <div
                            class="step-line"
                            :class="{ active: currentStep >= 2 }"
                        ></div>
                        <div
                            class="step-badge"
                            :class="{ active: currentStep >= 2 }"
                        >
                            2
                        </div>
                        <div
                            class="step-line"
                            :class="{ active: currentStep >= 3 }"
                        ></div>
                        <div
                            class="step-badge"
                            :class="{ active: currentStep >= 3 }"
                        >
                            3
                        </div>
                        <div
                            class="step-line"
                            :class="{ active: currentStep >= 4 }"
                        ></div>
                        <div
                            class="step-badge"
                            :class="{ active: currentStep >= 4 }"
                        >
                            4
                        </div>
                    </div>
                    <div class="step-labels">
                        <span>Contact</span>
                        <span>Qualifications</span>
                        <span>Credentials</span>
                        <span>Documents</span>
                    </div>
                </div>

                <!-- Body -->
                <div class="modal-body">
                    <form id="apply-form" @submit.prevent="handleSubmit">
                        <!-- STEP 1: Personal & Contact Information -->
                        <div v-if="currentStep === 1" class="step-pane">
                            <!-- Full Name -->
                            <div class="form-group">
                                <label for="apply-name" class="form-label"
                                    >Full Name
                                    <span class="required">*</span></label
                                >
                                <input
                                    id="apply-name"
                                    v-model="form.applicant_name"
                                    placeholder="John Doe"
                                    :disabled="form.processing"
                                    class="form-input"
                                    required
                                />
                                <p
                                    v-if="form.errors.applicant_name"
                                    class="error-msg"
                                >
                                    {{ form.errors.applicant_name }}
                                </p>
                            </div>

                            <!-- Email Address -->
                            <div class="form-group">
                                <label for="apply-email" class="form-label"
                                    >Email Address
                                    <span class="required">*</span></label
                                >
                                <input
                                    id="apply-email"
                                    v-model="form.applicant_email"
                                    type="email"
                                    placeholder="john@example.com"
                                    :disabled="form.processing"
                                    class="form-input"
                                    required
                                />
                                <p
                                    v-if="form.errors.applicant_email"
                                    class="error-msg"
                                >
                                    {{ form.errors.applicant_email }}
                                </p>
                            </div>

                            <!-- Phone Number -->
                            <div class="form-group">
                                <label for="apply-phone" class="form-label"
                                    >Phone Number
                                    <span class="required">*</span></label
                                >
                                <input
                                    id="apply-phone"
                                    v-model="form.applicant_phone"
                                    placeholder="e.g. 0917 123 4567"
                                    :disabled="form.processing"
                                    class="form-input"
                                    required
                                />
                                <p
                                    v-if="form.errors.applicant_phone"
                                    class="error-msg"
                                >
                                    {{ form.errors.applicant_phone }}
                                </p>
                            </div>

                            <!-- Residential Address -->
                            <div class="form-group">
                                <label for="apply-address" class="form-label"
                                    >Residential Address
                                    <span class="required">*</span></label
                                >
                                <input
                                    id="apply-address"
                                    v-model="form.residential_address"
                                    placeholder="House No., Street, Barangay, City"
                                    :disabled="form.processing"
                                    class="form-input"
                                    required
                                />
                                <p
                                    v-if="form.errors.residential_address"
                                    class="error-msg"
                                >
                                    {{ form.errors.residential_address }}
                                </p>
                            </div>
                        </div>

                        <!-- STEP 2: Qualifications & Experience -->
                        <div v-if="currentStep === 2" class="step-pane">
                            <!-- Education Level -->
                            <div class="form-group">
                                <label for="apply-education" class="form-label"
                                    >Highest Education
                                    <span class="required">*</span></label
                                >
                                <select
                                    id="apply-education"
                                    v-model="form.education_level"
                                    :disabled="form.processing"
                                    class="form-select"
                                >
                                    <option value="high_school">
                                        High School Graduate
                                    </option>
                                    <option value="vocational">
                                        Vocational Graduate
                                    </option>
                                    <option value="college_undergrad">
                                        College Undergrad
                                    </option>
                                    <option value="college_graduate">
                                        College Graduate
                                    </option>
                                    <option value="post_graduate">
                                        Post Graduate
                                    </option>
                                </select>
                                <p
                                    v-if="form.errors.education_level"
                                    class="error-msg"
                                >
                                    {{ form.errors.education_level }}
                                </p>
                            </div>

                            <!-- Years of Experience -->
                            <div class="form-group">
                                <label for="apply-experience" class="form-label"
                                    >Years of Experience in Security
                                    <span class="required">*</span></label
                                >
                                <input
                                    id="apply-experience"
                                    v-model.number="form.years_of_experience"
                                    type="number"
                                    min="0"
                                    placeholder="0"
                                    :disabled="form.processing"
                                    class="form-input"
                                    required
                                />
                                <p
                                    v-if="form.errors.years_of_experience"
                                    class="error-msg"
                                >
                                    {{ form.errors.years_of_experience }}
                                </p>
                            </div>
                        </div>

                        <!-- STEP 3: Credentials (License & Physical Specs) -->
                        <div v-if="currentStep === 3" class="step-pane">
                            <!-- Height and Weight Grid -->
                            <div class="form-grid-2">
                                <div class="form-group">
                                    <label for="apply-height" class="form-label"
                                        >Height (cm)
                                        <span class="required">*</span></label
                                    >
                                    <input
                                        id="apply-height"
                                        v-model.number="form.height_cm"
                                        type="number"
                                        placeholder="170"
                                        :disabled="form.processing"
                                        class="form-input"
                                        required
                                    />
                                    <p
                                        v-if="form.errors.height_cm"
                                        class="error-msg"
                                    >
                                        {{ form.errors.height_cm }}
                                    </p>
                                </div>
                                <div class="form-group">
                                    <label for="apply-weight" class="form-label"
                                        >Weight (kg)
                                        <span class="required">*</span></label
                                    >
                                    <input
                                        id="apply-weight"
                                        v-model.number="form.weight_kg"
                                        type="number"
                                        placeholder="65"
                                        :disabled="form.processing"
                                        class="form-input"
                                        required
                                    />
                                    <p
                                        v-if="form.errors.weight_kg"
                                        class="error-msg"
                                    >
                                        {{ form.errors.weight_kg }}
                                    </p>
                                </div>
                            </div>

                            <!-- Has License Checkbox -->
                            <div class="form-group checkbox-group">
                                <label class="checkbox-label">
                                    <input
                                        type="checkbox"
                                        v-model="form.has_license"
                                        :disabled="form.processing"
                                        class="form-checkbox"
                                    />
                                    <span
                                        >I possess an active Security
                                        Guard/Officer License</span
                                    >
                                </label>
                            </div>

                            <!-- Conditional License Fields -->
                            <Transition name="slide-down">
                                <div
                                    v-if="form.has_license"
                                    class="conditional-fields"
                                >
                                    <div class="form-group">
                                        <label
                                            for="apply-license-num"
                                            class="form-label"
                                            >License Number
                                            <span class="required"
                                                >*</span
                                            ></label
                                        >
                                        <input
                                            id="apply-license-num"
                                            v-model="form.license_number"
                                            placeholder="SG-1234567-89"
                                            :disabled="form.processing"
                                            class="form-input"
                                            required
                                        />
                                        <p
                                            v-if="form.errors.license_number"
                                            class="error-msg"
                                        >
                                            {{ form.errors.license_number }}
                                        </p>
                                    </div>
                                    <div class="form-group">
                                        <label
                                            for="apply-license-exp"
                                            class="form-label"
                                            >Expiry Date
                                            <span class="required"
                                                >*</span
                                            ></label
                                        >
                                        <input
                                            id="apply-license-exp"
                                            v-model="form.license_expiry"
                                            type="date"
                                            :disabled="form.processing"
                                            class="form-input"
                                            required
                                        />
                                        <p
                                            v-if="form.errors.license_expiry"
                                            class="error-msg"
                                        >
                                            {{ form.errors.license_expiry }}
                                        </p>
                                    </div>
                                </div>
                            </Transition>
                        </div>

                        <!-- STEP 4: Attachments and References -->
                        <div v-if="currentStep === 4" class="step-pane">
                            <!-- Resume Upload -->
                            <div class="form-group">
                                <label for="apply-resume" class="form-label"
                                    >Resume / CV
                                    <span class="required">*</span></label
                                >
                                <div class="file-upload-wrapper">
                                    <input
                                        id="apply-resume"
                                        ref="fileInput"
                                        type="file"
                                        accept=".pdf,.docx"
                                        :disabled="form.processing"
                                        class="file-input-field"
                                        @change="handleFileChange"
                                        required
                                    />
                                </div>
                                <p class="file-hint">
                                    Accepted formats: PDF, DOCX · Max 5MB
                                </p>
                                <p v-if="form.errors.resume" class="error-msg">
                                    {{ form.errors.resume }}
                                </p>
                            </div>

                            <!-- Cover Letter -->
                            <div class="form-group">
                                <label
                                    for="apply-cover-letter"
                                    class="form-label"
                                    >Cover Letter / Message</label
                                >
                                <textarea
                                    id="apply-cover-letter"
                                    v-model="form.cover_letter"
                                    rows="3"
                                    placeholder="Introduce yourself and tell us why you're a good fit..."
                                    :disabled="form.processing"
                                    class="form-textarea"
                                />
                                <p
                                    v-if="form.errors.cover_letter"
                                    class="error-msg"
                                >
                                    {{ form.errors.cover_letter }}
                                </p>
                            </div>

                            <!-- References -->
                            <div class="form-group">
                                <label for="apply-references" class="form-label"
                                    >Character References</label
                                >
                                <textarea
                                    id="apply-references"
                                    v-model="form.references"
                                    rows="2"
                                    placeholder="Name, Position, Contact Number"
                                    :disabled="form.processing"
                                    class="form-textarea"
                                />
                                <p
                                    v-if="form.errors.references"
                                    class="error-msg"
                                >
                                    {{ form.errors.references }}
                                </p>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Footer Navigation -->
                <div class="modal-footer">
                    <button
                        v-if="currentStep > 1"
                        type="button"
                        class="btn btn-outline"
                        :disabled="form.processing"
                        @click="prevStep"
                    >
                        Back
                    </button>
                    <button
                        v-else
                        type="button"
                        class="btn btn-outline"
                        :disabled="form.processing"
                        @click="handleClose"
                    >
                        Cancel
                    </button>

                    <button
                        v-if="currentStep < 4"
                        type="button"
                        class="btn btn-primary"
                        @click="nextStep"
                    >
                        Next
                    </button>
                    <button
                        v-else
                        type="submit"
                        form="apply-form"
                        class="btn btn-primary"
                        :disabled="form.processing"
                    >
                        <span
                            v-if="form.processing"
                            class="spinner-icon"
                        ></span>
                        Submit Application
                    </button>
                </div>
            </div>
        </div>
    </Transition>
</template>

<style scoped>
@import '../../../../css/welcome/apply-modal.css';
</style>
