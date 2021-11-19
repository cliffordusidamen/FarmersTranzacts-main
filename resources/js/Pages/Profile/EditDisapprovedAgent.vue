<template>
    <app-layout>
        <div class="page-content py-4 bg-light">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-8">
                        <h4 class="text-center">
                            Re-submission of vendor registration
                        </h4>

                        <p class="text-center" v-if="agent.reject_reason">
                            <strong>Reason for disapproval:</strong>
                            {{ agent.reject_reason }}
                        </p>
                        <jet-validation-errors class="mb-3" />

                        <form ref="frmAgent" id="frmAgent">
                            <div class="card">
                                <div class="card-body">
                                    
                                    <p class="pb-1 mb-2 border-bottom text-muted text-uppercase">
                                        Organization/Store Information
                                    </p>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <jet-label class="required" for="business_name" value="Name" />
                                                <jet-input
                                                    name="business_name"
                                                    max-length="100"
                                                    id="business_name"
                                                    type="text"
                                                    v-model="form.business_name"
                                                    required
                                                />
                                                <jet-input-error :message="form.errors.business_name" />
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <jet-label class="required" for="store_address_line_1" value="Address Line 1" />
                                                <jet-input
                                                    name="store_address_line_1"
                                                    max-length="50"
                                                    id="store_address_line_1"
                                                    type="text"
                                                    v-model="form.store_address_line_1"
                                                    required
                                                    autocomplete="store_address_line_1"
                                                />
                                                <jet-input-error :message="form.errors.store_address_line_1" />
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <jet-label for="store_address_line_2" value="Address Line 2" />
                                                <jet-input
                                                    name="store_address_line_2"
                                                    max-length="50"
                                                    id="store_address_line_2"
                                                    type="text"
                                                    v-model="form.store_address_line_2"
                                                    autocomplete="store_address_line_2"
                                                />
                                                <jet-input-error :message="form.errors.store_address_line_2" />
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <jet-label for="store_city" value="City" />
                                                <jet-input
                                                    name="store_city"
                                                    max-length="50"
                                                    id="store_city"
                                                    type="text"
                                                    v-model="form.store_city"
                                                    autocomplete="store_city"
                                                />
                                                <jet-input-error :message="form.errors.store_city" />
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <jet-label class="required" for="store_state" value="State" />
                                                <custom-select
                                                    id="type"
                                                    name="type"
                                                    v-model="form.store_state"
                                                    :options="app_states"
                                                    required
                                                />
                                                <jet-input-error :message="form.errors.store_state" />
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <jet-label class="required" for="spending_volume_id" value="Target spending volume" />
                                                <custom-select
                                                    id="spending_volume_id"
                                                    name="spending_volume_id"
                                                    v-model="form.spending_volume_id"
                                                    :options="this.$page.props.spendingVolumes"
                                                    required
                                                />
                                                <jet-input-error :message="form.errors.spending_volume_id" />
                                            </div>
                                        </div>
                                    </div>

                                    <!-- PRODUCTS CATERGORIES -->
                                    <p class="pb-1 mb-2 border-bottom text-muted text-uppercase">
                                        Product categories
                                    </p>
                                    <div class="form-group row justify-content-center border-bottom pb-2 mb-3">
                                        <span class="is-invalid text-danger invalid-feedback">Select at least one(1) product category!</span>
                                        <div
                                            v-for="category in product_categories" :key="category.id"
                                            class="col-5"
                                        >
                                            <div class="custom-control custom-checkbox">
                                                <input
                                                    class="custom-control-input"
                                                    name="product_categories[]"
                                                    type="checkbox"
                                                    :id="'category_' + category.id"
                                                    :value="category.id"
                                                    v-model="form.product_categories"
                                                    required
                                                />

                                                <label class="custom-control-label" :for="'category_' + category.id">
                                                    {{ category.name }}
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group text-center">
                                        <button type="submit" class="btn btn-primary">
                                            SUBMIT
                                        </button>
                                    </div>

                                    <!-- [END] - PRODUCTS CATERGORIES -->
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </app-layout>
</template>

<script>
import AppLayout from '@/Layouts/AppLayout'
import JetButton from "@/Jetstream/Button";
import JetInput from "@/Jetstream/Input";
import JetInputError from "@/Jetstream/InputError";
import JetCheckbox from "@/Jetstream/Checkbox";
import JetLabel from "@/Jetstream/Label";
import JetValidationErrors from "@/Jetstream/ValidationErrors";
import CustomSelect from "@/Components/CustomSelect"
import { useForm, usePage } from '@inertiajs/inertia-vue3';
import { onMounted, ref } from '@vue/runtime-core';

export default {
    props: [
        'agent',
        'app_states',
        'product_categories',
        'product_category_ids',
    ],

    components: {
        AppLayout,
        JetButton,
        JetInput,
        JetInputError,
        JetCheckbox,
        JetLabel,
        JetValidationErrors,
        CustomSelect,
    },

    setup() {
        const { agent, product_categories, app_states } = usePage().props.value
        const jqueryValidator = ref(null)
        const frmAgent = ref(null)
        const processing = ref(false)
        const form = useForm({
            business_name: agent.business_name,
            spending_volume_id: agent.spending_volume_id,
            store_address_line_1: agent.address.line_1,
            store_address_line_2: agent.address.line_2,
            store_city: agent.address.city,
            store_state: agent.address.state_id,
            product_categories: agent.product_categories.map(category => category.id),
        })

        const submit = () => {
            processing.value = true;

            form.post(route("profile.resubmit_for_approval"), {
                preserveState: true,
                onFinish: () => {
                    processing.value = false;
                },
            });
        }

        const nameValidation = {
            required: true,
            minlength: 3,
            maxlength: 25,
        };

        const validationConfig =  {
            submitHandler() {
                frmAgent.value.classList.add("was-validated");
                submit();
            },
            rules: {
                business_name: { ...nameValidation },
                store_phone: {
                    required: true,
                    digits: true,
                    minlength: 11,
                    maxlength: 16,
                },
                store_address_line_1: {
                    required: true,
                    minlength: 5,
                },
                store_address_line_2: {
                    required: false,
                    minlength: 5,
                },
                store_city: {
                    required: false,
                    minlength: 5,
                },
                store_state: 'required',
                spending_volume_id: 'required',
                'product_categories[]': {
                    required: true,
                    rangelength: [1, product_categories.length]
                }
            },

            messages: {
                'product_categories[]': 'Select at least one(1) product category',
            },
        }

        onMounted(() => {
            agent.product_categories.forEach(category => {
                form.product_categories.push(category.id)
            })

            jqueryValidator.value = $(frmAgent.value).validate(validationConfig)
        })

        return {
            agent,
            product_categories,
            app_states,
            frmAgent,
            form,
        }

    }
}
</script>